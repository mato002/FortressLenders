<!-- Job Recommendations Widget -->
@if($recommendedJobs && $recommendedJobs->count() > 0)
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-900">Recommended for You</h2>
        <a href="{{ route('careers.index') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-700">
            View All →
        </a>
    </div>
    
    <p class="text-sm text-gray-600 mb-4">Based on your profile and application history:</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($recommendedJobs as $job)
            <div class="p-4 rounded-xl border border-gray-200 hover:border-teal-300 hover:shadow-md transition-all">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $job->title }}</h3>
                        <p class="text-sm text-gray-600">{{ $job->company->name ?? 'Company' }}</p>
                    </div>
                </div>
                
                <p class="text-xs text-gray-500 mb-3">{{ Str::limit($job->description ?? '', 80, '...') }}</p>
                
                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-500">{{ $job->employment_type ?? 'Full-time' }}</span>
                    <a href="{{ route('careers.show', $job) }}" class="inline-flex items-center px-3 py-1.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-semibold transition-colors">
                        View Job
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif
