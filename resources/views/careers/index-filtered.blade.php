@extends('layouts.website')

@section('title', 'Careers - Fortress Lenders Ltd')
@section('meta_description', 'Explore career opportunities at Fortress Lenders Ltd. Browse and apply for open positions.')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900 text-white py-12 sm:py-16 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4">Join Our Team</h1>
            <p class="text-lg sm:text-xl text-teal-100">Explore exciting career opportunities at Fortress Lenders Ltd</p>
        </div>
    </section>

    <!-- Jobs Section with Filters -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                <!-- Filters Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Filter Jobs</h3>

                        <form method="GET" class="space-y-6">
                            <!-- Search -->
                            <div>
                                <input type="text" name="search" placeholder="Job title..." value="{{ request('search') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                            </div>

                            <!-- Department -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Department</label>
                                <select name="department" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                                    <option value="">All Departments</option>
                                    @forelse($departments ?? [] as $dept)
                                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                                    @empty
                                        <option value="it">IT</option>
                                        <option value="hr">HR</option>
                                        <option value="finance">Finance</option>
                                        <option value="operations">Operations</option>
                                    @endforelse
                                </select>
                            </div>

                            <!-- Employment Type -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Employment Type</label>
                                <div class="space-y-2">
                                    @foreach(['full_time' => 'Full Time', 'part_time' => 'Part Time', 'contract' => 'Contract', 'internship' => 'Internship'] as $type => $label)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="employment_type[]" value="{{ $type }}" {{ in_array($type, request()->employment_type ?? []) ? 'checked' : '' }} class="w-4 h-4 text-teal-600 border-gray-300 rounded">
                                            <span class="ml-2 text-sm text-gray-600">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Experience Level -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Experience Level</label>
                                <select name="experience_level" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                                    <option value="">All Levels</option>
                                    <option value="entry" {{ request('experience_level') == 'entry' ? 'selected' : '' }}>Entry Level</option>
                                    <option value="mid" {{ request('experience_level') == 'mid' ? 'selected' : '' }}>Mid Level</option>
                                    <option value="senior" {{ request('experience_level') == 'senior' ? 'selected' : '' }}>Senior</option>
                                    <option value="lead" {{ request('experience_level') == 'lead' ? 'selected' : '' }}>Lead</option>
                                </select>
                            </div>

                            <!-- Location -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                                <select name="location" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                                    <option value="">All Locations</option>
                                    @forelse($locations ?? [] as $loc)
                                        <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                                    @empty
                                        <option value="nairobi">Nairobi</option>
                                        <option value="mombasa">Mombasa</option>
                                        <option value="kisumu">Kisumu</option>
                                        <option value="remote">Remote</option>
                                    @endforelse
                                </select>
                            </div>

                            <!-- Salary Range -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Salary Range (KES)</label>
                                <div class="space-y-2">
                                    <input type="number" name="salary_min" placeholder="Min" value="{{ request('salary_min') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                                    <input type="number" name="salary_max" placeholder="Max" value="{{ request('salary_max') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 text-sm">
                                </div>
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="w-full px-4 py-2 rounded-lg text-sm font-semibold text-white bg-teal-800 hover:bg-teal-900">
                                Apply Filters
                            </button>
                            @if(request()->query())
                                <a href="{{ route('careers.index') }}" class="block w-full text-center px-4 py-2 rounded-lg text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">
                                    Clear Filters
                                </a>
                            @endif
                        </form>
                    </div>
                </div>

                <!-- Jobs List -->
                <div class="lg:col-span-3 space-y-4">
                    @forelse($jobs as $job)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow p-6">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">
                                        <a href="{{ route('careers.show', $job->slug) }}" class="hover:text-teal-600">
                                            {{ $job->title }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-600 mt-1">{{ $job->department ?? 'General' }}</p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3 mb-4">
                                @if($job->location)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        {{ $job->location }}
                                    </span>
                                @endif

                                @if($job->employment_type)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                                        </svg>
                                        {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                    </span>
                                @endif

                                @if($job->salary_min || $job->salary_max)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8.16 5.314l4.897-1.596A1 1 0 0115 4.684V7a1 1 0 01-.82.983L10.85 8.85m0 6.3l3.34-1.088A1 1 0 0015 13.316v2.316a1 1 0 01-1.036.983l-4.897-1.596A1 1 0 018 13.316V8.684a1 1 0 011.16-.979l4.897 1.595M5 12a1 1 0 100-2 1 1 0 000 2z" />
                                        </svg>
                                        @if($job->salary_min && $job->salary_max)
                                            KES {{ number_format($job->salary_min) }} - {{ number_format($job->salary_max) }}
                                        @else
                                            Competitive
                                        @endif
                                    </span>
                                @endif
                            </div>

                            <p class="text-gray-600 text-sm mb-4">{{ Str::limit($job->description, 150) }}</p>

                            <div class="flex gap-3">
                                <a href="{{ route('careers.show', $job->slug) }}" class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold text-teal-600 border border-teal-200 hover:bg-teal-50 text-center">
                                    View Details
                                </a>
                                <a href="{{ route('careers.apply', $job->slug) }}" class="flex-1 px-4 py-2 rounded-lg text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 text-center">
                                    Apply Now
                                </a>
                                <form action="{{ route('candidate.jobs.save', $job) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 rounded-lg text-amber-600 border border-amber-200 hover:bg-amber-50" title="Save job">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-600 font-medium">No jobs found matching your criteria</p>
                            <p class="text-sm text-gray-500 mt-1">Try adjusting your filters.</p>
                        </div>
                    @endforelse

                    @if($jobs->count() > 0)
                        <div class="mt-8">
                            {{ $jobs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
