@csrf
@php($inputClasses = 'w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title', $product->title) }}" required class="{{ $inputClasses }}">
            @error('title')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <input type="text" name="category" value="{{ old('category', $product->category) }}" class="{{ $inputClasses }}">
            @error('category')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Summary</label>
            <textarea name="summary" rows="4" class="{{ $inputClasses }}">{{ old('summary', $product->summary) }}</textarea>
            @error('summary')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="space-y-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Highlight Color</label>
            <input type="text" name="highlight_color" value="{{ old('highlight_color', $product->highlight_color) }}" required class="{{ $inputClasses }}">
            @error('highlight_color')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CTA Label</label>
                <input type="text" name="cta_label" value="{{ old('cta_label', $product->cta_label) }}" class="{{ $inputClasses }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CTA Link</label>
                <input type="url" name="cta_link" value="{{ old('cta_link', $product->cta_link) }}" class="{{ $inputClasses }}">
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="6" class="{{ $inputClasses }}">{{ old('description', $product->description) }}</textarea>
            @error('description')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Display Order</label>
        <input type="number" min="0" name="display_order" value="{{ old('display_order', $product->display_order) }}" class="{{ $inputClasses }}">
    </div>
    <div class="flex items-center space-x-3 mt-2">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="h-5 w-5 text-teal-600 border-gray-300 rounded">
        <span class="text-sm text-gray-700">Visible on website</span>
    </div>
</div>

