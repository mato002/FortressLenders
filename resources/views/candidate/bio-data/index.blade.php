@extends('layouts.candidate')

@section('title', 'Bio Data')
@section('header-description', 'Complete your personal and professional information')

@section('content')
<div class="space-y-6">
    <!-- Statistics and Progress Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Completion Progress -->
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-teal-100 text-sm font-medium mb-2">Completion Status</p>
            <p class="text-4xl font-bold mb-2">{{ $stats['completion_percentage'] ?? 0 }}%</p>
            <div class="w-full bg-white/20 rounded-full h-2.5">
                <div class="bg-white h-2.5 rounded-full transition-all duration-300" style="width: {{ $stats['completion_percentage'] ?? 0 }}%"></div>
            </div>
        </div>

        <!-- Required Fields -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
            </div>
            <p class="text-blue-100 text-sm font-medium mb-2">Required Fields</p>
            <p class="text-4xl font-bold">{{ $stats['required_completed'] ?? 0 }}/{{ $stats['required_total'] ?? 0 }}</p>
            <p class="text-blue-100 text-xs mt-2">{{ $stats['required_total'] - $stats['required_completed'] }} remaining</p>
        </div>

        <!-- Optional Fields -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                </div>
            </div>
            <p class="text-purple-100 text-sm font-medium mb-2">Optional Fields</p>
            <p class="text-4xl font-bold">{{ $stats['optional_completed'] ?? 0 }}/{{ $stats['optional_total'] ?? 0 }}</p>
            <p class="text-purple-100 text-xs mt-2">Additional information</p>
        </div>
    </div>

    @if($stats['is_complete'] && $stats['completed_at'])
    <!-- Completion Banner -->
    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-1">Bio Data Complete!</h3>
                    <p class="text-green-100">Completed on {{ $stats['completed_at']->format('F d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    @php
        $isLocked = $stats['is_complete'] ?? false;
    @endphp

    <!-- Bio Data Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-md">
        <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-teal-50 rounded-lg">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Bio Data Form</h2>
                            <p class="text-gray-600 mt-1">Please fill in all required information</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('candidate.bio-data.update') }}" class="p-8" id="bio-data-form">
            @csrf

            <!-- Personal Information -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-teal-200">
                    <div class="p-2 bg-teal-50 rounded-lg">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Personal Information</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" id="full_name" value="{{ old('full_name', $bioData['full_name'] ?? '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('full_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth <span class="text-red-500">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $bioData['date_of_birth'] ?? '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('date_of_birth')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" id="gender" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $bioData['gender'] ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $bioData['gender'] ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $bioData['gender'] ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">Nationality <span class="text-red-500">*</span></label>
                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality', $bioData['nationality'] ?? '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('nationality')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="id_number" class="block text-sm font-medium text-gray-700 mb-2">ID Number <span class="text-red-500">*</span></label>
                        <input type="text" name="id_number" id="id_number" value="{{ old('id_number', $bioData['id_number'] ?? '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('id_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $bioData['phone'] ?? '') }}" required
                               placeholder="+254712345678"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        <p class="text-xs text-gray-500 mt-1">Enter your number in international format, starting with +254.</p>
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-teal-200">
                    <div class="p-2 bg-teal-50 rounded-lg">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Address Information</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Street Address <span class="text-red-500">*</span></label>
                        <textarea name="address" id="address" rows="3" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">{{ old('address', $bioData['address'] ?? '') }}</textarea>
                        @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City <span class="text-red-500">*</span></label>
                        <input type="text" name="city" id="city" value="{{ old('city', $bioData['city'] ?? '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $bioData['postal_code'] ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('postal_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Emergency Contact -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-teal-200">
                    <div class="p-2 bg-teal-50 rounded-lg">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Emergency Contact</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="emergency_contact_name" id="emergency_contact_name" value="{{ old('emergency_contact_name', $bioData['emergency_contact_name'] ?? '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('emergency_contact_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone <span class="text-red-500">*</span></label>
                        <input type="tel" name="emergency_contact_phone" id="emergency_contact_phone" value="{{ old('emergency_contact_phone', $bioData['emergency_contact_phone'] ?? '') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors"
                               placeholder="+254712345678">
                        @error('emergency_contact_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700 mb-2">Relationship <span class="text-red-500">*</span></label>
                        <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $bioData['emergency_contact_relationship'] ?? '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('emergency_contact_relationship')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Education -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-teal-200">
                    <div class="p-2 bg-teal-50 rounded-lg">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Education</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="education_level" class="block text-sm font-medium text-gray-700 mb-2">Education Level <span class="text-red-500">*</span></label>
                        <input type="text" name="education_level" id="education_level" value="{{ old('education_level', $bioData['education_level'] ?? '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors" placeholder="e.g., Bachelor's Degree">
                        @error('education_level')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="institution" class="block text-sm font-medium text-gray-700 mb-2">Institution <span class="text-red-500">*</span></label>
                        <input type="text" name="institution" id="institution" value="{{ old('institution', $bioData['institution'] ?? '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('institution')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="qualification" class="block text-sm font-medium text-gray-700 mb-2">Qualification <span class="text-red-500">*</span></label>
                        <input type="text" name="qualification" id="qualification" value="{{ old('qualification', $bioData['qualification'] ?? '') }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('qualification')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="year_completed" class="block text-sm font-medium text-gray-700 mb-2">Year Completed</label>
                        <input type="number" name="year_completed" id="year_completed" value="{{ old('year_completed', $bioData['year_completed'] ?? '') }}" min="1900" max="{{ date('Y') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('year_completed')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Work Experience -->
            @php
                $hasPreviousExperience = old('has_previous_experience', $bioData['has_previous_experience'] ?? 'no') === 'yes';
            @endphp
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-4 pb-3 border-b-2 border-teal-200">
                    <div class="p-2 bg-teal-50 rounded-lg">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900">Previous Work Experience</h3>
                        <p class="text-sm text-gray-500">If this is your first job, choose "No" below and you can leave this section blank.</p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Have you worked before?</label>
                    <div class="flex items-center gap-4">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="radio" name="has_previous_experience" value="yes" class="text-teal-600 border-gray-300"
                                   {{ $hasPreviousExperience ? 'checked' : '' }}>
                            <span>Yes, I have previous work experience</span>
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="radio" name="has_previous_experience" value="no" class="text-teal-600 border-gray-300"
                                   {{ ! $hasPreviousExperience ? 'checked' : '' }}>
                            <span>No, this is my first job</span>
                        </label>
                    </div>
                </div>

                <div id="work-experience-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 {{ $hasPreviousExperience ? '' : 'hidden' }}">
                    <div>
                        <label for="previous_employer" class="block text-sm font-medium text-gray-700 mb-2">Previous Employer</label>
                        <input type="text" name="previous_employer" id="previous_employer" value="{{ old('previous_employer', $bioData['previous_employer'] ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('previous_employer')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="previous_position" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                        <input type="text" name="previous_position" id="previous_position" value="{{ old('previous_position', $bioData['previous_position'] ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('previous_position')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="previous_start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" name="previous_start_date" id="previous_start_date" value="{{ old('previous_start_date', $bioData['previous_start_date'] ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('previous_start_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="previous_end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                        <input type="date" name="previous_end_date" id="previous_end_date" value="{{ old('previous_end_date', $bioData['previous_end_date'] ?? '') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors">
                        @error('previous_end_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="mb-10">
                <div class="flex items-center gap-3 mb-6 pb-3 border-b-2 border-teal-200">
                    <div class="p-2 bg-teal-50 rounded-lg">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Additional Information</h3>
                </div>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="skills" class="block text-sm font-medium text-gray-700 mb-2">Skills</label>
                        <textarea name="skills" id="skills" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors" placeholder="List your skills separated by commas">{{ old('skills', $bioData['skills'] ?? '') }}</textarea>
                        @error('skills')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="languages" class="block text-sm font-medium text-gray-700 mb-2">Languages</label>
                        <textarea name="languages" id="languages" rows="2" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors" placeholder="List languages you speak">{{ old('languages', $bioData['languages'] ?? '') }}</textarea>
                        @error('languages')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="additional_info" class="block text-sm font-medium text-gray-700 mb-2">Additional Information</label>
                        <textarea name="additional_info" id="additional_info" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors" placeholder="Any additional information you'd like to share">{{ old('additional_info', $bioData['additional_info'] ?? '') }}</textarea>
                        @error('additional_info')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            @if(! $isLocked)
                <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-teal-600 to-teal-700 text-white rounded-lg hover:from-teal-700 hover:to-teal-800 transition-all font-semibold shadow-sm hover:shadow-md">
                        Save Bio Data
                    </button>
                </div>
            @else
                <div class="pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-500">
                        Your bio data has been submitted and is now locked. To make any changes, please contact HR or your administrator.
                    </p>
                </div>
            @endif
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Toggle previous work experience section
    (function () {
        const yesRadio = document.querySelector('input[name="has_previous_experience"][value="yes"]');
        const noRadio = document.querySelector('input[name="has_previous_experience"][value="no"]');
        const fields = document.getElementById('work-experience-fields');

        if (!yesRadio || !noRadio || !fields) return;

        function updateWorkExperienceVisibility() {
            if (yesRadio.checked) {
                fields.classList.remove('hidden');
            } else {
                fields.classList.add('hidden');
                // Optionally clear values when hidden so first-time workers don't accidentally save stale data
                ['previous_employer', 'previous_position', 'previous_start_date', 'previous_end_date'].forEach(function (id) {
                    const input = document.getElementById(id);
                    if (input) input.value = '';
                });
            }
        }

        yesRadio.addEventListener('change', updateWorkExperienceVisibility);
        noRadio.addEventListener('change', updateWorkExperienceVisibility);
        updateWorkExperienceVisibility();
    })();

    document.getElementById('bio-data-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Save Bio Data?',
            text: 'Are you sure you want to save your bio data information?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#14b8a6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, save it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Saving...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                this.submit();
            }
        });
    });
</script>
@endpush
@endsection
