<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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
            
            // Get statistics for dashboard overview
            $stats = [
                'total' => JobApplication::where('candidate_id', $candidate->id)->count(),
                'pending' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'pending')->count(),
                'sieving_passed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'sieving_passed')->count(),
                'sieving_rejected' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'sieving_rejected')->count(),
                'aptitude_failed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'aptitude_failed')->count(),
                'stage_2_passed' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'stage_2_passed')->count(),
                'hired' => JobApplication::where('candidate_id', $candidate->id)->where('status', 'hired')->count(),
            ];
            
            // Get recent applications (last 5) for quick overview
            $recentApplications = JobApplication::where('candidate_id', $candidate->id)
                ->with(['jobPost', 'jobPost.company'])
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
            
            return view('candidate.dashboard', compact('stats', 'recentApplications', 'actionRequired', 'candidate'));
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
     * Link existing applications by email to the candidate.
     */
    private function linkApplicationsByEmail(Candidate $candidate): void
    {
        // Only try to link if candidate_id column exists
        if (!Schema::hasColumn('job_applications', 'candidate_id')) {
            return;
        }
        
        try {
            JobApplication::where('email', $candidate->email)
                ->whereNull('candidate_id')
                ->update(['candidate_id' => $candidate->id]);
        } catch (\Illuminate\Database\QueryException $e) {
            if (str_contains($e->getMessage(), 'candidate_id')) {
                // Column doesn't exist, skip linking
                return;
            }
            throw $e;
        }
    }
}

