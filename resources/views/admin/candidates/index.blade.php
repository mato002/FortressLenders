@extends('layouts.admin')

@section('title', 'Candidates')
@section('header-description', 'Manage all candidates who have applied for positions.')

@section('content')
    @php use Illuminate\Support\Str; @endphp

    <!-- Top bar: stats + templates shortcut -->
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 mb-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 flex-1">
        <div class="group relative bg-gradient-to-br from-white to-slate-50 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-slate-200/60 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100/30 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 mb-1">Total Candidates</p>
                <p class="text-4xl font-bold text-slate-900">{{ $totalCandidatesCount }}</p>
            </div>
        </div>
        <div class="group relative bg-gradient-to-br from-white to-emerald-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-emerald-200/60 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-emerald-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 mb-1">Bio Data Completed</p>
                <p class="text-4xl font-bold text-emerald-700">{{ $bioDataCompletedCount }}</p>
            </div>
        </div>
        <div class="group relative bg-gradient-to-br from-white to-amber-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-amber-200/60 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 mb-1">Bio Data Incomplete</p>
                <p class="text-4xl font-bold text-amber-600">{{ $bioDataIncompleteCount }}</p>
            </div>
        </div>
        <div class="group relative bg-gradient-to-br from-white to-purple-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-purple-200/60 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-purple-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 mb-1">Filtered Results</p>
                <p class="text-4xl font-bold text-purple-600">{{ $filteredCandidatesCount }}</p>
            </div>
        </div>
        </div>

        <!-- Templates quick action -->
        <div class="w-full lg:w-80 bg-white rounded-2xl border border-slate-200/60 shadow-sm p-4 space-y-3">
            <div class="flex items-center justify-between gap-2">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Onboarding forms</p>
                    <p class="text-sm font-bold text-slate-900 mt-0.5">Standard templates</p>
                </div>
                @if(!empty($hasTemplates) && $hasTemplates)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700">
                        Up to date
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-700">
                        Not set
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-600">
                Upload the latest blank <strong>offer letter</strong> and <strong>contract</strong> once here. All candidates will be able to download them from their portal.
            </p>
            <a href="{{ route('admin.document-templates.index') }}" class="inline-flex items-center justify-center gap-2 w-full px-3 py-2 bg-teal-600 text-white rounded-xl text-xs font-semibold hover:bg-teal-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Manage standard forms
            </a>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.candidates.index') }}" class="mb-6">
        <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." 
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Bio Data Status</label>
                    <select name="bio_data_completed" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                        <option value="all">All Statuses</option>
                        <option value="1" {{ request('bio_data_completed') === '1' ? 'selected' : '' }}>Completed</option>
                        <option value="0" {{ request('bio_data_completed') === '0' ? 'selected' : '' }}>Incomplete</option>
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-5">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold text-sm shadow-sm">
                    Apply Filters
                </button>
                @if(request()->hasAny(['search', 'bio_data_completed']))
                    <a href="{{ route('admin.candidates.index') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-semibold text-sm">
                        Clear Filters
                    </a>
                @endif
            </div>
        </div>
    </form>

    <div class="bg-white border border-slate-200/60 rounded-2xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left text-sm min-w-[640px]">
            <thead class="bg-gradient-to-r from-slate-50 to-slate-100 text-slate-600 uppercase tracking-wide text-xs font-semibold">
                <tr>
                    <th class="px-4 sm:px-6 py-4">Candidate</th>
                    <th class="px-4 sm:px-6 py-4 hidden sm:table-cell">Email</th>
                    <th class="px-4 sm:px-6 py-4">Applications</th>
                    <th class="px-4 sm:px-6 py-4 hidden md:table-cell">Documents</th>
                    <th class="px-4 sm:px-6 py-4 hidden md:table-cell">Appraisals</th>
                    <th class="px-4 sm:px-6 py-4">Bio Data</th>
                    <th class="px-4 sm:px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($candidates as $candidate)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                        <td class="px-4 sm:px-6 py-5">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl overflow-hidden bg-gradient-to-br from-blue-100 to-indigo-100 border-2 border-slate-200 flex-shrink-0 shadow-sm flex items-center justify-center">
                                    <div class="text-sm font-bold text-blue-700">{{ strtoupper(substr($candidate->name, 0, 2)) }}</div>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 text-sm sm:text-base truncate">{{ $candidate->name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Joined {{ $candidate->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-5 text-slate-700 text-sm hidden sm:table-cell">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <p class="truncate">{{ $candidate->email }}</p>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-5">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                {{ $candidate->job_applications_count }} {{ Str::plural('application', $candidate->job_applications_count) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-5 text-slate-600 text-sm hidden md:table-cell">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                                {{ $candidate->documents_count }} {{ Str::plural('document', $candidate->documents_count) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-5 text-slate-600 text-sm hidden md:table-cell">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-700">
                                {{ $candidate->appraisals_count }} {{ Str::plural('appraisal', $candidate->appraisals_count) }}
                            </span>
                        </td>
                        <td class="px-4 sm:px-6 py-5">
                            @if($candidate->bio_data_completed)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Completed
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">
                                    Incomplete
                                </span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-5">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.candidates.show', $candidate) }}" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-xs font-semibold shadow-sm">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 sm:px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-slate-500 font-semibold text-lg mb-1">No candidates found</p>
                                <p class="text-slate-400 text-sm">Try adjusting your search filters.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>

        <!-- Pagination -->
        @if($candidates->hasPages())
            <div class="px-4 sm:px-6 py-4 border-t border-slate-200 bg-slate-50">
                {{ $candidates->links() }}
            </div>
        @endif
    </div>
@endsection
