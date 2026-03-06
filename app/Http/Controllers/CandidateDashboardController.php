<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\Candidate;
use App\Models\JobPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            // Try to query with candidate_id - if it fails, the column doesn't exist
            try {
                JobApplication::where('candidate_id', $candidate->id)->limit(1)->exists();
            } catch (\Exception $e) {
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
            // Link any existing applications by email if not already linked (only once per session)
            $this->linkApplicationsByEmail($candidate);
            
            // Get statistics for dashboard overview (cached and optimized - single query)
            $cacheKey = "candidate:{$candidate->id}:dashboard_stats";
            $stats = Cache::remember($cacheKey, 60, function () use ($candidate) {
                // Use single query with conditional aggregation instead of multiple queries
                $statsQuery = JobApplication::where('candidate_id', $candidate->id)
                    ->selectRaw('
                        COUNT(*) as total,
                        SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
                        SUM(CASE WHEN status = "sieving_passed" THEN 1 ELSE 0 END) as sieving_passed,
                        SUM(CASE WHEN status = "sieving_rejected" THEN 1 ELSE 0 END) as sieving_rejected,
                        SUM(CASE WHEN status = "aptitude_failed" THEN 1 ELSE 0 END) as aptitude_failed,
                        SUM(CASE WHEN status = "stage_2_passed" THEN 1 ELSE 0 END) as stage_2_passed,
                        SUM(CASE WHEN status = "hired" THEN 1 ELSE 0 END) as hired
                    ')
                    ->first();
                
                return [
                    'total' => (int) ($statsQuery->total ?? 0),
                    'pending' => (int) ($statsQuery->pending ?? 0),
                    'sieving_passed' => (int) ($statsQuery->sieving_passed ?? 0),
                    'sieving_rejected' => (int) ($statsQuery->sieving_rejected ?? 0),
                    'aptitude_failed' => (int) ($statsQuery->aptitude_failed ?? 0),
                    'stage_2_passed' => (int) ($statsQuery->stage_2_passed ?? 0),
                    'hired' => (int) ($statsQuery->hired ?? 0),
                ];
            });
            
            // Get recent applications (last 5) for quick overview (eager load company with column selection)
            $recentApplications = JobApplication::where('candidate_id', $candidate->id)
                ->select('id', 'job_post_id', 'status', 'created_at', 'candidate_id')
                ->with(['jobPost:id,title,company_id', 'jobPost.company:id,name'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            // Get applications requiring action (use try-catch instead of schema checks)
            $actionRequired = collect();
            try {
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
                    ->select('id', 'job_post_id', 'status', 'candidate_id')
                    ->with(['jobPost:id,title'])
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                // If columns don't exist, try simpler query
                try {
                    $actionRequired = JobApplication::where('candidate_id', $candidate->id)
                        ->whereIn('status', ['sieving_passed', 'pending_manual_review'])
                        ->whereNull('aptitude_test_completed_at')
                        ->select('id', 'job_post_id', 'status', 'candidate_id')
                        ->with(['jobPost:id,title'])
                        ->limit(5)
                        ->get();
                } catch (\Exception $e2) {
                    // If that also fails, return empty collection
                    $actionRequired = collect();
                }
            }
            
            // Calculate profile completion
            $completionPercentage = $this->calculateProfileCompletion($candidate);
            $bioDataComplete = $this->checkBioDataCompletion($candidate);
            $documentsUploaded = $this->checkDocumentsUploaded($candidate);
            
            // Get upcoming activities (tests and interviews)
            $upcomingActivities = $this->getUpcomingActivities($candidate);
            
            // Get active applications for timeline (eager load company with column selection)
            $activeApplications = JobApplication::where('candidate_id', $candidate->id)
                ->whereNotIn('status', ['sieving_rejected'])
                ->select('id', 'job_post_id', 'status', 'created_at', 'candidate_id')
                ->with(['jobPost:id,title,company_id', 'jobPost.company:id,name'])
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
     * Only runs if there are unlinked applications (cached check)
     */
    private function linkApplicationsByEmail(Candidate $candidate): void
    {
        // Check cache to see if we've already linked applications recently
        $cacheKey = "candidate:{$candidate->id}:applications_linked";
        if (Cache::has($cacheKey)) {
            return; // Already linked recently, skip
        }
        
        // Check if there are any unlinked applications
        $hasUnlinked = JobApplication::where('email', $candidate->email)
            ->whereNull('candidate_id')
            ->exists();
        
        if ($hasUnlinked) {
            // Find applications by email that don't have a candidate_id and link them
            JobApplication::where('email', $candidate->email)
                ->whereNull('candidate_id')
                ->update(['candidate_id' => $candidate->id]);
        }
        
        // Cache for 1 hour to avoid checking on every request
        Cache::put($cacheKey, true, 3600);
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
        
        // Use try-catch instead of schema checks
        try {
            // Get applications needing aptitude test
            $needingTest = JobApplication::where('candidate_id', $candidate->id)
                ->whereIn('status', ['sieving_passed', 'pending_manual_review'])
                ->whereNull('aptitude_test_completed_at')
                ->select('id', 'job_post_id', 'status', 'candidate_id')
                ->with(['jobPost:id,title'])
                ->limit(3)
                ->get();
            
            foreach ($needingTest as $app) {
                if ($app->jobPost) {
                    $activities->push([
                        'type' => 'aptitude',
                        'job_title' => $app->jobPost->title,
                        'application_id' => $app->id,
                        'time_remaining' => 'soon'
                    ]);
                }
            }
        
            // Get applications needing self interview
            try {
                $needingInterview = JobApplication::where('candidate_id', $candidate->id)
                    ->where('aptitude_test_passed', true)
                    ->whereNull('self_interview_completed_at')
                    ->whereNotIn('status', ['stage_2_passed', 'hired', 'sieving_rejected'])
                    ->select('id', 'job_post_id', 'status', 'candidate_id')
                    ->with(['jobPost:id,title'])
                    ->limit(3)
                    ->get();
                
                foreach ($needingInterview as $app) {
                    if ($app->jobPost) {
                        $activities->push([
                            'type' => 'interview',
                            'job_title' => $app->jobPost->title,
                            'application_id' => $app->id,
                            'time_remaining' => 'soon'
                        ]);
                    }
                }
            } catch (\Exception $e) {
                // Columns don't exist, skip
            }
        } catch (\Exception $e) {
            // Columns don't exist, return empty collection
            return $activities;
        }
        
        return $activities;
    }
    
    /**
     * Get document reminders
     */
    private function getDocumentReminders(Candidate $candidate): \Illuminate\Support\Collection
    {
        $reminders = collect();
        
        // Get active applications (limit to prevent memory issues)
        $activeApps = JobApplication::where('candidate_id', $candidate->id)
            ->whereNotIn('status', ['sieving_rejected', 'hired'])
            ->select('id', 'job_post_id', 'status', 'candidate_id')
            ->with(['jobPost:id,title'])
            ->limit(10) // Reduced limit to prevent memory issues
            ->get();
        
        // Get uploaded documents once (outside loop)
        $uploadedDocs = \App\Models\CandidateDocument::where('candidate_id', $candidate->id)
            ->pluck('document_type')
            ->toArray();
        
        $requiredDocs = ['Resume', 'Cover Letter']; // Customize as needed
        $missingDocs = array_diff($requiredDocs, $uploadedDocs);
        
        // Only add reminders if there are missing docs
        if (count($missingDocs) > 0) {
            foreach ($activeApps as $app) {
                if (!$app->jobPost) {
                    continue;
                }
                
                $reminders->push([
                    'job_title' => $app->jobPost->title,
                    'missing_docs' => $missingDocs
                ]);
                
                // Limit reminders to prevent too many
                if ($reminders->count() >= 5) {
                    break;
                }
            }
        }
        
        return $reminders;
    }
    
    /**
     * Get performance metrics (optimized with single query)
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
        
        try {
            // Use single query with conditional aggregation
            $metricsQuery = JobApplication::where('candidate_id', $candidate->id)
                ->selectRaw('
                    COUNT(*) as total_applications,
                    SUM(CASE WHEN status IN ("sieving_passed", "pending_manual_review", "stage_2_passed", "hired") THEN 1 ELSE 0 END) as passed_applications
                ')
                ->first();
            
            $metrics['total_applications'] = (int) ($metricsQuery->total_applications ?? 0);
            $metrics['passed_applications'] = (int) ($metricsQuery->passed_applications ?? 0);
            
            if ($metrics['total_applications'] > 0) {
                $metrics['success_rate'] = round(($metrics['passed_applications'] / $metrics['total_applications']) * 100, 1);
            }
            
            // Count tests (use try-catch instead of schema checks)
            try {
                $testsQuery = JobApplication::where('candidate_id', $candidate->id)
                    ->selectRaw('
                        SUM(CASE WHEN aptitude_test_completed_at IS NOT NULL THEN 1 ELSE 0 END) as tests_completed,
                        SUM(CASE WHEN status IN ("sieving_passed", "pending_manual_review") AND aptitude_test_completed_at IS NULL THEN 1 ELSE 0 END) as pending_tests
                    ')
                    ->first();
                
                $metrics['tests_completed'] = (int) ($testsQuery->tests_completed ?? 0);
                $metrics['pending_tests'] = (int) ($testsQuery->pending_tests ?? 0);
                $metrics['completed_tests'] = $metrics['tests_completed'];
            } catch (\Exception $e) {
                // Columns don't exist, keep defaults
            }
        } catch (\Exception $e) {
            // If query fails, return defaults
        }
        
        return $metrics;
    }
    
    /**
     * Get recent activity feed
     */
    private function getActivityFeed(Candidate $candidate): \Illuminate\Support\Collection
    {
        $activities = collect();
        
        // Get recent applications (limit to prevent memory issues)
        $recentApps = JobApplication::where('candidate_id', $candidate->id)
            ->select('id', 'job_post_id', 'status', 'created_at', 'candidate_id')
            ->with(['jobPost:id,title'])
            ->orderBy('created_at', 'desc')
            ->limit(5) // Reduced from 10 to 5 to prevent memory issues
            ->get();
        
        foreach ($recentApps as $app) {
            if (!$app->jobPost) {
                continue;
            }
            
            $activities->push([
                'type' => 'application',
                'title' => 'Applied to ' . $app->jobPost->title,
                'description' => 'Status: ' . ucfirst(str_replace('_', ' ', $app->status)),
                'time' => $app->created_at->diffForHumans(),
                'timestamp' => $app->created_at->timestamp, // For sorting
                'link' => route('candidate.application.show', $app)
            ]);
            
            // Add test completion if available
            try {
                if (isset($app->aptitude_test_completed_at) && $app->aptitude_test_completed_at) {
                    $activities->push([
                        'type' => 'test_completed',
                        'title' => 'Completed aptitude test for ' . $app->jobPost->title,
                        'time' => $app->aptitude_test_completed_at->diffForHumans(),
                        'timestamp' => $app->aptitude_test_completed_at->timestamp, // For sorting
                        'link' => route('candidate.application.show', $app)
                    ]);
                }
            } catch (\Exception $e) {
                // Column doesn't exist, skip
            }
            
            // Add interview completion if available
            try {
                if (isset($app->self_interview_completed_at) && $app->self_interview_completed_at) {
                    $activities->push([
                        'type' => 'interview_completed',
                        'title' => 'Completed self interview for ' . $app->jobPost->title,
                        'time' => $app->self_interview_completed_at->diffForHumans(),
                        'timestamp' => $app->self_interview_completed_at->timestamp, // For sorting
                        'link' => route('candidate.application.show', $app)
                    ]);
                }
            } catch (\Exception $e) {
                // Column doesn't exist, skip
            }
        }
        
        // Sort by timestamp and limit to 5 (reduced to prevent memory issues)
        return $activities->sortByDesc('timestamp')->take(5)->values();
    }
    
    /**
     * Get recommended jobs (optimized to prevent memory issues)
     */
    private function getRecommendedJobs(Candidate $candidate)
    {
        // Use a more efficient query to get departments without loading full models
        $appliedDepartments = JobApplication::where('candidate_id', $candidate->id)
            ->join('job_posts', 'job_applications.job_post_id', '=', 'job_posts.id')
            ->select('job_posts.department')
            ->whereNotNull('job_posts.department')
            ->distinct()
            ->limit(5) // Limit to 5 departments max
            ->pluck('department')
            ->filter()
            ->unique()
            ->take(5);
        
        if ($appliedDepartments->isEmpty()) {
            return JobPost::where('is_active', true)
                ->select('id', 'title', 'department', 'company_id', 'created_at')
                ->with('company:id,name')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        }
        
        return JobPost::where('is_active', true)
            ->whereIn('department', $appliedDepartments->toArray())
            ->select('id', 'title', 'department', 'company_id', 'created_at')
            ->with('company:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    }
}

