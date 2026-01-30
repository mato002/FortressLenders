<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\Candidate;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;

class CandidateDashboardController extends Controller
{
    /**
     * Display all applications for the candidate.
     */
    public function applications(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate) {
            abort(403, 'Unauthorized. Please log in as a candidate.');
        }
        
        try {
            // Check if candidate_id column exists
            if (!Schema::hasColumn('job_applications', 'candidate_id')) {
                throw new \Exception(
                    'Database migration required: The candidate_id column is missing from the job_applications table. ' .
                    'Please run: php artisan migrate or execute the SQL: ALTER TABLE job_applications ADD COLUMN candidate_id BIGINT UNSIGNED NULL AFTER id;'
                );
            }
            
            // Link any existing applications by email if not already linked
            $this->linkApplicationsByEmail($candidate);
            
            $query = JobApplication::where('candidate_id', $candidate->id)
                ->with(['jobPost', 'jobPost.company', 'aiSievingDecision']);
            
            // Filter by status if provided
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            // Search by job title
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('jobPost', function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            }
            
            $applications = $query->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString();
            
            // Get statistics
            $stats = [
                'total' => JobApplication::where('candidate_id', $candidate->id)->count(),
                'pending' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'pending')->count(),
                'sieving_passed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'sieving_passed')->count(),
                'sieving_rejected' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'sieving_rejected')->count(),
                'stage_2_passed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'stage_2_passed')->count(),
                'hired' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'hired')->count(),
            ];
            
            return view('candidate.applications', compact('applications', 'stats', 'candidate'));
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'candidate_id')) {
                throw new \Exception(
                    'Database Error: The candidate_id column is missing from the job_applications table. ' .
                    'Original error: ' . $e->getMessage() . '. ' .
                    'Please run: php artisan migrate or execute the SQL: ALTER TABLE job_applications ADD COLUMN candidate_id BIGINT UNSIGNED NULL AFTER id;'
                );
            }
            throw $e;
        }
    }

    /**
     * Display the candidate dashboard (overview).
     */
    public function index(Request $request)
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate) {
            abort(403, 'Unauthorized. Please log in as a candidate.');
        }
        
        try {
            // Check if candidate_id column exists
            if (!\Schema::hasColumn('job_applications', 'candidate_id')) {
                throw new \Exception(
                    'Database migration required: The candidate_id column is missing from the job_applications table. ' .
                    'Please run: php artisan migrate or execute the SQL: ALTER TABLE job_applications ADD COLUMN candidate_id BIGINT UNSIGNED NULL AFTER id;'
                );
            }
            
            // Link any existing applications by email if not already linked
            $this->linkApplicationsByEmail($candidate);
            
            // Get statistics for dashboard overview (cached briefly)
            $cacheKey = "candidate:{$candidate->id}:dashboard_stats";
            $stats = Cache::remember($cacheKey, 30, function () use ($candidate) {
                return [
                    'total' => JobApplication::where('candidate_id', $candidate->id)->count(),
                    'pending' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'pending')->count(),
                    'sieving_passed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'sieving_passed')->count(),
                    'sieving_rejected' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'sieving_rejected')->count(),
                    'aptitude_failed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'aptitude_failed')->count(),
                    'stage_2_passed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'stage_2_passed')->count(),
                    'hired' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'hired')->count(),
                ];
            });
            
            // Get recent applications (last 5) for quick overview (eager load company)
            $recentApplications = JobApplication::where('candidate_id', $candidate->id)
                ->with(['jobPost.company'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            // Get applications requiring action
            // Check if columns exist before querying
            $hasAptitudeColumns = Schema::hasColumn('job_applications', 'aptitude_test_completed_at');
            $hasSelfInterviewColumns = Schema::hasColumn('job_applications', 'self_interview_completed_at');
            $hasAptitudePassed = Schema::hasColumn('job_applications', 'aptitude_test_passed');
            
            $actionRequired = collect();
            
            if ($hasAptitudeColumns && $hasSelfInterviewColumns && $hasAptitudePassed) {
                $actionRequired = JobApplication::where('candidate_id', $candidate->id)
                    ->where(function($query) {
                        $query->where(function($q) {
                            $q->whereIn('status', ['sieving_passed', 'pending_manual_review'])
                              ->whereNull('aptitude_test_completed_at');
                        })
                        ->orWhere(function($q) {
                            $q->where('aptitude_test_passed', true)
                              ->whereNull('self_interview_completed_at')
                              ->whereNotIn('status', ['stage_2_passed', 'hired', 'sieving_rejected']);
                        });
                    })
                    ->with(['jobPost'])
                    ->limit(5)
                    ->get();
            } elseif ($hasAptitudeColumns) {
                // Only check aptitude test if self interview columns don't exist
                $actionRequired = JobApplication::where('candidate_id', $candidate->id)
                    ->whereIn('status', ['sieving_passed', 'pending_manual_review'])
                    ->whereNull('aptitude_test_completed_at')
                    ->with(['jobPost'])
                    ->limit(5)
                    ->get();
            }
            
            // Calculate profile completion
            $completionPercentage = $this->calculateProfileCompletion($candidate);
            $bioDataComplete = $this->checkBioDataCompletion($candidate);
            $documentsUploaded = $this->checkDocumentsUploaded($candidate);
            
            // Get upcoming activities (tests and interviews)
            $upcomingActivities = $this->getUpcomingActivities($candidate);
            
            // Get active applications for timeline (eager load company)
            $activeApplications = JobApplication::where('candidate_id', $candidate->id)
                ->whereNotIn('status', ['sieving_rejected'])
                ->with(['jobPost.company'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            // Get document reminders
            $documentReminders = $this->getDocumentReminders($candidate);
            
            // Get performance metrics
            $performanceMetrics = $this->getPerformanceMetrics($candidate);
            
            // Get recent activity feed
            $activityFeed = $this->getActivityFeed($candidate);
            
            // Get recommended jobs
            $recommendedJobs = $this->getRecommendedJobs($candidate);
            
            return view('candidate.dashboard', compact(
                'stats', 'recentApplications', 'actionRequired', 'candidate',
                'completionPercentage', 'bioDataComplete', 'documentsUploaded',
                'upcomingActivities', 'activeApplications', 'documentReminders',
                'performanceMetrics', 'activityFeed', 'recommendedJobs'
            ));
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'candidate_id')) {
                throw new \Exception(
                    'Database Error: The candidate_id column is missing from the job_applications table. ' .
                    'Original error: ' . $e->getMessage() . '. ' .
                    'Please run: php artisan migrate or execute the SQL: ALTER TABLE job_applications ADD COLUMN candidate_id BIGINT UNSIGNED NULL AFTER id;'
                );
            }
            throw $e;
        }
    }

    /**
     * Show a specific application.
     */
    public function show(JobApplication $application)
    {
        $candidate = Auth::guard('candidate')->user();
        
        if (!$candidate) {
            abort(403, 'Unauthorized. Please log in as a candidate.');
        }
        
        // Ensure the application belongs to the logged-in candidate
        if ($application->candidate_id !== $candidate->id) {
            abort(403, 'Unauthorized access to this application.');
        }
        
        $application->load(['jobPost', 'aiSievingDecision', 'aptitudeTestSession']);
        
        // Generate token for direct access
        $token = md5($application->email . $application->id . config('app.key'));
        
        return view('candidate.application-show', compact('application', 'token'));
    }

    /**
     * Link applications by email if not already linked
     */
    private function linkApplicationsByEmail(Candidate $candidate): void
    {
        // Find applications by email that don't have a candidate_id
        JobApplication::where('email', $candidate->email)
            ->whereNull('candidate_id')
            ->update(['candidate_id' => $candidate->id]);
    }

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion(Candidate $candidate): int
    {
        $score = 0;
        $total = 3;
        
        // Email verified
        if ($candidate->email_verified_at) $score++;
        
        // Bio data complete
        if ($this->checkBioDataCompletion($candidate)) $score++;
        
        // Documents uploaded
        if ($this->checkDocumentsUploaded($candidate)) $score++;
        
        return (int) (($score / $total) * 100);
    }
    
    /**
     * Check if bio data is complete
     */
    private function checkBioDataCompletion(Candidate $candidate): bool
    {
        return $candidate->phone && $candidate->address && $candidate->city && $candidate->country;
    }
    
    /**
     * Check if documents are uploaded
     */
    private function checkDocumentsUploaded(Candidate $candidate): bool
    {
        return \App\Models\CandidateDocument::where('candidate_id', $candidate->id)->exists();
    }
    
    /**
     * Get upcoming activities (aptitude tests and interviews)
     */
    private function getUpcomingActivities(Candidate $candidate): \Illuminate\Support\Collection
    {
        $activities = collect();
        
        if (!Schema::hasColumn('job_applications', 'aptitude_test_completed_at')) {
            return $activities;
        }
        
        // Get applications needing aptitude test
        $needingTest = JobApplication::where('candidate_id', $candidate->id)
            ->whereIn('status', ['sieving_passed', 'pending_manual_review'])
            ->whereNull('aptitude_test_completed_at')
            ->with(['jobPost'])
            ->limit(3)
            ->get();
        
        foreach ($needingTest as $app) {
            $activities->push([
                'type' => 'aptitude',
                'job_title' => $app->jobPost->title,
                'application_id' => $app->id,
                'time_remaining' => 'soon'
            ]);
        }
        
        // Get applications needing self interview
        if (Schema::hasColumn('job_applications', 'self_interview_completed_at') && 
            Schema::hasColumn('job_applications', 'aptitude_test_passed')) {
            $needingInterview = JobApplication::where('candidate_id', $candidate->id)
                ->where('aptitude_test_passed', true)
                ->whereNull('self_interview_completed_at')
                ->whereNotIn('status', ['stage_2_passed', 'hired', 'sieving_rejected'])
                ->with(['jobPost'])
                ->limit(3)
                ->get();
            
            foreach ($needingInterview as $app) {
                $activities->push([
                    'type' => 'interview',
                    'job_title' => $app->jobPost->title,
                    'application_id' => $app->id,
                    'time_remaining' => 'soon'
                ]);
            }
        }
        
        return $activities;
    }
    
    /**
     * Get document reminders
     */
    private function getDocumentReminders(Candidate $candidate): \Illuminate\Support\Collection
    {
        $reminders = collect();
        
        // Get active applications
        $activeApps = JobApplication::where('candidate_id', $candidate->id)
            ->whereNotIn('status', ['sieving_rejected', 'hired'])
            ->with(['jobPost'])
            ->get();
        
        foreach ($activeApps as $app) {
            $requiredDocs = ['Resume', 'Cover Letter']; // Customize as needed
            $uploadedDocs = \App\Models\CandidateDocument::where('candidate_id', $candidate->id)
                ->pluck('document_type')
                ->toArray();
            
            $missingDocs = array_diff($requiredDocs, $uploadedDocs);
            
            if (count($missingDocs) > 0) {
                $reminders->push([
                    'job_title' => $app->jobPost->title,
                    'missing_docs' => $missingDocs
                ]);
            }
        }
        
        return $reminders;
    }
    
    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics(Candidate $candidate): array
    {
        $metrics = [
            'total_applications' => 0,
            'passed_applications' => 0,
            'success_rate' => 0,
            'avg_aptitude_score' => 0,
            'tests_completed' => 0,
            'completed_tests' => 0,
            'pending_tests' => 0,
        ];
        
        // Count applications
        $metrics['total_applications'] = JobApplication::where('candidate_id', $candidate->id)->count();
        $metrics['passed_applications'] = JobApplication::where('candidate_id', $candidate->id)
            ->whereIn('status', ['sieving_passed', 'pending_manual_review', 'stage_2_passed', 'hired'])
            ->count();
        
        if ($metrics['total_applications'] > 0) {
            $metrics['success_rate'] = ($metrics['passed_applications'] / $metrics['total_applications']) * 100;
        }
        
        // Count tests
        if (Schema::hasColumn('job_applications', 'aptitude_test_completed_at')) {
            $metrics['tests_completed'] = JobApplication::where('candidate_id', $candidate->id)
                ->whereNotNull('aptitude_test_completed_at')
                ->count();
            
            $metrics['pending_tests'] = JobApplication::where('candidate_id', $candidate->id)
                ->whereIn('status', ['sieving_passed', 'pending_manual_review'])
                ->whereNull('aptitude_test_completed_at')
                ->count();
            
            $metrics['completed_tests'] = $metrics['tests_completed'];
        }
        
        return $metrics;
    }
    
    /**
     * Get recent activity feed
     */
    private function getActivityFeed(Candidate $candidate): \Illuminate\Support\Collection
    {
        $activities = collect();
        
        // Get recent applications
        $recentApps = JobApplication::where('candidate_id', $candidate->id)
            ->with(['jobPost'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        foreach ($recentApps as $app) {
            $activities->push([
                'type' => 'application',
                'title' => 'Applied to ' . $app->jobPost->title,
                'description' => 'Status: ' . ucfirst(str_replace('_', ' ', $app->status)),
                'time' => $app->created_at->diffForHumans(),
                'link' => route('candidate.application.show', $app)
            ]);
            
            // Add test completion if available
            if ($app->aptitude_test_completed_at) {
                $activities->push([
                    'type' => 'test_completed',
                    'title' => 'Completed aptitude test for ' . $app->jobPost->title,
                    'time' => $app->aptitude_test_completed_at->diffForHumans(),
                    'link' => route('candidate.application.show', $app)
                ]);
            }
            
            // Add interview completion if available
            if (isset($app->self_interview_completed_at) && $app->self_interview_completed_at) {
                $activities->push([
                    'type' => 'interview_completed',
                    'title' => 'Completed self interview for ' . $app->jobPost->title,
                    'time' => $app->self_interview_completed_at->diffForHumans(),
                    'link' => route('candidate.application.show', $app)
                ]);
            }
        }
        
        return $activities->sortByDesc(function($item) {
            return strtotime(str_replace(' ago', '', $item['time']));
        })->take(10);
    }
    
    /**
     * Get recommended jobs
     */
    private function getRecommendedJobs(Candidate $candidate)
    {
        // Get jobs from departments the candidate has applied to
        $appliedDepartments = JobApplication::where('candidate_id', $candidate->id)
            ->with(['jobPost'])
            ->get()
            ->pluck('jobPost.department')
            ->unique();
        
        if ($appliedDepartments->isEmpty()) {
            return JobPost::where('is_active', true)
                ->with('company')
                ->limit(3)
                ->get();
        }
        
        return JobPost::where('is_active', true)
            ->whereIn('department', $appliedDepartments)
            ->with('company')
            ->limit(3)
            ->get();
    }
}

