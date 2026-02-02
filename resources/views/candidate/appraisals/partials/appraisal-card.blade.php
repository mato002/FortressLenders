@php
    $typeColors = [
        'performance_review' => ['bg' => 'bg-blue-50', 'border' => 'border-blue-200', 'text' => 'text-blue-800', 'badge' => 'bg-blue-100', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
        'hr_communication' => ['bg' => 'bg-purple-50', 'border' => 'border-purple-200', 'text' => 'text-purple-800', 'badge' => 'bg-purple-100', 'icon' => 'M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        'warning' => ['bg' => 'bg-red-50', 'border' => 'border-red-200', 'text' => 'text-red-800', 'badge' => 'bg-red-100', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
    ];
    $color = $typeColors[$appraisal->type] ?? $typeColors['performance_review'];
    
    $severityColors = [
        'high' => ['bg' => 'bg-red-500', 'text' => 'text-red-900', 'badge' => 'bg-red-200'],
        'medium' => ['bg' => 'bg-orange-500', 'text' => 'text-orange-900', 'badge' => 'bg-orange-200'],
        'low' => ['bg' => 'bg-amber-500', 'text' => 'text-amber-900', 'badge' => 'bg-amber-200'],
    ];
    $severityColor = $severityColors[$appraisal->severity ?? 'low'] ?? $severityColors['low'];
@endphp

<div class="group border-2 {{ $color['border'] }} {{ $color['bg'] }} rounded-xl p-6 hover:shadow-xl transition-all duration-300 hover:scale-[1.02]">
    <div class="flex items-start justify-between gap-6">
        <div class="flex-1 min-w-0">
            <div class="flex items-start gap-4 mb-4">
                <div class="flex-shrink-0 p-3 {{ $color['badge'] }} rounded-lg">
                    <svg class="w-6 h-6 {{ $color['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $color['icon'] }}"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                        <h3 class="text-xl font-bold text-gray-900">{{ $appraisal->title }}</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $color['badge'] }} {{ $color['text'] }}">
                            {{ ucfirst(str_replace('_', ' ', $appraisal->type)) }}
                        </span>
                        @if($appraisal->type === 'warning' && $appraisal->severity)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $severityColor['badge'] }} {{ $severityColor['text'] }}">
                                <span class="w-2 h-2 {{ $severityColor['bg'] }} rounded-full mr-2"></span>
                                {{ ucfirst($appraisal->severity) }} Severity
                            </span>
                        @endif
                        @if($appraisal->is_acknowledged)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Acknowledged
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 animate-pulse">
                                <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span>
                                Pending
                            </span>
                        @endif
                    </div>
                    
                    <p class="text-gray-700 leading-relaxed mb-4 line-clamp-2">{{ Str::limit($appraisal->content, 200) }}</p>
                    
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="font-medium">From:</span>
                            <span>{{ $appraisal->createdBy->name ?? 'HR Department' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">Created:</span>
                            <span>{{ $appraisal->created_at->format('M d, Y') }}</span>
                        </div>
                        @if($appraisal->review_date)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-medium">Review Date:</span>
                                <span>{{ $appraisal->review_date->format('M d, Y') }}</span>
                            </div>
                        @endif
                        @if($appraisal->is_acknowledged && $appraisal->acknowledged_at)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-medium">Acknowledged:</span>
                                <span>{{ $appraisal->acknowledged_at->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex-shrink-0 flex flex-col items-end gap-3">
            <a href="{{ route('candidate.appraisals.show', $appraisal) }}" 
               class="group/view inline-flex items-center px-5 py-2.5 bg-white border-2 border-teal-600 text-teal-600 font-semibold rounded-lg hover:bg-teal-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                View Details
                <svg class="w-4 h-4 ml-2 group-hover/view:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
            @if(!$appraisal->is_acknowledged)
                <form method="POST" action="{{ route('candidate.appraisals.acknowledge', $appraisal) }}" class="inline">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to acknowledge this appraisal?')"
                            class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors text-sm font-semibold">
                        Quick Acknowledge
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
