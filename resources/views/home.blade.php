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

        <div class="relative w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 py-16 sm:py-20 md:py-24 lg:py-32 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold mb-4 sm:mb-6 animate-fade-in-up leading-tight">
                <span class="hero-word-animate-block">WELCOME TO</span><br>
                <span class="text-amber-400 hero-word-animate-main">FORTRESS LENDERS LTD</span>
            </h1>
            <p class="text-lg sm:text-xl md:text-2xl mb-6 sm:mb-8 text-teal-100 animate-fade-in-up animation-delay-200 px-2">
                The Force Of Possibilities!
            </p>
            <p class="text-base sm:text-lg md:text-xl mb-8 sm:mb-10 max-w-3xl mx-auto text-teal-50 animate-fade-in-up animation-delay-400 px-4">
                Empowering communities through accessible financial solutions.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center animate-fade-in-up animation-delay-600 px-4">
                <a href="{{ route('loan.apply') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-white text-teal-800 rounded-lg font-semibold hover:bg-teal-50 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                    Apply for a Loan
                </a>

                <a href="{{ route('products') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-amber-500 text-white rounded-lg font-semibold hover:bg-amber-600 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                    View Products
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 sm:py-16 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6 md:gap-8 text-center">
                <div class="animate-fade-in">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-teal-800">3000+</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-medium">Active Clients</div>
                </div>

                <div class="animate-fade-in animation-delay-200">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-teal-800">5</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-medium">Branch Locations</div>
                </div>

                <div class="animate-fade-in animation-delay-400">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-teal-800">2019</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-medium">Established</div>
                </div>

                <div class="animate-fade-in animation-delay-600">
                    <div class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-teal-800">100%</div>
                    <div class="text-xs sm:text-sm md:text-base text-gray-600 font-medium">Customer Focused</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50" id="services">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="text-center mb-12">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold">Our Services</h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-xl mx-auto">
                    Comprehensive financial solutions for individuals, groups, and SMEs.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- Loans -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-4">Loans</h3>
                    <p class="text-gray-600 mb-6">
                        Individual, Group, Agricultural, Education, and Emergency loans.
                    </p>
                    <a href="{{ route('products') }}" class="text-teal-800 font-semibold hover:text-teal-700 inline-flex items-center">
                        Learn More
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <!-- Advisory -->
                <div class="bg-white rounded-xl shadow-lg p-8 hover:shadow-2xl transition transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-4">Financial Advisory</h3>
                    <p class="text-gray-600 mb-6">Financial literacy and business management programs.</p>
                    <a href="{{ route('about') }}" class="text-teal-800 font-semibold hover:text-teal-700 inline-flex items-center">
                        Learn More
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- About Preview -->
    <section class="py-12 sm:py-16 md:py-20 bg-white" id="about-preview">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 grid grid-cols-1 lg:grid-cols-2 gap-12">
            <div>
                <h2 class="text-3xl font-bold mb-6">About Fortress Lenders</h2>

                <p class="text-gray-600 mb-4">
                    Fortress Lenders Ltd is a registered credit-only institution in Kenya, established in 2019.
                </p>

                <p class="text-gray-600 mb-6">
                    We provide financial and non-financial products that improve lives, especially in low-income communities.
                </p>

                <a href="{{ route('about') }}" class="px-6 py-3 bg-teal-800 text-white rounded-lg font-semibold hover:bg-teal-700 transition transform hover:scale-105">
                    Learn More About Us
                </a>
            </div>

            <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-2xl p-8 shadow-lg">
                <h3 class="text-2xl font-bold mb-6">Our Core Values</h3>

                <ul class="space-y-4">

                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-teal-800 mr-3 mt-1" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        <span><strong>Integrity:</strong> We operate with honesty and transparency.</span>
                    </li>

                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-blue-900 mr-3 mt-1" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        <span><strong>Excellence:</strong> We strive for the highest standards.</span>
                    </li>

                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-blue-900 mr-3 mt-1" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        <span><strong>Prudence:</strong> We manage resources wisely.</span>
                    </li>

                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-red-600 mr-3 mt-1" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        <span><strong>Commitment:</strong> We are dedicated to our clients.</span>
                    </li>

                    <li class="flex items-start">
                        <svg class="w-6 h-6 text-red-600 mr-3 mt-1" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                        </svg>
                        <span><strong>Teamwork:</strong> We work together for success.</span>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gray-50" id="team">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">

            <div class="text-center mb-14">
                <h2 class="text-3xl font-bold">Meet Our Team</h2>
                <p class="text-gray-600 max-w-xl mx-auto">
                    The people behind Fortress Lenders.
                </p>
            </div>

            @if(isset($teamMembers) && $teamMembers->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

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
                                    <p class="text-lg font-semibold text-gray-900">{{ ucwords(strtolower($member->name)) }}</p>
                                    <p class="text-sm text-teal-700 line-clamp-2">{{ $member->role }}</p>
                                </div>
                            </div>

                            <p class="text-sm text-gray-600 leading-relaxed mb-4">
                                @if($member->bio && strlen($member->bio) > 160)
                                    {{ Str::words($member->bio, 25, '...') }}
                                @else
                                    {{ $member->bio ?? '' }}
                                @endif
                            </p>

                            @if($member->bio && strlen(trim($member->bio)) > 160)
                                <button class="w-full mt-2 px-4 py-2 bg-teal-50 border border-teal-200 text-teal-700 hover:bg-teal-100 hover:border-teal-300 rounded-lg font-semibold transition">
                                    Read More
                                </button>
                            @endif

                        </div>
                    @endforeach

                </div>

            @endif

        </div>
    </section>

@endsection
