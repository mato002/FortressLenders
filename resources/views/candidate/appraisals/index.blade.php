@extends('layouts.candidate')

@section('title', 'Appraisals')
@section('header-description', 'View your performance reviews, HR communications, and warnings')

@section('content')
    <div class="space-y-6">
        <!-- Performance Reviews -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Performance Reviews</h2>
                <p class="text-sm text-gray-600 mt-1">Your performance evaluation history</p>
            </div>
            <div class="p-6">
                @if($performanceReviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($performanceReviews as $review)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ $review->title }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($review->content, 150) }}</p>
                                        <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                            <span>Review Date: {{ $review->review_date ? $review->review_date->format('M d, Y') : 'N/A' }}</span>
                                            <span>Created by: {{ $review->createdBy->name ?? 'HR' }}</span>
                                            <span>{{ $review->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        <a href="{{ route('candidate.appraisals.show', $review) }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                                            View Details
                                        </a>
                                        @if(!$review->is_acknowledged)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                Pending Acknowledgment
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                Acknowledged
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600">No performance reviews yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- HR Communications -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">HR Communications</h2>
                <p class="text-sm text-gray-600 mt-1">Messages and communications from HR</p>
            </div>
            <div class="p-6">
                @if($hrCommunications->count() > 0)
                    <div class="space-y-4">
                        @foreach($hrCommunications as $communication)
                            <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ $communication->title }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($communication->content, 150) }}</p>
                                        <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                            <span>From: {{ $communication->createdBy->name ?? 'HR' }}</span>
                                            <span>{{ $communication->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        <a href="{{ route('candidate.appraisals.show', $communication) }}" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                                            View Details
                                        </a>
                                        @if(!$communication->is_acknowledged)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                Pending Acknowledgment
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                Acknowledged
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600">No HR communications yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Warnings -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Warnings</h2>
                <p class="text-sm text-gray-600 mt-1">Formal warnings and notices</p>
            </div>
            <div class="p-6">
                @if($warnings->count() > 0)
                    <div class="space-y-4">
                        @foreach($warnings as $warning)
                            <div class="border border-red-200 rounded-lg p-4 bg-red-50 hover:bg-red-100 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <h3 class="font-semibold text-gray-900">{{ $warning->title }}</h3>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold 
                                                @if($warning->severity === 'high') bg-red-200 text-red-900
                                                @elseif($warning->severity === 'medium') bg-orange-200 text-orange-900
                                                @else bg-yellow-200 text-yellow-900
                                                @endif">
                                                {{ ucfirst($warning->severity ?? 'low') }} Severity
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-700 mt-1">{{ Str::limit($warning->content, 150) }}</p>
                                        <div class="flex items-center gap-4 mt-2 text-xs text-gray-600">
                                            <span>From: {{ $warning->createdBy->name ?? 'HR' }}</span>
                                            <span>{{ $warning->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        <a href="{{ route('candidate.appraisals.show', $warning) }}" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                                            View Details
                                        </a>
                                        @if(!$warning->is_acknowledged)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                Pending Acknowledgment
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                Acknowledged
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-600">No warnings</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
