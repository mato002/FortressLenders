@extends('layouts.admin')

@section('title', 'Team Members')
@section('header-description', 'Manage the leadership and branch teams displayed on the website.')

@section('header-actions')
    <a href="{{ route('admin.team-members.create') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 lg:px-5 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/></svg>
        <span class="hidden sm:inline">Add Member</span>
        <span class="sm:hidden">Add</span>
    </a>
@endsection

@section('content')
    @php use Illuminate\Support\Str; @endphp

    @if (session('status'))
        <div class="mb-6 bg-teal-50 border border-teal-200 text-teal-900 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-teal-600 hover:text-teal-800">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-900 px-4 py-3 rounded-xl flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    @endif

    @if (session('errors') && is_array(session('errors')))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-900 px-4 py-3 rounded-xl">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold mb-2">Errors occurred:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach(session('errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-900 px-4 py-3 rounded-xl">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-red-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="font-semibold mb-2">Validation errors:</p>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if (session('show_bulk_credentials') && session('bulk_credentials'))
        <div class="mb-6 bg-amber-50 border border-amber-200 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-amber-900 mb-3">Generated Login Credentials</h3>
            <p class="text-sm text-amber-800 mb-4">The following login credentials have been generated. Please save this information; it will not be shown again.</p>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach(session('bulk_credentials') as $credential)
                    <div class="bg-white rounded-lg p-4 border border-amber-200">
                        <p class="font-semibold text-gray-900 mb-2">{{ $credential['name'] }}</p>
                        <dl class="text-sm space-y-1">
                            <div><dt class="inline font-medium text-gray-700">Email:</dt> <dd class="inline">{{ $credential['email'] }}</dd></div>
                            <div><dt class="inline font-medium text-gray-700">Password:</dt> <dd class="inline font-mono bg-amber-100 px-2 py-0.5 rounded">{{ $credential['password'] }}</dd></div>
                        </dl>
                    </div>
                @endforeach
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="mt-4 px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors font-semibold text-sm">
                Dismiss
            </button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
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
                <p class="text-sm font-medium text-slate-600 mb-1">Total Members</p>
                <p class="text-4xl font-bold text-slate-900">{{ $totalTeamMembersCount }}</p>
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
                <p class="text-sm font-medium text-slate-600 mb-1">Active Members</p>
                <p class="text-4xl font-bold text-emerald-700">{{ $activeTeamMembersCount }}</p>
            </div>
        </div>
        <div class="group relative bg-gradient-to-br from-white to-amber-50/30 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-amber-200/60 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-100/40 rounded-full -mr-16 -mt-16 blur-2xl"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm font-medium text-slate-600 mb-1">Hidden Members</p>
                <p class="text-4xl font-bold text-amber-600">{{ $hiddenTeamMembersCount }}</p>
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
                <p class="text-4xl font-bold text-purple-600">{{ $filteredTeamMembersCount }}</p>
            </div>
        </div>
    </div>

    <!-- Share onboarding link -->
    <div class="mb-6 bg-teal-50 border border-teal-200 rounded-2xl p-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h3 class="font-semibold text-teal-900 mb-1">Team Onboarding Form</h3>
                <p class="text-sm text-teal-800">Share this link with company members to let them add their own profiles. Submissions will appear here as pending (hidden) until you activate them.</p>
            </div>
            <div class="flex items-center gap-2 flex-shrink-0">
                <input type="text" id="onboarding-url" readonly value="{{ route('team.onboarding') }}"
                    class="flex-1 min-w-0 px-4 py-2.5 border border-teal-200 rounded-xl bg-white text-sm text-gray-700 font-mono">
                <button type="button" onclick="navigator.clipboard.writeText(document.getElementById('onboarding-url').value); this.textContent='Copied!'; setTimeout(()=>this.textContent='Copy link', 2000)"
                    class="px-4 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl text-sm font-semibold whitespace-nowrap">
                    Copy link
                </button>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.team-members.index') }}" data-auto-filter class="mb-6">
        <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, role, email, phone, or bio..." 
                           class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Status</label>
                    <select name="is_active" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all">
                        <option value="all">All Statuses</option>
                        <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Hidden</option>
                    </select>
                </div>
            </div>
            @if(request()->hasAny(['search', 'is_active']))
            <div class="flex items-center gap-3 mt-5">
                <a href="{{ route('admin.team-members.index') }}" class="px-5 py-2.5 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-colors font-semibold text-sm">
                    Clear Filters
                </a>
            </div>
            @endif
        </div>
    </form>

    <!-- Bulk Actions Bar -->
    <div id="bulk-actions-container" class="hidden mb-4 bg-blue-50 border border-blue-200 rounded-2xl p-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <span id="selected-count" class="font-semibold text-blue-900">0 selected</span>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <form id="bulk-action-form" method="POST" action="{{ route('admin.team-members.bulk-action') }}" class="flex items-center gap-2">
                    @csrf
                    <div id="bulk-selected-ids-container"></div>
                    <select name="action" id="bulk-action-select" class="px-3 py-2 border border-blue-300 rounded-lg text-sm font-medium text-blue-900 bg-white focus:ring-2 focus:ring-blue-500">
                        <option value="">Choose action...</option>
                        <optgroup label="Status Actions">
                            <option value="activate">Activate Selected</option>
                            <option value="deactivate">Deactivate Selected</option>
                        </optgroup>
                        <optgroup label="Login Actions">
                            <option value="generate_login">Generate Login (for members without login)</option>
                            <option value="regenerate_login">Regenerate Login (for members with login)</option>
                        </optgroup>
                        <optgroup label="Danger Zone">
                            <option value="delete">Delete Selected</option>
                        </optgroup>
                    </select>
                    <button type="submit" id="bulk-action-btn" disabled class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        Apply
                    </button>
                </form>
                <button type="button" onclick="clearSelection()" class="px-4 py-2 border border-blue-300 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors font-semibold text-sm">
                    Clear Selection
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200/60 rounded-2xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left text-sm min-w-[640px]">
            <thead class="bg-gradient-to-r from-slate-50 to-slate-100 text-slate-600 uppercase tracking-wide text-xs font-semibold">
                <tr>
                    <th class="px-4 sm:px-6 py-4 w-12">
                        <input type="checkbox" id="select-all" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" onchange="toggleSelectAll(this)">
                    </th>
                    <th class="px-4 sm:px-6 py-4">Name</th>
                    <th class="px-4 sm:px-6 py-4 hidden sm:table-cell">Role</th>
                    <th class="px-4 sm:px-6 py-4 hidden md:table-cell">Contact</th>
                    <th class="px-4 sm:px-6 py-4">Status</th>
                    <th class="px-4 sm:px-6 py-4 hidden md:table-cell">Order</th>
                    <th class="px-4 sm:px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($teamMembers as $member)
                    <tr class="hover:bg-slate-50/50 transition-colors duration-150">
                        <td class="px-4 sm:px-6 py-5">
                            <input type="checkbox" name="selected_members[]" value="{{ $member->id }}" class="member-checkbox w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" onchange="updateBulkActions()">
                        </td>
                        <td class="px-4 sm:px-6 py-5">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-xl overflow-hidden bg-gradient-to-br from-blue-100 to-indigo-100 border-2 border-slate-200 flex-shrink-0 shadow-sm">
                                    @if ($member->photo_path)
                                        <img src="{{ asset('storage/'.$member->photo_path) }}" alt="{{ $member->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-sm font-bold text-blue-700">{{ strtoupper(substr($member->name, 0, 2)) }}</div>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 text-sm sm:text-base truncate">{{ $member->name }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Updated {{ $member->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-5 text-slate-700 text-sm hidden sm:table-cell font-medium">{{ $member->role ?? '—' }}</td>
                        <td class="px-4 sm:px-6 py-5 text-slate-600 text-sm hidden md:table-cell">
                            @if($member->email)
                                <div class="flex items-center gap-1.5 mb-1">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="truncate">{{ $member->email }}</p>
                                </div>
                            @endif
                            @if($member->phone)
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <p>{{ $member->phone }}</p>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-5">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold {{ $member->is_active ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                    {{ $member->is_active ? 'Visible on site' : 'Hidden on site' }}
                                </span>
                                @if($member->user_id)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $member->account_active === false ? 'bg-rose-100 text-rose-800' : 'bg-teal-100 text-teal-800' }}">
                                        {{ $member->account_active === false ? 'Portal login disabled' : 'Portal access' }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-5 text-slate-600 text-sm hidden md:table-cell font-medium">{{ $member->display_order }}</td>
                        <td class="px-4 sm:px-6 py-5 text-right">
                            <div class="flex items-center justify-end gap-2 sm:gap-3">
                                <form action="{{ route('admin.team-members.toggle-status', $member) }}" method="POST" class="inline-block toggle-status-form" data-name="{{ $member->name }}" data-active="{{ $member->is_active ? '1' : '0' }}">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold {{ $member->is_active ? 'text-amber-600 hover:bg-amber-50' : 'text-emerald-600 hover:bg-emerald-50' }} transition-colors" title="{{ $member->is_active ? 'Deactivate' : 'Activate' }}">
                                        @if($member->is_active)
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                            </svg>
                                            <span class="hidden sm:inline">Deactivate</span>
                                        @else
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span class="hidden sm:inline">Activate</span>
                                        @endif
                                    </button>
                                </form>
                                @if($member->email)
                                    @if(!$member->user_id)
                                        <form action="{{ route('admin.team-members.generate-login', $member) }}" method="POST" class="inline-block generate-login-form" data-name="{{ $member->name }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold text-teal-600 hover:bg-teal-50 transition-colors" title="Generate login for candidate portal">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                                <span class="hidden sm:inline">Generate login</span>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.team-members.regenerate-login', $member) }}" method="POST" class="inline-block regenerate-login-form" data-name="{{ $member->name }}">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold text-purple-600 hover:bg-purple-50 transition-colors" title="Regenerate/resend login credentials">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                <span class="hidden sm:inline">Regenerate login</span>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                <a href="{{ route('admin.team-members.show', $member) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold text-blue-600 hover:bg-blue-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <span class="hidden sm:inline">View</span>
                                </a>
                                <a href="{{ route('admin.team-members.edit', $member) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold text-emerald-600 hover:bg-emerald-50 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    <span class="hidden sm:inline">Edit</span>
                                </a>
                                <form action="{{ route('admin.team-members.destroy', $member) }}" method="POST" class="inline-block delete-form" data-id="{{ $member->id }}" data-name="{{ $member->name }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold text-red-600 hover:bg-red-50 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span class="hidden sm:inline">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <p class="text-slate-500 font-medium text-base mb-2">No team members yet</p>
                                <p class="text-slate-400 text-sm mb-4">Get started by adding your first team member</p>
                                <a href="{{ route('admin.team-members.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors font-semibold text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add First Member
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4 sm:mt-6">
        {{ $teamMembers->links() }}
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle status confirmation (activate / deactivate)
        document.querySelectorAll('.toggle-status-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formElement = this;
                const memberName = formElement.getAttribute('data-name') || 'this member';
                const isActive = formElement.getAttribute('data-active') === '1';
                const actionText = isActive ? 'deactivate' : 'activate';

                Swal.fire({
                    title: `Confirm ${actionText}`,
                    text: `Are you sure you want to ${actionText} "${memberName}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: isActive ? '#dc2626' : '#059669',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: `Yes, ${actionText}`,
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: isActive ? 'Deactivating...' : 'Activating...',
                            text: 'Please wait.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        formElement.submit();
                    }
                });
            });
        });

        // Generate login confirmation
        document.querySelectorAll('.generate-login-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formElement = this;
                const memberName = formElement.getAttribute('data-name') || 'this member';

                Swal.fire({
                    title: 'Generate login?',
                    text: `Generate portal login for "${memberName}"? They will be able to log in to the candidate dashboard (except aptitude test and self interview).`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#059669',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, generate',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Generating login...',
                            text: 'Please wait while we create their account.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        formElement.submit();
                    }
                });
            });
        });

        // Regenerate login confirmation
        document.querySelectorAll('.regenerate-login-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formElement = this;
                const memberName = formElement.getAttribute('data-name') || 'this member';

                Swal.fire({
                    title: 'Regenerate login?',
                    text: `Regenerate login credentials for "${memberName}"? A new password will be generated and displayed.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#8b5cf6',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, regenerate',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Regenerating...',
                            text: 'Please wait while we update their credentials.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        formElement.submit();
                    }
                });
            });
        });

        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formElement = this;
                const memberName = formElement.getAttribute('data-name') || 'this member';
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to remove "${memberName}". This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, remove it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Removing...',
                            text: 'Please wait while we remove the team member.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        formElement.submit();
                    }
                });
            });
        });

        // Bulk action form submission
        const bulkActionForm = document.getElementById('bulk-action-form');
        if (bulkActionForm) {
            bulkActionForm.addEventListener('submit', function(e) {
                const action = document.getElementById('bulk-action-select').value;
                const selectedIds = getSelectedIds();
                
                if (!action) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Action Selected',
                        text: 'Please select an action to perform.',
                    });
                    return false;
                }

                if (selectedIds.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Members Selected',
                        text: 'Please select at least one team member.',
                    });
                    return false;
                }

                // Always prevent default first
                e.preventDefault();

                // Function to submit the form with proper data
                const submitForm = () => {
                    // Ensure IDs are set in the form
                    const idsSet = setSelectedIds(selectedIds);
                    
                    if (!idsSet) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to prepare form data. Please refresh the page and try again.',
                        });
                        return;
                    }
                    
                    // Verify IDs are set
                    const hiddenInputs = document.querySelectorAll('#bulk-selected-ids-container input[type="hidden"][name="selected_members[]"]');
                    const inputValues = Array.from(hiddenInputs).map(input => input.value);
                    
                    console.log('=== BULK ACTION DEBUG ===');
                    console.log('Action:', action);
                    console.log('Selected IDs (from checkboxes):', selectedIds);
                    console.log('Hidden inputs count:', hiddenInputs.length);
                    console.log('Hidden input values:', inputValues);
                    console.log('Form action:', bulkActionForm.action);
                    console.log('CSRF token exists:', !!document.querySelector('input[name="_token"]'));
                    
                    // Verify all IDs are in the form
                    const missingIds = selectedIds.filter(id => !inputValues.includes(String(id)));
                    if (missingIds.length > 0) {
                        console.error('Missing IDs in form:', missingIds);
                        Swal.fire({
                            icon: 'error',
                            title: 'Data Error',
                            text: 'Some selected members could not be added to the form. Please try again.',
                        });
                        return;
                    }
                    
                    if (hiddenInputs.length === 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No members selected. Please select at least one team member.',
                        });
                        return;
                    }
                    
                    // Final verification: Create FormData to see what will be sent
                    const testFormData = new FormData(bulkActionForm);
                    const formSelectedMembers = testFormData.getAll('selected_members[]');
                    console.log('FormData will send:', {
                        action: testFormData.get('action'),
                        selected_members: formSelectedMembers,
                        _token: testFormData.get('_token') ? 'present' : 'missing'
                    });
                    
                    if (formSelectedMembers.length === 0) {
                        console.error('FormData shows no selected_members!');
                        Swal.fire({
                            icon: 'error',
                            title: 'Form Error',
                            text: 'Form data is incomplete. Please refresh the page and try again.',
                        });
                        return;
                    }

                    // Verify CSRF token exists
                    const csrfToken = document.querySelector('input[name="_token"]');
                    if (!csrfToken) {
                        console.error('CSRF token not found!');
                        Swal.fire({
                            icon: 'error',
                            title: 'Security Error',
                            text: 'CSRF token missing. Please refresh the page and try again.',
                        });
                        return;
                    }

                    // Show loading indicator
                    Swal.fire({
                        title: 'Processing...',
                        text: 'Please wait while we process your request.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Submit the form normally - this will handle redirects properly
                    try {
                        bulkActionForm.submit();
                    } catch (error) {
                        console.error('Form submission error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Submission Error',
                            text: 'An error occurred while submitting the form: ' + error.message,
                        });
                    }
                };

                // Handle different actions
                if (action === 'delete') {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete ${selectedIds.length} team member(s). This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, delete them!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitForm();
                        }
                    });
                } else if (action === 'generate_login' || action === 'regenerate_login') {
                    const actionText = action === 'generate_login' ? 'generate login credentials' : 'regenerate login credentials';
                    Swal.fire({
                        title: 'Confirm Action',
                        text: `You are about to ${actionText} for ${selectedIds.length} team member(s). New passwords will be displayed after completion.`,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#059669',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Yes, proceed!',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitForm();
                        }
                    });
                } else {
                    // For activate/deactivate, submit directly
                    submitForm();
                }

            });
        }
    });

    function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.member-checkbox');
        checkboxes.forEach(cb => cb.checked = checkbox.checked);
        updateBulkActions();
    }

    function getSelectedIds() {
        const checkboxes = document.querySelectorAll('.member-checkbox:checked');
        return Array.from(checkboxes).map(cb => parseInt(cb.value));
    }

    function updateBulkActions() {
        const selectedIds = getSelectedIds();
        const bulkContainer = document.getElementById('bulk-actions-container');
        const selectedCount = document.getElementById('selected-count');
        const bulkActionBtn = document.getElementById('bulk-action-btn');
        const bulkActionSelect = document.getElementById('bulk-action-select');

        if (selectedIds.length > 0) {
            bulkContainer.classList.remove('hidden');
            selectedCount.textContent = `${selectedIds.length} selected`;
            bulkActionBtn.disabled = !bulkActionSelect.value;
        } else {
            bulkContainer.classList.add('hidden');
        }
    }

    function clearSelection() {
        document.querySelectorAll('.member-checkbox').forEach(cb => cb.checked = false);
        document.getElementById('select-all').checked = false;
        updateBulkActions();
    }

    function setSelectedIds(ids) {
        const container = document.getElementById('bulk-selected-ids-container');
        if (!container) {
            console.error('bulk-selected-ids-container not found');
            return false;
        }
        
        // Clear existing inputs
        container.innerHTML = '';
        
        // Ensure IDs are integers
        const validIds = ids.filter(id => id && !isNaN(parseInt(id))).map(id => parseInt(id));
        
        if (validIds.length === 0) {
            console.error('No valid IDs to set');
            return false;
        }
        
        // Create hidden inputs for each ID
        validIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_members[]';
            input.value = id;
            input.setAttribute('data-member-id', id);
            container.appendChild(input);
        });
        
        // Force a reflow to ensure DOM is updated
        container.offsetHeight;
        
        // Verify inputs were added
        const addedInputs = container.querySelectorAll('input[type="hidden"][name="selected_members[]"]');
        if (addedInputs.length !== validIds.length) {
            console.error('Mismatch: Expected', validIds.length, 'inputs but found', addedInputs.length);
            return false;
        }
        
        // Debug: Log what was set
        console.log('✓ Set selected IDs:', validIds);
        console.log('✓ Container now has', addedInputs.length, 'inputs');
        
        return true;
    }

    // Enable/disable bulk action button based on select value
    document.getElementById('bulk-action-select').addEventListener('change', function() {
        document.getElementById('bulk-action-btn').disabled = !this.value || getSelectedIds().length === 0;
    });
</script>
@endpush







