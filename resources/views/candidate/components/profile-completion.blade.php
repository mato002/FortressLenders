<!-- Profile Completion Status Widget -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-gray-900">Profile Completion</h2>
        <span class="text-3xl font-bold text-teal-600">{{ $completionPercentage }}%</span>
    </div>
    
    <!-- Progress Bar -->
    <div class="w-full bg-gray-200 rounded-full h-3 mb-6 overflow-hidden">
        <div class="bg-gradient-to-r from-teal-500 to-emerald-500 h-full rounded-full transition-all duration-500" style="width: {{ $completionPercentage }}%"></div>
    </div>
    
    <!-- Completion Items -->
    <div class="space-y-3">
        <!-- Email Verification -->
        <div class="flex items-center justify-between p-3 rounded-lg {{ $candidate->email_verified_at ? 'bg-green-50 border border-green-200' : 'bg-amber-50 border border-amber-200' }}">
            <div class="flex items-center gap-3">
                @if($candidate->email_verified_at)
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-semibold text-green-800">Email Verified</span>
                @else
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-sm font-semibold text-amber-800">Email Not Verified</span>
                @endif
            </div>
            <span class="text-xs font-semibold {{ $candidate->email_verified_at ? 'text-green-600' : 'text-amber-600' }}">{{ $candidate->email_verified_at ? 'Done' : 'Pending' }}</span>
        </div>

        <!-- Bio Data -->
        <div class="flex items-center justify-between p-3 rounded-lg {{ $bioDataComplete ? 'bg-green-50 border border-green-200' : 'bg-amber-50 border border-amber-200' }}">
            <div class="flex items-center gap-3">
                @if($bioDataComplete)
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-semibold text-green-800">Bio Data Completed</span>
                @else
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-sm font-semibold text-amber-800">Bio Data Incomplete</span>
                @endif
            </div>
            <a href="{{ route('candidate.bio-data.index') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700 underline">{{ $bioDataComplete ? 'View' : 'Complete' }}</a>
        </div>

        <!-- Documents -->
        <div class="flex items-center justify-between p-3 rounded-lg {{ $documentsUploaded ? 'bg-green-50 border border-green-200' : 'bg-amber-50 border border-amber-200' }}">
            <div class="flex items-center gap-3 flex-1">
                @if($documentsUploaded)
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-sm font-semibold text-green-800">Documents Uploaded</span>
                @else
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span class="text-sm font-semibold text-amber-800">Upload Documents</span>
                @endif
            </div>
            <a href="{{ route('candidate.documents.index') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700 underline">{{ $documentsUploaded ? 'Manage' : 'Upload' }}</a>
        </div>
    </div>
    
    @if($completionPercentage < 100)
        <div class="mt-6 p-4 bg-teal-50 border border-teal-200 rounded-lg">
            <p class="text-sm text-teal-800">
                <span class="font-semibold">{{ 100 - $completionPercentage }}% remaining</span> - Complete your profile to improve your chances of success in job applications.
            </p>
        </div>
    @else
        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm text-green-800">
                <span class="font-semibold">Profile Complete!</span> - Your profile is fully updated. Good luck with your applications!
            </p>
        </div>
    @endif
</div>
