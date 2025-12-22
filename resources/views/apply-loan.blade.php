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
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 max-w-6xl mx-auto space-y-8 sm:space-y-10">
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

            <!-- Eligibility & Simple Estimator wrapper for mobile -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 items-stretch">
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

                <!-- Simple Loan Estimator (client-side only) -->
                <div class="bg-white rounded-2xl border border-teal-100 p-5 sm:p-6 shadow-sm">
                    <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 sm:mb-4">
                        Quick Loan Estimate (Approximate)
                    </h3>
                    <p class="text-xs sm:text-sm text-gray-600 mb-4">
                        Use this simple tool to estimate a rough monthly repayment.
                        Actual terms will be discussed with you by our team.
                    </p>
                    <div class="space-y-4">
                        <div>
                            <label for="estimate_amount" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                Amount Requested (KES)
                            </label>
                            <input
                                type="number"
                                id="estimate_amount"
                                min="1000"
                                step="1000"
                                inputmode="numeric"
                                class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent text-sm"
                                placeholder="e.g. 50000"
                            >
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label for="estimate_period" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                    Repayment Period
                                </label>
                                <select
                                    id="estimate_period"
                                    class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent text-sm"
                                >
                                    <option value="1">1 month</option>
                                    <option value="3" selected>3 months</option>
                                    <option value="6">6 months</option>
                                    <option value="9">9 months</option>
                                    <option value="12">12 months</option>
                                </select>
                            </div>
                            <div>
                                <label for="estimate_rate" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                    Approx. Monthly Rate
                                </label>
                                <select
                                    id="estimate_rate"
                                    class="w-full px-3 py-2 sm:px-4 sm:py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent text-sm"
                                >
                                    <option value="0.02">2% per month</option>
                                    <option value="0.025">2.5% per month</option>
                                    <option value="0.03" selected>3% per month</option>
                                    <option value="0.035">3.5% per month</option>
                                </select>
                            </div>
                        </div>
                        <div class="bg-teal-50 border border-teal-100 rounded-xl p-4 text-sm sm:text-base">
                            <p class="text-gray-700 mb-1">
                                Estimated Monthly Repayment:
                            </p>
                            <p id="estimate_result" class="text-xl sm:text-2xl font-bold text-teal-800">
                                KES 0
                            </p>
                            <p class="text-[11px] sm:text-xs text-gray-500 mt-2">
                                This is a rough illustration using simple interest for your convenience and
                                <span class="font-semibold">not</span> a formal offer or guarantee.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Loan Application Form -->
    <section class="py-12 sm:py-16 md:py-20 lg:py-24 relative" id="apply-loan" style="background-image: url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="absolute inset-0 bg-gradient-to-br from-teal-900/90 via-teal-800/85 to-teal-900/90 backdrop-blur-md"></div>
        <div class="relative w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 max-w-6xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-6 sm:p-8 md:p-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">Loan Application Form</h2>
                <p class="text-sm text-gray-600 mb-6">
                    Complete the form in a few quick steps. We’ll only show you the most important fields one step at a time.
                </p>

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
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all"
                                       placeholder="e.g. 07XX XXX XXX">
                                @error('phone')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email (optional)</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all"
                                       placeholder="example@email.com">
                                @error('email')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all">
                                @error('date_of_birth')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="town" class="block text-sm font-medium text-gray-700 mb-1">Town</label>
                                <input type="text" id="town" name="town" value="{{ old('town') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all"
                                       placeholder="e.g. Nakuru">
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

                        <div>
                            <label for="client_type" class="block text-sm font-medium text-gray-700 mb-1">Client Type</label>
                            <select id="client_type" name="client_type"
                                    class="w-full md:w-1/2 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all">
                                <option value="">Select type</option>
                                <option value="business" @selected(old('client_type') === 'business')>Business</option>
                                <option value="employed" @selected(old('client_type') === 'employed')>Employed</option>
                                <option value="casual" @selected(old('client_type') === 'casual')>Casual</option>
                                <option value="student" @selected(old('client_type') === 'student')>Student</option>
                            </select>
                            @error('client_type')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Loan Information -->
                    <div class="space-y-4 hidden" data-form-step="1">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-2">Loan Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="loan_type" class="block text-sm font-medium text-gray-700 mb-1">Loan Type <span class="text-red-500">*</span></label>
                                <select id="loan_type" name="loan_type" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all">
                                    <option value="">Select loan type</option>
                                    <option value="Business Loan" @selected(old('loan_type') === 'Business Loan')>Business Loan</option>
                                    <option value="Personal Loan" @selected(old('loan_type') === 'Personal Loan')>Personal Loan</option>
                                    <option value="Asset Finance" @selected(old('loan_type') === 'Asset Finance')>Asset Finance</option>
                                    <option value="Emergency Loan" @selected(old('loan_type') === 'Emergency Loan')>Emergency Loan</option>
                                    <option value="Group Loan" @selected(old('loan_type') === 'Group Loan')>Group Loan</option>
                                    <option value="Agricultural Loan" @selected(old('loan_type') === 'Agricultural Loan')>Agricultural Loan</option>
                                    <option value="Education Loan" @selected(old('loan_type') === 'Education Loan')>Education Loan</option>
                                    <option value="Other" @selected(old('loan_type') === 'Other')>Other</option>
                                </select>
                                @error('loan_type')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="amount_requested" class="block text-sm font-medium text-gray-700 mb-1">Amount Requested (KES) <span class="text-red-500">*</span></label>
                                <input type="number" id="amount_requested" name="amount_requested" value="{{ old('amount_requested') }}"
                                       min="0" step="100"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all"
                                       placeholder="e.g. 50000" required>
                                @error('amount_requested')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="repayment_period" class="block text-sm font-medium text-gray-700 mb-1">Repayment Period <span class="text-red-500">*</span></label>
                                <select id="repayment_period" name="repayment_period" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-800 focus:border-transparent transition-all">
                                    <option value="">Select repayment period</option>
                                    <option value="1 month" @selected(old('repayment_period') === '1 month')>1 month</option>
                                    <option value="2 months" @selected(old('repayment_period') === '2 months')>2 months</option>
                                    <option value="3 months" @selected(old('repayment_period') === '3 months')>3 months</option>
                                    <option value="6 months" @selected(old('repayment_period') === '6 months')>6 months</option>
                                    <option value="9 months" @selected(old('repayment_period') === '9 months')>9 months</option>
                                    <option value="12 months" @selected(old('repayment_period') === '12 months')>12 months</option>
                                </select>
                                @error('repayment_period')<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

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
        // Loan estimator
        (function () {
            const amountInput = document.getElementById('estimate_amount');
            const periodSelect = document.getElementById('estimate_period');
            const rateSelect = document.getElementById('estimate_rate');
            const resultEl = document.getElementById('estimate_result');

            if (!amountInput || !periodSelect || !rateSelect || !resultEl) {
                return;
            }

            function formatKES(value) {
                if (!isFinite(value) || value <= 0) {
                    return 'KES 0';
                }
                return 'KES ' + value.toLocaleString('en-KE', {
                    maximumFractionDigits: 0
                });
            }

            function recalc() {
                const principal = parseFloat(amountInput.value || '0');
                const months = parseInt(periodSelect.value || '0', 10);
                const monthlyRate = parseFloat(rateSelect.value || '0');

                if (!principal || !months || !monthlyRate) {
                    resultEl.textContent = 'KES 0';
                    return;
                }

                // Simple-interest style estimate for clarity on mobile:
                // total = principal + (principal * monthlyRate * months)
                const total = principal + (principal * monthlyRate * months);
                const monthly = total / months;

                resultEl.textContent = formatKES(monthly);
            }

            ['input', 'change'].forEach(evt => {
                amountInput.addEventListener(evt, recalc);
                periodSelect.addEventListener(evt, recalc);
                rateSelect.addEventListener(evt, recalc);
            });

            // Initial calculation with any default values
            recalc();
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

