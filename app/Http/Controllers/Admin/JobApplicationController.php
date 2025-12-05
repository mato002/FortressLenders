<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobApplicationReview;
use App\Models\Interview;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index()
    {
        $applications = JobApplication::with(['jobPost'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.job-applications.index', compact('applications'));
    }

    public function show(JobApplication $application)
    {
        $application->load(['jobPost', 'reviews.reviewer', 'interviews.conductedBy']);
        return view('admin.job-applications.show', compact('application'));
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
}

