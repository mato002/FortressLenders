<?php

namespace App\Http\Controllers\Admin;

use App\Models\JobApplication;
use App\Models\Candidate;
use App\Models\JobPost;
use App\Models\LoanApplication;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController
{
    public function dashboard(): View
    {
        $timeRange = request('range', '30'); // days
        $startDate = Carbon::now()->subDays($timeRange);

        // Job Application Funnel
        $funnel = $this->getApplicationFunnel($startDate);
        
        // Job Application Metrics
        $jobMetrics = $this->getJobMetrics($startDate);
        
        // Hiring Trends
        $hiringTrends = $this->getHiringTrends($startDate);
        
        // Job Performance
        $jobPerformance = $this->getJobPerformance($startDate);
        
        // Candidate Demographics
        $demographics = $this->getCandidateDemographics();
        
        // Interviewer Performance
        $interviewerPerformance = $this->getInterviewerPerformance($startDate);
        
        // Time to Hire
        $timeToHire = $this->getTimeToHire();

        return view('admin.analytics.dashboard', [
            'funnel' => $funnel,
            'jobMetrics' => $jobMetrics,
            'hiringTrends' => $hiringTrends,
            'jobPerformance' => $jobPerformance,
            'demographics' => $demographics,
            'interviewerPerformance' => $interviewerPerformance,
            'timeToHire' => $timeToHire,
            'timeRange' => $timeRange,
        ]);
    }

    private function getApplicationFunnel($startDate)
    {
        $stages = [
            'submitted' => JobApplication::where('created_at', '>=', $startDate)->count(),
            'sieving' => JobApplication::where('created_at', '>=', $startDate)->where('status', 'sieving_passed')->count(),
            'aptitude' => JobApplication::where('created_at', '>=', $startDate)->where('status', 'aptitude_passed')->count(),
            'interview' => JobApplication::where('created_at', '>=', $startDate)->where('status', 'interview_passed')->count(),
            'hired' => JobApplication::where('created_at', '>=', $startDate)->where('status', 'hired')->count(),
        ];

        // Calculate conversion rates
        $submitted = $stages['submitted'] ?: 1;
        
        return [
            'stages' => $stages,
            'conversionRates' => [
                'sieving' => round(($stages['sieving'] / $submitted) * 100, 2),
                'aptitude' => round(($stages['aptitude'] / $submitted) * 100, 2),
                'interview' => round(($stages['interview'] / $submitted) * 100, 2),
                'hired' => round(($stages['hired'] / $submitted) * 100, 2),
            ],
        ];
    }

    private function getJobMetrics($startDate)
    {
        return [
            'totalApplications' => JobApplication::where('created_at', '>=', $startDate)->count(),
            'acceptedApplications' => JobApplication::where('created_at', '>=', $startDate)->where('status', 'hired')->count(),
            'averageApplicationsPerJob' => round(
                JobApplication::where('created_at', '>=', $startDate)
                    ->groupBy('job_post_id')
                    ->selectRaw('COUNT(*) as count')
                    ->pluck('count')
                    ->avg() ?? 0,
                2
            ),
            'activeJobs' => JobPost::where('is_active', true)->count(),
            'totalCandidates' => Candidate::count(),
            'newCandidates' => Candidate::where('created_at', '>=', $startDate)->count(),
        ];
    }

    private function getHiringTrends($startDate)
    {
        return JobApplication::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'))
            ->selectRaw('COUNT(*) as applications')
            ->selectRaw('SUM(CASE WHEN status = "hired" THEN 1 ELSE 0 END) as hired')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'applications' => $item->applications,
                    'hired' => $item->hired,
                ];
            });
    }

    private function getJobPerformance($startDate)
    {
        return JobPost::where('is_active', true)
            ->with([
                'applications' => function ($query) use ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                }
            ])
            ->get()
            ->map(function ($job) {
                $apps = $job->applications;
                $hired = $apps->where('status', 'hired')->count();
                
                return [
                    'jobTitle' => $job->title,
                    'totalApplications' => $apps->count(),
                    'hired' => $hired,
                    'conversionRate' => $apps->count() > 0 ? round(($hired / $apps->count()) * 100, 2) : 0,
                ];
            })
            ->sortByDesc('totalApplications')
            ->take(10);
    }

    private function getCandidateDemographics()
    {
        return [
            'byExperience' => JobApplication::select('years_of_experience', DB::raw('COUNT(*) as count'))
                ->groupBy('years_of_experience')
                ->orderBy('years_of_experience')
                ->get(),
            'byEducation' => JobApplication::select('education_level', DB::raw('COUNT(*) as count'))
                ->groupBy('education_level')
                ->orderBy('education_level')
                ->get(),
            'byLocation' => JobApplication::select('current_location', DB::raw('COUNT(*) as count'))
                ->groupBy('current_location')
                ->orderByDesc('count')
                ->limit(10)
                ->get(),
        ];
    }

    private function getInterviewerPerformance($startDate)
    {
        return DB::table('job_application_reviews')
            ->where('created_at', '>=', $startDate)
            ->select('reviewer_id', DB::raw('COUNT(*) as reviews_given'))
            ->selectRaw('SUM(CASE WHEN recommendation = "proceed" THEN 1 ELSE 0 END) as positive_reviews')
            ->groupBy('reviewer_id')
            ->get()
            ->map(function ($item) {
                return [
                    'reviewerId' => $item->reviewer_id,
                    'reviewsGiven' => $item->reviews_given,
                    'positiveRate' => round(($item->positive_reviews / $item->reviews_given) * 100, 2),
                ];
            });
    }

    private function getTimeToHire()
    {
        return JobApplication::where('status', 'hired')
            ->select(DB::raw('AVG(DATEDIFF(updated_at, created_at)) as avg_days'))
            ->selectRaw('MIN(DATEDIFF(updated_at, created_at)) as min_days')
            ->selectRaw('MAX(DATEDIFF(updated_at, created_at)) as max_days')
            ->first();
    }
}
