<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobPostController extends Controller
{
    public function index()
    {
        $jobs = JobPost::withCount('applications')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.jobs.index', compact('jobs'));
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

