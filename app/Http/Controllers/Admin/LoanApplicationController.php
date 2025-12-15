<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\LoanApplicationConfirmation;
use App\Models\LoanApplication;
use App\Models\LoanApplicationMessage;
use App\Services\MessagingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class LoanApplicationController extends Controller
{
    public function index(Request $request): View
    {
        $query = LoanApplication::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('loan_type', 'like', "%{$search}%")
                  ->orWhere('town', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        // Loan type filter
        if ($request->filled('loan_type')) {
            $query->where('loan_type', $request->string('loan_type'));
        }

        $totalApplicationsCount = LoanApplication::count();
        $filteredApplicationsCount = $query->count();

        $applications = $query->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $statusCounts = [
            'pending' => LoanApplication::where('status', 'pending')->count(),
            'in_review' => LoanApplication::where('status', 'in_review')->count(),
            'approved' => LoanApplication::where('status', 'approved')->count(),
            'rejected' => LoanApplication::where('status', 'rejected')->count(),
        ];

        // Get unique loan types for filter
        $loanTypes = LoanApplication::select('loan_type')
            ->distinct()
            ->orderBy('loan_type')
            ->pluck('loan_type');

        return view('admin.loan-applications.index', compact('applications', 'statusCounts', 'totalApplicationsCount', 'filteredApplicationsCount', 'loanTypes'));
    }

    public function show(LoanApplication $loanApplication): View
    {
        $loanApplication->load(['messages.sender']);
        return view('admin.loan-applications.show', compact('loanApplication'));
    }

    public function update(Request $request, LoanApplication $loanApplication): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,in_review,approved,rejected'],
            'admin_notes' => ['nullable', 'string'],
        ]);

        $loanApplication->fill($data);

        if (in_array($data['status'], ['approved', 'rejected'], true) && ! $loanApplication->handled_at) {
            $loanApplication->handled_at = now();
        } elseif (! in_array($data['status'], ['approved', 'rejected'], true)) {
            $loanApplication->handled_at = null;
        }

        $loanApplication->save();

        return back()->with('status', 'Application updated successfully.');
    }

    public function sendMessage(Request $request, LoanApplication $loanApplication): RedirectResponse
    {
        $validated = $request->validate([
            'channel' => 'required|in:email,sms,whatsapp',
            'message' => 'required|string|max:5000',
            'recipient' => 'required|string',
        ]);

        // Validate recipient based on channel
        if ($validated['channel'] === 'email') {
            $request->validate(['recipient' => 'email']);
        } else {
            $request->validate(['recipient' => 'regex:/^[0-9+\-\s()]+$/']);
        }

        // Create message record
        $message = LoanApplicationMessage::create([
            'loan_application_id' => $loanApplication->id,
            'sent_by' => auth()->id(),
            'channel' => $validated['channel'],
            'message' => $validated['message'],
            'recipient' => $validated['recipient'],
            'status' => 'pending',
        ]);

        // Send the message
        $messagingService = new MessagingService();
        $sent = $messagingService->send($message);

        if ($sent) {
            // Update loan application status if needed
            if ($loanApplication->status === 'pending') {
                $loanApplication->update(['status' => 'in_review']);
            }

            return back()->with('status', 'Message sent successfully via ' . strtoupper($validated['channel']) . '!');
        } else {
            return back()->withErrors(['message' => 'Failed to send message. Please check the error and try again.']);
        }
    }

    public function destroy(LoanApplication $loanApplication): RedirectResponse
    {
        $loanApplication->delete();

        return redirect()
            ->route('admin.loan-applications.index')
            ->with('status', 'Application deleted.');
    }

    /**
     * Send confirmation email to applicant
     */
    public function sendConfirmationEmail(LoanApplication $loanApplication): RedirectResponse
    {
        if (! $loanApplication->email) {
            return back()->withErrors(['email' => 'This application does not have an email address.']);
        }

        try {
            Mail::to($loanApplication->email)->send(new LoanApplicationConfirmation($loanApplication));
            
            // Record the confirmation email in message history
            LoanApplicationMessage::create([
                'loan_application_id' => $loanApplication->id,
                'sent_by' => auth()->id(),
                'channel' => 'email',
                'message' => 'Loan application confirmation email sent automatically.',
                'recipient' => $loanApplication->email,
                'status' => 'sent',
                'metadata' => ['type' => 'confirmation_email'],
            ]);
            
            return back()->with('status', 'Confirmation email sent successfully to ' . $loanApplication->email . '!');
        } catch (\Exception $e) {
            // Record failed attempt
            LoanApplicationMessage::create([
                'loan_application_id' => $loanApplication->id,
                'sent_by' => auth()->id(),
                'channel' => 'email',
                'message' => 'Loan application confirmation email - Failed to send.',
                'recipient' => $loanApplication->email,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'metadata' => ['type' => 'confirmation_email'],
            ]);
            
            return back()->withErrors(['email' => 'Failed to send email: ' . $e->getMessage()]);
        }
    }

    /**
     * Bulk send confirmation emails
     */
    public function sendBulkConfirmationEmails(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:loan_applications,id',
        ]);

        $applications = LoanApplication::whereIn('id', $validated['application_ids'])
            ->whereNotNull('email')
            ->get();

        if ($applications->isEmpty()) {
            return back()->withErrors(['email' => 'No valid applications with email addresses found.']);
        }

        $sent = 0;
        $failed = 0;

        foreach ($applications as $application) {
            try {
                Mail::to($application->email)->send(new LoanApplicationConfirmation($application));
                
                // Record the confirmation email in message history
                LoanApplicationMessage::create([
                    'loan_application_id' => $application->id,
                    'sent_by' => auth()->id(),
                    'channel' => 'email',
                    'message' => 'Loan application confirmation email sent automatically.',
                    'recipient' => $application->email,
                    'status' => 'sent',
                    'metadata' => ['type' => 'confirmation_email'],
                ]);
                
                $sent++;
            } catch (\Exception $e) {
                // Record failed attempt
                LoanApplicationMessage::create([
                    'loan_application_id' => $application->id,
                    'sent_by' => auth()->id(),
                    'channel' => 'email',
                    'message' => 'Loan application confirmation email - Failed to send.',
                    'recipient' => $application->email,
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'metadata' => ['type' => 'confirmation_email'],
                ]);
                
                $failed++;
            }
        }

        $message = "Confirmation emails sent: {$sent} successful";
        if ($failed > 0) {
            $message .= ", {$failed} failed";
        }

        return back()->with('status', $message . '!');
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:loan_applications,id',
            'status' => 'required|in:pending,in_review,approved,rejected',
        ]);

        $applications = LoanApplication::whereIn('id', $validated['application_ids'])->get();
        $count = 0;

        foreach ($applications as $application) {
            $application->status = $validated['status'];
            
            if (in_array($validated['status'], ['approved', 'rejected'], true) && ! $application->handled_at) {
                $application->handled_at = now();
            } elseif (! in_array($validated['status'], ['approved', 'rejected'], true)) {
                $application->handled_at = null;
            }
            
            $application->save();
            $count++;
        }

        return back()->with('status', "Status updated for {$count} application(s).");
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:loan_applications,id',
        ]);

        $count = LoanApplication::whereIn('id', $validated['application_ids'])->delete();

        return back()->with('status', "{$count} application(s) deleted successfully.");
    }

    /**
     * Export applications to CSV
     */
    public function export(Request $request)
    {
        $query = LoanApplication::query();

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('loan_type', 'like', "%{$search}%")
                  ->orWhere('town', 'like', "%{$search}%");
            });
        }

        if ($request->filled('loan_type')) {
            $query->where('loan_type', $request->string('loan_type'));
        }

        $applications = $query->orderBy('created_at', 'desc')->get();

        $filename = 'loan_applications_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Full Name',
                'Email',
                'Phone',
                'Date of Birth',
                'Town',
                'Residence',
                'Client Type',
                'Loan Type',
                'Amount Requested',
                'Repayment Period',
                'Purpose',
                'Status',
                'Admin Notes',
                'Handled At',
                'Submitted Date',
            ]);

            // CSV Data
            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->id,
                    $application->full_name,
                    $application->email ?? 'N/A',
                    $application->phone,
                    $application->date_of_birth ? $application->date_of_birth->format('Y-m-d') : 'N/A',
                    $application->town ?? 'N/A',
                    $application->residence ?? 'N/A',
                    $application->client_type ?? 'N/A',
                    $application->loan_type,
                    $application->amount_requested,
                    $application->repayment_period,
                    $application->purpose ?? 'N/A',
                    $application->status,
                    $application->admin_notes ?? 'N/A',
                    $application->handled_at ? $application->handled_at->format('Y-m-d H:i:s') : 'N/A',
                    $application->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}









