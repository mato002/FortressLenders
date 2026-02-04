<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile Submitted - Fortress Lenders Ltd</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-xl bg-white rounded-2xl shadow-xl border border-gray-200 p-8 md:p-12 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-teal-100 text-teal-600 mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-teal-800 mb-2">Thank You!</h1>
            <p class="text-gray-600 mb-6">Your profile has been submitted successfully.</p>
            @if (session('status'))
                <p class="text-gray-700 mb-6">{{ session('status') }}</p>
            @else
                <p class="text-gray-600 mb-6">Our team will review your profile and add you to the website soon.</p>
            @endif
        </div>
    </div>
</body>
</html>
