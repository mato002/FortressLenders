@extends('layouts.admin')

@section('title', 'Job Application Details')

@section('header-description', 'Review application, conduct interviews, and manage the hiring process.')

@section('content')
    @php
        // Get application ID - try multiple methods to ensure we have it
        $applicationId = $application->id ?? $application->getKey() ?? $application->getAttribute('id') ?? '';
        // If still empty, try to get from route parameter
        if (empty($applicationId)) {
            $routeParam = request()->route('application') ?? request()->route('job_application');
            $applicationId = is_object($routeParam) ? ($routeParam->id ?? $routeParam->getKey()) : $routeParam;
        }

        // Helper flags/URLs for aptitude + public views
        $canTakeAptitudeTest = in_array($application->status, ['sieving_passed', 'pending_manual_review']);
        $publicStatusToken = $application->email
            ? md5($application->email . $applicationId . config('app.key'))
            : null;
    @endphp
    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">{{ $application->name ?: 'No Name' }}</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Applied for {{ optional($application->jobPost)->title ?? 'Unknown Position' }}@if($application->created_at) on {{ $application->created_at->format('M d, Y H:i') }}@endif
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-2 justify-end">
                {{-- Public candidate views --}}
                @if($publicStatusToken)
                    <a href="{{ route('application.status', ['application' => $applicationId, 'token' => $publicStatusToken]) }}"
                       target="_blank"
                       class="px-3 py-2 text-xs sm:text-sm rounded-xl border border-teal-200 text-teal-700 bg-teal-50 hover:bg-teal-100">
                        View as Candidate
                    </a>
                @endif
                @if($canTakeAptitudeTest)
                    <a href="{{ route('aptitude-test.show', $applicationId) }}"
                       target="_blank"
                       class="px-3 py-2 text-xs sm:text-sm rounded-xl border border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100">
                        Open Aptitude Test
                    </a>
                @endif
                <a href="{{ route('admin.job-applications.index') }}" class="px-3 py-2 text-sm rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">
                    Back to list
                </a>
                <form action="{{ route('admin.job-applications.destroy', $application) }}" method="POST" class="inline-block delete-form" data-id="{{ $application->id }}" data-name="{{ $application->name }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-3 py-2 text-sm rounded-xl border border-red-200 text-red-600 hover:bg-red-50">
                        Delete Application
                    </button>
                </form>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
                @if (session('candidate_password'))
                    <div class="mt-3 p-3 bg-white rounded-lg border border-emerald-300">
                        <p class="font-semibold text-emerald-900 mb-2">Candidate Login Credentials:</p>
                        <div class="space-y-1 text-sm">
                            <p><strong>Email:</strong> <code class="bg-gray-100 px-2 py-1 rounded">{{ session('candidate_email') }}</code></p>
                            <p><strong>Password:</strong> <code class="bg-gray-100 px-2 py-1 rounded font-mono">{{ session('candidate_password') }}</code></p>
                        </div>
                        <p class="text-xs text-emerald-700 mt-2">⚠️ Save these credentials now - they won't be shown again for security reasons.</p>
                    </div>
                @endif
            </div>
        @endif

        @if (session('warning'))
            <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                {{ session('warning') }}
                @if (session('candidate_password'))
                    <div class="mt-3 p-3 bg-white rounded-lg border border-amber-300">
                        <p class="font-semibold text-amber-900 mb-2">Candidate Login Credentials:</p>
                        <div class="space-y-1 text-sm">
                            <p><strong>Email:</strong> <code class="bg-gray-100 px-2 py-1 rounded">{{ session('candidate_email') }}</code></p>
                            <p><strong>Password:</strong> <code class="bg-gray-100 px-2 py-1 rounded font-mono">{{ session('candidate_password') }}</code></p>
                        </div>
                        <p class="text-xs text-amber-700 mt-2">⚠️ Save these credentials now - they won't be shown again for security reasons.</p>
                    </div>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main details -->
            <div class="lg:col-span-2">
                <!-- Tabs Navigation -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 mb-6">
                    <div class="border-b border-slate-200">
                        <nav class="flex -mb-px overflow-x-auto" aria-label="Tabs">
                            <button onclick="showTab('overview')" id="tab-overview" class="tab-button active px-6 py-4 text-sm font-medium border-b-2 border-teal-600 text-teal-600 whitespace-nowrap">
                                Overview
                            </button>
                            <button onclick="showTab('education')" id="tab-education" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap">
                                Education
                            </button>
                            <button onclick="showTab('experience')" id="tab-experience" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap">
                                Experience
                            </button>
                            <button onclick="showTab('additional')" id="tab-additional" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap">
                                Additional Info
                            </button>
                            <button onclick="showTab('history')" id="tab-history" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 whitespace-nowrap">
                                History
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="space-y-6">
                    <!-- Overview Tab -->
                    <div id="content-overview" class="tab-content">
                        <!-- Personal Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Personal Information</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-slate-500">Full Name</dt>
                            <dd class="text-slate-900 font-medium">{{ $application->name ?: 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Phone</dt>
                            <dd class="text-slate-900 font-medium">{{ $application->phone ?: 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Email</dt>
                            <dd class="text-slate-900">{{ $application->email ?: 'Not provided' }}</dd>
                        </div>
                        @if($application->availability_date)
                            <div>
                                <dt class="text-slate-500">Availability Date</dt>
                                <dd class="text-slate-900">{{ \Carbon\Carbon::parse($application->availability_date)->format('M d, Y') }}</dd>
                            </div>
                        @endif
                        @if($application->notice_period)
                            <div>
                                <dt class="text-slate-500">Notice Period</dt>
                                <dd class="text-slate-900">{{ $application->notice_period }}</dd>
                            </div>
                        @endif
                        @if($application->cv_path)
                            <div>
                                <dt class="text-slate-500">CV</dt>
                                <dd class="text-slate-900">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('admin.job-applications.view-cv', $application) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-teal-50 text-teal-800 rounded-lg hover:bg-teal-100 font-semibold text-sm transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View CV
                                        </a>
                                        <a href="{{ route('admin.job-applications.download-cv', $application) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-800 rounded-lg hover:bg-blue-100 font-semibold text-sm transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                            </svg>
                                            Download CV
                                        </a>
                                    </div>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <!-- Job Application Questions -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Job Application Questions</h2>
                    <dl class="space-y-4 text-sm">
                        @if($application->why_interested)
                            <div>
                                <dt class="text-slate-500 mb-1">Why are you interested in this position?</dt>
                                <dd class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->why_interested }}</dd>
                            </div>
                        @endif
                        @if($application->why_good_fit)
                            <div>
                                <dt class="text-slate-500 mb-1">Why do you think you're a good fit?</dt>
                                <dd class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->why_good_fit }}</dd>
                            </div>
                        @endif
                        @if($application->career_goals)
                            <div>
                                <dt class="text-slate-500 mb-1">Career Goals</dt>
                                <dd class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->career_goals }}</dd>
                            </div>
                        @endif
                        @if($application->salary_expectations)
                            <div>
                                <dt class="text-slate-500">Salary Expectations</dt>
                                <dd class="text-slate-900">{{ $application->salary_expectations }}</dd>
                            </div>
                        @endif
                        @if($application->relevant_skills)
                            <div>
                                <dt class="text-slate-500 mb-1">Relevant Skills</dt>
                                <dd class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->relevant_skills }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
                    </div>

                    <!-- Education Tab -->
                    <div id="content-education" class="tab-content hidden">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Education</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-slate-500">Education Level</dt>
                            <dd class="text-slate-900">{{ $application->education_level ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Area of Study</dt>
                            <dd class="text-slate-900">{{ $application->area_of_study ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Institution</dt>
                            <dd class="text-slate-900">{{ $application->institution ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Status</dt>
                            <dd class="text-slate-900">{{ $application->education_status ?? 'Not provided' }}</dd>
                        </div>
                        @if($application->other_achievements)
                            <div class="sm:col-span-2">
                                <dt class="text-slate-500">Other Achievements</dt>
                                <dd class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->other_achievements }}</dd>
                            </div>
                        @endif
                    </dl>
                        </div>
                    </div>

                    <!-- Experience Tab -->
                    <div id="content-experience" class="tab-content hidden">
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Work Experience</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        @if($application->currently_working)
                            <div>
                                <dt class="text-slate-500">Currently Working</dt>
                                <dd class="text-slate-900">Yes</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500">Current Job Title</dt>
                                <dd class="text-slate-900">{{ $application->current_job_title ?? 'Not provided' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500">Current Company</dt>
                                <dd class="text-slate-900">{{ $application->current_company ?? 'Not provided' }}</dd>
                            </div>
                        @endif
                        @if($application->duties_and_responsibilities)
                            <div class="sm:col-span-2">
                                <dt class="text-slate-500">Duties and Responsibilities</dt>
                                <dd class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->duties_and_responsibilities }}</dd>
                            </div>
                        @endif
                        @if($application->other_experiences)
                            <div class="sm:col-span-2">
                                <dt class="text-slate-500">Other Experiences</dt>
                                <dd class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->other_experiences }}</dd>
                            </div>
                        @endif
                    </dl>
                        </div>
                    </div>

                    <!-- Additional Info Tab -->
                    <div id="content-additional" class="tab-content hidden">
                        <!-- AI Analysis -->
                        @if($application->ai_summary || $application->ai_details)
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6 mb-6">
                                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">AI Analysis</h2>
                                @if($application->ai_summary)
                                    <div class="mb-4">
                                        <dt class="text-slate-500 text-sm mb-1">Summary</dt>
                                        <dd class="text-slate-900 text-sm whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->ai_summary }}</dd>
                                    </div>
                                @endif
                                @if($application->ai_details)
                                    <div>
                                        <dt class="text-slate-500 text-sm mb-1">Details</dt>
                                        <dd class="text-slate-900 text-sm whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->ai_details }}</dd>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Additional Information -->
                        @if($application->certifications || $application->languages || $application->professional_memberships || $application->awards_recognition || $application->portfolio_links || $application->availability_travel || $application->availability_relocation)
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6 mb-6">
                        <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Additional Information</h2>
                        <dl class="space-y-4 text-sm">
                            @if($application->certifications)
                                <div>
                                    <dt class="text-slate-500 mb-1">Certifications</dt>
                                    <dd class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->certifications }}</dd>
                                </div>
                            @endif
                            @if($application->languages)
                                <div>
                                    <dt class="text-slate-500 mb-1">Languages</dt>
                                    <dd class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->languages }}</dd>
                                </div>
                            @endif
                            @if($application->professional_memberships)
                                <div>
                                    <dt class="text-slate-500 mb-1">Professional Memberships</dt>
                                    <dd class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->professional_memberships }}</dd>
                                </div>
                            @endif
                            @if($application->awards_recognition)
                                <div>
                                    <dt class="text-slate-500 mb-1">Awards & Recognition</dt>
                                    <dd class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->awards_recognition }}</dd>
                                </div>
                            @endif
                            @if($application->portfolio_links)
                                <div>
                                    <dt class="text-slate-500 mb-1">Portfolio Links</dt>
                                    <dd class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->portfolio_links }}</dd>
                                </div>
                            @endif
                            @if($application->availability_travel)
                                <div>
                                    <dt class="text-slate-500">Availability for Travel</dt>
                                    <dd class="text-slate-900">{{ $application->availability_travel }}</dd>
                                </div>
                            @endif
                            @if($application->availability_relocation)
                                <div>
                                    <dt class="text-slate-500">Availability for Relocation</dt>
                                    <dd class="text-slate-900">{{ $application->availability_relocation }}</dd>
                                </div>
                            @endif
                        </dl>
                            </div>
                        @endif

                        <!-- Support Details -->
                        @if($application->support_details)
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6 mb-6">
                                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Support Details</h2>
                                <p class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->support_details }}</p>
                            </div>
                        @endif

                        <!-- References -->
                        @if($application->referrers && count($application->referrers) > 0)
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6 mb-6">
                                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">References</h2>
                                <div class="space-y-4">
                                    @foreach($application->referrers as $referrer)
                                        <div class="bg-slate-50 rounded-xl p-3">
                                            <p class="font-semibold text-slate-900">{{ $referrer['name'] ?? 'N/A' }}</p>
                                            <p class="text-sm text-slate-600">{{ $referrer['position'] ?? '' }} at {{ $referrer['company'] ?? '' }}</p>
                                            <p class="text-sm text-slate-600">Contact: {{ $referrer['contact'] ?? 'N/A' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Application Message -->
                        @if($application->application_message)
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Application Message</h2>
                                <p class="text-slate-900 whitespace-pre-line bg-slate-50 rounded-xl p-3">{{ $application->application_message }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- History Tab -->
                    <div id="content-history" class="tab-content hidden">
                        <!-- Status History -->
                        @if($application->statusHistories && $application->statusHistories->count() > 0)
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6 mb-6">
                                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Status History</h2>
                                <div class="space-y-3">
                                    @foreach($application->statusHistories->sortByDesc('created_at') as $history)
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-sm font-semibold text-slate-900">
                                                    {{ Str::headline(str_replace('_', ' ', $history->new_status)) }}
                                                </p>
                                                @if($history->previous_status)
                                                    <p class="text-xs text-slate-500">
                                                        From {{ Str::headline(str_replace('_', ' ', $history->previous_status)) }}
                                                    </p>
                                                @endif
                                                @if($history->notes)
                                                    <p class="text-xs text-slate-600 mt-1">
                                                        {{ $history->notes }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="text-right text-xs text-slate-500">
                                                @if($history->user)
                                                    <p>{{ $history->user->name }}</p>
                                                @endif
                                                @if($history->created_at)
                                                    <p>{{ $history->created_at->format('M d, Y H:i') }}</p>
                                                @endif
                                                @if($history->source)
                                                    <p class="mt-1 italic text-slate-400">
                                                        via {{ Str::headline(str_replace('_', ' ', $history->source)) }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Reviews -->
                        @if($application->reviews->count() > 0)
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6 mb-6">
                                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Reviews</h2>
                                <div class="space-y-4">
                                    @foreach($application->reviews as $review)
                                        <div class="bg-slate-50 rounded-xl p-3">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $review->decision === 'pass' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ Str::headline($review->decision) }}
                                                </span>
                                                <span class="text-xs text-slate-500">By {{ $review->reviewer->name ?? 'Admin' }}@if($review->created_at !== null) on {{ $review->created_at->format('M d, Y') }}@endif</span>
                                            </div>
                                            @if($review->review_notes)
                                                <p class="text-sm text-slate-700 mt-2">{{ $review->review_notes }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Interviews -->
                        @if($application->interviews->count() > 0)
                            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                                <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Interviews</h2>
                                <div class="space-y-4">
                                    @foreach($application->interviews as $interview)
                                        @php
                                            // Derive effective result so history stays in sync with application status
                                            $effectiveResult = $interview->result;

                                            if (! $effectiveResult || $effectiveResult === 'pending') {
                                                $status = $application->status;
                                                if (in_array($status, ['interview_passed', 'hired'])) {
                                                    $effectiveResult = 'pass';
                                                } elseif (in_array($status, ['interview_failed', 'rejected'])) {
                                                    $effectiveResult = 'fail';
                                                } else {
                                                    $effectiveResult = 'pending';
                                                }
                                            }
                                        @endphp
                                        <div class="bg-slate-50 rounded-xl p-3">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    {{ Str::headline(str_replace('_', ' ', $interview->interview_type)) }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $effectiveResult === 'pass' ? 'bg-green-100 text-green-800' : ($effectiveResult === 'fail' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ Str::headline($effectiveResult) }}
                                                </span>
                                            </div>
                                            @if($interview->scheduled_at !== null)
                                                <p class="text-sm text-slate-600 mt-2">Scheduled: {{ $interview->scheduled_at->format('M d, Y H:i') }}</p>
                                            @endif
                                            @if($interview->feedback)
                                                <p class="text-sm text-slate-700 mt-2">{{ $interview->feedback }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Aptitude Test Results -->
                @if($application->aptitudeTestSession || $application->aptitude_test_completed_at)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6 mb-6">
                        <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Aptitude Test Results</h2>
                        <div class="space-y-4">
                            @if($application->aptitude_test_completed_at)
                                <div class="flex items-center justify-between">
                                    <div>
                                        <span class="text-sm text-slate-500">Status:</span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ml-2 {{ $application->aptitude_test_passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $application->aptitude_test_passed ? 'Passed' : 'Failed' }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-sm text-slate-500">Score:</span>
                                        <span class="text-lg font-semibold text-slate-900 ml-2">{{ $application->aptitude_test_score ?? 'N/A' }}%</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-sm text-slate-500">Completed:</span>
                                    <span class="text-sm font-medium text-slate-900 ml-2">{{ $application->aptitude_test_completed_at->format('M d, Y g:i A') }}</span>
                                </div>
                                @if($application->aptitudeTestSession)
                                    @php
                                        $session = $application->aptitudeTestSession;
                                    @endphp
                                    @if($session->total_possible_score)
                                        <div>
                                            <span class="text-sm text-slate-500">Raw Score:</span>
                                            <span class="text-sm font-medium text-slate-900 ml-2">{{ $session->total_score ?? 0 }}/{{ $session->total_possible_score }}</span>
                                        </div>
                                    @endif
                                    @if($session->time_taken_seconds)
                                        <div>
                                            <span class="text-sm text-slate-500">Time Taken:</span>
                                            <span class="text-sm font-medium text-slate-900 ml-2">{{ gmdate('H:i:s', $session->time_taken_seconds) }}</span>
                                        </div>
                                    @endif
                                    @if($session->started_at)
                                        <div>
                                            <span class="text-sm text-slate-500">Started:</span>
                                            <span class="text-sm font-medium text-slate-900 ml-2">{{ $session->started_at->format('M d, Y g:i A') }}</span>
                                        </div>
                                    @endif
                                @endif
                                <div class="pt-3 border-t border-slate-200">
                                    <a href="{{ route('aptitude-test.results', $application) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View Detailed Results
                                    </a>
                                </div>
                            @else
                                <div class="text-sm text-slate-600">
                                    <p>Aptitude test has not been completed yet.</p>
                                    @if($canTakeAptitudeTest)
                                        <p class="text-xs text-slate-500 mt-1">Candidate is eligible to take the test.</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- AI Sieving Decision -->
                @if($application->aiSievingDecision)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6 mb-6">
                        <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">AI Sieving Decision</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-sm text-slate-500">Decision:</span>
                                    @php
                                        $decisionClasses = match($application->aiSievingDecision->ai_decision) {
                                            'pass' => 'bg-green-100 text-green-800',
                                            'reject' => 'bg-red-100 text-red-800',
                                            'manual_review' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $decisionClasses }} ml-2">
                                        {{ Str::headline(str_replace('_', ' ', $application->aiSievingDecision->ai_decision)) }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm text-slate-500">Score:</span>
                                    <span class="text-lg font-semibold text-slate-900 ml-2">{{ $application->aiSievingDecision->ai_score }}/100</span>
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">Confidence:</span>
                                <span class="text-sm font-medium text-slate-900 ml-2">{{ number_format($application->aiSievingDecision->ai_confidence * 100, 1) }}%</span>
                            </div>
                            @if($application->aiSievingDecision->ai_reasoning)
                                <div>
                                    <span class="text-sm font-medium text-slate-700 block mb-2">Reasoning:</span>
                                    <div class="text-sm text-slate-600 whitespace-pre-line bg-slate-50 rounded-lg p-3">
                                        {{ $application->aiSievingDecision->ai_reasoning }}
                                    </div>
                                </div>
                            @endif
                            @if($application->aiSievingDecision->ai_strengths && count($application->aiSievingDecision->ai_strengths) > 0)
                                <div>
                                    <span class="text-sm font-medium text-green-700 block mb-2">Strengths:</span>
                                    <ul class="text-sm text-slate-600 space-y-1">
                                        @foreach($application->aiSievingDecision->ai_strengths as $strength)
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $strength }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if($application->aiSievingDecision->ai_weaknesses && count($application->aiSievingDecision->ai_weaknesses) > 0)
                                <div>
                                    <span class="text-sm font-medium text-red-700 block mb-2">Weaknesses:</span>
                                    <ul class="text-sm text-slate-600 space-y-1">
                                        @foreach($application->aiSievingDecision->ai_weaknesses as $weakness)
                                            <li class="flex items-start">
                                                <svg class="w-4 h-4 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $weakness }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Candidate Account Status -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6 mb-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Candidate Account</h2>
                    @php
                        $candidate = $application->candidate ?? null;
                        $hasAccount = $candidate !== null;
                    @endphp
                    <div class="space-y-4">
                        @if($hasAccount)
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-sm text-slate-500">Account Status:</span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                        ✓ Account Created
                                    </span>
                                </div>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">Email:</span>
                                <span class="text-sm font-medium text-slate-900 ml-2">{{ $candidate->email }}</span>
                            </div>
                            <div>
                                <span class="text-sm text-slate-500">Created:</span>
                                <span class="text-sm font-medium text-slate-900 ml-2">{{ $candidate->created_at->format('M d, Y') }}</span>
                            </div>
                            @php
                                // Check if password is stored in session (recently created/reset)
                                $storedPassword = session('candidate_password_' . $candidate->id);
                                $passwordTime = session('candidate_password_time_' . $candidate->id);
                                $passwordAvailable = $storedPassword && $passwordTime && now()->lt($passwordTime);
                            @endphp
                            @if($passwordAvailable)
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mt-3">
                                    <p class="text-xs font-semibold text-amber-900 mb-2">🔑 Temporary Password (Available for 5 minutes):</p>
                                    <code class="block bg-white px-3 py-2 rounded border border-amber-300 font-mono text-sm text-amber-900">{{ $storedPassword }}</code>
                                    <p class="text-xs text-amber-700 mt-2">This password was recently generated. Use it to test login.</p>
                                </div>
                            @endif
                            <div class="pt-3 border-t border-slate-200 space-y-2">
                                <form method="POST" action="{{ route('admin.job-applications.resend-candidate-credentials', $application) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-semibold">
                                        Resend Login Credentials
                                    </button>
                                </form>
                                <a href="{{ route('admin.job-applications.view-candidate-dashboard', $application) }}" target="_blank" class="block w-full px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold text-center">
                                    View Candidate Dashboard
                                </a>
                            </div>
                        @else
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <p class="text-sm text-amber-800 mb-3">
                                    <strong>No candidate account found.</strong> Create an account to allow the candidate to access their dashboard.
                                </p>
                                <form method="POST" action="{{ route('admin.job-applications.create-candidate-account', $application) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                                        Create Account & Send Credentials
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Status</h2>
                    @php
                        $statusClasses = match($application->status) {
                            'pending' => 'bg-amber-100 text-amber-800',
                            'sieving_passed' => 'bg-green-100 text-green-800',
                            'sieving_rejected' => 'bg-red-100 text-red-800',
                            'pending_manual_review' => 'bg-yellow-100 text-yellow-800',
                            'stage_2_passed' => 'bg-emerald-100 text-emerald-800',
                            'reviewed' => 'bg-blue-100 text-blue-800',
                            'shortlisted' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'interview_scheduled' => 'bg-purple-100 text-purple-800',
                            'interview_passed' => 'bg-emerald-100 text-emerald-800',
                            'interview_failed' => 'bg-red-100 text-red-800',
                            'hired' => 'bg-teal-100 text-teal-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <div class="mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClasses }}">
                            {{ Str::headline(str_replace('_', ' ', $application->status)) }}
                        </span>
                    </div>

                    <form method="POST" action="/admin/job-applications/{{ $applicationId }}/update-status" class="space-y-4">
                        @csrf
                        <div class="space-y-1">
                            <label for="status" class="text-xs font-medium text-slate-600">Update Status</label>
                            <select id="status" name="status"
                                    class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                                <option value="pending" @selected($application->status === 'pending')>Pending</option>
                                <option value="sieving_passed" @selected($application->status === 'sieving_passed')>Sieving Passed</option>
                                <option value="sieving_rejected" @selected($application->status === 'sieving_rejected')>Sieving Rejected</option>
                                <option value="pending_manual_review" @selected($application->status === 'pending_manual_review')>Pending Manual Review</option>
                                <option value="stage_2_passed" @selected($application->status === 'stage_2_passed')>Stage 2 Passed</option>
                                <option value="reviewed" @selected($application->status === 'reviewed')>Reviewed</option>
                                <option value="shortlisted" @selected($application->status === 'shortlisted')>Shortlisted</option>
                                <option value="rejected" @selected($application->status === 'rejected')>Rejected</option>
                                <option value="interview_scheduled" @selected($application->status === 'interview_scheduled')>Interview Scheduled</option>
                                <option value="interview_passed" @selected($application->status === 'interview_passed')>Interview Passed</option>
                                <option value="interview_failed" @selected($application->status === 'interview_failed')>Interview Failed</option>
                                <option value="second_interview" @selected($application->status === 'second_interview')>Second Interview</option>
                                <option value="written_test" @selected($application->status === 'written_test')>Written Test</option>
                                <option value="case_study" @selected($application->status === 'case_study')>Case Study</option>
                                <option value="hired" @selected($application->status === 'hired')>Hired</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-teal-700 hover:bg-teal-800">
                            Update Status
                        </button>
                    </form>
                </div>

                <!-- Send Confirmation Email -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Application Confirmation</h2>
                    <p class="text-xs text-slate-600 mb-4">Send the application confirmation email to the candidate.</p>
                    <form method="POST" action="{{ route('admin.job-applications.send-confirmation', $application) }}" class="mb-4">
                        @csrf
                        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Send Confirmation Email
                        </button>
                    </form>
                </div>

                <!-- Send Message -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Send Message</h2>
                    
                    @if(session('success'))
                        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.job-applications.send-message', $application) }}" class="space-y-4" id="message-form">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-2">Message Channel <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-3 gap-2">
                                <label class="flex items-center p-2 border-2 border-slate-200 rounded-lg cursor-pointer hover:border-teal-500 transition-colors message-channel-option">
                                    <input type="radio" name="channel" value="email" class="sr-only" checked onchange="updateMessageRecipient()">
                                    <div class="flex items-center gap-2 w-full">
                                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs font-medium">Email</span>
                                    </div>
                                </label>
                                <label class="flex items-center p-2 border-2 border-slate-200 rounded-lg cursor-pointer hover:border-teal-500 transition-colors message-channel-option">
                                    <input type="radio" name="channel" value="sms" class="sr-only" onchange="updateMessageRecipient()">
                                    <div class="flex items-center gap-2 w-full">
                                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        <span class="text-xs font-medium">SMS</span>
                                    </div>
                                </label>
                                <label class="flex items-center p-2 border-2 border-slate-200 rounded-lg cursor-pointer hover:border-teal-500 transition-colors message-channel-option">
                                    <input type="radio" name="channel" value="whatsapp" class="sr-only" onchange="updateMessageRecipient()">
                                    <div class="flex items-center gap-2 w-full">
                                        <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                        </svg>
                                        <span class="text-xs font-medium">WhatsApp</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label for="message_recipient" class="block text-xs font-medium text-slate-600 mb-1">Recipient <span class="text-red-500">*</span></label>
                            <input type="text" id="message_recipient" name="recipient" value="{{ $application->email }}" required
                                   class="w-full text-sm px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                            <p class="text-xs text-slate-500 mt-1" id="message-recipient-hint">Enter email address</p>
                        </div>

                        <div>
                            <label for="message_content" class="block text-xs font-medium text-slate-600 mb-1">Message <span class="text-red-500">*</span></label>
                            <textarea id="message_content" name="message" rows="4" required maxlength="5000"
                                      class="w-full text-sm px-3 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent"
                                      placeholder="Type your message here..."></textarea>
                            <p class="text-xs text-slate-500 mt-1">
                                <span id="message-char-count">0</span> / 5000 characters
                                <span id="message-sms-count" class="hidden"> (Approx. <span id="message-sms-messages">0</span> SMS)</span>
                            </p>
                        </div>

                        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-teal-700 hover:bg-teal-800">
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Message History -->
                @if($application->messages && $application->messages->count() > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                        <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Message History</h2>
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            @foreach($application->messages->sortByDesc('created_at') as $message)
                                @php
                                    $isConfirmationEmail = isset($message->metadata['type']) && $message->metadata['type'] === 'confirmation_email';
                                @endphp
                                <div class="border border-slate-200 rounded-lg p-3 {{ $message->status === 'failed' ? 'bg-red-50' : ($isConfirmationEmail ? 'bg-blue-50' : 'bg-slate-50') }}">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center gap-2">
                                            @if($isConfirmationEmail)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                                    📧 CONFIRMATION EMAIL
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                                                @if($message->status === 'sent') bg-green-100 text-green-800
                                                @elseif($message->status === 'failed') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ strtoupper($message->channel) }} - {{ \Illuminate\Support\Str::headline($message->status) }}
                                            </span>
                                            <span class="text-xs text-slate-500">
                                                {{ $message->created_at->format('M d, Y g:i A') }}
                                            </span>
                                        </div>
                                        <span class="text-xs text-slate-500">
                                            By {{ $message->sender->name ?? 'Admin' }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-slate-700 mb-1">
                                        <strong>To:</strong> {{ $message->recipient }}
                                    </p>
                                    <div class="text-xs text-slate-800 whitespace-pre-line bg-white rounded p-2 border border-slate-200">
                                        {{ $message->message }}
                                    </div>
                                    @if($message->error_message)
                                        <p class="text-xs text-red-600 mt-2">
                                            <strong>Error:</strong> {{ $message->error_message }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Review Application -->
                @if($application->status === 'pending')
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                        <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Review Application</h2>
                        <form method="POST" action="/admin/job-applications/{{ $applicationId }}/review" class="space-y-4">
                        @csrf
                        <div class="space-y-1">
                            <label for="decision" class="text-xs font-medium text-slate-600">Decision</label>
                            <select id="decision" name="decision" required
                                    class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                                <option value="">Select Decision</option>
                                <option value="pass">Pass</option>
                                <option value="regret">Regret</option>
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label for="review_notes" class="text-xs font-medium text-slate-600">Review Notes</label>
                            <textarea id="review_notes" name="review_notes" rows="3"
                                      class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent"></textarea>
                        </div>
                        <div class="space-y-1" id="regret_template_section" style="display: none;">
                            <label for="regret_template" class="text-xs font-medium text-slate-600">Regret Template</label>
                            <textarea id="regret_template" name="regret_template" rows="3"
                                      class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent"></textarea>
                        </div>
                        <div class="space-y-1" id="pass_template_section" style="display: none;">
                            <label for="pass_template" class="text-xs font-medium text-slate-600">Pass Template</label>
                            <textarea id="pass_template" name="pass_template" rows="3"
                                      class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent"></textarea>
                        </div>
                        <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-700 hover:bg-blue-800">
                            Submit Review
                        </button>
                        </form>
                    </div>
                @endif

                <!-- Schedule Interview -->
                @if(in_array($application->status, ['shortlisted', 'reviewed', 'interview_scheduled', 'interview_passed', 'interview_failed', 'second_interview', 'written_test', 'case_study']))
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                        <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Schedule Interview</h2>
                        <form method="POST" action="/admin/job-applications/{{ $applicationId }}/schedule-interview" class="space-y-4">
                        @csrf
                        <div class="space-y-1">
                            <label for="interview_type" class="text-xs font-medium text-slate-600">Interview Type</label>
                            <select id="interview_type" name="interview_type" required
                                    class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                                <option value="first">First Interview</option>
                                <option value="second">Second Interview</option>
                                <option value="written_test">Written Test</option>
                                <option value="case_study">Case Study</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label for="scheduled_date" class="text-xs font-medium text-slate-600">Interview Date</label>
                                <input type="date" id="scheduled_date" name="scheduled_date" required
                                       class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                            </div>
                            <div class="space-y-1">
                                <label for="scheduled_time" class="text-xs font-medium text-slate-600">Interview Time</label>
                                <input type="time" id="scheduled_time" name="scheduled_time" required
                                       class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                            </div>
                        </div>
                        <input type="hidden" id="scheduled_at" name="scheduled_at">
                        <div class="space-y-1">
                            <label for="location" class="text-xs font-medium text-slate-600">Location</label>
                            <input type="text" id="location" name="location" placeholder="Example: Boardroom 2, Fortress House"
                                   class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                        </div>
                        <div class="space-y-1">
                            <label for="notes" class="text-xs font-medium text-slate-600">Interview Notes (Optional)</label>
                            <textarea id="notes" name="notes" rows="3"
                                      class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent"
                                      placeholder="Any instructions or comments for the interview..."></textarea>
                        </div>
                        <button type="submit"
                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 rounded-xl text-sm font-semibold text-white bg-purple-700 hover:bg-purple-800">
                            Schedule Interview
                        </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .tab-button {
            transition: all 0.2s ease;
        }
        .tab-button:hover {
            color: rgb(51 65 85);
        }
        .tab-button.active {
            border-bottom-color: rgb(20 184 166);
            color: rgb(20 184 166);
        }
        .tab-content {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        // Tab functionality
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active class from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-teal-600', 'text-teal-600');
                button.classList.add('border-transparent', 'text-slate-500');
            });
            
            // Show selected tab content
            const selectedContent = document.getElementById('content-' + tabName);
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
            }
            
            // Add active class to selected tab
            const selectedTab = document.getElementById('tab-' + tabName);
            if (selectedTab) {
                selectedTab.classList.add('active', 'border-teal-600', 'text-teal-600');
                selectedTab.classList.remove('border-transparent', 'text-slate-500');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Handle decision selection to show/hide templates
            const decisionSelect = document.getElementById('decision');
            const regretTemplateSection = document.getElementById('regret_template_section');
            const passTemplateSection = document.getElementById('pass_template_section');

            if (decisionSelect) {
                decisionSelect.addEventListener('change', function() {
                    if (this.value === 'regret') {
                        regretTemplateSection.style.display = 'block';
                        passTemplateSection.style.display = 'none';
                        document.getElementById('pass_template').value = '';
                    } else if (this.value === 'pass') {
                        regretTemplateSection.style.display = 'none';
                        passTemplateSection.style.display = 'block';
                        document.getElementById('regret_template').value = '';
                    } else {
                        regretTemplateSection.style.display = 'none';
                        passTemplateSection.style.display = 'none';
                        document.getElementById('regret_template').value = '';
                        document.getElementById('pass_template').value = '';
                    }
                });
            }

            // Handle interview scheduling form
            const form = document.querySelector('form[action*="schedule-interview"]');
            if (form) {
                const dateInput = document.getElementById('scheduled_date');
                const timeInput = document.getElementById('scheduled_time');
                const hiddenInput = document.getElementById('scheduled_at');

                function combineDateTime() {
                    if (dateInput.value && timeInput.value) {
                        hiddenInput.value = dateInput.value + ' ' + timeInput.value + ':00';
                    }
                }

                if (dateInput && timeInput && hiddenInput) {
                    dateInput.addEventListener('change', combineDateTime);
                    timeInput.addEventListener('change', combineDateTime);

                    form.addEventListener('submit', function(e) {
                        combineDateTime();
                        if (!hiddenInput.value) {
                            e.preventDefault();
                            alert('Please select both date and time for the interview.');
                            return false;
                        }
                    });
                }
            }

            // Handle message form channel switching
            updateMessageRecipient();
        });

        // Update message recipient field based on selected channel
        function updateMessageRecipient() {
            const channel = document.querySelector('input[name="channel"]:checked')?.value;
            if (!channel) return;
            
            const recipientInput = document.getElementById('message_recipient');
            const recipientHint = document.getElementById('message-recipient-hint');
            const channelOptions = document.querySelectorAll('.message-channel-option');

            // Update visual selection
            channelOptions.forEach(option => {
                const input = option.querySelector('input[type="radio"]');
                if (input && input.checked) {
                    option.classList.add('border-teal-500', 'bg-teal-50');
                } else {
                    option.classList.remove('border-teal-500', 'bg-teal-50');
                }
            });

            // Update recipient field based on channel
            if (channel === 'email') {
                recipientInput.type = 'email';
                recipientInput.value = '{{ $application->email }}';
                recipientHint.textContent = 'Enter email address';
            } else {
                recipientInput.type = 'tel';
                recipientInput.value = '{{ $application->phone ?? "" }}';
                recipientHint.textContent = 'Enter phone number (e.g., 0712345678 or +254712345678)';
            }
        }

        // Character counter and SMS calculator for message form
        document.addEventListener('DOMContentLoaded', function() {
            const messageInput = document.getElementById('message_content');
            if (messageInput) {
                messageInput.addEventListener('input', function() {
                    const message = this.value;
                    const charCount = message.length;
                    const charCountEl = document.getElementById('message-char-count');
                    const smsCountEl = document.getElementById('message-sms-count');
                    const smsMessagesEl = document.getElementById('message-sms-messages');
                    const channel = document.querySelector('input[name="channel"]:checked')?.value;

                    if (charCountEl) {
                        charCountEl.textContent = charCount;
                    }

                    // Show SMS count for SMS channel
                    if (channel === 'sms' && smsCountEl && smsMessagesEl) {
                        const smsCount = Math.ceil(charCount / 160);
                        smsMessagesEl.textContent = smsCount;
                        smsCountEl.classList.remove('hidden');
                    } else if (smsCountEl) {
                        smsCountEl.classList.add('hidden');
                    }
                });

                // Update on channel change
                document.querySelectorAll('input[name="channel"]').forEach(radio => {
                    radio.addEventListener('change', function() {
                        updateMessageRecipient();
                        // Trigger character count update
                        messageInput.dispatchEvent(new Event('input'));
                    });
                });
            }
        });
    </script>
    
    <script>
        // Handle delete form with SweetAlert
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForm = document.querySelector('.delete-form');
            if (deleteForm) {
                deleteForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formElement = this;
                    const applicantName = formElement.getAttribute('data-name') || 'this application';
                    
                    Swal.fire({
                        title: 'Are you sure?',
                        html: `<p>You are about to delete the application from <strong>${applicantName}</strong>.</p><p class="mt-2 text-sm text-gray-600">This will permanently delete:</p><ul class="text-sm text-left mt-2 ml-4 list-disc"><li>The application</li><li>All related reviews</li><li>All interview records</li><li>All messages</li><li>The CV file (if any)</li></ul><p class="mt-3 text-red-600 font-semibold">This action cannot be undone!</p>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,
                        width: '500px'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show loading state
                            Swal.fire({
                                title: 'Deleting...',
                                text: 'Please wait while we delete the application and all related data.',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                showConfirmButton: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            
                            // Submit the form
                            formElement.submit();
                        }
                    });
                });
            }
        });
    </script>
@endsection
