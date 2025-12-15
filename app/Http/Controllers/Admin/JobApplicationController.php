<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\JobApplicationConfirmation;
use App\Models\JobApplication;
use App\Models\JobApplicationMessage;
use App\Models\JobApplicationReview;
use App\Models\JobApplicationStatusHistory;
use App\Models\Interview;
use App\Services\MessagingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    /**
     * Record a status change in the history table.
     */
    protected function recordStatusChange(JobApplication $application, string $newStatus, ?string $source = null, ?string $notes = null): void
    {
        JobApplicationStatusHistory::create([
            'job_application_id' => $application->id,
            'previous_status' => $application->getOriginal('status'),
            'new_status' => $newStatus,
            'changed_by' => auth()->id(),
            'source' => $source,
            'notes' => $notes,
        ]);
    }
    public function index(Request $request)
    {
        $query = JobApplication::with(['jobPost']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('jobPost', function ($jobQuery) use ($search) {
                      $jobQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by job post
        if ($request->filled('job_post_id')) {
            $query->where('job_post_id', $request->integer('job_post_id'));
        }

        $applications = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Get total counts for banner
        $totalApplications = JobApplication::count();
        $filteredCount = $applications->total();

        // Get status counts for filters
        $statusCounts = [
            'pending' => JobApplication::where('status', 'pending')->count(),
            'reviewed' => JobApplication::where('status', 'reviewed')->count(),
            'shortlisted' => JobApplication::where('status', 'shortlisted')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
            'interview_scheduled' => JobApplication::where('status', 'interview_scheduled')->count(),
            'interview_passed' => JobApplication::where('status', 'interview_passed')->count(),
            'interview_failed' => JobApplication::where('status', 'interview_failed')->count(),
            'hired' => JobApplication::where('status', 'hired')->count(),
        ];

        // Get job posts for filter dropdown
        $jobPosts = \App\Models\JobPost::select('id', 'title')
            ->orderBy('title')
            ->get();

        return view('admin.job-applications.index', compact(
            'applications',
            'totalApplications',
            'filteredCount',
            'statusCounts',
            'jobPosts'
        ));
    }

    public function show(JobApplication $job_application)
    {
        // Use the route model binding parameter name
        $application = $job_application;
        
        // Refresh the model to ensure we have the latest data
        $application->refresh();
        
        // Make sure all attributes are loaded
        $application->makeVisible($application->getFillable());
        
        // Load relationships
        $application->load(['jobPost', 'reviews.reviewer', 'interviews.conductedBy', 'messages.sender']);
        
        // Ensure jobPost relationship is available even if null
        if (!$application->jobPost && $application->job_post_id) {
            // Job post might have been deleted, set to null
            $application->job_post_id = null;
        }
        
        return view('admin.job-applications.show', compact('application'));
    }

    public function sendMessage(Request $request, JobApplication $application): RedirectResponse
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
        $jobMessage = JobApplicationMessage::create([
            'job_application_id' => $application->id,
            'sent_by' => auth()->id(),
            'channel' => $validated['channel'],
            'message' => $validated['message'],
            'recipient' => $validated['recipient'],
            'status' => 'pending',
        ]);

        // Send the message
        $messagingService = new MessagingService();
        $sent = $messagingService->send($jobMessage);

        if ($sent) {
            return back()->with('success', 'Message sent successfully via ' . strtoupper($validated['channel']) . '!');
        } else {
            return back()->withErrors(['message' => 'Failed to send message. Please check the error and try again.']);
        }
    }

    public function review(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'decision' => 'required|in:pass,regret',
            'review_notes' => 'nullable|string',
            'regret_template' => 'nullable|string',
            'pass_template' => 'nullable|string',
        ]);

        $review = JobApplicationReview::create([
            'job_application_id' => $application->id,
            'reviewed_by' => auth()->id(),
            'decision' => $validated['decision'],
            'review_notes' => $validated['review_notes'] ?? null,
            'regret_template' => $validated['regret_template'] ?? null,
            'pass_template' => $validated['pass_template'] ?? null,
        ]);

        $newStatus = $validated['decision'] === 'pass' ? 'shortlisted' : 'rejected';

        $this->recordStatusChange($application, $newStatus, 'review', $validated['review_notes'] ?? null);

        $application->update([
            'status' => $newStatus,
        ]);

        return redirect()->route('admin.job-applications.show', $application)
            ->with('success', 'Application reviewed successfully!');
    }

    public function scheduleInterview(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'interview_type' => 'required|in:first,second,written_test,case_study',
            'scheduled_at' => 'required|date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $interview = Interview::create([
            'job_application_id' => $application->id,
            'interview_type' => $validated['interview_type'],
            'scheduled_at' => $validated['scheduled_at'],
            'location' => $validated['location'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'result' => 'pending',
        ]);

        $statusMap = [
            'first' => 'interview_scheduled',
            'second' => 'second_interview',
            'written_test' => 'written_test',
            'case_study' => 'case_study',
        ];

        $newStatus = $statusMap[$validated['interview_type']] ?? 'interview_scheduled';

        $this->recordStatusChange($application, $newStatus, 'schedule_interview');

        $application->update([
            'status' => $newStatus,
        ]);

        return redirect()->route('admin.job-applications.show', $application)
            ->with('success', 'Interview scheduled successfully!');
    }

    public function updateInterviewResult(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'result' => 'required|in:pass,fail',
            'feedback' => 'nullable|string',
            'test_submission_email' => 'nullable|email',
            'test_document_path' => 'nullable|string',
        ]);

        $interview->update($validated);

        $application = $interview->application;

        if ($validated['result'] === 'pass') {
            if ($interview->interview_type === 'first') {
                $this->recordStatusChange($application, 'interview_passed', 'interview_result', $validated['feedback'] ?? null);
                $application->update(['status' => 'interview_passed']);
            } elseif ($interview->interview_type === 'second') {
                $this->recordStatusChange($application, 'hired', 'interview_result', $validated['feedback'] ?? null);
                $application->update(['status' => 'hired']);
            }
        } else {
            $this->recordStatusChange($application, 'interview_failed', 'interview_result', $validated['feedback'] ?? null);
            $application->update(['status' => 'interview_failed']);
        }

        return redirect()->route('admin.job-applications.show', $application)
            ->with('success', 'Interview result updated successfully!');
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,reviewed,shortlisted,rejected,interview_scheduled,interview_passed,interview_failed,second_interview,written_test,case_study,hired',
        ]);

        $newStatus = $validated['status'];

        $this->recordStatusChange($application, $newStatus, 'manual_update');

        $application->update(['status' => $newStatus]);

        return redirect()->route('admin.job-applications.show', $application)
            ->with('success', 'Application status updated successfully!');
    }

    public function destroy(JobApplication $job_application)
    {
        $application = $job_application;
        
        // Delete CV file if it exists
        if ($application->cv_path && Storage::disk('public')->exists($application->cv_path)) {
            Storage::disk('public')->delete($application->cv_path);
        }

        // Delete the application (related records will be cascade deleted)
        $application->delete();

        return redirect()->route('admin.job-applications.index')
            ->with('success', 'Job application deleted successfully!');
    }

    public function sendConfirmationEmail(JobApplication $application): RedirectResponse
    {
        if (! $application->email) {
            return back()->withErrors(['email' => 'This application does not have an email address.']);
        }

        try {
            Mail::to($application->email)->send(new JobApplicationConfirmation($application));
            
            // Record the confirmation email in message history
            JobApplicationMessage::create([
                'job_application_id' => $application->id,
                'sent_by' => auth()->id(),
                'channel' => 'email',
                'message' => 'Application confirmation email sent automatically.',
                'recipient' => $application->email,
                'status' => 'sent',
                'metadata' => ['type' => 'confirmation_email'],
            ]);
            
            return back()->with('success', 'Confirmation email sent successfully to ' . $application->email . '!');
        } catch (\Exception $e) {
            // Record failed attempt
            JobApplicationMessage::create([
                'job_application_id' => $application->id,
                'sent_by' => auth()->id(),
                'channel' => 'email',
                'message' => 'Application confirmation email - Failed to send.',
                'recipient' => $application->email,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'metadata' => ['type' => 'confirmation_email'],
            ]);
            
            return back()->withErrors(['email' => 'Failed to send email: ' . $e->getMessage()]);
        }
    }

    public function sendBulkConfirmationEmails(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:job_applications,id',
        ]);

        $applications = JobApplication::whereIn('id', $validated['application_ids'])
            ->whereNotNull('email')
            ->get();

        if ($applications->isEmpty()) {
            return back()->withErrors(['email' => 'No valid applications with email addresses found.']);
        }

        $sent = 0;
        $failed = 0;

        foreach ($applications as $application) {
            try {
                Mail::to($application->email)->send(new JobApplicationConfirmation($application));
                
                // Record the confirmation email in message history
                JobApplicationMessage::create([
                    'job_application_id' => $application->id,
                    'sent_by' => auth()->id(),
                    'channel' => 'email',
                    'message' => 'Application confirmation email sent automatically.',
                    'recipient' => $application->email,
                    'status' => 'sent',
                    'metadata' => ['type' => 'confirmation_email'],
                ]);
                
                $sent++;
            } catch (\Exception $e) {
                // Record failed attempt
                JobApplicationMessage::create([
                    'job_application_id' => $application->id,
                    'sent_by' => auth()->id(),
                    'channel' => 'email',
                    'message' => 'Application confirmation email - Failed to send.',
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

        return back()->with('success', $message . '!');
    }

    /**
     * View CV in browser
     */
    public function viewCv(JobApplication $application)
    {
        if (!$application->cv_path) {
            abort(404, 'CV not found for this application.');
        }

        $path = storage_path('app/public/' . $application->cv_path);
        
        if (!file_exists($path)) {
            abort(404, 'CV file not found.');
        }

        $mimeType = mime_content_type($path);
        
        return response()->file($path, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($application->cv_path) . '"',
        ]);
    }

    /**
     * Download CV
     */
    public function downloadCv(JobApplication $application)
    {
        if (!$application->cv_path) {
            abort(404, 'CV not found for this application.');
        }

        if (!Storage::disk('public')->exists($application->cv_path)) {
            abort(404, 'CV file not found.');
        }

        return Storage::disk('public')->download(
            $application->cv_path,
            $application->name . '_CV_' . now()->format('Y-m-d') . '.' . pathinfo($application->cv_path, PATHINFO_EXTENSION)
        );
    }

    /**
     * Bulk update status
     */
    public function bulkUpdateStatus(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:job_applications,id',
            'status' => 'required|in:pending,reviewed,shortlisted,rejected,interview_scheduled,interview_passed,interview_failed,second_interview,written_test,case_study,hired',
        ]);

        $count = JobApplication::whereIn('id', $validated['application_ids'])
            ->update(['status' => $validated['status']]);

        return back()->with('success', "Status updated for {$count} application(s).");
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:job_applications,id',
        ]);

        $applications = JobApplication::whereIn('id', $validated['application_ids'])->get();
        $count = 0;

        foreach ($applications as $application) {
            // Delete CV file if it exists
            if ($application->cv_path && Storage::disk('public')->exists($application->cv_path)) {
                Storage::disk('public')->delete($application->cv_path);
            }
            $application->delete();
            $count++;
        }

        return back()->with('success', "{$count} application(s) deleted successfully.");
    }

    /**
     * Export applications to CSV
     */
    public function export(Request $request)
    {
        $query = JobApplication::with(['jobPost']);

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('jobPost', function ($jobQuery) use ($search) {
                      $jobQuery->where('title', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('job_post_id')) {
            $query->where('job_post_id', $request->integer('job_post_id'));
        }

        $applications = $query->orderBy('created_at', 'desc')->get();

        $filename = 'job_applications_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($applications) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Phone',
                'Job Post',
                'Department',
                'Status',
                'Education Level',
                'Current Position',
                'Current Company',
                'Availability Date',
                'Submitted Date',
            ]);

            // CSV Data
            foreach ($applications as $application) {
                fputcsv($file, [
                    $application->id,
                    $application->name,
                    $application->email,
                    $application->phone,
                    $application->jobPost->title ?? 'N/A',
                    $application->jobPost->department ?? 'N/A',
                    $application->status,
                    $application->education_level ?? 'N/A',
                    $application->current_job_title ?? 'N/A',
                    $application->current_company ?? 'N/A',
                    $application->availability_date ? \Carbon\Carbon::parse($application->availability_date)->format('Y-m-d') : 'N/A',
                    $application->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Interview calendar view
     */
    public function interviewCalendar(Request $request)
    {
        $query = \App\Models\Interview::with(['application.jobPost', 'conductedBy'])
            ->whereHas('application') // only include interviews linked to an existing application
            ->whereNotNull('scheduled_at')
            ->orderBy('scheduled_at', 'asc');

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('scheduled_at', '>=', $request->date('start_date'));
        } else {
            $query->whereDate('scheduled_at', '>=', now());
        }

        if ($request->filled('end_date')) {
            $query->whereDate('scheduled_at', '<=', $request->date('end_date'));
        } else {
            $query->whereDate('scheduled_at', '<=', now()->addDays(30));
        }

        // Filter by interview type
        if ($request->filled('interview_type')) {
            $query->where('interview_type', $request->string('interview_type'));
        }

        // Filter by result
        if ($request->filled('result')) {
            $query->where('result', $request->string('result'));
        }

        $interviews = $query->get();

        // Group by date for list view
        $calendar = $interviews->groupBy(function ($interview) {
            return $interview->scheduled_at->format('Y-m-d');
        });

        // Prepare events for FullCalendar
        $events = $interviews->map(function ($interview) {
            $application = $interview->application;

            return [
                'id' => $interview->id,
                'title' => trim(($application->name ?? 'Unknown') . ' — ' . \Illuminate\Support\Str::headline(str_replace('_', ' ', $interview->interview_type))),
                'start' => optional($interview->scheduled_at)->toIso8601String(),
                'url' => $application ? route('admin.job-applications.show', $application) : null,
            ];
        });

        return view('admin.job-applications.calendar', compact('interviews', 'calendar', 'events'));
    }
}

