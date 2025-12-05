@extends('layouts.website')

@section('title', $job->title . ' - Careers - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900 text-white py-12 sm:py-16 md:py-20">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-24">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">{{ $job->title }}</h1>
                <div class="flex flex-wrap gap-4 text-teal-100">
                    @if($job->location)
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $job->location }}
                        </span>
                    @endif
                    @if($job->department)
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $job->department }}
                        </span>
                    @endif
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ ucfirst(str_replace('-', ' ', $job->employment_type)) }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Job Details Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-4xl mx-auto">
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    <a href="{{ route('careers.apply', $job) }}" class="flex-1 px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold text-center">
                        I'm Interested - Apply Now
                    </a>
                    <button onclick="shareJob()" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-semibold flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        Share
                    </button>
                </div>

                <div class="prose max-w-none mb-8">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Job Description</h2>
                        <div class="text-gray-700">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>

                    @if($job->responsibilities)
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Key Responsibilities</h2>
                            <div class="text-gray-700">
                                {!! nl2br(e($job->responsibilities)) !!}
                            </div>
                        </div>
                    @endif

                    @if($job->requirements)
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Requirements</h2>
                            <div class="text-gray-700">
                                {!! nl2br(e($job->requirements)) !!}
                            </div>
                        </div>
                    @endif

                    @if($job->experience_level)
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Experience Level</h3>
                            <p class="text-gray-700">{{ $job->experience_level }}</p>
                        </div>
                    @endif

                    @if($job->application_deadline)
                        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                            <p class="text-amber-800">
                                <strong>Application Deadline:</strong> {{ $job->application_deadline->format('F d, Y') }}
                            </p>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    <a href="{{ route('careers.apply', $job) }}" class="flex-1 px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold text-center">
                        Apply for This Position
                    </a>
                    <a href="{{ route('careers.index') }}" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-semibold text-center">
                        Back to All Jobs
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if($relatedJobs->count() > 0)
        <!-- Related Jobs Section -->
        <section class="py-12 sm:py-16 bg-gray-50">
            <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
                <div class="max-w-6xl mx-auto">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8">Related Positions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedJobs as $relatedJob)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $relatedJob->title }}</h3>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                    {{ Str::limit(strip_tags($relatedJob->description), 100) }}
                                </p>
                                <a href="{{ route('careers.show', $relatedJob) }}" class="text-teal-800 font-semibold hover:text-teal-900">
                                    View Details →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    @push('scripts')
    <script>
        function shareJob() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $job->title }}',
                    text: 'Check out this job opportunity at Fortress Lenders',
                    url: window.location.href
                }).catch(err => console.log('Error sharing', err));
            } else {
                // Fallback: Copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Link copied to clipboard!');
                });
            }
        }
    </script>
    @endpush
@endsection

