<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AptitudeTestQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AptitudeTestController extends Controller
{
    /**
     * Display list of aptitude test questions
     */
    public function index(Request $request)
    {
        $query = AptitudeTestQuestion::with('jobPost');

        // Filter by job post
        if ($request->filled('job_post_id')) {
            if ($request->string('job_post_id') === 'global') {
                $query->whereNull('job_post_id');
            } else {
                $query->where('job_post_id', $request->integer('job_post_id'));
            }
        }

        // Filter by section
        if ($request->filled('section')) {
            $query->where('section', $request->string('section'));
        }

        // Filter by active status
        if ($request->filled('is_active') && $request->string('is_active') !== 'all') {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $questions = $query->orderBy('job_post_id')
            ->orderBy('section')
            ->orderBy('display_order')
            ->paginate(20)
            ->withQueryString();

        $sectionCounts = [
            'numerical' => AptitudeTestQuestion::where('section', 'numerical')->count(),
            'logical' => AptitudeTestQuestion::where('section', 'logical')->count(),
            'verbal' => AptitudeTestQuestion::where('section', 'verbal')->count(),
            'scenario' => AptitudeTestQuestion::where('section', 'scenario')->count(),
        ];

        // Get job posts for filter
        $jobPosts = \App\Models\JobPost::select('id', 'title')
            ->orderBy('title')
            ->get();

        return view('admin.aptitude-test.index', compact('questions', 'sectionCounts', 'jobPosts'));
    }

    /**
     * Show form to create new question
     */
    public function create()
    {
        $question = new AptitudeTestQuestion([
            'points' => 4,
            'is_active' => true,
            'display_order' => 0,
        ]);

        return view('admin.aptitude-test.create', compact('question'));
    }

    /**
     * Store new question
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'job_post_id' => 'nullable|exists:job_posts,id',
            'section' => 'required|in:numerical,logical,verbal,scenario',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_answer' => 'required|string|in:a,b,c,d',
            'points' => 'required|integer|min:1|max:10',
            'explanation' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        
        // Convert empty string to null for job_post_id
        if (empty($validated['job_post_id'])) {
            $validated['job_post_id'] = null;
        }

        // Convert options array to JSON format
        $optionsArray = [];
        $letters = ['a', 'b', 'c', 'd', 'e'];
        foreach ($validated['options'] as $index => $option) {
            if (!empty($option)) {
                $optionsArray[$letters[$index]] = $option;
            }
        }
        $validated['options'] = $optionsArray;

        AptitudeTestQuestion::create($validated);

        return redirect()->route('admin.aptitude-test.index')
            ->with('success', 'Question created successfully!');
    }

    /**
     * Show form to edit question
     */
    public function edit($id)
    {
        $question = AptitudeTestQuestion::findOrFail($id);
        return view('admin.aptitude-test.edit', compact('question'));
    }

    /**
     * Update question
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'job_post_id' => 'nullable|exists:job_posts,id',
            'section' => 'required|in:numerical,logical,verbal,scenario',
            'question' => 'required|string',
            'options' => 'required|array|min:2',
            'options.*' => 'required|string',
            'correct_answer' => 'required|string|in:a,b,c,d',
            'points' => 'required|integer|min:1|max:10',
            'explanation' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        // Convert empty string to null for job_post_id
        if (empty($validated['job_post_id'])) {
            $validated['job_post_id'] = null;
        }

        // Convert options array to JSON format
        $optionsArray = [];
        $letters = ['a', 'b', 'c', 'd', 'e'];
        foreach ($validated['options'] as $index => $option) {
            if (!empty($option)) {
                $optionsArray[$letters[$index]] = $option;
            }
        }
        $validated['options'] = $optionsArray;

        $question = AptitudeTestQuestion::findOrFail($id);
        $question->update($validated);

        return redirect()->route('admin.aptitude-test.index')
            ->with('success', 'Question updated successfully!');
    }

    /**
     * Delete question
     */
    public function destroy($id): RedirectResponse
    {
        $question = AptitudeTestQuestion::findOrFail($id);
        $question->delete();

        return redirect()->route('admin.aptitude-test.index')
            ->with('success', 'Question deleted successfully!');
    }

    /**
     * Toggle question active status
     */
    public function toggleStatus($id): RedirectResponse
    {
        $question = AptitudeTestQuestion::findOrFail($id);
        $question->update([
            'is_active' => !$question->is_active
        ]);

        $status = $question->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.aptitude-test.index')
            ->with('success', "Question {$status} successfully!");
    }
}

