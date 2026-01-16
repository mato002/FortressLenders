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

    <!-- Loan Calculator Section -->
    @if($products->whereNotNull('interest_rate_min')->count() > 0)
    <section class="py-12 sm:py-16 md:py-20 bg-gradient-to-br from-gray-50 to-gray-100" id="calculator">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-8">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">Loan Calculator</h2>
                    <p class="text-base sm:text-lg text-gray-600">
                        Calculate your monthly payments and total interest for any of our loan products
                    </p>
                </div>
                
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 sm:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Product Selector --}}
                        <div>
                            <label for="calculator-product-select" class="block text-sm font-semibold text-gray-700 mb-2">
                                Select Product
                            </label>
                            <select 
                                id="calculator-product-select" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-base font-medium"
                                onchange="updateCalculatorFromProduct()"
                            >
                                <option value="">Choose a product...</option>
                                @foreach($products->whereNotNull('interest_rate_min') as $prod)
                                    <option 
                                        value="{{ $prod->id }}" 
                                        data-min-amount="{{ $prod->min_loan_amount ?? 0 }}"
                                        data-max-amount="{{ $prod->max_loan_amount ?? 10000000 }}"
                                        data-min-rate="{{ $prod->interest_rate_min ?? 0 }}"
                                        data-max-rate="{{ $prod->interest_rate_max ?? 100 }}"
                                        data-rate-type="{{ $prod->interest_rate_type ?? 'per_year' }}"
                                        data-min-period="{{ $prod->repayment_period_min ?? 1 }}"
                                        data-max-period="{{ $prod->repayment_period_max ?? 120 }}"
                                    >
                                        {{ $prod->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Loan Amount --}}
                        <div>
                            <label for="calculator-loan-amount" class="block text-sm font-semibold text-gray-700 mb-2">
                                Loan Amount (KES)
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    id="calculator-loan-amount" 
                                    min="0"
                                    step="1000"
                                    value="100000"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-lg font-semibold"
                                >
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">KES</div>
                            </div>
                        </div>

                        {{-- Interest Rate --}}
                        <div>
                            <label for="calculator-interest-rate" class="block text-sm font-semibold text-gray-700 mb-2">
                                Interest Rate (%)
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    id="calculator-interest-rate" 
                                    min="0"
                                    max="100"
                                    step="0.1"
                                    value="10"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-lg font-semibold"
                                >
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">%</div>
                            </div>
                            <p id="calculator-rate-type" class="mt-1 text-xs text-gray-500"></p>
                        </div>

                        {{-- Repayment Period --}}
                        <div>
                            <label for="calculator-repayment-period" class="block text-sm font-semibold text-gray-700 mb-2">
                                Repayment Period (Months)
                            </label>
                            <div class="relative">
                                <input 
                                    type="number" 
                                    id="calculator-repayment-period" 
                                    min="1"
                                    max="120"
                                    step="1"
                                    value="12"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-lg font-semibold"
                                >
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">months</div>
                            </div>
                        </div>
                    </div>

                    <button 
                        type="button"
                        onclick="calculateMainLoan()"
                        class="w-full mt-6 bg-gradient-to-r from-teal-600 to-teal-700 text-white py-4 rounded-lg font-semibold text-lg hover:from-teal-700 hover:to-teal-800 transition-all shadow-md hover:shadow-lg"
                    >
                        Calculate Payment
                    </button>

                    {{-- Results --}}
                    <div id="main-calculator-results" class="mt-6 hidden">
                        <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg p-6 space-y-4">
                            <h4 class="text-lg font-bold text-gray-900 mb-4">Payment Summary</h4>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-xs text-gray-600 mb-1">Monthly Payment</p>
                                    <p id="main-monthly-payment" class="text-2xl font-bold text-teal-700">KES 0</p>
                                </div>
                                <div class="bg-white rounded-lg p-4">
                                    <p class="text-xs text-gray-600 mb-1">Total Interest</p>
                                    <p id="main-total-interest" class="text-2xl font-bold text-blue-700">KES 0</p>
                                </div>
                                <div class="bg-white rounded-lg p-4 col-span-2">
                                    <p class="text-xs text-gray-600 mb-1">Total Amount Payable</p>
                                    <p id="main-total-amount" class="text-2xl font-bold text-gray-900">KES 0</p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-teal-200">
                                <p class="text-xs text-gray-600 text-center">
                                    * Calculations are estimates. Actual rates may vary based on your credit profile and product terms.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

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

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                @forelse($products as $product)
                    @php($colors = $colorMap[$product->highlight_color] ?? $colorMap['teal'])
                    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-2xl transition-all transform hover:-translate-y-1">
                        {{-- Product Header --}}
                        <div class="bg-gradient-to-br {{ $colors['card'] }} p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-14 h-14 bg-gradient-to-br {{ $colors['accent'] }} rounded-lg flex items-center justify-center">
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
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $product->title }}</h3>
                            <p class="text-sm text-gray-700">
                                {{ Str::limit($product->summary ?? strip_tags($product->description), 120) }}
                            </p>
                        </div>

                        {{-- Loan Details --}}
                        <div class="p-6 space-y-4">
                            {{-- Loan Amount --}}
                            @if($product->min_loan_amount || $product->max_loan_amount)
                                <div class="border-b border-gray-100 pb-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Loan Amount</span>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900">
                                        @if($product->min_loan_amount && $product->max_loan_amount)
                                            KES {{ number_format($product->min_loan_amount) }} - {{ number_format($product->max_loan_amount) }}
                                        @elseif($product->min_loan_amount)
                                            From KES {{ number_format($product->min_loan_amount) }}
                                        @elseif($product->max_loan_amount)
                                            Up to KES {{ number_format($product->max_loan_amount) }}
                                        @endif
                                    </p>
                                </div>
                            @endif

                            {{-- Interest Rate --}}
                            @if($product->interest_rate_min || $product->interest_rate_max)
                                <div class="border-b border-gray-100 pb-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Interest Rate</span>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900">
                                        @if($product->interest_rate_min && $product->interest_rate_max)
                                            {{ number_format($product->interest_rate_min, 2) }}% - {{ number_format($product->interest_rate_max, 2) }}%
                                        @elseif($product->interest_rate_min)
                                            From {{ number_format($product->interest_rate_min, 2) }}%
                                        @elseif($product->interest_rate_max)
                                            Up to {{ number_format($product->interest_rate_max, 2) }}%
                                        @endif
                                        @if($product->interest_rate_type)
                                            <span class="text-sm font-normal text-gray-600">
                                                ({{ ucfirst(str_replace('_', ' ', $product->interest_rate_type)) }})
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            @endif

                            {{-- Repayment Period --}}
                            @if($product->repayment_period_min || $product->repayment_period_max)
                                <div class="border-b border-gray-100 pb-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Repayment Period</span>
                                    </div>
                                    <p class="text-lg font-bold text-gray-900">
                                        @if($product->repayment_period_min && $product->repayment_period_max)
                                            {{ $product->repayment_period_min }} - {{ $product->repayment_period_max }} Months
                                        @elseif($product->repayment_period_min)
                                            From {{ $product->repayment_period_min }} Months
                                        @elseif($product->repayment_period_max)
                                            Up to {{ $product->repayment_period_max }} Months
                                        @endif
                                    </p>
                                </div>
                            @endif

                            {{-- Processing Time --}}
                            @if($product->processing_time)
                                <div class="border-b border-gray-100 pb-3">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Processing Time</span>
                                    </div>
                                    <p class="text-base font-semibold text-gray-900">{{ $product->processing_time }}</p>
                                </div>
                            @endif

                            {{-- Quick Info List --}}
                            <div class="space-y-2 pt-2">
                                @if($product->repayment_methods)
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-teal-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <p class="text-sm text-gray-700">
                                            <span class="font-semibold">Repayment:</span> {{ Str::limit($product->repayment_methods, 60) }}
                                        </p>
                                    </div>
                                @endif
                                @if($product->eligibility_criteria)
                                    <div class="flex items-start gap-2">
                                        <svg class="w-4 h-4 text-teal-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="text-sm text-gray-700">
                                            <span class="font-semibold">Eligible:</span> {{ Str::limit($product->eligibility_criteria, 60) }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="px-6 pb-6 pt-0 space-y-3">
                            <a href="{{ route('products.show', $product->slug) ?? '#' }}" class="block w-full text-center px-4 py-3 bg-gradient-to-r {{ $colors['accent'] }} text-white rounded-lg text-sm font-semibold hover:opacity-90 transition shadow-md hover:shadow-lg">
                                View Full Details
                            </a>
                            <div class="flex gap-3">
                                <a href="{{ $product->cta_link ?? route('loan.apply') }}" class="flex-1 text-center px-4 py-2 bg-white border-2 border-teal-600 text-teal-700 rounded-lg text-sm font-semibold hover:bg-teal-50 transition">
                                    {{ $product->cta_label ?? 'Apply Now' }}
                                </a>
                                <a href="{{ route('contact') }}" class="flex-1 text-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">
                                    Contact Us
                                </a>
                            </div>
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
                <a href="{{ route('loan.apply') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-white text-teal-800 rounded-lg font-semibold hover:bg-teal-50 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                    Apply for a Loan
                </a>
                <a href="{{ route('contact') }}" class="w-full sm:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-gray-900 text-white rounded-lg font-semibold hover:bg-gray-800 transition-all transform hover:scale-105 shadow-lg text-sm sm:text-base">
                    Contact Us
                </a>
            </div>
        </div>
    </section>

    <script>
    function updateCalculatorFromProduct() {
        const select = document.getElementById('calculator-product-select');
        const option = select.options[select.selectedIndex];
        
        if (!option.value) return;
        
        const minAmount = parseFloat(option.getAttribute('data-min-amount')) || 0;
        const maxAmount = parseFloat(option.getAttribute('data-max-amount')) || 10000000;
        const minRate = parseFloat(option.getAttribute('data-min-rate')) || 0;
        const maxRate = parseFloat(option.getAttribute('data-max-rate')) || 100;
        const rateType = option.getAttribute('data-rate-type') || 'per_year';
        const minPeriod = parseInt(option.getAttribute('data-min-period')) || 1;
        const maxPeriod = parseInt(option.getAttribute('data-max-period')) || 120;
        
        // Update inputs
        document.getElementById('calculator-loan-amount').value = minAmount || 100000;
        document.getElementById('calculator-loan-amount').min = minAmount;
        document.getElementById('calculator-loan-amount').max = maxAmount;
        
        document.getElementById('calculator-interest-rate').value = minRate || 10;
        document.getElementById('calculator-interest-rate').min = minRate;
        document.getElementById('calculator-interest-rate').max = maxRate;
        
        document.getElementById('calculator-repayment-period').value = minPeriod || 12;
        document.getElementById('calculator-repayment-period').min = minPeriod;
        document.getElementById('calculator-repayment-period').max = maxPeriod;
        
        // Update rate type display
        const rateTypeText = rateType === 'flat' ? 'Flat Rate' : 
                            rateType === 'per_month' ? 'Monthly Rate' : 'Annual Rate';
        document.getElementById('calculator-rate-type').textContent = rateTypeText;
        
        // Store rate type for calculation
        document.getElementById('calculator-interest-rate').setAttribute('data-rate-type', rateType);
    }

    function calculateMainLoan() {
        const loanAmount = parseFloat(document.getElementById('calculator-loan-amount').value) || 0;
        const interestRate = parseFloat(document.getElementById('calculator-interest-rate').value) || 0;
        const repaymentPeriod = parseInt(document.getElementById('calculator-repayment-period').value) || 1;
        const interestRateType = document.getElementById('calculator-interest-rate').getAttribute('data-rate-type') || 'per_year';

        if (loanAmount <= 0 || repaymentPeriod <= 0) {
            alert('Please enter valid loan amount and repayment period.');
            return;
        }

        let monthlyPayment = 0;
        let totalInterest = 0;
        let totalAmount = 0;

        // Calculate based on interest rate type
        if (interestRateType === 'flat') {
            // Flat rate: Interest is calculated on the original principal for the entire loan period
            totalInterest = (loanAmount * interestRate / 100) * (repaymentPeriod / 12);
            totalAmount = loanAmount + totalInterest;
            monthlyPayment = totalAmount / repaymentPeriod;
        } else if (interestRateType === 'per_month') {
            // Monthly interest rate
            const monthlyRate = interestRate / 100;
            totalInterest = loanAmount * monthlyRate * repaymentPeriod;
            totalAmount = loanAmount + totalInterest;
            monthlyPayment = totalAmount / repaymentPeriod;
        } else {
            // Per year (annual) - using amortization formula
            const monthlyRate = (interestRate / 100) / 12;
            if (monthlyRate > 0) {
                monthlyPayment = loanAmount * (monthlyRate * Math.pow(1 + monthlyRate, repaymentPeriod)) / 
                                (Math.pow(1 + monthlyRate, repaymentPeriod) - 1);
                totalAmount = monthlyPayment * repaymentPeriod;
                totalInterest = totalAmount - loanAmount;
            } else {
                // No interest
                monthlyPayment = loanAmount / repaymentPeriod;
                totalAmount = loanAmount;
                totalInterest = 0;
            }
        }

        // Update results
        document.getElementById('main-monthly-payment').textContent = 
            'KES ' + monthlyPayment.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('main-total-interest').textContent = 
            'KES ' + totalInterest.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('main-total-amount').textContent = 
            'KES ' + totalAmount.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // Show results
        document.getElementById('main-calculator-results').classList.remove('hidden');
        
        // Scroll to results
        document.getElementById('main-calculator-results').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    </script>
@endsection

