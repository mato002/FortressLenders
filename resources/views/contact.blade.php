@extends('layouts.website')

@section('title', 'Contact Us - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section
        class="relative text-white py-12 sm:py-16 md:py-20 overflow-hidden"
        @if (!empty($contactSettings?->hero_image_path))
            style="background-image: linear-gradient(to bottom right, rgba(4, 120, 87, 0.9), rgba(6, 78, 59, 0.9)), url('{{ asset('storage/'.$contactSettings->hero_image_path) }}'); background-size: cover; background-position: center;"
        @else
            style="background-image: linear-gradient(to bottom right, rgba(4, 120, 87, 0.9), rgba(6, 78, 59, 0.9)), url('https://images.unsplash.com/photo-1423666639041-f56000c27a9a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80'); background-size: cover; background-position: center;"
        @endif
    >
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4">Contact Us</h1>
            <p class="text-lg sm:text-xl text-teal-100">Get in touch with Fortress Lenders today</p>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-b from-white via-gray-50 to-white" id="contact">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-10 md:gap-12">
                <!-- Contact Form -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8 md:p-10 transform transition-all hover:shadow-2xl">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-teal-700 to-teal-800 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900">Send us a Message</h2>
                    </div>
                    @if (session('status'))
                        <div class="mb-6 rounded-lg border border-teal-200 bg-teal-50 px-4 py-3 text-teal-900">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                            <p>Please review the highlighted fields and try again.</p>
                        </div>
                    @endif
                    <!-- Response Time Info -->
                    <div class="mb-6 p-4 bg-teal-50 border border-teal-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-teal-700 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-teal-900 mb-1">Quick Response Guaranteed</p>
                                <p class="text-xs text-teal-700">We typically respond within <strong>2 hours</strong> during business hours (Monday-Friday, 8 AM - 5 PM). Weekend inquiries will be addressed on the next business day.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="hidden">
                            <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                            <input type="text" id="company" name="company" tabindex="-1" autocomplete="off">
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 hover:border-teal-400"
                                placeholder="Enter your full name">
                            @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 hover:border-teal-400"
                                placeholder="Enter your email">
                            @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number (Kenya)</label>
                            <div class="flex rounded-lg shadow-sm border-2 border-gray-300 focus-within:ring-2 focus-within:ring-teal-500 focus-within:border-teal-500 transition-all duration-300 hover:border-teal-400">
                                <span class="inline-flex items-center px-3 bg-gray-50 border-r border-gray-300 text-sm text-gray-700 select-none">
                                    +254
                                </span>
                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    inputmode="numeric"
                                    maxlength="9"
                                    class="w-full px-3 py-3 border-0 rounded-r-lg focus:outline-none text-sm sm:text-base"
                                    placeholder="7XX XXX XXX"
                                >
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                Enter the <strong>9 digits</strong> after +254 (no leading 0), e.g. 712345678. Leave blank if you prefer email only.
                            </p>
                            @error('phone')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <select id="subject" name="subject"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 hover:border-teal-400">
                                <option value="">Select a subject</option>
                                <option value="loan" @selected(old('subject') === 'loan')>Loan Inquiry</option>
                                <option value="general" @selected(old('subject') === 'general')>General Inquiry</option>
                                <option value="complaint" @selected(old('subject') === 'complaint')>Complaint</option>
                                <option value="other" @selected(old('subject') === 'other')>Other</option>
                            </select>
                            @error('subject')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea id="message" name="message" rows="6" required
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300 hover:border-teal-400 resize-none"
                                placeholder="Enter your message">{{ old('message') }}</textarea>
                            @error('message')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <!-- Privacy Notice -->
                        <div class="text-xs text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <p class="mb-2"><strong>Privacy Notice:</strong> By submitting this form, you agree to our privacy policy. We will use your information solely to respond to your inquiry and will not share it with third parties. For more details, please review our <a href="{{ $generalSettings->privacy_policy_url ?? route('contact') }}" class="text-teal-700 hover:text-teal-800 underline" target="_blank" rel="noopener">Privacy Policy</a>.</p>
                        </div>

                        <button type="submit" 
                            class="w-full px-6 py-4 bg-gradient-to-r from-teal-800 to-teal-700 text-white rounded-lg font-semibold hover:from-teal-900 hover:to-teal-800 transition-all transform hover:scale-105 shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div class="space-y-6">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-teal-700 to-teal-800 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-900">Contact Information</h2>
                    </div>
                    <div class="space-y-6">
                        <!-- Head Office -->
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Head Office</h3>
                                    <p class="text-gray-600 mb-2">
                                        Fortress Lenders Hse, Nakuru County<br>
                                        Barnabas Muguga Opp. Epic ridge Academy
                                    </p>
                                    <p class="text-gray-600 mb-2">
                                        P.O BOX: 7214- 20110<br>
                                        Nakuru Town, KENYA
                                    </p>
                                    <div class="mt-4 space-y-2">
                                        <a href="tel:+254743838312" class="flex items-center text-gray-700 hover:text-teal-800 transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            +254 743 838 312
                                        </a>
                                        <a href="tel:+254722295194" class="flex items-center text-gray-700 hover:text-teal-800 transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            +254 722 295 194
                                        </a>
                                        <a href="mailto:info@fortresslenders.com" class="flex items-center text-gray-700 hover:text-teal-800 transition-colors">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            info@fortresslenders.com
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Working Hours -->
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-yellow-500 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Working Hours</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li><strong>Monday - Friday:</strong> 8:00 AM - 5:00 PM</li>
                                        <li><strong>Saturday:</strong> 9:00 AM - 12:00 PM</li>
                                        <li><strong>Sunday:</strong> Closed</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Quick Actions</h3>
                                    <div class="space-y-2">
                                        <a href="{{ route('loan.apply') }}" class="block text-teal-800 hover:text-teal-700 font-medium transition-colors">Apply for a Loan</a>
                                        <a href="{{ route('products') }}" class="block text-teal-800 hover:text-teal-700 font-medium transition-colors">View Our Products</a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Trust Signals -->
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-yellow-500 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-3">Why Trust Us</h3>
                                    <div class="space-y-3">
                                        <div class="flex items-center text-sm text-gray-700">
                                            <svg class="w-5 h-5 text-teal-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Licensed & Regulated Financial Institution</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-700">
                                            <svg class="w-5 h-5 text-teal-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Years of Trusted Service</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-700">
                                            <svg class="w-5 h-5 text-teal-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Secure & Confidential</span>
                                        </div>
                                        <div class="flex items-center text-sm text-gray-700">
                                            <svg class="w-5 h-5 text-teal-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <span>Customer-Focused Approach</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Links -->
                        @if($generalSettings && ($generalSettings->facebook_url || $generalSettings->twitter_url || $generalSettings->linkedin_url || $generalSettings->instagram_url || $generalSettings->youtube_url))
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all transform hover:-translate-y-1">
                            <div class="flex items-start">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-gray-900 mb-3">Follow Us</h3>
                                    <div class="flex flex-wrap gap-3">
                                        @if($generalSettings->facebook_url)
                                            <a href="{{ $generalSettings->facebook_url }}" target="_blank" rel="noopener noreferrer"
                                               aria-label="Visit our Facebook page"
                                               class="w-10 h-10 bg-blue-600 hover:bg-blue-700 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                </svg>
                                            </a>
                                        @endif
                                        @if($generalSettings->twitter_url)
                                            <a href="{{ $generalSettings->twitter_url }}" target="_blank" rel="noopener noreferrer"
                                               aria-label="Visit our Twitter page"
                                               class="w-10 h-10 bg-blue-400 hover:bg-blue-500 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                                </svg>
                                            </a>
                                        @endif
                                        @if($generalSettings->linkedin_url)
                                            <a href="{{ $generalSettings->linkedin_url }}" target="_blank" rel="noopener noreferrer"
                                               aria-label="Visit our LinkedIn page"
                                               class="w-10 h-10 bg-blue-700 hover:bg-blue-800 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                </svg>
                                            </a>
                                        @endif
                                        @if($generalSettings->instagram_url)
                                            <a href="{{ $generalSettings->instagram_url }}" target="_blank" rel="noopener noreferrer"
                                               aria-label="Visit our Instagram page"
                                               class="w-10 h-10 bg-gradient-to-r from-purple-600 via-pink-600 to-orange-500 hover:opacity-90 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                </svg>
                                            </a>
                                        @endif
                                        @if($generalSettings->youtube_url)
                                            <a href="{{ $generalSettings->youtube_url }}" target="_blank" rel="noopener noreferrer"
                                               aria-label="Visit our YouTube channel"
                                               class="w-10 h-10 bg-red-600 hover:bg-red-700 rounded-lg flex items-center justify-center transition-all duration-300 transform hover:scale-110 hover:shadow-lg">
                                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Branch Locations Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-b from-gray-50 via-white to-gray-50">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="text-center mb-8 sm:mb-10 md:mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-700 to-teal-800 rounded-2xl mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 px-4">Our Branch Locations</h2>
                <p class="text-gray-600 mt-2 px-4">Visit us at any of our convenient locations</p>
            </div>
            @php
                $colorClasses = [
                    'teal' => 'from-teal-700 to-teal-800',
                    'amber' => 'from-amber-500 to-yellow-500',
                    'green' => 'from-green-500 to-green-600',
                    'purple' => 'from-purple-500 to-purple-600',
                ];
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($branches as $branch)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-2xl transition-all transform hover:-translate-y-2 hover:scale-105">
                        <div class="w-12 h-12 bg-gradient-to-br {{ $colorClasses[$branch->accent_color] ?? $colorClasses['teal'] }} rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $branch->name }}</h3>
                        <p class="text-gray-600 mb-4">
                            {{ $branch->address_line1 }}<br>
                            @if($branch->address_line2)
                                {{ $branch->address_line2 }}<br>
                            @endif
                            @if($branch->city)
                                {{ $branch->city }}
                            @endif
                        </p>
                        <div class="space-y-1">
                            @if($branch->phone_primary)
                                <a href="tel:{{ preg_replace('/\s+/', '', $branch->phone_primary) }}" class="block text-teal-800 hover:text-teal-700 font-medium transition-colors">
                                    {{ $branch->phone_primary }}
                                </a>
                            @endif
                            @if($branch->phone_secondary)
                                <a href="tel:{{ preg_replace('/\s+/', '', $branch->phone_secondary) }}" class="block text-teal-800 hover:text-teal-700 font-medium transition-colors">
                                    {{ $branch->phone_secondary }}
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="sm:col-span-2 lg:col-span-3 bg-white border border-dashed border-gray-200 rounded-2xl p-8 text-center text-gray-500">
                        Branch information is coming soon.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Google Maps Section -->
    {{-- 
        TO UPDATE THE MAP LOCATION:
        1. Go to https://www.google.com/maps
        2. Search for your exact location: "Fortress Lenders, Barnabas Muguga Road, Nakuru, Kenya"
        3. Once you find the correct location, click "Share" button
        4. Click "Embed a map" tab
        5. Copy the iframe src URL and replace the src attribute below
        OR
        If you have coordinates (latitude, longitude), you can use:
        src="https://www.google.com/maps?q=LATITUDE,LONGITUDE&output=embed&zoom=15"
    --}}
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-b from-white to-gray-50">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="text-center mb-6 sm:mb-8 md:mb-12">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-teal-700 to-teal-800 rounded-2xl mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 px-4">Find Us on the Map</h2>
                <p class="text-gray-600 mt-2 px-4">Get directions to our head office</p>
            </div>
            <div class="bg-gray-200 rounded-2xl overflow-hidden shadow-2xl border-4 border-white h-64 sm:h-96 md:h-[500px]">
                @php
                    // Google Maps location: https://maps.app.goo.gl/pffuMDz24srLVizD9
                    $googleMapsLink = 'https://maps.app.goo.gl/pffuMDz24srLVizD9';
                    
                    // Accurate embed URL from Google Maps
                    $embedUrl = 'https://www.google.com/maps/embed?pb=!4v1764659724175!6m8!1m7!1stulhXHyj76WEwUCFqQt4Uw!2m2!1d-0.3192877795733329!2d36.15273649844357!3f178.91267444612296!4f-7.178498962649044!5f0.7820865974627469';
                @endphp
                <iframe 
                    src="{{ $embedUrl }}"
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    class="w-full h-full">
                </iframe>
            </div>
            <p class="text-center text-gray-600 mt-6">
                <strong>Head Office Location:</strong> Fortress Lenders Hse, Nakuru County - Barnabas Muguga Opp. Epic ridge Academy
            </p>
            <p class="text-center text-sm text-gray-500 mt-2">
                <a href="{{ $googleMapsLink }}" target="_blank" class="text-teal-700 hover:text-teal-800 underline">View on Google Maps / Get Directions</a>
            </p>
            <p class="text-center text-xs text-gray-400 mt-1">
                <em>If the map location is incorrect, please open the link above and click "Share" → "Embed a map" to get the correct embed code</em>
            </p>
        </div>
    </section>

    <!-- Scroll Animation Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Intersection Observer for scroll animations
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-in');
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);

            // Observe all sections and cards
            const sections = document.querySelectorAll('section');
            const cards = document.querySelectorAll('.bg-white.rounded-xl, .bg-white.rounded-2xl');
            
            sections.forEach(section => {
                section.style.opacity = '0';
                section.style.transform = 'translateY(30px)';
                section.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                observer.observe(section);
            });

            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                observer.observe(card);
            });

            // Stagger animation for branch cards
            const branchCards = document.querySelectorAll('.grid .bg-white.rounded-xl');
            branchCards.forEach((card, index) => {
                card.style.transitionDelay = `${index * 0.1}s`;
            });
        });
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Enhanced form focus states */
        input:focus, textarea:focus, select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.1);
        }

        /* Hover effects for contact info cards */
        .bg-white.rounded-xl:hover {
            border-color: rgba(20, 184, 166, 0.3);
        }
    </style>
@endsection

