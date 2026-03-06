<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Team Onboarding - Fortress Lenders Ltd</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom select dropdown styling */
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        
        select:focus {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3E%3Cpath stroke='%230d9488' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-gray-50 via-teal-50/40 to-gray-100 text-gray-900 antialiased">
    <div class="min-h-screen relative flex flex-col items-center justify-center py-8 sm:py-12 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <!-- Decorative background orbs -->
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-32 -right-24 w-72 h-72 bg-teal-400/30 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -left-16 w-80 h-80 bg-amber-300/25 rounded-full blur-3xl"></div>
            <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-96 h-96 bg-teal-700/5 rounded-full blur-3xl"></div>
        </div>

        <div class="relative w-full max-w-2xl bg-white/95 backdrop-blur shadow-2xl border border-teal-50 rounded-3xl overflow-hidden">
            <!-- Header with Logo -->
            <div class="bg-gradient-to-r from-teal-700 via-teal-600 to-teal-700 px-6 sm:px-8 md:px-10 py-8 text-center">
                @if($logoPath)
                    <div class="mb-4 flex justify-center">
                        <div class="bg-white/95 rounded-2xl px-4 py-3 shadow-lg">
                            <img src="{{ asset('storage/'.$logoPath) }}" alt="{{ $companyName ?? 'Fortress Lenders Ltd' }}" class="h-12 sm:h-14 w-auto object-contain">
                        </div>
                    </div>
                @else
                    <div class="mb-4 flex justify-center">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-white/20 rounded-xl flex items-center justify-center shadow-lg backdrop-blur-sm">
                            <span class="text-amber-300 font-bold text-2xl sm:text-3xl">F</span>
                        </div>
                    </div>
                @endif
                <p class="text-white/90 text-xs sm:text-sm font-semibold tracking-wide uppercase">
                    {{ $companyName ?? 'Fortress Lenders Ltd' }}
                </p>
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-2">Join Our Team</h1>
                <p class="text-teal-100 text-sm sm:text-base">Add your profile to the Fortress Lenders team page</p>
            </div>

            <div class="p-6 sm:p-8 md:p-10">
                <p class="text-gray-600 text-sm sm:text-base mb-6 leading-relaxed">Fill in your details below. Your profile will be reviewed by our team before being added to the website.</p>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border-2 border-red-200 bg-red-50 px-5 py-4 text-red-700 shadow-sm">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1">
                            <p class="font-semibold mb-2">Please fix the following errors:</p>
                            <ul class="list-disc list-inside text-sm space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('team.onboarding.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Required Fields Section -->
                <div class="space-y-5">
                    <div class="pb-2 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Required Information</h2>
                        <p class="text-sm text-gray-500 mt-1">Fields marked with <span class="text-red-500">*</span> are required</p>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-gray-900 placeholder-gray-400"
                            placeholder="Your full name">
                        @error('name')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-gray-900 placeholder-gray-400"
                            placeholder="your.email@example.com">
                        @error('email')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                            Role / Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="role" name="role" value="{{ old('role') }}" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-gray-900 placeholder-gray-400"
                            placeholder="e.g. Branch Manager, Credit Officer">
                        @error('role')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        @php
                            $oldPhone = old('phone', '');
                            $defaultCountryCode = '+254';
                            $phoneNumber = $oldPhone;
                            
                            // Extract country code from old phone value if it exists
                            if (!empty($oldPhone)) {
                                foreach (['+254', '+255', '+256', '+250', '+257', '+211', '+251', '+253', '+291', '+252'] as $code) {
                                    if (strpos($oldPhone, $code) === 0) {
                                        $defaultCountryCode = $code;
                                        $phoneNumber = trim(substr($oldPhone, strlen($code)));
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <div class="flex gap-2">
                            <div class="w-40 flex-shrink-0">
                                <select id="country_code" name="country_code" required
                                    class="w-full px-3 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-gray-900 bg-white appearance-none cursor-pointer">
                                    <option value="+254" {{ old('country_code', $defaultCountryCode) === '+254' ? 'selected' : '' }}>🇰🇪 +254 (Kenya)</option>
                                    <option value="+255" {{ old('country_code', $defaultCountryCode) === '+255' ? 'selected' : '' }}>🇹🇿 +255 (Tanzania)</option>
                                    <option value="+256" {{ old('country_code', $defaultCountryCode) === '+256' ? 'selected' : '' }}>🇺🇬 +256 (Uganda)</option>
                                    <option value="+250" {{ old('country_code', $defaultCountryCode) === '+250' ? 'selected' : '' }}>🇷🇼 +250 (Rwanda)</option>
                                    <option value="+257" {{ old('country_code', $defaultCountryCode) === '+257' ? 'selected' : '' }}>🇧🇮 +257 (Burundi)</option>
                                    <option value="+211" {{ old('country_code', $defaultCountryCode) === '+211' ? 'selected' : '' }}>🇸🇸 +211 (South Sudan)</option>
                                    <option value="+251" {{ old('country_code', $defaultCountryCode) === '+251' ? 'selected' : '' }}>🇪🇹 +251 (Ethiopia)</option>
                                    <option value="+253" {{ old('country_code', $defaultCountryCode) === '+253' ? 'selected' : '' }}>🇩🇯 +253 (Djibouti)</option>
                                    <option value="+291" {{ old('country_code', $defaultCountryCode) === '+291' ? 'selected' : '' }}>🇪🇷 +291 (Eritrea)</option>
                                    <option value="+252" {{ old('country_code', $defaultCountryCode) === '+252' ? 'selected' : '' }}>🇸🇴 +252 (Somalia)</option>
                                </select>
                            </div>
                            <div class="flex-1">
                                <input type="tel" id="phone" name="phone" value="{{ $phoneNumber }}" required
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-gray-900 placeholder-gray-400"
                                    placeholder="7XX XXX XXX">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1.5">Select your country code and enter your phone number</p>
                        @error('phone')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                        @error('country_code')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bio" class="block text-sm font-semibold text-gray-700 mb-2">
                            Short Bio <span class="text-red-500">*</span>
                        </label>
                        <textarea id="bio" name="bio" rows="5" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all resize-none text-gray-900 placeholder-gray-400"
                            placeholder="A brief introduction about yourself, your experience, and what you bring to the team...">{{ old('bio') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1.5">This will appear on the team page</p>
                        @error('bio')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Optional Fields Section -->
                <div class="space-y-5 pt-4">
                    <div class="pb-2 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Additional Information</h2>
                        <p class="text-sm text-gray-500 mt-1">All fields below are optional</p>
                    </div>

                    <div>
                        <label for="linkedin_url" class="block text-sm font-semibold text-gray-700 mb-2">
                            LinkedIn Profile URL <span class="text-xs font-normal text-gray-500 ml-1">(Optional)</span>
                        </label>
                        <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all text-gray-900 placeholder-gray-400"
                            placeholder="https://linkedin.com/in/yourprofile">
                        <p class="text-xs text-gray-500 mt-1.5">Share your LinkedIn profile to help visitors connect with you</p>
                        @error('linkedin_url')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="photo" class="block text-sm font-semibold text-gray-700 mb-2">
                            Profile Photo <span class="text-xs font-normal text-gray-500 ml-1">(Optional)</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-200 border-dashed rounded-xl hover:border-teal-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-teal-600 hover:text-teal-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-teal-500">
                                        <span>Upload a file</span>
                                        <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/jpg" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG up to 4MB</p>
                            </div>
                        </div>
                        @error('photo')
                            <p class="text-sm text-red-600 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button type="submit"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-teal-600 to-teal-700 hover:from-teal-700 hover:to-teal-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                        Submit My Profile
                    </button>
                    <p class="text-xs text-center text-gray-500 mt-3">Your profile will be reviewed before being published</p>
                </div>
            </form>
            </div>
        </div>
    </div>
</body>
</html>
