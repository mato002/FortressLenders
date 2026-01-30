<?php

namespace App\Http\Controllers\Admin;

use App\Models\JobApplication;
use App\Models\LoanApplication;
use App\Models\ContactMessage;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ReportController
{
    public function index(): View
    {
        $jobFunnel = $this->getJobApplicationFunnel();
        $loanFunnel = $this->getLoanApplicationFunnel();
        $conversionMetrics = $this->getConversionMetrics();
        $recentTrends = $this->getRecentTrends();

        return view('admin.reports.dashboard', [
            'jobFunnel' => $jobFunnel,
            'loanFunnel' => $loanFunnel,
            'conversionMetrics' => $conversionMetrics,
            'recentTrends' => $recentTrends,
        ]);
    }

    public function jobApplicationsReport(Request $request): View
    {
        $startDate = $request->input('start_date', now()->subMonths(1)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $applications = JobApplication::whereBetween('created_at', [$startDate, $endDate])
            ->with('jobPost')
            ->get();

        $funnel = [
            'total' => $applications->count(),
            'sieving_passed' => $applications->where('status', 'sieving_passed')->count(),
            'sieving_failed' => $applications->where('status', 'sieving_failed')->count(),
            'aptitude_passed' => $applications->where('status', 'aptitude_passed')->count(),
            'aptitude_failed' => $applications->where('status', 'aptitude_failed')->count(),
            'interview_passed' => $applications->where('status', 'interview_passed')->count(),
            'interview_failed' => $applications->where('status', 'interview_failed')->count(),
            'hired' => $applications->where('status', 'hired')->count(),
        ];

        $byJob = $applications->groupBy('job_post_id')->map(function ($group) {
            return [
                'job' => $group->first()->jobPost->title ?? 'Unknown',
                'count' => $group->count(),
                'hired' => $group->where('status', 'hired')->count(),
            ];
        });

        return view('admin.reports.job-applications', [
            'applications' => $applications,
            'funnel' => $funnel,
            'byJob' => $byJob,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function loanApplicationsReport(Request $request): View
    {
        $startDate = $request->input('start_date', now()->subMonths(1)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $applications = LoanApplication::whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $funnel = [
            'total' => $applications->count(),
            'pending' => $applications->where('status', 'pending')->count(),
            'under_review' => $applications->where('status', 'under_review')->count(),
            'approved' => $applications->where('status', 'approved')->count(),
            'rejected' => $applications->where('status', 'rejected')->count(),
            'disbursed' => $applications->where('status', 'disbursed')->count(),
        ];

        $byLoanType = $applications->groupBy('loan_type')->map(function ($group) {
            return [
                'loan_type' => $group->first()->loan_type ?? 'Unknown',
                'count' => $group->count(),
                'approved' => $group->where('status', 'approved')->count(),
                'total_amount' => $group->sum('amount_requested'),
            ];
        });

        return view('admin.reports.loan-applications', [
            'applications' => $applications,
            'funnel' => $funnel,
            'byLoanType' => $byLoanType,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function exportJobApplications(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonths(1)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $format = $request->input('format', 'csv');

        $applications = JobApplication::whereBetween('created_at', [$startDate, $endDate])
            ->with('jobPost')
            ->get()
            ->map(function ($app) {
                return [
                    'Full Name' => $app->full_name,
                    'Email' => $app->email,
                    'Phone' => $app->phone,
                    'Job Title' => $app->jobPost->title ?? 'N/A',
                    'Status' => ucfirst(str_replace('_', ' ', $app->status)),
                    'Applied On' => $app->created_at->format('Y-m-d'),
                    'Years Experience' => $app->years_of_experience,
                    'Education' => $app->education_level,
                ];
            });

        if ($format === 'pdf') {
            return PDF::loadView('admin.reports.export.job-applications-pdf', ['applications' => $applications])
                ->download('job-applications-report.pdf');
        }

        return Excel::download(new class($applications) implements \Maatwebsite\Excel\Concerns\FromCollection {
            public function __construct(private $data) {}
            public function collection() { return collect($this->data); }
        }, 'job-applications-report.csv');
    }

    public function exportLoanApplications(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonths(1)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $format = $request->input('format', 'csv');

        $applications = LoanApplication::whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->map(function ($app) {
                return [
                    'Full Name' => $app->full_name,
                    'Email' => $app->email,
                    'Phone' => $app->phone,
                    'Loan Type' => $app->loan_type,
                    'Amount (KES)' => number_format($app->amount_requested, 2),
                    'Status' => ucfirst(str_replace('_', ' ', $app->status)),
                    'Applied On' => $app->created_at->format('Y-m-d'),
                    'Client Type' => ucfirst($app->client_type ?? 'N/A'),
                ];
            });

        if ($format === 'pdf') {
            return PDF::loadView('admin.reports.export.loan-applications-pdf', ['applications' => $applications])
                ->download('loan-applications-report.pdf');
        }

        return Excel::download(new class($applications) implements \Maatwebsite\Excel\Concerns\FromCollection {
            public function __construct(private $data) {}
            public function collection() { return collect($this->data); }
        }, 'loan-applications-report.csv');
    }

    private function getJobApplicationFunnel()
    {
        return [
            'submitted' => JobApplication::count(),
            'sieving_passed' => JobApplication::where('status', 'sieving_passed')->count(),
            'aptitude_passed' => JobApplication::where('status', 'aptitude_passed')->count(),
            'interview_passed' => JobApplication::where('status', 'interview_passed')->count(),
            'hired' => JobApplication::where('status', 'hired')->count(),
        ];
    }

    private function getLoanApplicationFunnel()
    {
        return [
            'total' => LoanApplication::count(),
            'approved' => LoanApplication::where('status', 'approved')->count(),
            'rejected' => LoanApplication::where('status', 'rejected')->count(),
            'disbursed' => LoanApplication::where('status', 'disbursed')->count(),
        ];
    }

    private function getConversionMetrics()
    {
        $jobApps = JobApplication::count();
        $hired = JobApplication::where('status', 'hired')->count();
        
        $loanApps = LoanApplication::count();
        $approved = LoanApplication::where('status', 'approved')->count();

        return [
            'job_conversion' => $jobApps > 0 ? round(($hired / $jobApps) * 100, 2) : 0,
            'loan_approval' => $loanApps > 0 ? round(($approved / $loanApps) * 100, 2) : 0,
        ];
    }

    private function getRecentTrends()
    {
        return JobApplication::select(DB::raw('DATE(created_at) as date'))
            ->selectRaw('COUNT(*) as applications')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}
