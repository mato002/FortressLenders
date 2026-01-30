@extends('layouts.website')

@section('title', 'Two-Factor Authentication Settings')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Two-Factor Authentication</h1>

            @if($twoFactorEnabled)
                <!-- Enabled State -->
                <div class="space-y-6">
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <p class="font-semibold text-green-900">Two-Factor Authentication is Enabled</p>
                                <p class="text-sm text-green-800 mt-1">Your account is protected with an additional security layer.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('two-factor-auth.disable') }}" method="POST" class="mt-6">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Enter your password to disable 2FA:</label>
                            <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500" required>
                        </div>

                        <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold text-red-600 border border-red-200 hover:bg-red-50" onclick="return confirm('Are you sure you want to disable 2FA?')">
                            Disable Two-Factor Authentication
                        </button>
                    </form>
                </div>
            @else
                <!-- Disabled State -->
                <div class="space-y-6">
                    <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-900">
                            <strong>Recommendation:</strong> Enable two-factor authentication to add an extra layer of security to your account.
                        </p>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">How it works:</h2>
                        <ol class="space-y-2 text-sm text-gray-600 list-decimal list-inside">
                            <li>Download an authenticator app (Google Authenticator, Microsoft Authenticator, Authy, etc.)</li>
                            <li>Scan a QR code from your account settings</li>
                            <li>Enter the code from your app when logging in</li>
                            <li>Save backup codes in case you lose access to your authenticator</li>
                        </ol>
                    </div>

                    <a href="{{ route('two-factor-auth.setup') }}" class="inline-block px-6 py-2 rounded-lg text-sm font-semibold text-white bg-teal-800 hover:bg-teal-900">
                        Enable Two-Factor Authentication
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
