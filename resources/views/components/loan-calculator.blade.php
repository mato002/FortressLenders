@props(['product'])

<div class="loan-calculator bg-white rounded-xl shadow-lg border border-gray-200 p-6">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-gradient-to-br from-teal-600 to-teal-700 rounded-lg flex items-center justify-center">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-900">Loan Calculator</h3>
            <p class="text-sm text-gray-600">Calculate your {{ $product->payment_frequency === 'weekly' ? 'weekly' : 'monthly' }} payments</p>
        </div>
    </div>

    <form id="loan-calculator-form-{{ $product->id }}" class="space-y-4">
        {{-- Loan Amount --}}
        <div>
            <label for="loan-amount-{{ $product->id }}" class="block text-sm font-semibold text-gray-700 mb-2">
                Loan Amount (KES)
            </label>
            <div class="relative">
                <input 
                    type="number" 
                    id="loan-amount-{{ $product->id }}" 
                    name="loan_amount"
                    min="{{ $product->min_loan_amount ?? 0 }}"
                    max="{{ $product->max_loan_amount ?? 10000000 }}"
                    step="1000"
                    value="{{ $product->min_loan_amount ?? 100000 }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-lg font-semibold"
                    required
                >
                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">KES</div>
            </div>
            @if($product->min_loan_amount || $product->max_loan_amount)
                <p class="mt-1 text-xs text-gray-500">
                    Range: KES {{ number_format($product->min_loan_amount ?? 0) }} - {{ number_format($product->max_loan_amount ?? 0) }}
                </p>
            @endif
        </div>

        {{-- Duration (Weeks) --}}
        <div>
            <label for="duration-weeks-{{ $product->id }}" class="block text-sm font-semibold text-gray-700 mb-2">
                Duration (Weeks)
            </label>
            <div class="relative">
                <input 
                    type="number" 
                    id="duration-weeks-{{ $product->id }}" 
                    name="duration_weeks"
                    min="1"
                    max="{{ $product->max_duration_weeks ?? 52 }}"
                    step="1"
                    value="{{ $product->max_duration_weeks ?? 12 }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-lg font-semibold"
                    required
                >
                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-medium">weeks</div>
            </div>
            @if($product->max_duration_weeks)
                <p class="mt-1 text-xs text-gray-500">
                    Maximum: {{ $product->max_duration_weeks }} weeks
                </p>
            @endif
        </div>

        {{-- Service Charge Info Display --}}
        @if($product->service_charge_type && $product->service_charge_value)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm font-semibold text-blue-900 mb-1">Service Charge:</p>
                <p class="text-base text-blue-800">
                    @if($product->service_charge_type === 'fixed_amount')
                        KES {{ number_format($product->service_charge_value, 2) }} 
                        @if($product->service_charge_period === 'per_month')
                            per month
                        @elseif($product->service_charge_period === 'for_6weeks')
                            for 6 weeks
                        @endif
                    @else
                        {{ number_format($product->service_charge_value, 2) }}% 
                        @if($product->service_charge_period === 'per_month')
                            per month
                        @endif
                    @endif
                </p>
                <p class="text-xs text-blue-700 mt-1">
                    Payment Frequency: {{ ucfirst($product->payment_frequency ?? 'weekly') }}
                </p>
            </div>
        @endif

        {{-- Calculate Button --}}
        <button 
            type="button"
            onclick="calculateLoanWithServiceCharge({{ $product->id }}, '{{ $product->service_charge_type ?? 'percentage' }}', {{ $product->service_charge_value ?? 0 }}, '{{ $product->service_charge_period ?? 'per_month' }}', '{{ $product->payment_frequency ?? 'weekly' }}')"
            class="w-full bg-gradient-to-r from-teal-600 to-teal-700 text-white py-3 rounded-lg font-semibold hover:from-teal-700 hover:to-teal-800 transition-all shadow-md hover:shadow-lg"
        >
            Calculate Payment
        </button>
    </form>

    {{-- Results --}}
    <div id="calculator-results-{{ $product->id }}" class="mt-6 hidden">
        <div class="bg-gradient-to-br from-teal-50 to-teal-100 rounded-lg p-6 space-y-4">
            <h4 class="text-lg font-bold text-gray-900 mb-4">Payment Summary</h4>
            
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-lg p-4">
                    <p class="text-xs text-gray-600 mb-1">{{ ucfirst($product->payment_frequency ?? 'weekly') }} Payment</p>
                    <p id="payment-amount-{{ $product->id }}" class="text-2xl font-bold text-teal-700">KES 0</p>
                </div>
                <div class="bg-white rounded-lg p-4">
                    <p class="text-xs text-gray-600 mb-1">Total Service Charge</p>
                    <p id="total-service-charge-{{ $product->id }}" class="text-2xl font-bold text-blue-700">KES 0</p>
                </div>
                <div class="bg-white rounded-lg p-4 col-span-2">
                    <p class="text-xs text-gray-600 mb-1">Total Amount Payable</p>
                    <p id="total-amount-{{ $product->id }}" class="text-2xl font-bold text-gray-900">KES 0</p>
                </div>
            </div>

            <div class="pt-4 border-t border-teal-200">
                <p class="text-xs text-gray-600 text-center">
                    * Calculations are estimates. Actual rates may vary based on your credit profile.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
