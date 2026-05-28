@extends('layouts.admin')

@section('title', 'Candidate Filtering')
@section('header-description', 'Advanced search and filter candidates by multiple criteria.')

@section('header-actions')
    <button onclick="document.getElementById('filterForm').reset(); window.location.href='{{ route('admin.candidates.filter') }}';" class="px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50">
        Clear Filters
    </button>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Filter Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Advanced Filters</h3>
                
                <form id="filterForm" method="GET" action="{{ route('admin.candidates.filter') }}" data-auto-filter class="space-y-6">
                    <!-- Experience Level -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Experience Level</label>
                        <div class="space-y-2">
                            @foreach($experienceLevels as $level)
                                <label class="flex items-center">
                                    <input type="checkbox" name="experience_level[]" value="{{ $level }}" {{ in_array($level, request()->experience_level ?? []) ? 'checked' : '' }} class="w-4 h-4 text-teal-600 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $level)) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                        <select name="location[]" multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500" size="5">
                            @foreach($locations as $location)
                                <option value="{{ $location }}" {{ in_array($location, request()->location ?? []) ? 'selected' : '' }}>{{ $location ?? 'N/A' }}</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Hold Ctrl to select multiple</p>
                    </div>

                    <!-- Education Level -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Education Level</label>
                        <div class="space-y-2">
                            @foreach($educationLevels as $edu)
                                <label class="flex items-center">
                                    <input type="checkbox" name="education_level[]" value="{{ $edu }}" {{ in_array($edu, request()->education_level ?? []) ? 'checked' : '' }} class="w-4 h-4 text-teal-600 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $edu)) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Salary Range -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Expected Salary Range</label>
                        <div class="space-y-2">
                            <input type="number" name="salary_min" placeholder="Min (KES)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500" value="{{ request('salary_min') }}">
                            <input type="number" name="salary_max" placeholder="Max (KES)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500" value="{{ request('salary_max') }}">
                        </div>
                    </div>

                    <!-- Application Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Application Status</label>
                        <div class="space-y-2">
                            @foreach($applicationStatuses as $status => $label)
                                <label class="flex items-center">
                                    <input type="checkbox" name="application_status[]" value="{{ $status }}" {{ in_array($status, request()->application_status ?? []) ? 'checked' : '' }} class="w-4 h-4 text-teal-600 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-600">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Notice Period -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notice Period</label>
                        <div class="space-y-2">
                            @foreach($noticePeriods as $period)
                                <label class="flex items-center">
                                    <input type="checkbox" name="notice_period[]" value="{{ $period }}" {{ in_array($period, request()->notice_period ?? []) ? 'checked' : '' }} class="w-4 h-4 text-teal-600 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $period)) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <!-- Results -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900">Candidates ({{ $candidates->total() }})</h3>
                </div>

                @if($candidates->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($candidates as $candidate)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-900">
                                            <a href="{{ route('admin.candidates.show', $candidate) }}" class="hover:text-teal-600">
                                                {{ $candidate->full_name }}
                                            </a>
                                        </h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $candidate->email }}</p>
                                    </div>
                                    <a href="{{ route('admin.candidates.show', $candidate) }}" class="px-4 py-2 rounded-lg text-sm font-semibold text-teal-600 border border-teal-200 hover:bg-teal-50">
                                        View Profile
                                    </a>
                                </div>

                                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @if($candidate->current_location)
                                        <div>
                                            <p class="text-xs text-gray-500">Location</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $candidate->current_location }}</p>
                                        </div>
                                    @endif
                                    @if($candidate->experience_level)
                                        <div>
                                            <p class="text-xs text-gray-500">Experience</p>
                                            <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $candidate->experience_level)) }}</p>
                                        </div>
                                    @endif
                                    @if($candidate->education_level)
                                        <div>
                                            <p class="text-xs text-gray-500">Education</p>
                                            <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $candidate->education_level)) }}</p>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-xs text-gray-500">Applications</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $candidate->jobApplications->count() }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $candidates->links() }}
                    </div>
                @else
                    <div class="p-12 text-center">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-600 font-medium">No candidates found matching your filters.</p>
                        <p class="text-sm text-gray-500 mt-1">Try adjusting your search criteria.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
