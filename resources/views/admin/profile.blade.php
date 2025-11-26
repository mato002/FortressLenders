@extends('layouts.admin')

@section('title', 'My Profile')
@section('header-description', 'Manage your admin account details, password, and security.')

@section('header-actions')
    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Dashboard
    </a>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto space-y-8">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            @include('profile.partials.update-password-form')
        </div>

        <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
@endsection

