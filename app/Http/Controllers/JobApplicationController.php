<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class JobApplicationController extends Controller
{
    public function create($slug)
    {
        $job = JobPost::where('slug', $slug)->firstOrFail();
        
        if (!$job->is_active) {
            abort(404);
        }

        // Check if application deadline has passed
        if ($job->application_deadline && $job->application_deadline->isPast()) {
            return redirect()->route('careers.show', $job->slug)
                ->with('error', 'The application deadline for this position has passed.');
        }

        return view('careers.apply', compact('job'));
    }

    public function store(Request $request, $slug)
    {
        $job = JobPost::where('slug', $slug)->firstOrFail();
        
        // Check if application deadline has passed
        if ($job->application_deadline && $job->application_deadline->isPast()) {
            return redirect()->route('careers.show', $job->slug)
                ->with('error', 'The application deadline for this position has passed. Applications are no longer being accepted.');
        }
        
        $validated = $request->validate([
            // Page 1
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'education_level' => 'nullable|string|max:255',
            'area_of_study' => 'nullable|string|max:255',
            'institution' => 'nullable|string|max:255',
            'education_status' => 'nullable|string|max:255',
            'other_achievements' => 'nullable|string',
            'work_experience' => 'nullable|array',
            'current_job_title' => 'nullable|string|max:255',
            'current_company' => 'nullable|string|max:255',
            'currently_working' => 'boolean',
            'duties_and_responsibilities' => 'nullable|string',
            'other_experiences' => 'nullable|string',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            
            // Page 2
            'support_details' => 'nullable|string',
            
            // Page 3
            'referrers' => 'nullable|array',
            'notice_period' => 'nullable|string|max:255',
            'agreement_accepted' => 'required|accepted',
            
            // Application message
            'application_message' => 'nullable|string',
        ]);

        // Handle CV upload
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('job-applications/cvs', 'public');
            $validated['cv_path'] = $cvPath;
        }

        $validated['job_post_id'] = $job->id;
        $validated['status'] = 'pending';

        // Generate AI summary (placeholder - you can integrate actual AI service)
        $validated['ai_summary'] = $this->generateAISummary($validated);
        $validated['ai_details'] = $this->generateAIDetails($validated);

        JobApplication::create($validated);

        return redirect()->route('careers.index')
            ->with('success', 'Your application has been submitted successfully!');
    }

    private function generateAISummary(array $data): string
    {
        // Placeholder AI summary generation
        $summary = "Application from {$data['name']}";
        if (!empty($data['education_level'])) {
            $summary .= " with {$data['education_level']} in {$data['area_of_study']}";
        }
        if (!empty($data['current_job_title'])) {
            $summary .= ". Currently working as {$data['current_job_title']} at {$data['current_company']}";
        }
        return $summary;
    }

    private function generateAIDetails(array $data): string
    {
        // Placeholder AI details generation
        $details = "Education: {$data['education_level']} in {$data['area_of_study']}\n";
        $details .= "Institution: {$data['institution']}\n";
        if (!empty($data['work_experience'])) {
            $details .= "Work Experience: " . json_encode($data['work_experience']) . "\n";
        }
        return $details;
    }
}

