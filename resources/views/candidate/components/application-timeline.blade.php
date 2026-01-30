<!-- Application Timeline Widget -->
@if($activeApplications && $activeApplications->count() > 0)
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-900 mb-6">Application Progress</h2>
    
    <div class="space-y-6">
        @foreach($activeApplications as $application)
            <div class="relative">
                <div class="flex items-start gap-4">
                    <!-- Job Info -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $application->jobPost->title }}</h3>
                        <p class="text-sm text-gray-500 mb-4">{{ $application->jobPost->company->name ?? 'Company' }} • Applied {{ $application->created_at->diffForHumans() }}</p>
                        
                        <!-- Timeline -->
                        <div class="space-y-2">
                            @php
                                $stages = [
                                    ['status' => 'pending', 'label' => 'Application Submitted', 'completed' => true],
                                    ['status' => 'sieving_passed', 'label' => 'Sieving Completed', 'completed' => in_array($application->status, ['sieving_passed', 'pending_manual_review', 'aptitude_failed', 'stage_2_passed', 'hired'])],
                                    ['status' => 'aptitude_test', 'label' => 'Aptitude Test', 'completed' => $application->aptitude_test_completed_at !== null],
                                    ['status' => 'self_interview', 'label' => 'Self Interview', 'completed' => $application->self_interview_completed_at !== null],
                                    ['status' => 'stage_2_passed', 'label' => 'Final Interview', 'completed' => in_array($application->status, ['stage_2_passed', 'hired'])],
                                    ['status' => 'hired', 'label' => 'Hired', 'completed' => $application->status === 'hired'],
                                ];
                            @endphp
                            
                            @foreach($stages as $stage)
                                @if($stage['status'] === 'sieving_passed' && !in_array($application->status, ['sieving_passed', 'pending_manual_review', 'aptitude_failed', 'stage_2_passed', 'hired']))
                                    @continue
                                @endif
                                
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        @if($stage['completed'])
                                            <div class="flex items-center justify-center h-6 w-6 rounded-full bg-green-100">
                                                <svg class="h-4 w-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="flex items-center justify-center h-6 w-6 rounded-full bg-gray-200">
                                                <div class="h-2 w-2 bg-gray-400 rounded-full"></div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium {{ $stage['completed'] ? 'text-gray-900' : 'text-gray-500' }}">
                                            {{ $stage['label'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Status Badge -->
                    <div class="flex-shrink-0">
                        <a href="{{ route('candidate.application.show', $application) }}" class="inline-flex items-center px-4 py-2 rounded-lg bg-teal-50 border border-teal-200 text-teal-700 hover:bg-teal-100 transition-colors text-sm font-semibold">
                            View Details
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Status Label -->
                <div class="ml-7 mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                        @if($application->status === 'hired') bg-purple-100 text-purple-800
                        @elseif($application->status === 'stage_2_passed') bg-blue-100 text-blue-800
                        @elseif(in_array($application->status, ['sieving_passed', 'pending_manual_review'])) bg-green-100 text-green-800
                        @elseif(in_array($application->status, ['sieving_rejected'])) bg-red-100 text-red-800
                        @elseif($application->status === 'aptitude_failed') bg-orange-100 text-orange-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                    </span>
                </div>
            </div>
            
            @if(!$loop->last)
                <hr class="my-4 border-gray-200">
            @endif
        @endforeach
    </div>
</div>
@endif
