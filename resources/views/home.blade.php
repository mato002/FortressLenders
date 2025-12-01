@extends('layouts.website')

@section('title', 'Home - Fortress Lenders Ltd')

@section('content')
@php use Illuminate\Support\Str; @endphp
    <!-- Hero Section -->
    <section
        class="relative text-white overflow-hidden bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900"
        @if (!empty($homeSettings?->hero_image_path))
            style="background-image: linear-gradient(to bottom right, rgba(4, 120, 87, 0.85), rgba(6, 78, 59, 0.9)), url('{{ asset('storage/'.$homeSettings->hero_image_path) }}'); background-size: cover; background-position: center;"
        @endif
    >
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="absolute inset-0 hidden md:block">
            <div class="absolute top-0 left-0 w-72 h-72 bg-teal-500 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob"></div>
            <div class="absolute top-0 right-0 w-72 h-72 bg-amber-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-0 left-1/2 w-72 h-72 bg-teal-400 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>
        <div class="relative w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 py-16 sm:py-20 md:py-24 lg:py-32">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold mb-4 sm:mb-6 animate-fade-in-up leading-tight">
                    <span class="hero-word-animate-block">WELCOME TO</span><br>
                    <span class="text-amber-400 hero-word-animate-main">FORTRESS LENDERS LTD</span>
                </h1>
                <p class="text-lg sm:text-xl md:text-2xl mb-6 sm:mb-8 text-teal-100 animate-fade-in-up animation-delay-200 px-2">
                    The Force Of Possibilities!
                </p>
                <p class="text-base sm:text-lg md:text-xl mb-8 sm:mb-10 max-w-3xl mx-auto text-teal-50 animate-fade-in-up animation-delay-400 px-4">
                    Empowering communities through accessible financial solutions. We enable people to achieve their dreams through customer-centric microfinance and microcredit services.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center animate-fade-in-up animation-delay-600 px-4">
                    <a href="#apply-loan" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-white text-teal-800 rounded-lg font-semibold hover:bg-teal-50 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                        Apply for a Loan
                    </a>
                    <a href="{{ route('products') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-amber-500 text-white rounded-lg font-semibold hover:bg-amber-600 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                        View Products
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 sm:py-16 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 md:gap-8">
                <div class="text-center animate-fade-in">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-teal-800 mb-1 sm:mb-2">3000+</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-medium">Active Clients</div>
                </div>
                <div class="text-center animate-fade-in animation-delay-200">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-teal-800 mb-1 sm:mb-2">5</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-medium">Branch Locations</div>
                </div>
                <div class="text-center animate-fade-in animation-delay-400">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-teal-800 mb-1 sm:mb-2">2019</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-medium">Established</div>
                </div>
                <div class="text-center animate-fade-in animation-delay-600">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-teal-800 mb-1 sm:mb-2">100%</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-medium">Customer Focused</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Overview -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50" id="services">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="text-center mb-8 sm:mb-12 md:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4 px-4">Our Services</h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto px-4">
                    Comprehensive financial solutions designed to support individuals, groups, and micro-enterprises
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8 max-w-4xl mx-auto">
                <!-- Loans Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all transform hover:-translate-y-2 animate-fade-in-up">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center mb-4 sm:mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-3 sm:mb-4">Loans</h3>
                    <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">Individual, Group, Agricultural, Education, and Emergency loans tailored to your needs.</p>
                    <a href="{{ route('products') }}" class="text-teal-800 font-semibold hover:text-teal-700 inline-flex items-center">
                        Learn More
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                <!-- Financial Advisory Card -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition-all transform hover:-translate-y-2 animate-fade-in-up animation-delay-400">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Financial Advisory</h3>
                    <p class="text-gray-600 mb-6">Free financial literacy and business management programs to help you succeed.</p>
                    <a href="{{ route('about') }}" class="text-blue-900 font-semibold hover:text-blue-700 inline-flex items-center">
                        Learn More
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Preview Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white" id="about-preview">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 sm:gap-10 md:gap-12 items-center">
                <div class="animate-fade-in px-4 lg:px-0">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4 sm:mb-6">About Fortress Lenders</h2>
                    <p class="text-base sm:text-lg text-gray-600 mb-3 sm:mb-4">
                        Fortress Lenders Ltd is a registered credit-only institution in the Republic of Kenya, established in 2019. We are licensed and trade as a credit-only institution as stated in our company memorandum.
                    </p>
                    <p class="text-lg text-gray-600 mb-6">
                        Our mission is to provide a full range of financial and non-financial products aimed at improving lives of low-income rural and urban communities, deriving great economic impact with increased income levels and restoring customer dignity.
                    </p>
                    <a href="{{ route('about') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-teal-800 to-teal-700 text-white rounded-lg font-semibold hover:from-teal-700 hover:to-teal-600 transition-all transform hover:scale-105">
                        Learn More About Us
                    </a>
                </div>
                <div class="animate-fade-in animation-delay-200">
                <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-2xl p-6 sm:p-8 shadow-lg mx-4 lg:mx-0">
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Our Core Values</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-teal-800 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700"><strong>Integrity:</strong> We operate with honesty and transparency</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700"><strong>Excellence:</strong> We strive for the highest standards</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-blue-900 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700"><strong>Prudence:</strong> We manage resources wisely</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700"><strong>Commitment:</strong> We are dedicated to our clients</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700"><strong>Teamwork:</strong> We work together for success</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Team Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50" id="team">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="text-center mb-8 sm:mb-12 md:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4 px-4">Meet Our Team</h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto px-4">
                    The people behind Fortress Lenders who keep branches running and customers supported every day.
                </p>
            </div>
            @if(isset($teamMembers) && $teamMembers->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    @foreach ($teamMembers as $member)
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:-translate-y-1 transition">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 rounded-2xl overflow-hidden bg-gradient-to-br from-teal-600 to-teal-800 text-white flex items-center justify-center text-xl font-semibold">
                                    @if ($member->photo_path)
                                        <img src="{{ asset('storage/'.$member->photo_path) }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($member->name, 0, 2)) }}
                                    @endif
                                </div>
                                <div>
                                    <p class="text-lg font-semibold text-gray-900">{{ $member->name }}</p>
                                    <p class="text-sm text-teal-700">{{ $member->role }}</p>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">{{ Str::limit($member->bio, 160) }}</p>
                            <div class="space-y-1 text-sm text-gray-600">
                                @if ($member->email)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        <a href="mailto:{{ $member->email }}" class="hover:text-teal-700">{{ $member->email }}</a>
                                    </div>
                                @endif
                                @if ($member->phone)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2H6a3 3 0 01-3-3V5z"/></svg>
                                        <a href="tel:{{ preg_replace('/\s+/', '', $member->phone) }}" class="hover:text-teal-700">{{ $member->phone }}</a>
                                    </div>
                                @endif
                                @if ($member->linkedin_url)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-teal-700" fill="currentColor" viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><path d="M2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg>
                                        <a href="{{ $member->linkedin_url }}" target="_blank" rel="noopener" class="hover:text-teal-700">LinkedIn</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-500 bg-white border border-dashed border-gray-200 rounded-2xl py-12">
                    Team profiles are being updated. Check back soon.
                </div>
            @endif
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-br from-gray-900 to-gray-800 text-white" id="testimonials">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="text-center mb-8 sm:mb-12 md:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-3 sm:mb-4 px-4">What Our Clients Say</h2>
                <p class="text-base sm:text-lg text-gray-300 max-w-2xl mx-auto px-4">
                    Testimonials from our satisfied clients across Nakuru, Gilgil, Olkalou, Nyahururu, and Rumuruti
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <div class="bg-gray-800 rounded-xl p-6 sm:p-8 hover:bg-gray-700 transition-all animate-fade-in-up">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">JM</span>
                        </div>
                        <div>
                            <div class="font-semibold">Jane Muthoni</div>
                            <div class="text-sm text-gray-400">Nakuru Branch</div>
                        </div>
                    </div>
                    <p class="text-gray-300 italic">"Fortress Lenders helped me expand my small business. Their loan process was smooth, and the financial advisory services were invaluable. I'm grateful for their support!"</p>
                </div>

                <div class="bg-gray-800 rounded-xl p-8 hover:bg-gray-700 transition-all animate-fade-in-up animation-delay-200">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">PK</span>
                        </div>
                        <div>
                            <div class="font-semibold">Peter Kariuki</div>
                            <div class="text-sm text-gray-400">Gilgil Branch</div>
                        </div>
                    </div>
                    <p class="text-gray-300 italic">"The agricultural loan helped me expand my farming operations. Fortress Lenders made the process smooth, and their customer service is excellent. I'm grateful for their support!"</p>
                </div>

                <div class="bg-gray-800 rounded-xl p-8 hover:bg-gray-700 transition-all animate-fade-in-up animation-delay-400">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-500 rounded-full flex items-center justify-center mr-4">
                            <span class="text-white font-bold">AW</span>
                        </div>
                        <div>
                            <div class="font-semibold">Ann Wanjiru</div>
                            <div class="text-sm text-gray-400">Olkalou Branch</div>
                        </div>
                    </div>
                    <p class="text-gray-300 italic">"The agricultural loan transformed my farming operations. The flexible payment terms and excellent customer service make Fortress Lenders my preferred financial partner."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-r from-teal-800 to-teal-700 text-white" id="apply-loan">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 sm:mb-6 px-4">Ready to Get Started?</h2>
            <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 text-teal-100 max-w-2xl mx-auto px-4">
                Whether you need a loan for your business, education, or personal needs, we're here to help you achieve your goals.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4">
                <a href="{{ route('contact') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-white text-teal-800 rounded-lg font-semibold hover:bg-teal-50 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                    Contact Us Today
                </a>
                <a href="{{ route('products') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-amber-500 text-white rounded-lg font-semibold hover:bg-amber-600 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                    View Loan Products
                </a>
            </div>
        </div>
    </section>
@endsection

@push('styles')
<style>
    @keyframes blob {
        0%, 100% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }

    @keyframes fade-in-up {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-fade-in-up {
        animation: fade-in-up 0.8s ease-out forwards;
        opacity: 0;
    }
    
    .animation-delay-200 {
        animation-delay: 0.2s;
    }
    
    .animation-delay-400 {
        animation-delay: 0.4s;
    }
    
    .animation-delay-600 {
        animation-delay: 0.6s;
    }

    @keyframes fade-in {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    .animate-fade-in {
        animation: fade-in 1s ease-out forwards;
        opacity: 0;
    }

    @keyframes hero-word-pulse {
        0%, 100% {
            transform: translateY(0);
            letter-spacing: 0.05em;
        }
        50% {
            transform: translateY(-4px);
            letter-spacing: 0.18em;
        }
    }

    .hero-word-animate-main {
        display: inline-block;
        animation: hero-word-pulse 2.8s ease-in-out infinite;
    }

    .hero-word-animate-block {
        display: inline-block;
        animation: hero-word-pulse 3.2s ease-in-out infinite;
        animation-delay: 0.15s;
    }
</style>
@endpush

