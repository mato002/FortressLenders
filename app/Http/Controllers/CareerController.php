<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        $jobs = JobPost::active()
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('careers.index', compact('jobs'));
    }

    public function show(JobPost $jobPost)
    {
        $job = $jobPost;
        
        if (!$job->is_active) {
            abort(404);
        }

        $job->incrementViews();

        $relatedJobs = JobPost::active()
            ->where('id', '!=', $job->id)
            ->where('department', $job->department)
            ->limit(3)
            ->get();

        return view('careers.show', compact('job', 'relatedJobs'));
    }
}

