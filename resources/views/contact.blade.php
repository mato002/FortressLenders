@extends('layouts.website')

@section('title', 'Contact Us - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section
        class="relative text-white py-12 sm:py-16 md:py-20 overflow-hidden"
        @if (!empty($contactSettings?->hero_image_path))
            style="background-image: linear-gradient(to bottom right, rgba(4, 120, 87, 0.9), rgba(6, 78, 59, 0.9)), url('{{ asset('storage/'.$contactSettings->hero_image_path) }}'); background-size: cover; background-position: center;"
        @else
            style="background-image: linear-gradient(to bottom right, #115e59, #0f766e, #134e4a);"
        @endif
    >
        <div class="relative w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4 px-4">Contact Us</h1>
            <p class="text-lg sm:text-xl text-teal-100 px-4">Get in touch with Fortress Lenders today</p>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white" id="contact">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-10 md:gap-12">
                <!-- Contact Form -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Send us a Message</h2>
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
                    <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="hidden">
                            <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                            <input type="text" id="company" name="company" tabindex="-1" autocomplete="off">
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all"
                                placeholder="Enter your full name">
                            @error('name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all"
                                placeholder="Enter your email">
                            @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all"
                                placeholder="Enter your phone number">
                            @error('phone')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                            <select id="subject" name="subject"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all">
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
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all"
                                placeholder="Enter your message">{{ old('message') }}</textarea>
                            @error('message')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" 
                            class="w-full px-6 py-4 bg-gradient-to-r from-teal-800 to-teal-700 text-white rounded-lg font-semibold hover:from-red-700 hover:to-orange-700 transition-all transform hover:scale-105 shadow-lg">
                            Send Message
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Contact Information</h2>
                    <div class="space-y-8">
                        <!-- Head Office -->
                        <div class="bg-gray-50 rounded-xl p-6">
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
                        <div class="bg-gray-50 rounded-xl p-6">
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
                                        <li><strong>Saturday:</strong> 9:00 AM - 1:00 PM</li>
                                        <li><strong>Sunday:</strong> Closed</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Links -->
                        <div class="bg-gray-50 rounded-xl p-6">
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
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Branch Locations Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-8 sm:mb-10 md:mb-12 text-center px-4">Our Branch Locations</h2>
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
                    <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all">
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
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-6 sm:mb-8 md:mb-12 text-center px-4">Find Us on the Map</h2>
            <div class="bg-gray-200 rounded-xl overflow-hidden shadow-lg h-64 sm:h-96 md:h-[500px]">
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
@endsection

