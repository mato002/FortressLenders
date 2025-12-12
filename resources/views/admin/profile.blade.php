@extends('layouts.admin')

@section('title', 'My Profile')
@section('header-description', 'Manage your admin account details, password, and security.')

@section('header-actions')
    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 border border-teal-200 rounded-xl text-xs sm:text-sm font-semibold text-teal-700 hover:bg-teal-50 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        <span class="hidden sm:inline">Back to Dashboard</span>
        <span class="sm:hidden">Back</span>
    </a>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto space-y-6 px-4 sm:px-6">
        <!-- Profile Information Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-teal-100 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-50 to-emerald-50 px-4 sm:px-6 py-4 border-b border-teal-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-teal-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg sm:text-xl font-bold text-teal-900">Profile Information</h2>
                        <p class="text-xs sm:text-sm text-teal-600">Update your account's profile information and email address.</p>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:p-6">
                @include('admin.profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-teal-100 overflow-hidden">
            <div class="bg-gradient-to-r from-teal-50 to-emerald-50 px-4 sm:px-6 py-4 border-b border-teal-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-teal-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg sm:text-xl font-bold text-teal-900">Update Password</h2>
                        <p class="text-xs sm:text-sm text-teal-600">Ensure your account is using a long, random password to stay secure.</p>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:p-6">
                @include('admin.profile.partials.update-password-form')
            </div>
        </div>

        <!-- Active Sessions Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-4 sm:px-6 py-4 border-b border-blue-100">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h2 class="text-lg sm:text-xl font-bold text-blue-900">Active Sessions</h2>
                            <p class="text-xs sm:text-sm text-blue-600">Manage your active sessions across different devices. Maximum 2 sessions allowed.</p>
                        </div>
                    </div>
                    @if($sessions->count() > 1)
                        <form method="POST" action="{{ route('profile.sessions.revoke-others') }}" class="inline-block">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 transition">
                                Revoke All Others
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="p-4 sm:p-6">
                @if (session('status'))
                    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($sessions->isEmpty())
                    <p class="text-sm text-gray-500 text-center py-4">No active sessions found.</p>
                @else
                    <div class="space-y-3">
                        @foreach($sessions as $session)
                            <div class="border border-gray-200 rounded-lg p-4 {{ $session->session_id === $currentSessionId ? 'bg-blue-50 border-blue-300' : 'bg-gray-50' }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-2xl">{{ $session->device_icon }}</span>
                                            <div>
                                                <p class="font-semibold text-gray-900 text-sm">
                                                    {{ $session->platform }} - {{ $session->browser }}
                                                    @if($session->session_id === $currentSessionId)
                                                        <span class="ml-2 px-2 py-0.5 text-xs font-semibold bg-blue-600 text-white rounded">Current Session</span>
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500 capitalize">{{ $session->device_type }}</p>
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-600 space-y-1">
                                            <p><strong>IP Address:</strong> {{ $session->ip_address ?? 'Unknown' }}</p>
                                            <p><strong>Last Activity:</strong> {{ $session->last_activity ? $session->last_activity->diffForHumans() : 'Never' }}</p>
                                            <p><strong>Logged In:</strong> {{ $session->created_at->format('M d, Y g:i A') }}</p>
                                        </div>
                                    </div>
                                    @if($session->session_id !== $currentSessionId)
                                        <form method="POST" action="{{ route('profile.sessions.revoke', $session->session_id) }}" class="ml-4">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-100 rounded-lg hover:bg-red-200 transition">
                                                Revoke
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Delete Account Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
            <div class="bg-gradient-to-r from-red-50 to-orange-50 px-4 sm:px-6 py-4 border-b border-red-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-red-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg sm:text-xl font-bold text-red-900">Delete Account</h2>
                        <p class="text-xs sm:text-sm text-red-600">Permanently delete your account and all associated data.</p>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:p-6">
                @include('admin.profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection

