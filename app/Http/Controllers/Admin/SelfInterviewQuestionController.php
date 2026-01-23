<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SelfInterviewQuestion;
use App\Models\JobPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SelfInterviewQuestionController extends Controller
{
    /**
     * Apply company filter if user is a client
     */
    protected function applyCompanyFilter($query)
    {
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            return $query->where('company_id', $user->company_id);
        }
        return $query;
    }

    /**
     * Check if user can access this question (for clients, must belong to their company)
     */
    protected function checkQuestionAccess(SelfInterviewQuestion $question)
    {
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id && $question->company_id !== $user->company_id) {
            abort(403, 'You do not have permission to access this question.');
        }
    }

    /**
     * Display a listing of self‑interview questions.
     */
    public function index(Request $request)
    {
        $query = SelfInterviewQuestion::with('jobPost');
        
        // Filter by company for clients
        $query = $this->applyCompanyFilter($query);

        // Filter by job post
        $jobPostId = $request->input('job_post_id');
        if ($jobPostId !== null && $jobPostId !== '') {
            if ($jobPostId === 'global') {
                $query->whereNull('job_post_id');
            } else {
                $jobPostIdInt = (int) $jobPostId;
                if ($jobPostIdInt > 0) {
                    $query->where('job_post_id', $jobPostIdInt);
                }
            }
        }

        // Filter by active status
        $isActiveFilter = $request->input('is_active');
        if ($isActiveFilter !== null && $isActiveFilter !== '' && $isActiveFilter !== 'all') {
            $isActive = ($isActiveFilter === '1' || $isActiveFilter === 1 || $isActiveFilter === true);
            $query->where('is_active', $isActive);
        }

        $questions = $query->orderByRaw('job_post_id IS NULL ASC')
            ->orderBy('job_post_id')
            ->orderBy('display_order')
            ->paginate(20)
            ->withQueryString();

        // Get job posts for filter (filtered by company for clients)
        $jobPostsQuery = JobPost::select('id', 'title');
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            $jobPostsQuery->where('company_id', $user->company_id);
        }
        $jobPosts = $jobPostsQuery->orderBy('title')->get();

        return view('admin.self-interview.index', compact('questions', 'jobPosts'));
    }

    public function create()
    {
        $question = new SelfInterviewQuestion([
            'points' => 4,
            'is_active' => true,
            'display_order' => 0,
        ]);

        // Get job posts (filtered by company for clients)
        $jobPostsQuery = JobPost::select('id', 'title');
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            $jobPostsQuery->where('company_id', $user->company_id);
        }
        $jobPosts = $jobPostsQuery->orderBy('title')->get();

        return view('admin.self-interview.create', compact('question', 'jobPosts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $questionType = $request->input('question_type', 'multiple_choice');
        
        $rules = [
            'job_post_id' => 'nullable|exists:job_posts,id',
            'question' => 'required|string',
            'question_type' => 'required|in:multiple_choice,text,calculation',
            'points' => 'required|integer|min:1|max:10',
            'explanation' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ];

        // Only require options and correct_answer for multiple choice questions
        if ($questionType === 'multiple_choice') {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*'] = 'required|string';
            $rules['correct_answer'] = 'required|string|in:a,b,c,d,e';
        } else {
            // For text and calculation questions, these are optional
            $rules['options'] = 'nullable|array';
            $rules['correct_answer'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        
        // Auto-assign company_id for clients
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            $validated['company_id'] = $user->company_id;
        }

        if (empty($validated['job_post_id'])) {
            $validated['job_post_id'] = null;
        }

        // Convert options array to JSON format (only for multiple choice)
        if ($questionType === 'multiple_choice' && isset($validated['options'])) {
            $optionsArray = [];
            $letters = ['a', 'b', 'c', 'd', 'e'];
            foreach ($validated['options'] as $index => $option) {
                if (!empty($option)) {
                    $optionsArray[$letters[$index]] = $option;
                }
            }
            $validated['options'] = $optionsArray;
        } else {
            // Set to null for text/calculation questions
            $validated['options'] = null;
            $validated['correct_answer'] = null;
        }

        SelfInterviewQuestion::create($validated);

        return redirect()->route('admin.self-interview.index')
            ->with('success', 'Self interview question created successfully!');
    }

    public function edit(SelfInterviewQuestion $selfInterview)
    {
        $this->checkQuestionAccess($selfInterview);
        
        // Get job posts (filtered by company for clients)
        $jobPostsQuery = JobPost::select('id', 'title');
        $user = auth()->user();
        if ($user && $user->isClient() && $user->company_id) {
            $jobPostsQuery->where('company_id', $user->company_id);
        }
        $jobPosts = $jobPostsQuery->orderBy('title')->get();

        return view('admin.self-interview.edit', [
            'question' => $selfInterview,
            'jobPosts' => $jobPosts,
        ]);
    }

    public function update(Request $request, SelfInterviewQuestion $selfInterview): RedirectResponse
    {
        $this->checkQuestionAccess($selfInterview);
        
        $questionType = $request->input('question_type', 'multiple_choice');
        
        $rules = [
            'job_post_id' => 'nullable|exists:job_posts,id',
            'question' => 'required|string',
            'question_type' => 'required|in:multiple_choice,text,calculation',
            'points' => 'required|integer|min:1|max:10',
            'explanation' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ];

        // Only require options and correct_answer for multiple choice questions
        if ($questionType === 'multiple_choice') {
            $rules['options'] = 'required|array|min:2';
            $rules['options.*'] = 'required|string';
            $rules['correct_answer'] = 'required|string|in:a,b,c,d,e';
        } else {
            // For text and calculation questions, these are optional
            $rules['options'] = 'nullable|array';
            $rules['correct_answer'] = 'nullable|string';
        }

        $validated = $request->validate($rules);

        $validated['is_active'] = $request->has('is_active');

        if (empty($validated['job_post_id'])) {
            $validated['job_post_id'] = null;
        }

        // Convert options array to JSON format (only for multiple choice)
        if ($questionType === 'multiple_choice' && isset($validated['options'])) {
            $optionsArray = [];
            $letters = ['a', 'b', 'c', 'd', 'e'];
            foreach ($validated['options'] as $index => $option) {
                if (!empty($option)) {
                    $optionsArray[$letters[$index]] = $option;
                }
            }
            $validated['options'] = $optionsArray;
        } else {
            // Set to null for text/calculation questions
            $validated['options'] = null;
            $validated['correct_answer'] = null;
        }

        $selfInterview->update($validated);

        return redirect()->route('admin.self-interview.index')
            ->with('success', 'Self interview question updated successfully!');
    }

    public function destroy(SelfInterviewQuestion $selfInterview): RedirectResponse
    {
        $this->checkQuestionAccess($selfInterview);
        
        $selfInterview->delete();

        return redirect()->route('admin.self-interview.index')
            ->with('success', 'Self interview question deleted successfully!');
    }

    public function toggleStatus(SelfInterviewQuestion $selfInterview): RedirectResponse
    {
        $this->checkQuestionAccess($selfInterview);
        
        $selfInterview->update(['is_active' => ! $selfInterview->is_active]);

        $status = $selfInterview->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.self-interview.index')
            ->with('success', "Question {$status} successfully!");
    }

    /**
     * Bulk activate questions
     */
    public function bulkActivate(Request $request): RedirectResponse
    {
        $request->validate([
            'question_ids' => 'required|string',
        ]);

        $questionIds = json_decode($request->string('question_ids'), true);
        
        if (!is_array($questionIds) || empty($questionIds)) {
            return back()->withErrors(['error' => 'Invalid question IDs provided.']);
        }

        // Apply company filter for clients
        $query = SelfInterviewQuestion::whereIn('id', $questionIds);
        $query = $this->applyCompanyFilter($query);
        
        $count = $query->update(['is_active' => true]);

        return redirect()->route('admin.self-interview.index')
            ->with('success', "Activated {$count} question(s) successfully!");
    }

    /**
     * Bulk deactivate questions
     */
    public function bulkDeactivate(Request $request): RedirectResponse
    {
        $request->validate([
            'question_ids' => 'required|string',
        ]);

        $questionIds = json_decode($request->string('question_ids'), true);
        
        if (!is_array($questionIds) || empty($questionIds)) {
            return back()->withErrors(['error' => 'Invalid question IDs provided.']);
        }

        // Apply company filter for clients
        $query = SelfInterviewQuestion::whereIn('id', $questionIds);
        $query = $this->applyCompanyFilter($query);
        
        $count = $query->update(['is_active' => false]);

        return redirect()->route('admin.self-interview.index')
            ->with('success', "Deactivated {$count} question(s) successfully!");
    }

    /**
     * Bulk delete questions
     */
    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'question_ids' => 'required|string',
        ]);

        $questionIds = json_decode($request->string('question_ids'), true);
        
        if (!is_array($questionIds) || empty($questionIds)) {
            return back()->withErrors(['error' => 'Invalid question IDs provided.']);
        }

        // Apply company filter for clients and check access
        $questions = SelfInterviewQuestion::whereIn('id', $questionIds)->get();
        $user = auth()->user();
        $count = 0;
        
        foreach ($questions as $question) {
            if ($user && $user->isClient() && $user->company_id && $question->company_id !== $user->company_id) {
                continue; // Skip questions not belonging to client's company
            }
            $question->delete();
            $count++;
        }

        return redirect()->route('admin.self-interview.index')
            ->with('success', "Deleted {$count} question(s) successfully!");
    }
}


