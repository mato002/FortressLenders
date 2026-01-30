@extends('layouts.website')

@section('title', 'Apply for a Loan - Fortress Lenders Ltd')
@section('meta_description', 'Apply for a flexible loan with Fortress Lenders Ltd. Share a few details and our team will review your application and get back to you quickly.')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900 text-white py-10 sm:py-14 md:py-16 lg:py-20">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-24 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4 px-4">Apply for a Loan</h1>
            <p class="text-base sm:text-lg md:text-xl text-teal-100 px-4 max-w-2xl mx-auto">
                Share a few details and our team will review your application and get back to you.
            </p>
        </div>
    </section>

    <!-- How It Works & Eligibility (mobile-first cards) -->
    <section class="bg-white py-8 sm:py-10 md:py-12">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16 max-w-7xl mx-auto space-y-8 sm:space-y-10">
            <!-- How It Works -->
            <div>
                <h2 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 mb-4 sm:mb-5 text-center">
                    How the Loan Process Works
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
                    <div class="bg-gray-50 rounded-xl p-4 sm:p-5 flex items-start gap-3 shadow-sm">
                        <div class="flex-shrink-0 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-teal-800 text-white flex items-center justify-center text-sm sm:text-base font-bold">
                            1
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-1">Submit your details</h3>
                            <p class="text-xs sm:text-sm text-gray-600">
                                Fill in the short form below with your contact information and basic loan request.
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 sm:p-5 flex items-start gap-3 shadow-sm">
                        <div class="flex-shrink-0 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-amber-500 text-white flex items-center justify-center text-sm sm:text-base font-bold">
                            2
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-1">We review & contact you</h3>
                            <p class="text-xs sm:text-sm text-gray-600">
                                Our credit team reviews your request and may call you for clarification or supporting documents.
                            </p>
                        </div>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4 sm:p-5 flex items-start gap-3 shadow-sm">
                        <div class="flex-shrink-0 w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-emerald-600 text-white flex items-center justify-center text-sm sm:text-base font-bold">
                            3
                        </div>
                        <div>
                            <h3 class="text-sm sm:text-base font-semibold text-gray-900 mb-1">Approval & disbursement</h3>
                            <p class="text-xs sm:text-sm text-gray-600">
                                Once approved, funds are disbursed according to your preferred option and agreed repayment plan.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Eligibility & Calculator wrapper -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-stretch">
                <!-- Eligibility -->
                <div class="bg-gray-50 rounded-2xl border border-gray-100 p-5 sm:p-6 shadow-sm">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">
                        Who Can Apply?
                    </h3>
                    <ul class="space-y-2 text-sm sm:text-base text-gray-700">
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 text-teal-700">✔</span>
                            <span>Kenyan residents aged 18 years and above.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 text-teal-700">✔</span>
                            <span>Individuals, employees, business owners, groups, and farmers.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-0.5 text-teal-700">✔</span>
                            <span>Have a valid phone number we can reach you on.</span>
                        </li>
                    </ul>
                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mt-4 mb-2">
                        Commonly requested documents
                    </h4>
                    <ul class="space-y-1 text-xs sm:text-sm text-gray-700">
                        <li>- National ID copy</li>
                        <li>- Recent payslip or business records (for higher limits)</li>
                        <li>- Guarantor or group details for group loans</li>
                    </ul>
                    <p class="text-xs sm:text-sm text-gray-500 mt-3">
                        Our team will confirm exactly what is needed after you submit this form.
                    </p>
                </div>

                <!-- Loan Calculator - Takes 2 columns on large screens -->
                <div class="lg:col-span-2 bg-white rounded-2xl border border-teal-100 p-5 sm:p-6 shadow-sm">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">
                        Loan Calculator
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-600 mb-4">
                        Select the loan amount range you need, then enter your specific amount and duration.
                    </p>
                    <div class="grid md:grid-cols-[minmax(0,1.6fr)_minmax(0,1.1fr)] gap-4 md:gap-6 items-start">
                        <!-- Calculator + WhatsApp (left) -->
                        <div class="space-y-4">
                            <div>
                                <label for="calculator_range" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                    Loan Amount Range <span class="text-red-500">*</span>
                                </label>
                                <select
                                    id="calculator_range"
                                    class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent text-sm"
                                    onchange="updateCalculatorFields()"
                                >
                                    <option value="">-- Select loan amount range --</option>
                                    @foreach($loanRanges as $range)
                                        @php
                                            // Use the first product in the range as default (or you could pick the most common one)
                                            $defaultProduct = $range['products'][0];
                                        @endphp
                                        <option 
                                            value="{{ $defaultProduct->id }}"
                                            data-min-amount="{{ $defaultProduct->min_loan_amount }}"
                                            data-max-amount="{{ $defaultProduct->max_loan_amount }}"
                                            data-service-charge-type="{{ $defaultProduct->service_charge_type }}"
                                            data-service-charge-value="{{ $defaultProduct->service_charge_value }}"
                                            data-service-charge-period="{{ $defaultProduct->service_charge_period }}"
                                            data-max-duration="{{ $defaultProduct->max_duration_weeks }}"
                                            data-payment-frequency="{{ $defaultProduct->payment_frequency }}"
                                            data-target-clients="{{ $defaultProduct->target_clients }}"
                                        >
                                            KES {{ number_format($defaultProduct->min_loan_amount) }} - {{ number_format($defaultProduct->max_loan_amount) }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Select the range that includes the amount you want to borrow</p>
                            </div>

                            <div id="calculator-fields" class="hidden space-y-4">
                                <div>
                                    <label for="calculator_amount" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                        Enter Your Loan Amount (KES) <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        id="calculator_amount"
                                        min="0"
                                        step="1000"
                                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent text-sm"
                                        placeholder="Enter amount within the selected range"
                                    >
                                    <p id="calculator-amount-range" class="text-xs text-gray-500 mt-1"></p>
                                </div>

                                <div>
                                    <label for="calculator_duration" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                        <span id="duration-label">Repayment Duration (Weeks)</span> <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="number"
                                        id="calculator_duration"
                                        min="1"
                                        step="1"
                                        class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent text-sm"
                                        placeholder="Enter duration"
                                    >
                                    <p id="calculator-duration-max" class="text-xs text-gray-500 mt-1"></p>
                                </div>

                                <div id="calculator-service-charge-info" class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs sm:text-sm">
                                    <p class="font-semibold text-blue-900 mb-1">Service Charge:</p>
                                    <p id="service-charge-display" class="text-blue-800"></p>
                                    <p id="payment-frequency-display" class="text-blue-700 mt-1"></p>
                                </div>

                                <button
                                    type="button"
                                    onclick="calculateLoanPayment()"
                                    class="w-full px-4 py-3 bg-gradient-to-r from-teal-800 to-teal-700 text-white rounded-lg font-semibold hover:from-teal-900 hover:to-teal-800 transition-all shadow-md hover:shadow-lg text-sm sm:text-base"
                                >
                                    Calculate Payment
                                </button>

                                <div id="calculator-results" class="hidden bg-teal-50 border border-teal-200 rounded-xl p-4 space-y-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="bg-white rounded-lg p-3">
                                            <p class="text-xs text-gray-600 mb-1" id="payment-label">Payment</p>
                                            <p id="payment-amount" class="text-lg sm:text-xl font-bold text-teal-800">KES 0</p>
                                        </div>
                                        <div class="bg-white rounded-lg p-3">
                                            <p class="text-xs text-gray-600 mb-1">Total Service Charges</p>
                                            <p class="text-[10px] text-gray-500 mb-0.5">(Fees over entire loan period)</p>
                                            <p id="total-service-charge" class="text-lg sm:text-xl font-bold text-blue-700">KES 0</p>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg p-3">
                                        <p class="text-xs text-gray-600 mb-1">Total Amount Payable</p>
                                        <p id="total-amount" class="text-xl sm:text-2xl font-bold text-gray-900">KES 0</p>
                                    </div>
                                    <p class="text-[11px] sm:text-xs text-gray-500 text-center mb-3">
                                        * Calculations are estimates. Actual terms will be discussed with you by our team.
                                    </p>
                                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-between">
                                        <button
                                            type="button"
                                            id="proceed-to-application-btn"
                                            class="w-full sm:w-auto px-4 py-2.5 bg-teal-800 text-white rounded-lg text-xs sm:text-sm font-semibold hover:bg-teal-900 transition-colors"
                                        >
                                            Proceed to Application Form
                                        </button>
                                    </div>
                                </div>

                                <!-- WhatsApp Lead Capture - shown only after calculation -->
                                <div id="whatsapp-lead-section" class="hidden mt-4 bg-white border border-teal-200 rounded-xl p-4 sm:p-5 shadow-sm">
                                    <h4 class="text-sm sm:text-base font-semibold text-gray-900 mb-2">
                                        Want this loan breakdown sent to your WhatsApp?
                                    </h4>
                                    <p class="text-xs sm:text-sm text-gray-600 mb-3">
                                        We can send you the loan amount, duration, service charge, and total repayment so you can review it later.
                                    </p>
                                    <div class="grid grid-cols-1 sm:grid-cols-[2fr,1.2fr] gap-3 sm:gap-4 items-center">
                                        <div>
                                            <label for="whatsapp_number" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                                WhatsApp Number
                                            </label>
                                            <div class="flex rounded-lg shadow-sm border border-gray-300 focus-within:ring-2 focus-within:ring-teal-800 focus-within:border-transparent transition-all">
                                                <span class="inline-flex items-center px-3 bg-gray-50 border-r border-gray-300 text-xs sm:text-sm text-gray-700 select-none">
                                                    +254
                                                </span>
                                                <input
                                                    type="tel"
                                                    id="whatsapp_number"
                                                    inputmode="numeric"
                                                    pattern="\d{9}"
                                                    maxlength="9"
                                                    class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border-0 rounded-r-lg focus:outline-none text-sm"
                                                    placeholder="7XX XXX XXX"
                                                >
                                            </div>
                                            <p class="mt-1 text-[11px] sm:text-xs text-gray-500">
                                                Kenyan WhatsApp numbers only. Enter the <strong>9 digits</strong> after +254 (no leading 0).
                                            </p>
                                        </div>
                                        <div class="flex sm:block">
                                            <button
                                                type="button"
                                                id="whatsapp-send-btn"
                                                class="w-full mt-4 sm:mt-6 px-4 py-2.5 bg-emerald-600 text-white rounded-lg font-semibold text-sm shadow-md hover:bg-emerald-700 hover:shadow-lg transition-colors flex items-center justify-center gap-2"
                                            >
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 32 32" aria-hidden="true">
                                                    <path d="M16 3C9.373 3 4 8.373 4 15c0 2.297.652 4.438 1.787 6.254L4 29l8.004-1.758C13.754 27.74 14.85 28 16 28c6.627 0 12-5.373 12-12S22.627 3 16 3zm0 22c-1.02 0-2.016-.203-2.957-.6l-.212-.09-4.754 1.044 1.015-4.64-.104-.239A8.96 8.96 0 0 1 7 15c0-4.962 4.038-9 9-9s9 4.038 9 9-4.038 9-9 9zm5.004-6.76c-.273-.136-1.62-.8-1.872-.89-.252-.094-.435-.136-.619.136-.185.273-.71.89-.87 1.073-.16.184-.321.205-.594.068-.273-.136-1.154-.424-2.197-1.352-.812-.723-1.36-1.616-1.52-1.89-.16-.273-.017-.42.12-.557.124-.123.273-.319.41-.478.137-.159.183-.273.273-.456.09-.182.046-.34-.023-.478-.068-.136-.619-1.49-.848-2.04-.223-.536-.45-.464-.619-.473l-.528-.009c-.183 0-.48.068-.731.34-.252.273-.96.938-.96 2.287s.983 2.652 1.12 2.836c.137.182 1.935 2.956 4.69 4.143.655.283 1.166.452 1.563.579.657.209 1.255.18 1.728.109.527-.079 1.62-.662 1.85-1.301.228-.64.228-1.188.159-1.301-.068-.114-.25-.182-.523-.318z"/>
                                                </svg>
                                                <span>Send My Loan Details on WhatsApp</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product & Fees Note (right on desktop) -->
                        <aside class="hidden md:block">
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 sm:p-5 text-xs sm:text-sm text-gray-800">
                                <h4 class="font-semibold text-amber-900 mb-2">Note</h4>
                                <ul class="list-disc pl-4 space-y-1">
                                    <li>
                                        The graduation levels are from one loan amount range to the next. New clients
                                        typically start with loans between
                                        <span class="font-semibold">Ksh. 3,000 – 10,000</span> before progressing to
                                        higher limits.
                                    </li>
                                    <li>
                                        Registration fee for all new clients is
                                        <span class="font-semibold">Ksh. 200</span>.
                                        Loan processing fees are
                                        <span class="font-semibold">Ksh. 450</span> for loans up to
                                        <span class="font-semibold">Ksh. 15,000</span> and
                                        <span class="font-semibold">4% of the loan amount</span> for amounts above
                                        <span class="font-semibold">Ksh. 15,000</span>.
                                    </li>
                                    <li>
                                        All loans include a risk insurance fee of
                                        <span class="font-semibold">Ksh. 50</span>.
                                    </li>
                                    <li>
                                        <span class="font-semibold">Dhahabu</span> loan processing fees are
                                        <span class="font-semibold">5%</span> of the loan amount and the insurance fee
                                        is <span class="font-semibold">Ksh. 500</span>.
                                    </li>
                                    <li>
                                        <span class="font-semibold">Kilimo Product</span>
                                        <ul class="list-disc pl-5 mt-1 space-y-1">
                                            <li>Registration fee: <span class="font-semibold">Ksh. 300</span></li>
                                            <li>Insurance: <span class="font-semibold">Ksh. 100</span></li>
                                            <li>
                                                Processing fees:
                                                <ul class="list-disc pl-5 mt-1 space-y-0.5">
                                                    <li>5,000 – 15,000: <span class="font-semibold">Ksh. 700</span></li>
                                                    <li>16,000 – 20,000: <span class="font-semibold">Ksh. 1,000</span></li>
                                                    <li>21,000 – 30,000: <span class="font-semibold">Ksh. 1,500</span></li>
                                                    <li>31,000 – 50,000: <span class="font-semibold">Ksh. 1,900</span></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </aside>
                    </div>

                    <!-- Mobile-only collapsible note -->
                    <div class="mt-4 md:hidden">
                        <details class="bg-amber-50 border border-amber-200 rounded-xl">
                            <summary class="px-4 py-3 text-xs sm:text-sm font-semibold text-amber-900 cursor-pointer select-none">
                                Fees & Loan Notes
                            </summary>
                            <div class="px-4 pb-4 pt-1 text-xs sm:text-sm text-gray-800">
                                <ul class="list-disc pl-4 space-y-1">
                                    <li>
                                        The graduation levels are from one loan amount range to the next. New clients
                                        typically start with loans between
                                        <span class="font-semibold">Ksh. 3,000 – 10,000</span> before progressing to
                                        higher limits.
                                    </li>
                                    <li>
                                        Registration fee for all new clients is
                                        <span class="font-semibold">Ksh. 200</span>.
                                        Loan processing fees are
                                        <span class="font-semibold">Ksh. 450</span> for loans up to
                                        <span class="font-semibold">Ksh. 15,000</span> and
                                        <span class="font-semibold">4% of the loan amount</span> for amounts above
                                        <span class="font-semibold">Ksh. 15,000</span>.
                                    </li>
                                    <li>
                                        All loans include a risk insurance fee of
                                        <span class="font-semibold">Ksh. 50</span>.
                                    </li>
                                    <li>
                                        <span class="font-semibold">Dhahabu</span> loan processing fees are
                                        <span class="font-semibold">5%</span> of the loan amount and the insurance fee
                                        is <span class="font-semibold">Ksh. 500</span>.
                                    </li>
                                    <li>
                                        <span class="font-semibold">Kilimo Product</span>
                                        <ul class="list-disc pl-5 mt-1 space-y-1">
                                            <li>Registration fee: <span class="font-semibold">Ksh. 300</span></li>
                                            <li>Insurance: <span class="font-semibold">Ksh. 100</span></li>
                                            <li>
                                                Processing fees:
                                                <ul class="list-disc pl-5 mt-1 space-y-0.5">
                                                    <li>5,000 – 15,000: <span class="font-semibold">Ksh. 700</span></li>
                                                    <li>16,000 – 20,000: <span class="font-semibold">Ksh. 1,000</span></li>
                                                    <li>21,000 – 30,000: <span class="font-semibold">Ksh. 1,500</span></li>
                                                    <li>31,000 – 50,000: <span class="font-semibold">Ksh. 1,900</span></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </details>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Loan Application Form (revealed after calculator is completed) -->
    <section
        class="py-12 sm:py-16 md:py-20 lg:py-24 relative @if(!$errors->any() && !session('status')) hidden @endif"
        id="apply-loan"
        style="background-image: url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80'); background-size: cover; background-position: center; background-attachment: fixed;"
    >
        <div class="absolute inset-0 bg-gradient-to-br from-teal-900/90 via-teal-800/85 to-teal-900/90 backdrop-blur-md"></div>
        <div class="relative w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 max-w-6xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-6 sm:p-8 md:p-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">Loan Application Form</h2>
                <p class="text-sm text-gray-600 mb-4">
                    Complete the form in a few quick steps. We’ll only show you the most important fields one step at a time.
                </p>
                <div id="loan-summary-banner" class="hidden mb-6 rounded-xl border border-teal-100 bg-teal-50 px-4 py-3 text-sm text-teal-900">
                    <p class="font-semibold mb-1">Loan details from the calculator</p>
                    <p id="loan-summary-text"></p>
                    <p class="text-xs text-teal-800 mt-1">If you need to change these, adjust the loan calculator above and recalculate.</p>
                </div>

                <!-- Step indicator -->
                <div class="mb-6">
                    <ol class="flex items-center justify-between text-xs sm:text-sm text-gray-500">
                        <li class="flex-1 flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-semibold bg-teal-800 text-white step-indicator-circle" data-step-indicator="0">
                                1
                            </div>
                            <span class="hidden sm:inline font-medium step-indicator-label" data-step-indicator-label="0">Personal Info</span>
                        </li>
                        <li class="flex-1 flex items-center justify-center gap-2">
                            <div class="h-[1px] w-full bg-gray-200"></div>
                        </li>
                        <li class="flex-1 flex items-center gap-2 justify-center sm:justify-start">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-semibold bg-gray-200 text-gray-600 step-indicator-circle" data-step-indicator="1">
                                2
                            </div>
                            <span class="hidden sm:inline font-medium step-indicator-label" data-step-indicator-label="1">Loan Details</span>
                        </li>
                        <li class="flex-1 flex items-center justify-center gap-2">
                            <div class="h-[1px] w-full bg-gray-200"></div>
                        </li>
                        <li class="flex-1 flex items-center gap-2 justify-end">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center font-semibold bg-gray-200 text-gray-600 step-indicator-circle" data-step-indicator="2">
                                3
                            </div>
                            <span class="hidden sm:inline font-medium step-indicator-label" data-step-indicator-label="2">Terms & Submit</span>
                        </li>
                    </ol>
                </div>

                @if (session('status'))
                    <div class="mb-6 rounded-lg border border-teal-200 bg-teal-50 px-4 py-3 text-teal-900">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                        <p class="font-semibold mb-1">Please review the highlighted fields and try again.</p>
                    </div>
                @endif

                <form action="{{ route('loan.apply.submit') }}" method="POST" class="space-y-8" id="loan-application-form">
                    @csrf
                    <!-- Hidden fields populated from the loan calculator -->
                    <input type="hidden" id="amount_requested" name="amount_requested" value="{{ old('amount_requested') }}">
                    <input type="hidden" id="repayment_period" name="repayment_period" value="{{ old('repayment_period') }}">

                    <!-- Personal Information -->
                    <div class="space-y-4" data-form-step="0">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2">Client Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all"
                                       placeholder="Enter your full name">
                                @error('full_name')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number <span class="text-red-500">*</span></label>
                                <div class="flex rounded-lg shadow-sm border border-gray-300 focus-within:ring-2 focus-within:ring-teal-800 focus-within:border-transparent transition-all">
                                    <span class="inline-flex items-center px-3 bg-gray-50 border-r border-gray-300 text-sm text-gray-700 select-none">
                                        +254
                                    </span>
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        required
                                        inputmode="numeric"
                                        pattern="\d{9}"
                                        maxlength="9"
                                        class="w-full px-3 py-3 border-0 rounded-r-lg focus:outline-none text-sm sm:text-base"
                                        placeholder="7XX XXX XXX"
                                    >
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    Kenyan numbers only. Enter the <strong>9 digits</strong> after +254 (no leading 0), e.g. 712345678.
                                </p>
                                @error('phone')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="town" class="block text-sm font-medium text-gray-700 mb-1">Service Region <span class="text-red-500">*</span></label>
                                <select id="town" name="town" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all">
                                    <option value="">-- Select your region --</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region }}" @selected(old('town') === $region)>{{ $region }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Select the region where you would like to access our services</p>
                                @error('town')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="residence" class="block text-sm font-medium text-gray-700 mb-1">Residence (Estate / Village)</label>
                                <input type="text" id="residence" name="residence" value="{{ old('residence') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all"
                                       placeholder="e.g. Barnabas, Estate Name">
                                @error('residence')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Client type removed: no longer required in application -->
                    </div>

                    <!-- Loan Information (amount & repayment period come from calculator) -->
                    <div class="space-y-4 hidden" data-form-step="1">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2">Loan Information</h3>
                        <p class="text-xs text-gray-500">
                            The amount and repayment period you selected in the calculator above will be attached to this application.
                        </p>

                        <!-- Loan type removed: loan amount & period now come purely from calculator -->

                        <div>
                            <label for="purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose of Loan</label>
                            <textarea id="purpose" name="purpose" rows="4"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all"
                                      placeholder="Briefly describe how you intend to use the loan">{{ old('purpose') }}</textarea>
                            @error('purpose')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Terms & Agreement -->
                    <div class="space-y-4 hidden" data-form-step="2">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2">Loan Terms & Agreement</h3>
                        <div class="flex items-start gap-3">
                            <input type="checkbox" id="agree_to_terms" name="agree_to_terms" value="1" class="mt-1 h-4 w-4 text-teal-800 border-gray-300 rounded focus:ring-teal-800"
                                   {{ old('agree_to_terms') ? 'checked' : '' }} required>
                            <label for="agree_to_terms" class="text-sm text-gray-700">
                                I confirm that all information provided is true and I agree to Fortress Lenders’ terms.
                            </label>
                        </div>
                        @error('agree_to_terms')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror

                        <p class="text-xs text-gray-500 mt-2">
                            By submitting this form, you consent to Fortress Lenders contacting you by phone, SMS, or email regarding your application.
                        </p>
                    </div>

                    <!-- Navigation / Submit -->
                    <div class="pt-4 border-t border-gray-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                        <div class="flex gap-3">
                            <button
                                type="button"
                                id="loan-prev-step"
                                class="flex-1 sm:flex-none px-4 py-3 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
                            >
                                Previous
                            </button>
                            <button
                                type="button"
                                id="loan-next-step"
                                class="flex-1 sm:flex-none px-6 py-3 bg-gradient-to-r from-teal-800 to-teal-700 text-white rounded-lg text-sm font-semibold hover:from-teal-900 hover:to-teal-800 transition-all shadow-lg"
                            >
                                Next
                            </button>
                        </div>

                        <div id="loan-submit-container" class="w-full sm:w-auto hidden">
                            <button type="submit"
                                    class="w-full px-8 py-3 bg-gradient-to-r from-teal-800 to-teal-700 text-white rounded-lg font-semibold hover:from-teal-900 hover:to-teal-800 transition-all transform hover:scale-105 shadow-lg">
                                Submit Application
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    @push('scripts')
    <script>
        let selectedProduct = null;

        function updateCalculatorFields() {
            const select = document.getElementById('calculator_range');
            const option = select.options[select.selectedIndex];
            const fieldsDiv = document.getElementById('calculator-fields');
            const resultsDiv = document.getElementById('calculator-results');

            if (!option.value) {
                fieldsDiv.classList.add('hidden');
                resultsDiv.classList.add('hidden');
                selectedProduct = null;
                return;
            }

            selectedProduct = {
                id: option.value,
                minAmount: parseFloat(option.getAttribute('data-min-amount')),
                maxAmount: parseFloat(option.getAttribute('data-max-amount')),
                serviceChargeType: option.getAttribute('data-service-charge-type'),
                serviceChargeValue: parseFloat(option.getAttribute('data-service-charge-value')),
                serviceChargePeriod: option.getAttribute('data-service-charge-period'),
                maxDuration: parseInt(option.getAttribute('data-max-duration')),
                paymentFrequency: option.getAttribute('data-payment-frequency'),
                targetClients: option.getAttribute('data-target-clients'),
            };

            // Update fields
            document.getElementById('calculator_amount').value = selectedProduct.minAmount;
            document.getElementById('calculator_amount').min = selectedProduct.minAmount;
            document.getElementById('calculator_amount').max = selectedProduct.maxAmount;
            document.getElementById('calculator-amount-range').textContent = 
                `Range: KES ${selectedProduct.minAmount.toLocaleString('en-KE')} - ${selectedProduct.maxAmount.toLocaleString('en-KE')}`;

            // Update duration field based on payment frequency
            const durationLabel = document.getElementById('duration-label');
            const durationInput = document.getElementById('calculator_duration');
            const durationMaxText = document.getElementById('calculator-duration-max');
            
            if (selectedProduct.paymentFrequency === 'monthly') {
                // For monthly payments, convert weeks to months (4 weeks = 1 month)
                const maxMonths = Math.floor(selectedProduct.maxDuration / 4);
                durationLabel.textContent = 'Repayment Duration (Months)';
                durationInput.placeholder = 'Enter number of months';
                durationInput.value = maxMonths || 1;
                durationInput.max = maxMonths || 1;
                durationInput.min = 1;
                durationMaxText.textContent = `Maximum: ${maxMonths} month${maxMonths > 1 ? 's' : ''}`;
            } else {
                // For weekly payments, use weeks directly
                durationLabel.textContent = 'Repayment Duration (Weeks)';
                durationInput.placeholder = 'Enter number of weeks';
                durationInput.value = selectedProduct.maxDuration;
                durationInput.max = selectedProduct.maxDuration;
                durationInput.min = 1;
                durationMaxText.textContent = `Maximum: ${selectedProduct.maxDuration} weeks`;
            }

            // Update service charge display
            let chargeDisplay = '';
            if (selectedProduct.serviceChargeType === 'fixed_amount') {
                chargeDisplay = `KES ${selectedProduct.serviceChargeValue.toLocaleString('en-KE', {minimumFractionDigits: 0})}`;
                if (selectedProduct.serviceChargePeriod === 'per_month') {
                    chargeDisplay += ' per month';
                } else if (selectedProduct.serviceChargePeriod === 'for_6weeks') {
                    chargeDisplay += ' for 6 weeks';
                }
            } else {
                // Percentage-based
                if (selectedProduct.serviceChargePeriod === 'per_month') {
                    chargeDisplay = `${selectedProduct.serviceChargeValue}% per month`;
                } else {
                    // Flat percentage (like Kuza with 28%)
                    chargeDisplay = `${selectedProduct.serviceChargeValue}%`;
                }
            }
            document.getElementById('service-charge-display').textContent = chargeDisplay;
            
            // Update payment frequency display with target clients
            const frequencyText = selectedProduct.paymentFrequency.charAt(0).toUpperCase() + selectedProduct.paymentFrequency.slice(1);
            const clientsText = selectedProduct.targetClients ? ` (${selectedProduct.targetClients})` : '';
            document.getElementById('payment-frequency-display').textContent = 
                `Payment Frequency: ${frequencyText}${clientsText}`;

            // Update payment label
            const paymentLabel = document.getElementById('payment-label');
            paymentLabel.textContent = selectedProduct.paymentFrequency === 'weekly' ? 'Weekly Payment' : 'Monthly Payment';

            fieldsDiv.classList.remove('hidden');
            resultsDiv.classList.add('hidden');
        }

        // Keep track of last calculation for WhatsApp lead capture
        let lastLoanCalculation = null;

        function showLoanApplicationFormFromCalculator() {
            if (!lastLoanCalculation) {
                return;
            }

            const section = document.getElementById('apply-loan');
            const amountField = document.getElementById('amount_requested');
            const repaymentField = document.getElementById('repayment_period');
            const summaryBanner = document.getElementById('loan-summary-banner');
            const summaryText = document.getElementById('loan-summary-text');

            // Build a human readable repayment period string, e.g. "12 weeks" or "6 months"
            const periodString = `${lastLoanCalculation.durationValue} ${lastLoanCalculation.durationUnit}`;

            if (amountField) {
                amountField.value = lastLoanCalculation.loanAmount;
            }
            if (repaymentField) {
                repaymentField.value = periodString;
            }

            if (summaryBanner && summaryText) {
                summaryText.textContent =
                    `Approximate request of KES ${lastLoanCalculation.loanAmount.toLocaleString('en-KE', { minimumFractionDigits: 0, maximumFractionDigits: 0 })} ` +
                    `to be repaid over ${periodString} (${lastLoanCalculation.paymentFrequency === 'weekly' ? 'weekly' : 'monthly'} payments).`;
                summaryBanner.classList.remove('hidden');
            }

            if (section) {
                section.classList.remove('hidden');
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        function calculateLoanPayment() {
            if (!selectedProduct) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Product Selected',
                    text: 'Please select a loan product first.'
                });
                return;
            }

            const loanAmount = parseFloat(document.getElementById('calculator_amount').value) || 0;
            const durationInput = parseInt(document.getElementById('calculator_duration').value) || 0;

            if (loanAmount < selectedProduct.minAmount || loanAmount > selectedProduct.maxAmount) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Loan Amount',
                    html: `Loan amount must be between <strong>KES ${selectedProduct.minAmount.toLocaleString('en-KE')}</strong> and <strong>KES ${selectedProduct.maxAmount.toLocaleString('en-KE')}</strong>`
                });
                return;
            }

            let totalServiceCharge = 0;
            let numberOfPayments = 0;
            let durationWeeks = 0;
            let durationMonths = 0;

            // Calculate based on payment frequency
            if (selectedProduct.paymentFrequency === 'monthly') {
                // For monthly payments, duration is in months
                durationMonths = durationInput;
                durationWeeks = durationMonths * 4; // Convert months to weeks (1 month = 4 weeks)
                
                if (durationMonths <= 0 || durationMonths > Math.floor(selectedProduct.maxDuration / 4)) {
                    const maxMonths = Math.floor(selectedProduct.maxDuration / 4);
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Duration',
                        html: `Duration must be between <strong>1</strong> and <strong>${maxMonths}</strong> month${maxMonths > 1 ? 's' : ''}`
                    });
                    return;
                }
                
                numberOfPayments = durationMonths;
            } else {
                // For weekly payments, duration is in weeks
                durationWeeks = durationInput;
                
                if (durationWeeks <= 0 || durationWeeks > selectedProduct.maxDuration) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Duration',
                        html: `Duration must be between <strong>1</strong> and <strong>${selectedProduct.maxDuration}</strong> weeks`
                    });
                    return;
                }
                
                numberOfPayments = durationWeeks;
            }

            // Calculate service charge
            if (selectedProduct.serviceChargeType === 'fixed_amount') {
                if (selectedProduct.serviceChargePeriod === 'for_6weeks') {
                    // Fixed amount for 6 weeks period
                    const sixWeekPeriods = Math.ceil(durationWeeks / 6);
                    totalServiceCharge = selectedProduct.serviceChargeValue * sixWeekPeriods;
                } else if (selectedProduct.serviceChargePeriod === 'per_month') {
                    // Fixed amount per month
                    if (selectedProduct.paymentFrequency === 'monthly') {
                        totalServiceCharge = selectedProduct.serviceChargeValue * durationMonths;
                    } else {
                        // For weekly payments, calculate months from weeks
                        const months = durationWeeks / 4;
                        totalServiceCharge = selectedProduct.serviceChargeValue * months;
                    }
                }
            } else {
                // Percentage-based service charge
                if (selectedProduct.serviceChargePeriod === 'per_month') {
                    if (selectedProduct.paymentFrequency === 'monthly') {
                        // For monthly payments, calculate directly from months
                        totalServiceCharge = (loanAmount * selectedProduct.serviceChargeValue / 100) * durationMonths;
                    } else {
                        // For weekly payments, calculate months from weeks
                        const months = durationWeeks / 4;
                        totalServiceCharge = (loanAmount * selectedProduct.serviceChargeValue / 100) * months;
                    }
                } else {
                    // Flat percentage (for special cases like Kuza with 28%)
                    totalServiceCharge = (loanAmount * selectedProduct.serviceChargeValue / 100);
                }
            }

            const totalAmount = loanAmount + totalServiceCharge;
            const paymentAmount = totalAmount / numberOfPayments;

            // Update results
            document.getElementById('payment-amount').textContent = 
                'KES ' + paymentAmount.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('total-service-charge').textContent = 
                'KES ' + totalServiceCharge.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            document.getElementById('total-amount').textContent = 
                'KES ' + totalAmount.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            // Save details for WhatsApp section and loan application form
            lastLoanCalculation = {
                loanAmount,
                durationValue: selectedProduct.paymentFrequency === 'monthly' ? durationMonths : durationWeeks,
                durationUnit: selectedProduct.paymentFrequency === 'monthly' ? 'months' : 'weeks',
                serviceCharge: totalServiceCharge,
                totalRepayment: totalAmount,
                paymentAmount,
                paymentFrequency: selectedProduct.paymentFrequency,
            };

            document.getElementById('calculator-results').classList.remove('hidden');
            const whatsappSection = document.getElementById('whatsapp-lead-section');
            if (whatsappSection) {
                whatsappSection.classList.remove('hidden');
            }
            document.getElementById('calculator-results').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        // WhatsApp lead capture
        (function () {
            const btn = document.getElementById('whatsapp-send-btn');
            const input = document.getElementById('whatsapp_number');
            if (!btn || !input) return;

            btn.addEventListener('click', async function () {
                if (!lastLoanCalculation) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Calculate First',
                        text: 'Please calculate your loan first so we can send accurate details.',
                    });
                    return;
                }

                const localDigits = (input.value || '').trim();
                if (!localDigits) {
                    input.focus();
                    input.classList.add('border-red-400');
                    setTimeout(() => input.classList.remove('border-red-400'), 1500);
                    return;
                }

                // Expect 9 Kenyan digits, no leading zero
                const localRegex = /^\d{9}$/;
                if (!localRegex.test(localDigits)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid WhatsApp Number',
                        text: 'Please enter 9 digits after +254 (no leading 0), e.g. 712345678.',
                    });
                    return;
                }

                // Build full international number with +254 prefix for backend and wa.me
                const rawNumber = '+254' + localDigits;

                // Basic E.164 style validation on full number
                const intlRegex = /^\+[1-9]\d{6,14}$/;
                if (!intlRegex.test(rawNumber)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid WhatsApp Number',
                        text: 'Please enter a valid Kenyan WhatsApp number.',
                    });
                    return;
                }

                // Prepare payload for backend
                const payload = {
                    name: document.getElementById('full_name')?.value || null,
                    whatsapp_number: rawNumber,
                    loan_amount: lastLoanCalculation.loanAmount,
                    loan_duration_value: lastLoanCalculation.durationValue,
                    loan_duration_unit: lastLoanCalculation.durationUnit,
                    service_charge: lastLoanCalculation.serviceCharge,
                    total_repayment: lastLoanCalculation.totalRepayment,
                    payment_frequency: lastLoanCalculation.paymentFrequency,
                };

                try {
                    btn.disabled = true;
                    btn.classList.add('opacity-80', 'cursor-not-allowed');

                    const response = await fetch("{{ route('loan.apply.whatsapp-lead') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    });

                    if (!response.ok) {
                        const data = await response.json().catch(() => ({}));
                        const message = data.message || 'Failed to save your details. Please try again.';
                        throw new Error(message);
                    }

                    // Build WhatsApp message
                    const pageUrl = @json(url()->current());
                    const siteUrl = @json(config('app.url') ?? url('/'));
                    const intro = 'Hello Fortress Lenders, I would like to get more details about this loan estimate:';
                    const lines = [
                        intro,
                        '',
                        `Loan amount: KES ${lastLoanCalculation.loanAmount.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
                        `Loan duration: ${lastLoanCalculation.durationValue} ${lastLoanCalculation.durationUnit}`,
                        `Service charge: KES ${lastLoanCalculation.serviceCharge.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
                        `Total repayment: KES ${lastLoanCalculation.totalRepayment.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
                        `Repayment frequency: ${lastLoanCalculation.paymentFrequency === 'weekly' ? 'Weekly' : 'Monthly'}`,
                        '',
                        `Apply from this page: ${pageUrl}`,
                        `Fortress Lenders website: ${siteUrl}`,
                    ];
                    const msg = encodeURIComponent(lines.join('\n'));

                    // wa.me expects number without +
                    const waNumber = rawNumber.replace('+', '');
                    const waUrl = `https://wa.me/${waNumber}?text=${msg}`;

                    // Try to open WhatsApp
                    const waWindow = window.open(waUrl, '_blank');
                    const popupBlocked = !waWindow || waWindow.closed || typeof waWindow.closed === 'undefined';

                    if (popupBlocked) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Almost there',
                            html: `We saved your loan estimate. Your browser blocked the WhatsApp window.<br><br>
                                   <a href="${waUrl}" target="_blank" class="text-teal-700 underline font-semibold">Tap here to open WhatsApp and send the message.</a>`,
                        });
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'WhatsApp Ready',
                            text: 'We saved your loan estimate and opened WhatsApp with your loan details. Please tap Send in WhatsApp to complete.',
                            timer: 5000,
                            showConfirmButton: false,
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Something went wrong',
                        text: error.message || 'Unable to send to WhatsApp. Please try again.',
                    });
                } finally {
                    btn.disabled = false;
                    btn.classList.remove('opacity-80', 'cursor-not-allowed');
                }
            });
        })();

        // Handle "Proceed to Application Form" button
        (function () {
            const proceedBtn = document.getElementById('proceed-to-application-btn');
            if (!proceedBtn) return;

            proceedBtn.addEventListener('click', function () {
                if (!lastLoanCalculation) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Calculate First',
                        text: 'Please calculate your loan first so we can attach the correct details to your application.',
                    });
                    return;
                }

                showLoanApplicationFormFromCalculator();
            });
        })();

        // Multi-step loan application form
        (function () {
            const form = document.getElementById('loan-application-form');
            if (!form) return;

            const steps = Array.from(form.querySelectorAll('[data-form-step]'));
            const prevBtn = document.getElementById('loan-prev-step');
            const nextBtn = document.getElementById('loan-next-step');
            const submitContainer = document.getElementById('loan-submit-container');
            const indicatorCircles = Array.from(document.querySelectorAll('.step-indicator-circle'));
            const indicatorLabels = Array.from(document.querySelectorAll('.step-indicator-label'));

            let currentStep = 0;

            function updateIndicators() {
                indicatorCircles.forEach((el, index) => {
                    if (index === currentStep) {
                        el.classList.remove('bg-gray-200', 'text-gray-600');
                        el.classList.add('bg-teal-800', 'text-white');
                    } else {
                        el.classList.add('bg-gray-200', 'text-gray-600');
                        el.classList.remove('bg-teal-800', 'text-white');
                    }
                });

                indicatorLabels.forEach((el, index) => {
                    if (index === currentStep) {
                        el.classList.add('text-teal-800', 'font-semibold');
                    } else {
                        el.classList.remove('text-teal-800', 'font-semibold');
                    }
                });
            }

            function showStep(index) {
                steps.forEach((step, i) => {
                    if (i === index) {
                        step.classList.remove('hidden');
                    } else {
                        step.classList.add('hidden');
                    }
                });

                prevBtn.disabled = index === 0;

                if (index === steps.length - 1) {
                    nextBtn.classList.add('hidden');
                    submitContainer.classList.remove('hidden');
                } else {
                    nextBtn.classList.remove('hidden');
                    submitContainer.classList.add('hidden');
                }

                updateIndicators();
            }

            function validateCurrentStep() {
                // Simple client-side validation: check required inputs in current step
                const currentFields = steps[currentStep].querySelectorAll('input, select, textarea');
                for (const field of currentFields) {
                    if (field.hasAttribute('required') && !field.value) {
                        field.focus();
                        field.classList.add('border-red-400');
                        setTimeout(() => field.classList.remove('border-red-400'), 1500);
                        return false;
                    }
                }
                return true;
            }

            prevBtn.addEventListener('click', () => {
                if (currentStep > 0) {
                    currentStep -= 1;
                    showStep(currentStep);
                }
            });

            nextBtn.addEventListener('click', () => {
                if (!validateCurrentStep()) return;
                if (currentStep < steps.length - 1) {
                    currentStep += 1;
                    showStep(currentStep);
                }
            });

            // Initialize first step
            showStep(currentStep);
        })();
    </script>
    @endpush
@endsection

