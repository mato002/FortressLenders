<!-- Recent Activity Feed -->
@if($activityFeed && $activityFeed->count() > 0)
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Activity</h2>
    
    <div class="space-y-4">
        @foreach($activityFeed as $activity)
            <div class="flex items-start gap-4 pb-4 border-b border-gray-200 last:border-b-0 last:pb-0">
                <!-- Activity Icon -->
                <div class="flex-shrink-0">
                    @if(str_contains($activity['type'], 'application'))
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    @elseif(str_contains($activity['type'], 'test'))
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-amber-100">
                            <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    @elseif(str_contains($activity['type'], 'interview'))
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-purple-100">
                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.14 3.14a6 6 0 00-8.488 8.488l3.14 3.14M9 12a3 3 0 110-6 3 3 0 010 6zm0 0a6 6 0 106 6 6 6 0 00-6-6zm0 0a1 1 0 100-2 1 1 0 000 2zm6 6a1 1 0 100-2 1 1 0 000 2z"/>
                            </svg>
                        </div>
                    @elseif(str_contains($activity['type'], 'status'))
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    @else
                        <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-100">
                            <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                
                <!-- Activity Details -->
                <div class="flex-1">
                    <p class="font-semibold text-gray-900">{{ $activity['title'] }}</p>
                    @if(isset($activity['description']))
                        <p class="text-sm text-gray-600">{{ $activity['description'] }}</p>
                    @endif
                    <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                </div>
                
                @if(isset($activity['link']))
                    <a href="{{ $activity['link'] }}" class="flex-shrink-0 text-teal-600 hover:text-teal-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif
            </div>
        @endforeach
    </div>
    
    <div class="mt-4 text-center">
        <p class="text-xs text-gray-500">Showing recent 10 activities</p>
    </div>
</div>
@else
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8 text-center mb-6">
    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <h3 class="text-lg font-semibold text-gray-900 mb-1">No Recent Activity</h3>
    <p class="text-gray-500">Your activity will appear here once you start applying to jobs.</p>
</div>
@endif
