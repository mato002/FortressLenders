<?php

namespace App\Http\Controllers\Candidate;

use App\Models\SavedJob;
use App\Models\JobPost;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class SavedJobController
{
    public function index(): View
    {
        $candidate = current_portal_candidate();
        
        $savedJobs = SavedJob::where('candidate_id', $candidate->id)
            ->with('jobPost')
            ->latest('saved_at')
            ->paginate(12);

        return view('candidate.saved-jobs', [
            'savedJobs' => $savedJobs,
        ]);
    }

    public function save(JobPost $jobPost): JsonResponse
    {
        $candidate = current_portal_candidate();

        $saved = SavedJob::firstOrCreate([
            'candidate_id' => $candidate->id,
            'job_post_id' => $jobPost->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => $saved->wasRecentlyCreated ? 'Job saved successfully' : 'Job already in your wishlist',
            'saved' => true,
        ]);
    }

    public function unsave(JobPost $jobPost): RedirectResponse
    {
        $candidate = current_portal_candidate();

        SavedJob::where('candidate_id', $candidate->id)
            ->where('job_post_id', $jobPost->id)
            ->delete();

        return back()->with('success', 'Job removed from your wishlist.');
    }
}
