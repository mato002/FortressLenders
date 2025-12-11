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

