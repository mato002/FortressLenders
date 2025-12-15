<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobPostController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPost::withCount('applications');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('employment_type', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('is_active') && $request->string('is_active') !== 'all') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Department filter
        if ($request->filled('department') && $request->string('department') !== 'all') {
            $query->where('department', $request->string('department'));
        }

        $totalJobsCount = JobPost::count();
        $filteredJobsCount = $query->count();

        $jobs = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $departments = JobPost::whereNotNull('department')
            ->distinct()
            ->pluck('department')
            ->sort()
            ->values();

        return view('admin.jobs.index', compact('jobs', 'totalJobsCount', 'filteredJobsCount', 'departments'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'employment_type' => 'required|in:full-time,part-time,contract,internship',
            'experience_level' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->has('is_active');

        JobPost::create($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job post created successfully!');
    }

    public function show(JobPost $job)
    {
        $job->loadCount('applications');
        return view('admin.jobs.show', compact('job'));
    }

    public function edit(JobPost $job)
    {
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, JobPost $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'employment_type' => 'required|in:full-time,part-time,contract,internship',
            'experience_level' => 'nullable|string|max:255',
            'application_deadline' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        if ($job->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $validated['is_active'] = $request->has('is_active');

        $job->update($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job post updated successfully!');
    }

    public function toggleStatus(JobPost $job)
    {
        $job->update([
            'is_active' => !$job->is_active
        ]);

        $status = $job->is_active ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.jobs.index')
            ->with('success', "Job post {$status} successfully!");
    }
}

