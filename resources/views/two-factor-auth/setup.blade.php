@extends('layouts.website')

@section('title', 'Set Up Two-Factor Authentication')

@section('content')
    <div class="max-w-md mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Enable Two-Factor Authentication</h1>
            <p class="text-gray-600 mb-6">Add an extra layer of security to your account using Google Authenticator or a similar app.</p>

            <div class="space-y-6">
                <!-- QR Code Section -->
                <div class="p-6 bg-gray-50 rounded-lg text-center">
                    <p class="text-sm font-semibold text-gray-700 mb-4">1. Scan this QR code with your authenticator app:</p>
                    <div class="flex justify-center mb-4">
                        <div class="w-48 h-48 p-4 bg-white rounded-lg border-2 border-gray-200">
                            {!! svg($qrCode) !!}
                        </div>
                    </div>
                    <p class="text-xs text-gray-600">Can't scan? Enter this code manually:</p>
                    <p class="text-lg font-mono font-bold text-gray-900 mt-2 select-all">{{ $secret }}</p>
                </div>

                <!-- Verification Form -->
                <form action="{{ route('two-factor-auth.confirm') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">2. Enter the 6-digit code from your app:</label>
                        <input type="text" name="otp" maxlength="6" placeholder="000000" class="w-full px-4 py-2 border border-gray-300 rounded-lg text-center text-2xl tracking-widest font-mono focus:ring-2 focus:ring-teal-500" required>
                        @error('otp')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full px-4 py-2 rounded-lg text-sm font-semibold text-white bg-teal-800 hover:bg-teal-900">
                        Verify & Enable
                    </button>
                </form>

                <!-- Info Box -->
                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <p class="text-xs text-blue-900 font-semibold">Save your secret key somewhere safe. You'll need it to set up 2FA on new devices.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
