@extends('layouts.admin')

@section('title', 'Candidate Details')
@section('header-description', 'View candidate information and related data.')

@section('header-actions')
    <a href="{{ route('admin.candidates.index') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 whitespace-nowrap border border-slate-200">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="hidden sm:inline">Back to Candidates</span>
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Candidate Information -->
            <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Name</label>
                        <p class="text-base text-slate-900 mt-1">{{ $candidate->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Email</label>
                        <p class="text-base text-slate-900 mt-1">{{ $candidate->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Bio Data Status</label>
                        <div class="mt-1">
                            @if($candidate->bio_data_completed)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Completed
                                </span>
                                @if($candidate->bio_data_completed_at)
                                    <p class="text-xs text-slate-500 mt-1">Completed on {{ $candidate->bio_data_completed_at->format('M d, Y') }}</p>
                                @endif
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    Incomplete
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-slate-600">Account Created</label>
                        <p class="text-base text-slate-900 mt-1">{{ $candidate->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Bio Data Information -->
            @if($candidate->bio_data_completed && !empty($bioData))
            <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Bio Data Information</h2>
                
                <!-- Personal Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-3 pb-2 border-b border-slate-200">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Full Name</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['full_name'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Date of Birth</label>
                            <p class="text-base text-slate-900 mt-1">{{ isset($bioData['date_of_birth']) ? \Carbon\Carbon::parse($bioData['date_of_birth'])->format('M d, Y') : '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Gender</label>
                            <p class="text-base text-slate-900 mt-1">{{ ucfirst($bioData['gender'] ?? '—') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Nationality</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['nationality'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">ID Number</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['id_number'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Phone Number</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['phone'] ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-3 pb-2 border-b border-slate-200">Address Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="text-sm font-semibold text-slate-600">Street Address</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['address'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">City</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['city'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Postal Code</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['postal_code'] ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-3 pb-2 border-b border-slate-200">Emergency Contact</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Name</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['emergency_contact_name'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Phone</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['emergency_contact_phone'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Relationship</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['emergency_contact_relationship'] ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Education -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-3 pb-2 border-b border-slate-200">Education</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Education Level</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['education_level'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Institution</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['institution'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Qualification</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['qualification'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Year Completed</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['year_completed'] ?? '—' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Work Experience -->
                @if(!empty($bioData['previous_employer']) || !empty($bioData['previous_position']))
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-3 pb-2 border-b border-slate-200">Previous Work Experience</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Previous Employer</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['previous_employer'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Position</label>
                            <p class="text-base text-slate-900 mt-1">{{ $bioData['previous_position'] ?? '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Start Date</label>
                            <p class="text-base text-slate-900 mt-1">{{ isset($bioData['previous_start_date']) ? \Carbon\Carbon::parse($bioData['previous_start_date'])->format('M d, Y') : '—' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-600">End Date</label>
                            <p class="text-base text-slate-900 mt-1">{{ isset($bioData['previous_end_date']) ? \Carbon\Carbon::parse($bioData['previous_end_date'])->format('M d, Y') : '—' }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Additional Information -->
                @if(!empty($bioData['skills']) || !empty($bioData['languages']) || !empty($bioData['additional_info']))
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-3 pb-2 border-b border-slate-200">Additional Information</h3>
                    <div class="space-y-4">
                        @if(!empty($bioData['skills']))
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Skills</label>
                            <p class="text-base text-slate-900 mt-1 whitespace-pre-line">{{ $bioData['skills'] }}</p>
                        </div>
                        @endif
                        @if(!empty($bioData['languages']))
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Languages</label>
                            <p class="text-base text-slate-900 mt-1 whitespace-pre-line">{{ $bioData['languages'] }}</p>
                        </div>
                        @endif
                        @if(!empty($bioData['additional_info']))
                        <div>
                            <label class="text-sm font-semibold text-slate-600">Additional Information</label>
                            <p class="text-base text-slate-900 mt-1 whitespace-pre-line">{{ $bioData['additional_info'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @else
            <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6">
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <p class="text-slate-500 font-semibold">Bio Data Not Completed</p>
                    <p class="text-slate-400 text-sm mt-1">This candidate has not completed their bio data form yet.</p>
                </div>
            </div>
            @endif

            <!-- Job Applications -->
            <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Job Applications ({{ $candidate->jobApplications->count() }})</h2>
                @if($candidate->jobApplications->count() > 0)
                    <div class="space-y-3">
                        @foreach($candidate->jobApplications as $application)
                            <div class="border border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-slate-900">{{ $application->jobPost->title ?? 'N/A' }}</h3>
                                        <p class="text-sm text-slate-600 mt-1">Applied on {{ $application->created_at->format('M d, Y') }}</p>
                                        <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-semibold 
                                            @if($application->status === 'hired') bg-purple-100 text-purple-700
                                            @elseif($application->status === 'stage_2_passed') bg-blue-100 text-blue-700
                                            @elseif(in_array($application->status, ['sieving_passed', 'pending_manual_review'])) bg-green-100 text-green-700
                                            @elseif(in_array($application->status, ['sieving_rejected', 'aptitude_failed'])) bg-red-100 text-red-700
                                            @elseif($application->status === 'received') bg-blue-100 text-blue-700
                                            @else bg-gray-100 text-gray-700
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('admin.job-applications.show', $application) }}" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-xs font-semibold ml-3">
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-slate-500 text-sm">No job applications found.</p>
                @endif
            </div>

            <!-- Documents -->
            @if($candidate->documents->count() > 0)
            <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Documents ({{ $candidate->documents->count() }})</h2>
                <div class="space-y-3">
                    @foreach($candidate->documents as $document)
                        <div class="border border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $document->document_type }}</p>
                                    <p class="text-sm text-slate-600 mt-1">Uploaded on {{ $document->created_at->format('M d, Y') }}</p>
                                </div>
                                <a href="{{ route('candidate.documents.download', $document) }}" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-xs font-semibold">
                                    Download
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Appraisals -->
            @if($candidate->appraisals->count() > 0)
            <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6">
                <h2 class="text-xl font-bold text-slate-900 mb-4">Appraisals ({{ $candidate->appraisals->count() }})</h2>
                <div class="space-y-3">
                    @foreach($candidate->appraisals as $appraisal)
                        <div class="border border-slate-200 rounded-xl p-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <p class="font-semibold text-slate-900">{{ $appraisal->title ?? 'Appraisal' }}</p>
                                    <p class="text-sm text-slate-600 mt-1">Created on {{ $appraisal->created_at->format('M d, Y') }}</p>
                                </div>
                                <a href="{{ route('candidate.appraisals.show', $appraisal) }}" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-xs font-semibold">
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Application Statistics</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-slate-600">Total Applications</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $stats['total'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Pending</p>
                        <p class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Sieving Passed</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['sieving_passed'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Stage 2 Passed</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $stats['stage_2_passed'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Hired</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $stats['hired'] }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Rejected</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['sieving_rejected'] + $stats['aptitude_failed'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-slate-600">Documents</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $candidate->documents->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600">Appraisals</p>
                        <p class="text-2xl font-bold text-slate-900">{{ $candidate->appraisals->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Actions</h3>
                <div class="space-y-2">
                    @if($candidate->jobApplications->count() > 0)
                        <a href="{{ route('admin.job-applications.index', ['search' => $candidate->email]) }}" class="block w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold text-center">
                            View All Applications
                        </a>
                    @endif
                    @if($candidate->jobApplications->count() > 0)
                        @php
                            $firstApplication = $candidate->jobApplications->first();
                        @endphp
                        <a href="{{ route('admin.job-applications.view-candidate-dashboard', $firstApplication) }}" class="block w-full px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold text-center">
                            View Candidate Dashboard
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