{{-- Loan Details Section --}}
<div class="mt-8 border-t border-gray-200 pt-8">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Loan Details & Terms</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Loan Amount Range --}}
        <div class="space-y-4">
            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Loan Amount</h4>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum Amount (KES)</label>
                    <input type="number" step="0.01" min="0" name="min_loan_amount" value="{{ old('min_loan_amount', $product->min_loan_amount) }}" class="{{ $inputClasses }}" placeholder="e.g., 10000">
                    @error('min_loan_amount')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Amount (KES)</label>
                    <input type="number" step="0.01" min="0" name="max_loan_amount" value="{{ old('max_loan_amount', $product->max_loan_amount) }}" class="{{ $inputClasses }}" placeholder="e.g., 500000">
                    @error('max_loan_amount')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Interest Rate --}}
        <div class="space-y-4">
            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Interest Rate</h4>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Min Rate (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="interest_rate_min" value="{{ old('interest_rate_min', $product->interest_rate_min) }}" class="{{ $inputClasses }}" placeholder="e.g., 2.5">
                    @error('interest_rate_min')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Rate (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="interest_rate_max" value="{{ old('interest_rate_max', $product->interest_rate_max) }}" class="{{ $inputClasses }}" placeholder="e.g., 5.0">
                    @error('interest_rate_max')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rate Type</label>
                <select name="interest_rate_type" class="{{ $inputClasses }}">
                    <option value="">Select type...</option>
                    <option value="per_month" {{ old('interest_rate_type', $product->interest_rate_type) == 'per_month' ? 'selected' : '' }}>Per Month</option>
                    <option value="per_year" {{ old('interest_rate_type', $product->interest_rate_type) == 'per_year' ? 'selected' : '' }}>Per Year</option>
                    <option value="flat" {{ old('interest_rate_type', $product->interest_rate_type) == 'flat' ? 'selected' : '' }}>Flat Rate</option>
                    <option value="reducing_balance" {{ old('interest_rate_type', $product->interest_rate_type) == 'reducing_balance' ? 'selected' : '' }}>Reducing Balance</option>
                </select>
                @error('interest_rate_type')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Repayment Period --}}
        <div class="space-y-4">
            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Repayment Period</h4>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Minimum (Months)</label>
                    <input type="number" min="1" name="repayment_period_min" value="{{ old('repayment_period_min', $product->repayment_period_min) }}" class="{{ $inputClasses }}" placeholder="e.g., 3">
                    @error('repayment_period_min')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Maximum (Months)</label>
                    <input type="number" min="1" name="repayment_period_max" value="{{ old('repayment_period_max', $product->repayment_period_max) }}" class="{{ $inputClasses }}" placeholder="e.g., 24">
                    @error('repayment_period_max')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Maximum Duration (Weeks)</label>
                <input type="number" min="1" name="max_duration_weeks" value="{{ old('max_duration_weeks', $product->max_duration_weeks) }}" class="{{ $inputClasses }}" placeholder="e.g., 12">
                <p class="text-xs text-gray-500 mt-1">Maximum loan duration in weeks</p>
                @error('max_duration_weeks')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Service Charge --}}
        <div class="space-y-4">
            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Service Charge</h4>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Service Charge Type</label>
                <select name="service_charge_type" class="{{ $inputClasses }}">
                    <option value="">Select type...</option>
                    <option value="fixed_amount" {{ old('service_charge_type', $product->service_charge_type) == 'fixed_amount' ? 'selected' : '' }}>Fixed Amount</option>
                    <option value="percentage" {{ old('service_charge_type', $product->service_charge_type) == 'percentage' ? 'selected' : '' }}>Percentage</option>
                </select>
                @error('service_charge_type')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Charge Value</label>
                    <input type="number" step="0.01" min="0" name="service_charge_value" value="{{ old('service_charge_value', $product->service_charge_value) }}" class="{{ $inputClasses }}" placeholder="KES amount or %">
                    <p class="text-xs text-gray-500 mt-1">Fixed amount in KES or percentage value</p>
                    @error('service_charge_value')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Charge Period</label>
                    <select name="service_charge_period" class="{{ $inputClasses }}">
                        <option value="">Select period...</option>
                        <option value="per_month" {{ old('service_charge_period', $product->service_charge_period) == 'per_month' ? 'selected' : '' }}>Per Month</option>
                        <option value="for_6weeks" {{ old('service_charge_period', $product->service_charge_period) == 'for_6weeks' ? 'selected' : '' }}>For 6 Weeks</option>
                    </select>
                    @error('service_charge_period')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Frequency</label>
                    <select name="payment_frequency" class="{{ $inputClasses }}">
                        <option value="">Select frequency...</option>
                        <option value="weekly" {{ old('payment_frequency', $product->payment_frequency) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                        <option value="monthly" {{ old('payment_frequency', $product->payment_frequency) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                    @error('payment_frequency')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target Clients</label>
                    <input type="text" name="target_clients" value="{{ old('target_clients', $product->target_clients) }}" class="{{ $inputClasses }}" placeholder="e.g., Trade & Service, Farming">
                    @error('target_clients')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Processing Time --}}
        <div class="space-y-4">
            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Processing</h4>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Processing Time</label>
                <input type="text" name="processing_time" value="{{ old('processing_time', $product->processing_time) }}" class="{{ $inputClasses }}" placeholder="e.g., 24-48 hours, 3-5 business days">
                <p class="text-xs text-gray-500 mt-1">Describe how long it takes to process the loan</p>
                @error('processing_time')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- Repayment Information --}}
    <div class="mt-6 space-y-4">
        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Repayment Information</h4>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Repayment Methods</label>
            <textarea name="repayment_methods" rows="3" class="{{ $inputClasses }}" placeholder="e.g., M-Pesa, Bank Transfer, Cash, Mobile Banking (one per line or comma-separated)">{{ old('repayment_methods', $product->repayment_methods) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">List available repayment methods</p>
            @error('repayment_methods')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Repayment Schedule Information</label>
            <textarea name="repayment_schedule_info" rows="3" class="{{ $inputClasses }}" placeholder="e.g., Monthly installments, Weekly payments, Flexible repayment options">{{ old('repayment_schedule_info', $product->repayment_schedule_info) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Explain how repayments are scheduled</p>
            @error('repayment_schedule_info')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Eligibility & Requirements --}}
    <div class="mt-6 space-y-4">
        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Eligibility & Requirements</h4>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Eligibility Criteria</label>
            <textarea name="eligibility_criteria" rows="4" class="{{ $inputClasses }}" placeholder="e.g., Age 18-65, Kenyan citizen, Minimum income of KES 15,000/month, etc. (one per line)">{{ old('eligibility_criteria', $product->eligibility_criteria) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">List all eligibility requirements</p>
            @error('eligibility_criteria')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Required Documents</label>
            <textarea name="required_documents" rows="4" class="{{ $inputClasses }}" placeholder="e.g., National ID, Proof of income, Bank statements, etc. (one per line)">{{ old('required_documents', $product->required_documents) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">List all required documents</p>
            @error('required_documents')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Fees & Additional Info --}}
    <div class="mt-6 space-y-4">
        <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Fees & Additional Information</h4>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Fees and Charges</label>
            <textarea name="fees_and_charges" rows="4" class="{{ $inputClasses }}" placeholder="e.g., Processing fee: KES 500, Late payment fee: 2% per month, etc.">{{ old('fees_and_charges', $product->fees_and_charges) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">List all applicable fees and charges</p>
            @error('fees_and_charges')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Additional Information</label>
            <textarea name="additional_info" rows="4" class="{{ $inputClasses }}" placeholder="Any other important information potential borrowers should know">{{ old('additional_info', $product->additional_info) }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Any other relevant details about this loan product</p>
            @error('additional_info')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Images</label>
    <input type="file" name="images[]" multiple class="block w-full text-sm text-gray-700">
    <p class="text-xs text-gray-500 mt-1">You can select multiple images (JPG, PNG, max 4 MB each).</p>
    @error('images.*')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
</div>

<div class="mt-8 flex justify-end space-x-3">
    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-gray-300 text-sm font-medium text-gray-700 rounded-lg hover:bg-gray-50">Cancel</a>
    <button type="submit" class="px-6 py-2 bg-teal-700 text-white rounded-lg text-sm font-semibold hover:bg-teal-800">
        {{ $button ?? 'Save Product' }}
    </button>
</div>

@push('styles')
    <style>
        .input-field {
            @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600 focus:border-transparent;
        }
    </style>
@endpush

