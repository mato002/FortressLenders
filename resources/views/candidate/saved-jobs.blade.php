@extends('layouts.candidate')

@section('title', 'Saved Jobs')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Saved Jobs</h1>
            <p class="text-lg text-gray-600 mt-2">{{ $savedJobs->total() }} job{{ $savedJobs->total() !== 1 ? 's' : '' }} in your wishlist</p>
        </div>

        @if($savedJobs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($savedJobs as $saved)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">
                                    <a href="{{ route('careers.show', $saved->jobPost->slug) }}" class="hover:text-teal-600">
                                        {{ $saved->jobPost->title }}
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $saved->jobPost->department ?? 'General' }}</p>
                            </div>
                            <form action="{{ route('candidate.saved-jobs.destroy', $saved->jobPost) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium" title="Remove from wishlist">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <div class="space-y-3 mb-6 py-4 border-y border-gray-100">
                            @if($saved->jobPost->location)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    {{ $saved->jobPost->location }}
                                </div>
                            @endif

                            @if($saved->jobPost->employment_type)
                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.728 0-7.333-.9-10.414-2.469M5 12a7 7 0 1114 0m0 0a7 7 0 11-14 0" />
                                    </svg>
                                    {{ ucfirst($saved->jobPost->employment_type) }}
                                </div>
                            @endif

                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Saved {{ $saved->saved_at->diffForHumans() }}
                            </div>
                        </div>

                        <p class="text-gray-600 text-sm mb-4">
                            {{ Str::limit($saved->jobPost->description, 150) }}
                        </p>

                        <div class="flex gap-3">
                            <a href="{{ route('careers.show', $saved->jobPost->slug) }}" class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 text-center">
                                View Job
                            </a>
                            <a href="{{ route('careers.apply', $saved->jobPost->slug) }}" class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold text-teal-600 border border-teal-200 hover:bg-teal-50 text-center">
                                Apply Now
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $savedJobs->links() }}
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h6a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V5z" />
                </svg>
                <h3 class="text-xl font-bold text-gray-900">No saved jobs yet</h3>
                <p class="text-gray-600 mt-2">Start saving jobs to keep track of opportunities that interest you.</p>
                <a href="{{ route('careers.index') }}" class="inline-block mt-6 px-6 py-2 rounded-lg text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700">
                    Browse Jobs
                </a>
            </div>
        @endif
    </div>
@endsection
