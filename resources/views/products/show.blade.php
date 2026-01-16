@extends('layouts.website')

@section('title', $product->title . ' - Products - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900 text-white py-12 sm:py-16 md:py-20">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-24">
            <div class="max-w-7xl mx-auto">
                <nav class="mb-6 text-sm">
                    <a href="{{ route('home') }}" class="text-teal-200 hover:text-white">Home</a>
                    <span class="mx-2 text-teal-300">/</span>
                    <a href="{{ route('products') }}" class="text-teal-200 hover:text-white">Products</a>
                    <span class="mx-2 text-teal-300">/</span>
                    <span class="text-white">{{ $product->title }}</span>
                </nav>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">{{ $product->title }}</h1>
                @if($product->summary)
                    <p class="text-lg sm:text-xl text-teal-100 max-w-3xl">{{ $product->summary }}</p>
                @endif
            </div>
        </div>
    </section>

    @php
        $colorMap = [
            'teal' => ['card' => 'from-teal-50 to-teal-100', 'accent' => 'from-teal-700 to-teal-800', 'text' => 'text-teal-700'],
            'blue' => ['card' => 'from-blue-50 to-indigo-50', 'accent' => 'from-blue-600 to-blue-700', 'text' => 'text-blue-700'],
            'green' => ['card' => 'from-green-50 to-emerald-50', 'accent' => 'from-green-500 to-green-600', 'text' => 'text-green-700'],
            'purple' => ['card' => 'from-purple-50 to-pink-50', 'accent' => 'from-purple-500 to-purple-600', 'text' => 'text-purple-700'],
            'yellow' => ['card' => 'from-yellow-50 to-orange-50', 'accent' => 'from-yellow-500 to-orange-500', 'text' => 'text-yellow-700'],
            'indigo' => ['card' => 'from-indigo-50 to-purple-50', 'accent' => 'from-indigo-500 to-indigo-600', 'text' => 'text-indigo-700'],
        ];
        $colors = $colorMap[$product->highlight_color] ?? $colorMap['teal'];
    @endphp

    <!-- Product Details Section -->
    <section class="py-12 sm:py-16 bg-gray-50">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Description -->
                        @if($product->description)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">About This Product</h2>
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>
                        @endif

                        <!-- Key Features Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Loan Amount -->
                            @if($product->min_loan_amount || $product->max_loan_amount)
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Loan Amount</h3>
                                    </div>
                                    <p class="text-3xl font-bold {{ $colors['text'] }} mb-2">
                                        @if($product->min_loan_amount && $product->max_loan_amount)
                                            KES {{ number_format($product->min_loan_amount) }} - {{ number_format($product->max_loan_amount) }}
                                        @elseif($product->min_loan_amount)
                                            From KES {{ number_format($product->min_loan_amount) }}
                                        @elseif($product->max_loan_amount)
                                            Up to KES {{ number_format($product->max_loan_amount) }}
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600">Available loan amount range</p>
                                </div>
                            @endif

                            <!-- Interest Rate -->
                            @if($product->interest_rate_min || $product->interest_rate_max)
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Interest Rate</h3>
                                    </div>
                                    <p class="text-3xl font-bold {{ $colors['text'] }} mb-2">
                                        @if($product->interest_rate_min && $product->interest_rate_max)
                                            {{ number_format($product->interest_rate_min, 2) }}% - {{ number_format($product->interest_rate_max, 2) }}%
                                        @elseif($product->interest_rate_min)
                                            From {{ number_format($product->interest_rate_min, 2) }}%
                                        @elseif($product->interest_rate_max)
                                            Up to {{ number_format($product->interest_rate_max, 2) }}%
                                        @endif
                                    </p>
                                    @if($product->interest_rate_type)
                                        <p class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $product->interest_rate_type)) }} basis</p>
                                    @endif
                                </div>
                            @endif

                            <!-- Repayment Period -->
                            @if($product->repayment_period_min || $product->repayment_period_max)
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Repayment Period</h3>
                                    </div>
                                    <p class="text-3xl font-bold {{ $colors['text'] }} mb-2">
                                        @if($product->repayment_period_min && $product->repayment_period_max)
                                            {{ $product->repayment_period_min }} - {{ $product->repayment_period_max }} Months
                                        @elseif($product->repayment_period_min)
                                            From {{ $product->repayment_period_min }} Months
                                        @elseif($product->repayment_period_max)
                                            Up to {{ $product->repayment_period_max }} Months
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-600">Flexible repayment terms</p>
                                </div>
                            @endif

                            <!-- Processing Time -->
                            @if($product->processing_time)
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900">Processing Time</h3>
                                    </div>
                                    <p class="text-2xl font-bold {{ $colors['text'] }} mb-2">{{ $product->processing_time }}</p>
                                    <p class="text-sm text-gray-600">Quick approval process</p>
                                </div>
                            @endif
                        </div>

                        <!-- How to Repay -->
                        @if($product->repayment_methods || $product->repayment_schedule_info)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                    <svg class="w-8 h-8 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    How to Repay
                                </h2>
                                @if($product->repayment_methods)
                                    <div class="mb-6">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Repayment Methods</h3>
                                        <div class="space-y-2">
                                            @foreach(explode("\n", $product->repayment_methods) as $method)
                                                @if(trim($method))
                                                    <div class="flex items-center gap-2 text-gray-700">
                                                        <svg class="w-5 h-5 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        <span>{{ trim($method) }}</span>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                @if($product->repayment_schedule_info)
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Repayment Schedule</h3>
                                        <p class="text-gray-700 leading-relaxed">{{ $product->repayment_schedule_info }}</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Eligibility Criteria -->
                        @if($product->eligibility_criteria)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Eligibility Criteria
                                </h2>
                                <div class="space-y-3">
                                    @foreach(explode("\n", $product->eligibility_criteria) as $criterion)
                                        @if(trim($criterion))
                                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                                                <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-gray-700">{{ trim($criterion) }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Required Documents -->
                        @if($product->required_documents)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Required Documents
                                </h2>
                                <div class="space-y-3">
                                    @foreach(explode("\n", $product->required_documents) as $document)
                                        @if(trim($document))
                                            <div class="flex items-start gap-3 p-3 bg-amber-50 rounded-lg border border-amber-100">
                                                <svg class="w-5 h-5 text-amber-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                                <span class="text-gray-700">{{ trim($document) }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Fees and Charges -->
                        @if($product->fees_and_charges)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Fees and Charges
                                </h2>
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($product->fees_and_charges)) !!}
                                </div>
                            </div>
                        @endif

                        <!-- Additional Information -->
                        @if($product->additional_info)
                            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sm:p-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">Additional Information</h2>
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($product->additional_info)) !!}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-6 space-y-6">
                            <!-- Apply Card -->
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Ready to Apply?</h3>
                                <p class="text-sm text-gray-600 mb-6">Get started with your loan application today. Our team is ready to assist you.</p>
                                <div class="space-y-3">
                                    <a href="{{ $product->cta_link ?? route('loan.apply') }}" class="block w-full px-6 py-4 bg-gradient-to-r {{ $colors['accent'] }} text-white rounded-xl hover:opacity-90 transition font-semibold text-center shadow-md hover:shadow-lg">
                                        {{ $product->cta_label ?? 'Apply Now' }}
                                    </a>
                                    <a href="{{ route('contact') }}" class="block w-full px-6 py-4 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition font-semibold text-center">
                                        Contact Us
                                    </a>
                                </div>
                            </div>

                            <!-- Quick Summary -->
                            <div class="bg-gradient-to-br {{ $colors['card'] }} rounded-2xl shadow-sm border border-gray-200 p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Summary</h3>
                                <div class="space-y-3 text-sm">
                                    @if($product->min_loan_amount || $product->max_loan_amount)
                                        <div>
                                            <span class="text-gray-600 font-medium">Amount:</span>
                                            <p class="text-gray-900 font-semibold">
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
                                    @if($product->interest_rate_min || $product->interest_rate_max)
                                        <div>
                                            <span class="text-gray-600 font-medium">Interest:</span>
                                            <p class="text-gray-900 font-semibold">
                                                @if($product->interest_rate_min && $product->interest_rate_max)
                                                    {{ number_format($product->interest_rate_min, 2) }}% - {{ number_format($product->interest_rate_max, 2) }}%
                                                @elseif($product->interest_rate_min)
                                                    From {{ number_format($product->interest_rate_min, 2) }}%
                                                @elseif($product->interest_rate_max)
                                                    Up to {{ number_format($product->interest_rate_max, 2) }}%
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                    @if($product->repayment_period_min || $product->repayment_period_max)
                                        <div>
                                            <span class="text-gray-600 font-medium">Repayment:</span>
                                            <p class="text-gray-900 font-semibold">
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
                                    @if($product->processing_time)
                                        <div>
                                            <span class="text-gray-600 font-medium">Processing:</span>
                                            <p class="text-gray-900 font-semibold">{{ $product->processing_time }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Back to Products -->
                            <a href="{{ route('products') }}" class="block w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium text-center">
                                ← Back to All Products
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($relatedProducts->count() > 0)
        <!-- Related Products Section -->
        <section class="py-12 sm:py-16 bg-white">
            <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
                <div class="max-w-7xl mx-auto">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8">Related Products</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $relatedProduct->title }}</h3>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                    {{ Str::limit(strip_tags($relatedProduct->description ?? $relatedProduct->summary), 100) }}
                                </p>
                                <a href="{{ route('products.show', $relatedProduct->slug) }}" class="text-teal-800 font-semibold hover:text-teal-900">
                                    View Details →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
