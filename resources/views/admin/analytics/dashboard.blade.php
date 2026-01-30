@extends('layouts.admin')

@section('title', 'Analytics Dashboard')
@section('header-description', 'Comprehensive recruitment and hiring metrics.')

@section('header-actions')
    <div class="flex items-center gap-2">
        <select onchange="window.location.href='?range=' + this.value" class="px-4 py-2 border border-slate-200 rounded-lg text-sm font-medium text-slate-700 bg-white hover:bg-slate-50">
            <option value="7" {{ request('range', '30') == '7' ? 'selected' : '' }}>Last 7 Days</option>
            <option value="30" {{ request('range', '30') == '30' ? 'selected' : '' }}>Last 30 Days</option>
            <option value="90" {{ request('range', '30') == '90' ? 'selected' : '' }}>Last 90 Days</option>
            <option value="365" {{ request('range', '30') == '365' ? 'selected' : '' }}>Last Year</option>
        </select>
    </div>
@endsection

@section('content')
    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Applications</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $jobMetrics['totalApplications'] }}</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Hired Candidates</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $jobMetrics['acceptedApplications'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $jobMetrics['totalApplications'] > 0 ? round(($jobMetrics['acceptedApplications'] / $jobMetrics['totalApplications']) * 100, 1) : 0 }}% success rate</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">Active Jobs</p>
                    <p class="text-3xl font-bold text-teal-600 mt-2">{{ $jobMetrics['activeJobs'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">{{ $jobMetrics['averageApplicationsPerJob'] }} avg applications</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-teal-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.728 0-7.333-.9-10.414-2.469M5 12a7 7 0 1114 0m0 0a7 7 0 11-14 0" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 font-medium">New Candidates</p>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $jobMetrics['newCandidates'] }}</p>
                    <p class="text-xs text-gray-500 mt-2">In selected period</p>
                </div>
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Application Funnel -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Application Funnel</h3>
            <div class="space-y-4">
                @foreach(['submitted' => 'Submitted', 'sieving' => 'Sieving', 'aptitude' => 'Aptitude', 'interview' => 'Interview', 'hired' => 'Hired'] as $key => $label)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                            <span class="text-sm font-bold text-gray-900">{{ $funnel['stages'][$key] }} ({{ $funnel['conversionRates'][$key] }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-teal-600 h-2 rounded-full" style="width: {{ $funnel['conversionRates'][$key] }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Time to Hire -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Time to Hire</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                    <div>
                        <p class="text-sm text-gray-600">Average Days to Hire</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $timeToHire->avg_days ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-green-50 rounded-lg">
                        <p class="text-xs text-gray-600">Fastest Hire</p>
                        <p class="text-xl font-bold text-green-600 mt-1">{{ $timeToHire->min_days ?? '—' }} days</p>
                    </div>
                    <div class="p-4 bg-amber-50 rounded-lg">
                        <p class="text-xs text-gray-600">Slowest Hire</p>
                        <p class="text-xl font-bold text-amber-600 mt-1">{{ $timeToHire->max_days ?? '—' }} days</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hiring Trends Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Hiring Trends</h3>
        <div class="h-64">
            <canvas id="trendsChart"></canvas>
        </div>
    </div>

    <!-- Job Performance -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Top Job Positions by Applications</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 font-semibold text-gray-700">Job Title</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Applications</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Hired</th>
                        <th class="text-right py-3 px-4 font-semibold text-gray-700">Conversion Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($jobPerformance as $job)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4 text-gray-900">{{ $job['jobTitle'] }}</td>
                            <td class="text-right py-3 px-4 text-gray-600">{{ $job['totalApplications'] }}</td>
                            <td class="text-right py-3 px-4 font-semibold text-green-600">{{ $job['hired'] }}</td>
                            <td class="text-right py-3 px-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                                    {{ $job['conversionRate'] }}%
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Candidate Demographics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- By Experience -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">By Experience Level</h3>
            <div class="space-y-3">
                @foreach($demographics['byExperience'] as $item)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ $item->years_of_experience ?? 'N/A' }} years</span>
                        <span class="text-sm font-bold text-gray-900">{{ $item->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- By Education -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">By Education Level</h3>
            <div class="space-y-3">
                @foreach($demographics['byEducation'] as $item)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ $item->education_level ?? 'N/A' }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ $item->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- By Location -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Top Candidate Locations</h3>
            <div class="space-y-3">
                @foreach($demographics['byLocation'] as $item)
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">{{ $item->current_location ?? 'N/A' }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ $item->count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Hiring Trends Chart
        const trendsCtx = document.getElementById('trendsChart').getContext('2d');
        const dates = {!! json_encode($hiringTrends->pluck('date')) !!};
        const applications = {!! json_encode($hiringTrends->pluck('applications')) !!};
        const hired = {!! json_encode($hiringTrends->pluck('hired')) !!};

        new Chart(trendsCtx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Applications Submitted',
                        data: applications,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                    },
                    {
                        label: 'Candidates Hired',
                        data: hired,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });
    </script>
@endsection
