<!-- Document Reminders Widget -->
@if($documentReminders && $documentReminders->count() > 0)
<div class="bg-red-50 border border-red-200 rounded-2xl shadow-lg p-6 mb-6">
    <div class="flex items-start gap-4">
        <div class="flex-shrink-0">
            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="flex-1">
            <h2 class="text-xl font-bold text-red-900 mb-2">Missing Documents</h2>
            <p class="text-sm text-red-800 mb-4">Some documents are missing for your active applications. Please upload them to improve your chances:</p>
            
            <div class="space-y-2">
                @foreach($documentReminders as $reminder)
                    <div class="p-3 bg-white rounded-lg border border-red-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $reminder['job_title'] }}</p>
                                <p class="text-sm text-gray-600">Missing: {{ implode(', ', $reminder['missing_docs']) }}</p>
                            </div>
                            <a href="{{ route('candidate.documents.index') }}" class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold transition-colors">
                                Upload
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
