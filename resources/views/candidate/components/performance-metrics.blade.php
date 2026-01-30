<!-- Performance Metrics Widget -->
@if($performanceMetrics)
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-900 mb-4">Your Performance</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Aptitude Test Score -->
        <div class="p-4 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200">
            <p class="text-sm font-semibold text-amber-900 mb-2">Average Aptitude Score</p>
            @if($performanceMetrics['avg_aptitude_score'])
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-amber-600">{{ round($performanceMetrics['avg_aptitude_score']) }}</span>
                    <span class="text-gray-600">/100</span>
                </div>
                <p class="text-xs text-amber-700 mt-2">
                    Based on {{ $performanceMetrics['tests_completed'] }} test{{ $performanceMetrics['tests_completed'] !== 1 ? 's' : '' }}
                </p>
            @else
                <p class="text-sm text-amber-700">No tests completed yet</p>
            @endif
        </div>
        
        <!-- Success Rate -->
        <div class="p-4 rounded-xl bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200">
            <p class="text-sm font-semibold text-green-900 mb-2">Application Success Rate</p>
            @if($performanceMetrics['total_applications'] > 0)
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-bold text-green-600">{{ round($performanceMetrics['success_rate']) }}</span>
                    <span class="text-gray-600">%</span>
                </div>
                <p class="text-xs text-green-700 mt-2">
                    {{ $performanceMetrics['passed_applications'] }} of {{ $performanceMetrics['total_applications'] }} passed sieving
                </p>
            @else
                <p class="text-sm text-green-700">No applications yet</p>
            @endif
        </div>
        
        <!-- Completed vs Pending -->
        <div class="p-4 rounded-xl bg-gradient-to-br from-blue-50 to-cyan-50 border border-blue-200">
            <p class="text-sm font-semibold text-blue-900 mb-2">Completion Status</p>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-xs text-blue-700">Completed</span>
                    <span class="font-bold text-blue-600">{{ $performanceMetrics['completed_tests'] }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs text-blue-700">Pending</span>
                    <span class="font-bold text-blue-600">{{ $performanceMetrics['pending_tests'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
