<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPost::where('is_active', true);

        // Search by job title or description
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->input('department'));
        }

        // Filter by employment type
        if ($request->filled('employment_type')) {
            $employmentTypes = $request->input('employment_type');
            $query->whereIn('employment_type', $employmentTypes);
        }

        // Filter by experience level
        if ($request->filled('experience_level')) {
            $query->where('required_experience_level', $request->input('experience_level'));
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', $request->input('location'));
        }

        // Filter by salary range
        if ($request->filled('salary_min')) {
            $query->where('salary_max', '>=', $request->input('salary_min'));
        }
        if ($request->filled('salary_max')) {
            $query->where('salary_min', '<=', $request->input('salary_max'));
        }

        $jobs = $query->orderBy('created_at', 'desc')->paginate(12);

        // Get filter options for sidebar
        $departments = JobPost::where('is_active', true)->distinct()->pluck('department')->filter()->toArray();
        $locations = JobPost::where('is_active', true)->distinct()->pluck('location')->filter()->toArray();

        return view('careers.index-filtered', compact('jobs', 'departments', 'locations'));
    }

    public function show(JobPost $jobPost)
    {
        $job = $jobPost;
        
        // Only hide inactive jobs, but allow viewing closed jobs (as evidence)
        if (!$job->is_active) {
            abort(404, 'This job posting is not available.');
        }

        $job->incrementViews();

        // Get related jobs (both open and closed, but active)
        $relatedJobs = JobPost::where('is_active', true)
            ->where('id', '!=', $job->id)
            ->where('department', $job->department)
            ->limit(3)
            ->get();

        return view('careers.show', compact('job', 'relatedJobs'));
    }
}

