@extends('layouts.candidate')

@section('title', 'Profile Settings')
@section('header-description', 'Manage your account details and password')

@section('header-actions')
    <a href="{{ route('candidate.dashboard') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 border border-teal-200 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-teal-700 hover:bg-white whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        <span class="hidden sm:inline">Back to Dashboard</span>
        <span class="sm:hidden">Back</span>
    </a>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        @if (session('status'))
            <div class="rounded-xl border border-teal-200 bg-teal-50 px-4 py-3 text-teal-900 shadow-sm">
                {{ session('status') === 'profile-updated' ? 'Profile updated successfully!' : (session('status') === 'password-updated' ? 'Password updated successfully!' : session('status')) }}
            </div>
        @endif

        <!-- Profile Information Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Profile Information</h2>
                <p class="text-sm text-gray-600 mt-1">Update your account's profile information and email address.</p>
            </div>
            <div class="p-6">
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name</label>
                        <input 
                            id="name" 
                            name="name" 
                            type="text" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('name') border-red-500 @enderror" 
                            value="{{ old('name', $candidate->name ?? $user->name ?? '') }}" 
                            required 
                            autofocus 
                            autocomplete="name"
                        />
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('email') border-red-500 @enderror" 
                            value="{{ old('email', $candidate->email ?? $user->email ?? '') }}" 
                            required 
                            autocomplete="username"
                        />
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        @if (isset($candidate) && $candidate instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $candidate->hasVerifiedEmail())
                            <div class="mt-3">
                                <p class="text-sm text-gray-600">
                                    {{ __('Your email address is unverified.') }}

                                    <form method="post" action="{{ route('verification.send') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="underline text-sm text-teal-600 hover:text-teal-800">
                                            {{ __('Click here to re-send the verification email.') }}
                                        </button>
                                    </form>
                                </p>

                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 text-sm font-medium text-green-600">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                            Save Changes
                        </button>

                        @if (session('status') === 'profile-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-green-600 font-medium"
                            >Saved successfully!</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Update Password Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Update Password</h2>
                <p class="text-sm text-gray-600 mt-1">Ensure your account is using a long, random password to stay secure.</p>
            </div>
            <div class="p-6">
                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <label for="update_password_current_password" class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                        <input 
                            id="update_password_current_password" 
                            name="current_password" 
                            type="password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('current_password', 'updatePassword') border-red-500 @enderror" 
                            autocomplete="current-password"
                        />
                        @error('current_password', 'updatePassword')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                        <input 
                            id="update_password_password" 
                            name="password" 
                            type="password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('password', 'updatePassword') border-red-500 @enderror" 
                            autocomplete="new-password"
                        />
                        @error('password', 'updatePassword')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <input 
                            id="update_password_password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('password_confirmation', 'updatePassword') border-red-500 @enderror" 
                            autocomplete="new-password"
                        />
                        @error('password_confirmation', 'updatePassword')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                            Update Password
                        </button>

                        @if (session('status') === 'password-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-green-600 font-medium"
                            >Password updated successfully!</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Account Card -->
        <div class="bg-white rounded-2xl shadow-lg border border-red-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-red-100 bg-red-50">
                <h2 class="text-xl font-bold text-red-900">Delete Account</h2>
                <p class="text-sm text-red-700 mt-1">Once your account is deleted, all of its resources and data will be permanently deleted.</p>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-600 mb-4">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                </p>
                <button
                    type="button"
                    onclick="confirmDeleteCandidateAccount()"
                    class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold"
                >
                    Delete Account
                </button>
            </div>
        </div>
    </div>

    <form id="delete-account-form" method="post" action="{{ route('profile.destroy') }}" class="hidden">
        @csrf
        @method('delete')
        <input type="hidden" name="password" id="delete-password-input">
    </form>
@endsection

@push('scripts')
<script>
    function confirmDeleteCandidateAccount() {
        Swal.fire({
            title: 'Delete Account?',
            html: `
                <div class="text-left">
                    <p class="mb-4 text-gray-700">Once your account is deleted, all of its resources and data will be permanently deleted. This action cannot be undone.</p>
                    <p class="mb-3 text-sm font-medium text-gray-700">Please enter your password to confirm:</p>
                    <input 
                        id="swal-password" 
                        type="password" 
                        class="swal2-input" 
                        placeholder="Enter your password"
                        autocomplete="current-password"
                    >
                    <div id="swal-error" class="text-red-600 text-sm mt-2 hidden"></div>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete my account!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusConfirm: false,
            preConfirm: () => {
                const password = document.getElementById('swal-password').value;
                const errorDiv = document.getElementById('swal-error');
                
                if (!password) {
                    errorDiv.textContent = 'Password is required.';
                    errorDiv.classList.remove('hidden');
                    return false;
                }
                
                return password;
            },
            didOpen: () => {
                const passwordInput = document.getElementById('swal-password');
                if (passwordInput) {
                    passwordInput.focus();
                    passwordInput.addEventListener('input', () => {
                        const errorDiv = document.getElementById('swal-error');
                        if (errorDiv) {
                            errorDiv.classList.add('hidden');
                        }
                    });
                }
            }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                // Set the password in the hidden input
                document.getElementById('delete-password-input').value = result.value;
                
                // Show loading state
                Swal.fire({
                    title: 'Deleting Account...',
                    text: 'Please wait while we delete your account.',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                document.getElementById('delete-account-form').submit();
            }
        });
    }
</script>
@endpush

