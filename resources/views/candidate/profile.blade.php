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
                        <div class="relative">
                            <input 
                                id="update_password_current_password" 
                                name="current_password" 
                                type="password" 
                                class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('current_password', 'updatePassword') border-red-500 @enderror" 
                                autocomplete="current-password"
                            />
                            <button 
                                type="button"
                                id="btn_toggle_current_password"
                                onclick="togglePasswordVisibility('update_password_current_password', 'toggle_current_password', 'btn_toggle_current_password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none transition-colors"
                                aria-label="Show password"
                            >
                                <svg id="toggle_current_password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('current_password', 'updatePassword')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password" class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                        <div class="relative">
                            <input 
                                id="update_password_password" 
                                name="password" 
                                type="password" 
                                class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('password', 'updatePassword') border-red-500 @enderror" 
                                autocomplete="new-password"
                            />
                            <button 
                                type="button"
                                id="btn_toggle_new_password"
                                onclick="togglePasswordVisibility('update_password_password', 'toggle_new_password', 'btn_toggle_new_password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none transition-colors"
                                aria-label="Show password"
                            >
                                <svg id="toggle_new_password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password', 'updatePassword')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="update_password_password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                        <div class="relative">
                            <input 
                                id="update_password_password_confirmation" 
                                name="password_confirmation" 
                                type="password" 
                                class="w-full px-4 py-2 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent @error('password_confirmation', 'updatePassword') border-red-500 @enderror" 
                                autocomplete="new-password"
                            />
                            <button 
                                type="button"
                                id="btn_toggle_confirm_password"
                                onclick="togglePasswordVisibility('update_password_password_confirmation', 'toggle_confirm_password', 'btn_toggle_confirm_password')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none transition-colors"
                                aria-label="Show password"
                            >
                                <svg id="toggle_confirm_password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
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
    function togglePasswordVisibility(inputId, iconId, buttonId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        const button = document.getElementById(buttonId);
        
        if (input.type === 'password') {
            input.type = 'text';
            // Show eye-slash icon (password is visible, so show "hide" icon)
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
            `;
            if (button) button.setAttribute('aria-label', 'Hide password');
        } else {
            input.type = 'password';
            // Show eye icon (password is hidden, so show "show" icon)
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            `;
            if (button) button.setAttribute('aria-label', 'Show password');
        }
    }

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

