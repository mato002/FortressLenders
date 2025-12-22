@extends('layouts.candidate')

@section('title', 'My Applications')
@section('header-description', 'Track your job applications and next steps')

@section('content')
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

    <!-- Next Steps / Action Required -->
    @php
        $pendingActions = $applications->filter(function($app) {
            return in_array($app->status, ['sieving_passed', 'pending_manual_review']) && !$app->aptitude_test_completed_at;
        });
    @endphp
    
    @if($pendingActions->count() > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl shadow-sm p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-amber-900 mb-2">Action Required</h2>
                    <p class="text-sm text-amber-800 mb-4">You have {{ $pendingActions->count() }} application(s) that require your attention:</p>
                    <div class="space-y-3">
                        @foreach($pendingActions as $app)
                            <div class="bg-white rounded-lg p-4 border border-amber-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $app->jobPost->title }}</p>
                                        <p class="text-sm text-gray-600">Aptitude test pending</p>
                                    </div>
                                    <a href="{{ route('aptitude-test.show', $app) }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-semibold">
                                        Take Test
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Applications List -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h2 class="text-xl font-bold text-gray-900">Your Applications</h2>
        </div>

        @if($applications->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($applications as $application)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $application->jobPost->title }}</h3>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        @if($application->status === 'hired') bg-purple-100 text-purple-800
                                        @elseif($application->status === 'stage_2_passed') bg-blue-100 text-blue-800
                                        @elseif($application->status === 'sieving_passed') bg-green-100 text-green-800
                                        @elseif($application->status === 'sieving_rejected') bg-red-100 text-red-800
                                        @elseif($application->status === 'pending_manual_review') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500 mb-2">
                                    Applied on {{ $application->created_at->format('M d, Y') }}
                                </p>
                                
                                <!-- Next Step Indicator -->
                                @if(in_array($application->status, ['sieving_passed', 'pending_manual_review']) && !$application->aptitude_test_completed_at)
                                    <div class="mt-3 bg-amber-50 border border-amber-200 rounded-lg p-3">
                                        <p class="text-sm font-semibold text-amber-900 mb-1">📋 Next Step: Complete Aptitude Test</p>
                                        <p class="text-xs text-amber-700">You've passed the initial screening. Take the aptitude test to proceed.</p>
                                    </div>
                                @elseif($application->aptitude_test_passed && $application->status !== 'stage_2_passed' && $application->status !== 'hired')
                                    <div class="mt-3 bg-blue-50 border border-blue-200 rounded-lg p-3">
                                        <p class="text-sm font-semibold text-blue-900 mb-1">✅ Aptitude Test Passed</p>
                                        <p class="text-xs text-blue-700">Waiting for next interview stage. We'll contact you soon.</p>
                                    </div>
                                @elseif($application->status === 'stage_2_passed')
                                    <div class="mt-3 bg-green-50 border border-green-200 rounded-lg p-3">
                                        <p class="text-sm font-semibold text-green-900 mb-1">🎉 Stage 2 Complete</p>
                                        <p class="text-xs text-green-700">You've passed both aptitude test and first interview. Next: In-person interview.</p>
                                    </div>
                                @elseif($application->status === 'sieving_rejected')
                                    <div class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3">
                                        <p class="text-sm font-semibold text-red-900 mb-1">Application Not Selected</p>
                                        <p class="text-xs text-red-700">This application did not meet the initial requirements.</p>
                                    </div>
                                @endif

                                @if($application->aiSievingDecision)
                                    <div class="flex items-center gap-4 text-sm mt-3">
                                        <span class="text-gray-600">
                                            AI Score: <strong class="text-gray-900">{{ $application->aiSievingDecision->ai_score }}/100</strong>
                                        </span>
                                        <span class="text-gray-600">
                                            Confidence: <strong class="text-gray-900">{{ number_format($application->aiSievingDecision->ai_confidence * 100, 1) }}%</strong>
                                        </span>
                                    </div>
                                @endif
                                @if($application->aptitude_test_completed_at)
                                    <div class="mt-2 text-sm">
                                        <span class="text-gray-600">Aptitude Test: </span>
                                        <span class="font-semibold {{ $application->aptitude_test_passed ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $application->aptitude_test_passed ? 'Passed' : 'Failed' }} ({{ $application->aptitude_test_score }}%)
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('candidate.application.show', $application) }}" 
                                   class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                                    View Details
                                </a>
                                @if(in_array($application->status, ['sieving_passed', 'pending_manual_review']) && !$application->aptitude_test_completed_at)
                                    <a href="{{ route('aptitude-test.show', $application) }}" 
                                       class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-semibold">
                                        Take Test
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $applications->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Applications Yet</h3>
                <p class="text-gray-500 mb-6">You haven't submitted any job applications yet.</p>
                <a href="{{ route('careers.index') }}" class="inline-flex items-center px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                    Browse Available Positions
                </a>
            </div>
        @endif
    </div>
@endsection
