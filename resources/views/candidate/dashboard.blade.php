@extends('layouts.candidate')

@section('title', 'Dashboard')
@section('header-description', 'Welcome to your candidate portal')

@section('content')
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-2xl shadow-lg p-6 sm:p-8 mb-6 text-white">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">Welcome back, {{ $candidate->name }}!</h1>
                <p class="text-teal-100 text-sm sm:text-base">Here's an overview of your application status and quick actions.</p>
            </div>
            <a href="{{ route('careers.index') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-semibold transition">
                Browse Jobs
            </a>
        </div>
    </div>

    <!-- Two-column layout: Main content + Sidebar with charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Completion -->
            @include('candidate.components.profile-completion')

            <!-- Document Reminders -->
            @include('candidate.components.document-reminders')

            <!-- Upcoming Activities -->
            @include('candidate.components.interview-schedule')

            <!-- Application Timeline -->
            @include('candidate.components.application-timeline')

            <!-- Recent Activity Feed -->
            @include('candidate.components.activity-feed')
        </div>

        <aside class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Profile Completion</h3>
                <div class="flex items-center gap-4">
                    <div class="w-36 h-36">
                        <canvas id="profileCompletionChart" width="160" height="160"></canvas>
                    </div>
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ $completionPercentage }}%</p>
                        <p class="text-sm text-gray-500">Complete</p>
                        <p class="mt-3 text-sm text-gray-600">Improve your profile to boost application success rate.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Applications Status</h3>
                <canvas id="applicationsChart" width="300" height="180"></canvas>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Performance</h3>
                <div class="grid grid-cols-2 gap-3 text-center">
                    <div>
                        <p class="text-sm text-gray-500">Success Rate</p>
                        <p class="text-lg font-bold text-gray-900">{{ round($performanceMetrics['success_rate'] ?? 0) }}%</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tests Completed</p>
                        <p class="text-lg font-bold text-gray-900">{{ $performanceMetrics['tests_completed'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Recommended Jobs</h3>
                <div class="space-y-3">
                    @foreach($recommendedJobs as $job)
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="font-semibold text-gray-900">{{ $job->title }}</p>
                            <p class="text-sm text-gray-500">{{ $job->company->name ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <p class="text-sm text-gray-500 mb-1">Total</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <p class="text-sm text-gray-500 mb-1">Pending</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-green-100 p-4 bg-green-50">
            <p class="text-sm text-gray-500 mb-1">Passed</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['sieving_passed'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-red-100 p-4 bg-red-50">
            <p class="text-sm text-gray-500 mb-1">Rejected</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['sieving_rejected'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-blue-100 p-4 bg-blue-50">
            <p class="text-sm text-gray-500 mb-1">Stage 2</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['stage_2_passed'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-purple-100 p-4 bg-purple-50">
            <p class="text-sm text-gray-500 mb-1">Hired</p>
            <p class="text-2xl font-bold text-purple-600">{{ $stats['hired'] }}</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('candidate.applications') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-teal-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">My Applications</p>
                    <p class="text-sm text-gray-500">View all applications</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('candidate.bio-data.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Bio Data</p>
                    <p class="text-sm text-gray-500">Update your profile</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('candidate.documents.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Documents</p>
                    <p class="text-sm text-gray-500">Manage documents</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('candidate.appraisals.index') }}" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Appraisals</p>
                    <p class="text-sm text-gray-500">View reviews</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Action Required Section -->
    @if($actionRequired->count() > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-amber-900 mb-2">Action Required</h2>
                    <p class="text-sm text-amber-800 mb-4">You have {{ $actionRequired->count() }} application(s) that require your attention:</p>
                    <div class="space-y-3">
                        @foreach($actionRequired as $app)
                            <div class="bg-white rounded-lg p-4 border border-amber-200">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $app->jobPost->title }}</p>
                                        <p class="text-sm text-gray-600">
                                            @if(in_array($app->status, ['sieving_passed', 'pending_manual_review']) && !$app->aptitude_test_completed_at)
                                                Aptitude test pending
                                            @elseif($app->aptitude_test_passed && !$app->self_interview_completed_at)
                                                Self interview pending
                                            @endif
                                        </p>
                                    </div>
                                    <div class="flex gap-2">
                                        @if(in_array($app->status, ['sieving_passed', 'pending_manual_review']) && !$app->aptitude_test_completed_at)
                                            <button 
                                                type="button"
                                                onclick="openAptitudeTestModal({{ $app->id }})"
                                                class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-semibold"
                                            >
                                                Take Test
                                            </button>
                                        @elseif($app->aptitude_test_passed && !$app->self_interview_completed_at)
                                            <a href="{{ route('self-interview.show', $app) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                                                Start Interview
                                            </a>
                                        @endif
                                        <a href="{{ route('candidate.application.show', $app) }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors text-sm font-semibold">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('candidate.applications') }}" class="text-sm font-semibold text-amber-800 hover:text-amber-900">
                            View all applications →
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Applications -->
    @if($recentApplications->count() > 0)
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Recent Applications</h2>
                <a href="{{ route('candidate.applications') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-700">
                    View All →
                </a>
            </div>
            <div class="p-4 overflow-x-auto">
                <table class="min-w-full text-left divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-sm font-medium text-gray-600">Job</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-600">Applied</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-600">Status</th>
                            <th class="px-4 py-3 text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @foreach($recentApplications as $application)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 align-middle">
                                    <div class="font-semibold text-gray-900">{{ $application->jobPost->title }}</div>
                                    <div class="text-sm text-gray-500">{{ $application->jobPost->company->name ?? '' }}</div>
                                </td>
                                <td class="px-4 py-3 align-middle text-sm text-gray-600">{{ $application->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3 align-middle">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        @if($application->status === 'hired') bg-purple-100 text-purple-800
                                        @elseif($application->status === 'stage_2_passed') bg-blue-100 text-blue-800
                                        @elseif(in_array($application->status, ['sieving_passed'])) bg-green-100 text-green-800
                                        @elseif(in_array($application->status, ['sieving_rejected'])) bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <div class="flex gap-2">
                                        <a href="{{ route('candidate.application.show', $application) }}" class="px-3 py-1.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">View</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">No Applications Yet</h3>
            <p class="text-gray-500 mb-6">Start your journey by applying to available positions.</p>
            <a href="{{ route('careers.index') }}" class="inline-flex items-center px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                Browse Available Positions
            </a>
        </div>
    @endif

    <!-- Aptitude Test Modal -->
    <div id="aptitudeTestModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeAptitudeTestModal()"></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-gray-900" id="modal-title">Aptitude Test</h3>
                        <button type="button" onclick="closeAptitudeTestModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="aptitudeTestContent" class="max-h-[80vh] overflow-y-auto">
                        <!-- Test content will be loaded here via AJAX -->
                        <div class="flex items-center justify-center py-12">
                            <div class="text-center">
                                <svg class="animate-spin h-8 w-8 text-teal-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-gray-600">Loading test...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openAptitudeTestModal(applicationId) {
        const modal = document.getElementById('aptitudeTestModal');
        const content = document.getElementById('aptitudeTestContent');
        
        // Show modal
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Load test content via AJAX
        content.innerHTML = `
            <div class="flex items-center justify-center py-12">
                <div class="text-center">
                    <svg class="animate-spin h-8 w-8 text-teal-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-600">Loading test...</p>
                </div>
            </div>
        `;
        
        fetch(`/aptitude-test/${applicationId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html',
            }
        })
        .then(response => {
            // If the backend redirected (e.g. to careers page), don't inject that HTML.
            if (response.redirected) {
                throw new Error('redirected');
            }
            return response.text();
        })
        .then(html => {
            // The response should be the modal content directly
            content.innerHTML = html;
            
            // Handle form submission within modal - submit normally but close modal first
            const form = content.querySelector('#testForm');
            if (form) {
                // Override form submission to close modal and then submit
                const originalSubmit = form.onsubmit;
                form.addEventListener('submit', function(e) {
                    // Close modal immediately
                    closeAptitudeTestModal();
                    // Let form submit normally - it will redirect to results/next step
                    // The page will reload/redirect, so modal is already closed
                });
            }
        })
        .catch(error => {
            console.error('Error loading test:', error);
            content.innerHTML = `
                <div class="py-10 px-6">
                    <div class="max-w-xl mx-auto text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Aptitude Test Not Available</h3>
                        <p class="text-gray-600 mb-6">There are no aptitude test questions set up yet, or you’re not eligible to take the test right now.</p>
                        <button type="button" onclick="closeAptitudeTestModal()" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors text-sm font-semibold">
                            Close
                        </button>
                    </div>
                </div>
            `;
        });
    }
    
    function closeAptitudeTestModal() {
        const modal = document.getElementById('aptitudeTestModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAptitudeTestModal();
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const stats = @json($stats ?? []);
        const completion = {{ $completionPercentage ?? 0 }};

        // Profile completion doughnut
        const profileEl = document.getElementById('profileCompletionChart');
        if (profileEl && typeof Chart !== 'undefined') {
            new Chart(profileEl.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Complete','Remaining'],
                    datasets: [{
                        data: [completion, Math.max(0, 100 - completion)],
                        backgroundColor: ['#059669', '#e6f4ef'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {legend: {display: false}}
                }
            });
        }

        // Applications status bar
        const appsEl = document.getElementById('applicationsChart');
        if (appsEl && typeof Chart !== 'undefined') {
            const data = [stats.pending || 0, stats.sieving_passed || 0, stats.sieving_rejected || 0, stats.stage_2_passed || 0, stats.hired || 0];
            new Chart(appsEl.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Pending','Passed','Rejected','Stage 2','Hired'],
                    datasets: [{
                        label: 'Applications',
                        data: data,
                        backgroundColor: ['#f59e0b','#10b981','#ef4444','#3b82f6','#8b5cf6']
                    }]
                },
                options: {responsive: true, plugins: {legend: {display: false}}, scales: {y: {beginAtZero: true}}}
            });
        }
    });
</script>
@endpush