function calculateLoanWithServiceCharge(productId, serviceChargeType, serviceChargeValue, serviceChargePeriod, paymentFrequency) {
    const loanAmount = parseFloat(document.getElementById('loan-amount-' + productId).value) || 0;
    const durationWeeks = parseInt(document.getElementById('duration-weeks-' + productId).value) || 1;

    if (loanAmount <= 0 || durationWeeks <= 0) {
        alert('Please enter valid loan amount and duration.');
        return;
    }

    let totalServiceCharge = 0;
    let paymentAmount = 0;
    let totalAmount = 0;
    let numberOfPayments = 0;

    // Calculate number of payments based on frequency
    if (paymentFrequency === 'weekly') {
        numberOfPayments = durationWeeks;
    } else if (paymentFrequency === 'monthly') {
        numberOfPayments = Math.ceil(durationWeeks / 4.33); // Approximate weeks to months
    } else {
        numberOfPayments = durationWeeks; // Default to weekly
    }

    // Calculate service charge based on type and period
    if (serviceChargeType === 'fixed_amount') {
        if (serviceChargePeriod === 'for_6weeks') {
            // Fixed charge for 6 weeks - calculate how many 6-week periods
            const sixWeekPeriods = Math.ceil(durationWeeks / 6);
            totalServiceCharge = serviceChargeValue * sixWeekPeriods;
        } else if (serviceChargePeriod === 'per_month') {
            // Fixed charge per month
            const months = durationWeeks / 4.33; // Approximate
            totalServiceCharge = serviceChargeValue * months;
        } else {
            // Default: per month
            const months = durationWeeks / 4.33;
            totalServiceCharge = serviceChargeValue * months;
        }
    } else if (serviceChargeType === 'percentage') {
        // Percentage service charge
        if (serviceChargePeriod === 'per_month') {
            const months = durationWeeks / 4.33; // Approximate
            totalServiceCharge = (loanAmount * serviceChargeValue / 100) * months;
        } else {
            // Default: per month
            const months = durationWeeks / 4.33;
            totalServiceCharge = (loanAmount * serviceChargeValue / 100) * months;
        }
    }

    // Calculate total amount
    totalAmount = loanAmount + totalServiceCharge;

    // Calculate payment amount
    paymentAmount = totalAmount / numberOfPayments;

    // Update results
    document.getElementById('payment-amount-' + productId).textContent = 
        'KES ' + paymentAmount.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('total-service-charge-' + productId).textContent = 
        'KES ' + totalServiceCharge.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    document.getElementById('total-amount-' + productId).textContent = 
        'KES ' + totalAmount.toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    // Show results
    document.getElementById('calculator-results-' + productId).classList.remove('hidden');
    
    // Scroll to results
    document.getElementById('calculator-results-' + productId).scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
</script>
