@extends('layouts.admin')

@section('title', 'Reports & Analytics')
@section('header-description', 'View detailed reports on job and loan applications.')

@section('content')
    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-600">Job Applications</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $jobFunnel['submitted'] }}</p>
            <p class="text-xs text-green-600 mt-2">{{ $conversionMetrics['job_conversion'] }}% hired</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-600">Candidates Hired</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $jobFunnel['hired'] }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-600">Loan Applications</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $loanFunnel['total'] }}</p>
            <p class="text-xs text-blue-600 mt-2">{{ $conversionMetrics['loan_approval'] }}% approved</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-600">Loans Approved</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $loanFunnel['approved'] }}</p>
        </div>
    </div>

    <!-- Report Links -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Job Applications Report</h3>
            <p class="text-gray-600 text-sm mb-4">Analyze job application funnel, conversion rates, and hiring performance by job position.</p>
            <a href="{{ route('admin.reports.job-applications') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-teal-800 hover:bg-teal-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                View Report
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Loan Applications Report</h3>
            <p class="text-gray-600 text-sm mb-4">Track loan application statuses, approval rates, and performance by loan type.</p>
            <a href="{{ route('admin.reports.loan-applications') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-teal-800 hover:bg-teal-900">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                View Report
            </a>
        </div>
    </div>

    <!-- Application Funnels -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Job Application Funnel</h3>
            <div class="space-y-4">
                @php
                    $total = $jobFunnel['submitted'] ?: 1;
                    $stages = [
                        'submitted' => 'Submitted',
                        'sieving_passed' => 'Sieving Passed',
                        'aptitude_passed' => 'Aptitude Passed',
                        'interview_passed' => 'Interview Passed',
                        'hired' => 'Hired',
                    ];
                @endphp
                @foreach($stages as $key => $label)
                    @php $count = $jobFunnel[$key] ?? 0; $percent = ($count / $total) * 100; @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ $count }} ({{ round($percent, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-teal-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Loan Application Funnel</h3>
            <div class="space-y-4">
                @php
                    $totalLoans = $loanFunnel['total'] ?: 1;
                    $loanStages = [
                        'total' => 'Total Applications',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'disbursed' => 'Disbursed',
                    ];
                @endphp
                @foreach($loanStages as $key => $label)
                    @php $count = $loanFunnel[$key] ?? 0; $percent = ($count / $totalLoans) * 100; @endphp
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ $count }} ({{ round($percent, 1) }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
