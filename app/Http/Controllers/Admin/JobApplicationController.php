<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\JobApplicationConfirmation;
use App\Models\JobApplication;
use App\Models\JobApplicationMessage;
use App\Models\JobApplicationReview;
use App\Models\Interview;
use App\Services\MessagingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    public function index()
    {
        $applications = JobApplication::with(['jobPost'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.job-applications.index', compact('applications'));
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

        $application->update([
            'status' => $validated['decision'] === 'pass' ? 'shortlisted' : 'rejected'
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

        $application->update([
            'status' => $statusMap[$validated['interview_type']] ?? 'interview_scheduled'
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
                $application->update(['status' => 'interview_passed']);
            } elseif ($interview->interview_type === 'second') {
                $application->update(['status' => 'hired']);
            }
        } else {
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

        $application->update(['status' => $validated['status']]);

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
}

