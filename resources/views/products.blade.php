@extends('layouts.website')

@section('title', 'Products & Services - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900 text-white py-12 sm:py-16 md:py-20">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-24 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4 px-4">Our Products & Services</h1>
            <p class="text-lg sm:text-xl text-teal-100 px-4">Comprehensive financial solutions tailored to your needs</p>
        </div>
    </section>

@php use Illuminate\Support\Str; @endphp

    <!-- Loans Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white" id="loans">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="text-center mb-8 sm:mb-12 md:mb-16">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4 px-4">Loan Products</h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto px-4">
                    Our wide range of credit products are designed specifically to support people, groups and micro enterprises in the regions we serve.
                </p>
            </div>

            @php
                $colorMap = [
                    'teal' => ['card' => 'from-teal-50 to-teal-100', 'accent' => 'from-teal-700 to-teal-800'],
                    'blue' => ['card' => 'from-blue-50 to-indigo-50', 'accent' => 'from-blue-600 to-blue-700'],
                    'green' => ['card' => 'from-green-50 to-emerald-50', 'accent' => 'from-green-500 to-green-600'],
                    'purple' => ['card' => 'from-purple-50 to-pink-50', 'accent' => 'from-purple-500 to-purple-600'],
                    'yellow' => ['card' => 'from-yellow-50 to-orange-50', 'accent' => 'from-yellow-500 to-orange-500'],
                    'indigo' => ['card' => 'from-indigo-50 to-purple-50', 'accent' => 'from-indigo-500 to-indigo-600'],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-6 sm:gap-8 xl:gap-10">
                @forelse($products as $product)
                    @php($colors = $colorMap[$product->highlight_color] ?? $colorMap['teal'])
                    <div class="bg-gradient-to-br {{ $colors['card'] }} rounded-xl shadow-lg p-6 sm:p-8 hover:shadow-2xl transition-all transform hover:-translate-y-2">
                        <div class="flex items-center justify-between mb-4 sm:mb-6">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br {{ $colors['accent'] }} rounded-lg flex items-center justify-center">
                                @if($product->images->isNotEmpty())
                                    <img src="{{ asset('storage/'.$product->images->first()->path) }}" alt="{{ $product->title }}" class="w-full h-full object-cover rounded-lg">
                                @else
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @endif
                            </div>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-white/70 text-gray-700">{{ $product->category ?? 'Finance' }}</span>
                        </div>
                        <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">{{ $product->title }}</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">
                            {{ Str::limit($product->summary ?? strip_tags($product->description), 140) }}
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ $product->cta_link ?? route('contact') }}" class="inline-flex items-center px-4 py-2 bg-white text-gray-900 rounded-lg text-sm font-semibold shadow-sm hover:bg-gray-100 transition">
                                {{ $product->cta_label ?? 'Talk to Us' }}
                            </a>
                            <a href="{{ route('contact') }}" class="inline-flex items-center px-4 py-2 bg-transparent border border-white/60 text-gray-800 rounded-lg text-sm font-semibold hover:bg-white/90 hover:text-gray-900 transition">
                                Learn More
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-600 bg-white rounded-2xl shadow-sm py-10">
                        <p class="text-lg font-semibold mb-2">Products coming soon</p>
                        <p class="text-sm">Our team is updating this section. Check back shortly.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Additional Services Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 sm:p-8 md:p-12 text-center">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-4 sm:mb-6 px-4">Additional Services</h2>
                <p class="text-base sm:text-lg text-gray-600 mb-6 sm:mb-8 max-w-2xl mx-auto px-4">
                    In addition to providing funds, we offer free financial advisory services to equip entrepreneurs with proper business management skills and cash flow management.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 max-w-4xl mx-auto">
                    <div class="bg-white rounded-lg p-6">
                        <svg class="w-12 h-12 text-teal-800 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="font-bold text-gray-900 mb-2">Financial Literacy</h3>
                        <p class="text-sm text-gray-600">Educational programs to improve financial knowledge</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <svg class="w-12 h-12 text-amber-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <h3 class="font-bold text-gray-900 mb-2">Business Management</h3>
                        <p class="text-sm text-gray-600">Training in business operations and management</p>
                    </div>
                    <div class="bg-white rounded-lg p-6">
                        <svg class="w-12 h-12 text-yellow-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <h3 class="font-bold text-gray-900 mb-2">Cash Flow Management</h3>
                        <p class="text-sm text-gray-600">Strategies for effective cash flow planning</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-r from-teal-800 to-teal-700 text-white" id="apply-loan">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 sm:mb-6 px-4">Ready to Get Started?</h2>
            <p class="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 text-teal-100 max-w-2xl mx-auto px-4">
                Contact us today to learn more about our products and services, or visit any of our branches for personalized assistance.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center px-4">
                <a href="{{ route('contact') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-white text-teal-800 rounded-lg font-semibold hover:bg-teal-50 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                    Contact Us
                </a>
                <a href="{{ route('home') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-gray-900 text-white rounded-lg font-semibold hover:bg-gray-800 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                    Back to Home
                </a>
            </div>
        </div>
    </section>
@endsection

