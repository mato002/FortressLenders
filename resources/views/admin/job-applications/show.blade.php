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
    @endphp
    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">{{ $application->name ?: 'No Name' }}</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Applied for {{ optional($application->jobPost)->title ?? 'Unknown Position' }}@if($application->created_at) on {{ $application->created_at->format('M d, Y H:i') }}@endif
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.job-applications.index') }}" class="px-3 py-2 text-sm rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">
                    Back to list
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
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
                                    <a href="{{ asset('storage/'.$application->cv_path) }}" target="_blank" class="text-teal-800 hover:text-teal-900 font-semibold">
                                        Download CV
                                    </a>
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
                                        <div class="bg-slate-50 rounded-xl p-3">
                                            <div class="flex justify-between items-start mb-2">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    {{ Str::headline(str_replace('_', ' ', $interview->interview_type)) }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $interview->result === 'pass' ? 'bg-green-100 text-green-800' : ($interview->result === 'fail' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ Str::headline($interview->result) }}
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
                <!-- Status -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Status</h2>
                    @php
                        $statusClasses = match($application->status) {
                            'pending' => 'bg-amber-100 text-amber-800',
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
        });
    </script>
@endsection
