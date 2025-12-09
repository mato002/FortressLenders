@extends('layouts.website')

@section('title', 'Apply for a Loan - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-teal-800 via-teal-700 to-teal-900 text-white py-12 sm:py-16 md:py-20">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-24 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-3 sm:mb-4 px-4">Apply for a Loan</h1>
            <p class="text-lg sm:text-xl text-teal-100 px-4 max-w-2xl mx-auto">
                Share a few details and our team will review your application and get back to you.
            </p>
        </div>
    </section>

    <!-- Loan Application Form -->
    <section class="py-12 sm:py-16 md:py-20 relative" id="apply-loan" style="background-image: url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80'); background-size: cover; background-position: center; background-attachment: fixed;">
        <div class="absolute inset-0 bg-gradient-to-br from-teal-900/90 via-teal-800/85 to-teal-900/90 backdrop-blur-md"></div>
        <div class="relative w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32 max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-6 sm:p-8 md:p-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">Loan Application Form</h2>

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

                <form action="{{ route('loan.apply.submit') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- Personal Information -->
                    <div class="space-y-4">
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
                    <div class="space-y-4">
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
                    <div class="space-y-4">
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

                    <!-- Submit -->
                    <div class="pt-4 border-t border-gray-100">
                        <button type="submit"
                                class="w-full md:w-auto px-8 py-3 bg-gradient-to-r from-teal-800 to-teal-700 text-white rounded-lg font-semibold hover:from-teal-900 hover:to-teal-800 transition-all transform hover:scale-105 shadow-lg">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection




