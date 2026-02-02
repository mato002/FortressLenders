<!-- Interview Schedule Widget -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4">Upcoming Activities</h2>
    
    @if($upcomingActivities && $upcomingActivities->count() > 0)
        <div class="space-y-3">
            @foreach($upcomingActivities as $activity)
                @if(empty($activity['job_title']))
                    @continue
                @endif
                <div class="p-4 border-l-4 rounded-lg {{ $activity['type'] === 'aptitude' ? 'border-amber-500 bg-amber-50' : 'border-blue-500 bg-blue-50' }}">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <p class="font-semibold {{ $activity['type'] === 'aptitude' ? 'text-amber-900' : 'text-blue-900' }}">
                                {{ $activity['job_title'] }}
                            </p>
                            <p class="text-sm {{ $activity['type'] === 'aptitude' ? 'text-amber-700' : 'text-blue-700' }}">
                                @if($activity['type'] === 'aptitude')
                                    Aptitude Test Pending
                                @else
                                    Self Interview Pending
                                @endif
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $activity['type'] === 'aptitude' ? 'bg-amber-200 text-amber-800' : 'bg-blue-200 text-blue-800' }}">
                            {{ ucfirst($activity['type']) }}
                        </span>
                    </div>
                    
                    @if(isset($activity['time_remaining']))
                        <div class="flex items-center gap-2 text-sm mb-3 {{ $activity['type'] === 'aptitude' ? 'text-amber-700' : 'text-blue-700' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Action needed within {{ $activity['time_remaining'] }}</span>
                        </div>
                    @endif
                    
                    @if($activity['type'] === 'aptitude')
                        <button 
                            type="button"
                            onclick="openAptitudeTestModal({{ $activity['application_id'] }})"
                            class="w-full px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors text-sm font-semibold">
                            Start Aptitude Test
                        </button>
                    @else
                        <a href="{{ route('self-interview.show', $activity['application_id']) }}" class="w-full block text-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-semibold">
                            Start Self Interview
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-sm font-semibold text-gray-900 mb-1">No Upcoming Activities</h3>
            <p class="text-sm text-gray-500">You're all caught up! Check back soon for new opportunities.</p>
        </div>
    @endif
</div>
