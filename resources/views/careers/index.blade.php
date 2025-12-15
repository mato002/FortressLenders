@extends('layouts.website')

@php use Illuminate\Support\Str; @endphp

@section('title', 'Careers - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section
        class="relative text-white py-12 sm:py-16 md:py-20 overflow-hidden"
        style="background-image: linear-gradient(to bottom right, rgba(4, 120, 87, 0.9), rgba(6, 78, 59, 0.9)), url('https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80'); background-size: cover; background-position: center;"
    >
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4">Join Our Team</h1>
            <p class="text-lg sm:text-xl text-teal-100">Explore exciting career opportunities with Fortress Lenders</p>
        </div>
    </section>

    <!-- Job Updates Section -->
    <section class="py-8 bg-teal-50">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="text-center mb-6">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Job Updates</h2>
                <p class="text-gray-600">Stay updated with our latest job openings</p>
            </div>
        </div>
    </section>

    <!-- Jobs Listing Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-8">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-2">Job Openings</h2>
                <p class="text-gray-600 text-sm sm:text-base">Browse our current and past job postings. Closed positions are kept for reference.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($jobs as $job)
                    <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all transform hover:-translate-y-2">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $job->title }}</h3>
                                <div class="flex flex-wrap gap-2 mb-3">
                                    @if($job->location)
                                        <span class="text-sm text-gray-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $job->location }}
                                        </span>
                                    @endif
                                    @if($job->department)
                                        <span class="text-sm text-gray-600 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            {{ $job->department }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                                    {{ Str::limit(strip_tags($job->description), 150) }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-teal-100 text-teal-800">
                                    {{ ucfirst(str_replace('-', ' ', $job->employment_type)) }}
                                </span>
                                @php
                                    $status = $job->application_status;
                                    $statusClasses = $job->status_badge_classes;
                                    $statusLabel = $job->status_label;
                                @endphp
                                <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusClasses }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <a href="{{ route('careers.show', $job->slug) }}" class="px-4 py-2 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold text-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-600 bg-white rounded-2xl shadow-sm py-10">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-lg font-semibold mb-2">No job openings at the moment</p>
                        <p class="text-sm">Check back later for new opportunities.</p>
                    </div>
                @endforelse
            </div>

            @if($jobs->hasPages())
                <div class="mt-8">
                    {{ $jobs->links() }}
                </div>
            @endif
        </div>
    </section>

    <!-- Marketing CTA Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-r from-teal-800 to-teal-700 text-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 sm:mb-6 px-4">Why Join Fortress Lenders?</h2>
            <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 text-teal-100 max-w-2xl mx-auto px-4">
                Be part of a team that's making a difference in communities through accessible financial solutions.
            </p>
            <a href="{{ route('careers.index') }}" class="inline-block px-6 sm:px-8 py-3 sm:py-4 bg-white text-teal-800 rounded-lg font-semibold hover:bg-teal-50 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                View All Openings
            </a>
        </div>
    </section>
@endsection

