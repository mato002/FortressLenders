@extends('layouts.admin')

@section('title', 'Loan Applications')
@section('header-description', 'Review and manage incoming loan applications.')

@section('header-actions')
    <button class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 border border-slate-200 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 whitespace-nowrap"
        onclick="window.location.reload()">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 12h15m-7.5 7.5v-15"/></svg>
        <span class="hidden sm:inline">Refresh</span>
    </button>
    <div class="hidden" id="bulk-actions-container">
        <div class="inline-flex items-center gap-2 flex-wrap">
            <button type="button" id="bulk-send-email-btn" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 whitespace-nowrap">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="hidden sm:inline">Send Emails (<span id="selected-count">0</span>)</span>
            </button>
            <button type="button" id="bulk-status-btn" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 whitespace-nowrap">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="hidden sm:inline">Change Status (<span id="selected-count-2">0</span>)</span>
            </button>
            <button type="button" id="bulk-delete-btn" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-red-600 hover:bg-red-700 whitespace-nowrap">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span class="hidden sm:inline">Delete (<span id="selected-count-3">0</span>)</span>
            </button>
        </div>
    </div>
    <a href="{{ route('admin.loan-applications.export', request()->query()) }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-green-600 hover:bg-green-700 whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
        </svg>
        <span class="hidden sm:inline">Export CSV</span>
    </a>
@endsection

@section('content')
    @php use Illuminate\Support\Str; @endphp
    
    @if (session('status'))
        <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <!-- Total Applications Banner -->
    <div class="mb-6 bg-gradient-to-r from-teal-600 to-teal-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold mb-1">
                    @if(request()->hasAny(['status', 'search', 'loan_type']))
                        {{ $filteredApplicationsCount }} Application{{ $filteredApplicationsCount !== 1 ? 's' : '' }} Found
                    @else
                        {{ $totalApplicationsCount }} Total Application{{ $totalApplicationsCount !== 1 ? 's' : '' }}
                    @endif
                </h2>
                <p class="text-teal-100 text-sm">
                    @if(request()->hasAny(['status', 'search', 'loan_type']))
                        Filtered from {{ $totalApplicationsCount }} total applications
                    @else
                        All loan applications in the system
                    @endif
                </p>
            </div>
            @if(request()->hasAny(['status', 'search', 'loan_type']))
                <a href="{{ route('admin.loan-applications.index') }}" class="px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg text-sm font-semibold transition">
                    Clear Filters
                </a>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <form method="GET" action="{{ route('admin.loan-applications.index') }}" class="mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, phone, loan type, or town..." 
                           class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending ({{ $statusCounts['pending'] }})</option>
                        <option value="in_review" {{ request('status') === 'in_review' ? 'selected' : '' }}>In Review ({{ $statusCounts['in_review'] }})</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved ({{ $statusCounts['approved'] }})</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected ({{ $statusCounts['rejected'] }})</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Loan Type</label>
                    <select name="loan_type" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent text-sm">
                        <option value="">All Loan Types</option>
                        @foreach($loanTypes as $loanType)
                            <option value="{{ $loanType }}" {{ request('loan_type') === $loanType ? 'selected' : '' }}>
                                {{ $loanType }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-4">
                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold text-sm">
                    Apply Filters
                </button>
                @if(request()->hasAny(['status', 'search', 'loan_type']))
                    <a href="{{ route('admin.loan-applications.index') }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors font-semibold text-sm">
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>

    <!-- Hidden forms for bulk actions -->
    <form id="bulk-email-form" method="POST" action="{{ route('admin.loan-applications.bulk-send-confirmation') }}" style="display: none;">
        @csrf
        <div id="bulk-email-inputs"></div>
    </form>
    <form id="bulk-status-form" method="POST" action="{{ route('admin.loan-applications.bulk-update-status') }}" style="display: none;">
        @csrf
        <div id="bulk-status-inputs"></div>
        <input type="hidden" name="status" id="bulk-status-value">
    </form>
    <form id="bulk-delete-form" method="POST" action="{{ route('admin.loan-applications.bulk-delete') }}" style="display: none;">
        @csrf
        <div id="bulk-delete-inputs"></div>
    </form>

    <div class="space-y-4 sm:space-y-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 p-3 sm:p-4 lg:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <div class="flex flex-wrap gap-1.5 sm:gap-2 text-xs sm:text-sm">
                    <span class="inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-full bg-slate-50 text-slate-700 whitespace-nowrap">
                        Pending: <strong>{{ $statusCounts['pending'] }}</strong>
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-full bg-blue-50 text-blue-700 whitespace-nowrap">
                        In Review: <strong>{{ $statusCounts['in_review'] }}</strong>
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 whitespace-nowrap">
                        Approved: <strong>{{ $statusCounts['approved'] }}</strong>
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 sm:px-3 py-1 rounded-full bg-rose-50 text-rose-700 whitespace-nowrap">
                        Rejected: <strong>{{ $statusCounts['rejected'] }}</strong>
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[640px]">
                    <thead>
                    <tr class="border-b border-slate-100 text-left text-slate-500">
                        <th class="py-2 px-3 sm:pr-4 font-medium text-xs sm:text-sm w-12">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        </th>
                        <th class="py-2 px-3 sm:pr-4 font-medium text-xs sm:text-sm">Applicant</th>
                        <th class="py-2 px-3 sm:px-4 font-medium text-xs sm:text-sm hidden sm:table-cell">Contact</th>
                        <th class="py-2 px-3 sm:px-4 font-medium text-xs sm:text-sm hidden md:table-cell">Loan Details</th>
                        <th class="py-2 px-3 sm:px-4 font-medium text-xs sm:text-sm">Status</th>
                        <th class="py-2 px-3 sm:pl-4 font-medium text-xs sm:text-sm text-right hidden sm:table-cell">Submitted</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($applications as $application)
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 px-3 sm:pr-4 align-top">
                                <input type="checkbox" name="application_ids[]" value="{{ $application->id }}" class="application-checkbox rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                            </td>
                            <td class="py-3 px-3 sm:pr-4 align-top">
                                <a href="{{ route('admin.loan-applications.show', $application) }}" class="font-semibold text-slate-900 hover:text-teal-700 text-sm sm:text-base">
                                    {{ $application->full_name }}
                                </a>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $application->client_type ? ucfirst($application->client_type) : 'N/A' }}
                                    @if($application->town)
                                        • {{ $application->town }}
                                    @endif
                                </p>
                            </td>
                            <td class="py-3 px-3 sm:px-4 align-top hidden sm:table-cell">
                                <p class="text-xs sm:text-sm text-slate-800">{{ $application->phone }}</p>
                                <p class="text-xs text-slate-500 truncate max-w-[150px]">{{ $application->email ?? 'No email' }}</p>
                            </td>
                            <td class="py-3 px-3 sm:px-4 align-top hidden md:table-cell">
                                <p class="text-xs sm:text-sm text-slate-800">{{ $application->loan_type }}</p>
                                <p class="text-xs text-slate-500">
                                    KES {{ number_format($application->amount_requested, 0) }}
                                    • {{ $application->repayment_period }}
                                </p>
                            </td>
                            <td class="py-3 px-3 sm:px-4 align-top">
                                @php
                                    $status = $application->status;
                                    $statusClasses = match($status) {
                                        'pending' => 'bg-amber-50 text-amber-700',
                                        'in_review' => 'bg-blue-50 text-blue-700',
                                        'approved' => 'bg-emerald-50 text-emerald-700',
                                        'rejected' => 'bg-rose-50 text-rose-700',
                                        default => 'bg-slate-50 text-slate-700',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClasses }}">
                                    {{ Str::headline($status) }}
                                </span>
                            </td>
                            <td class="py-3 px-3 sm:pl-4 align-top text-right text-xs text-slate-500 whitespace-nowrap hidden sm:table-cell">
                                {{ $application->created_at->format('M d, Y H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500 text-sm">
                                No loan applications have been submitted yet.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 sm:mt-6">
                {{ $applications->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('select-all');
            const applicationCheckboxes = document.querySelectorAll('.application-checkbox');
            const bulkActionsContainer = document.getElementById('bulk-actions-container');
            const selectedCountElements = [
                document.getElementById('selected-count'),
                document.getElementById('selected-count-2'),
                document.getElementById('selected-count-3')
            ];

            // Select all functionality
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    applicationCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    updateBulkActions();
                });
            }

            // Individual checkbox change
            applicationCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    updateSelectAllState();
                    updateBulkActions();
                });
            });

            function updateSelectAllState() {
                if (selectAllCheckbox) {
                    const checkedCount = document.querySelectorAll('.application-checkbox:checked').length;
                    selectAllCheckbox.checked = checkedCount === applicationCheckboxes.length && applicationCheckboxes.length > 0;
                    selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < applicationCheckboxes.length;
                }
            }

            function updateBulkActions() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                const count = checkedBoxes.length;

                selectedCountElements.forEach(el => {
                    if (el) el.textContent = count;
                });

                if (count > 0) {
                    bulkActionsContainer.classList.remove('hidden');
                } else {
                    bulkActionsContainer.classList.add('hidden');
                }
            }

            // Bulk send email
            document.getElementById('bulk-send-email-btn')?.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    alert('Please select at least one application.');
                    return;
                }

                if (!confirm(`Send confirmation emails to ${checkedBoxes.length} selected application(s)?`)) {
                    return;
                }

                const form = document.getElementById('bulk-email-form');
                const inputsContainer = document.getElementById('bulk-email-inputs');
                inputsContainer.innerHTML = '';

                checkedBoxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'application_ids[]';
                    input.value = checkbox.value;
                    inputsContainer.appendChild(input);
                });

                form.submit();
            });

            // Bulk update status
            document.getElementById('bulk-status-btn')?.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    alert('Please select at least one application.');
                    return;
                }

                const status = prompt('Enter new status:\n- pending\n- in_review\n- approved\n- rejected');
                if (!status || !['pending', 'in_review', 'approved', 'rejected'].includes(status)) {
                    return;
                }

                if (!confirm(`Update status to "${status}" for ${checkedBoxes.length} selected application(s)?`)) {
                    return;
                }

                const form = document.getElementById('bulk-status-form');
                const inputsContainer = document.getElementById('bulk-status-inputs');
                document.getElementById('bulk-status-value').value = status;
                inputsContainer.innerHTML = '';

                checkedBoxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'application_ids[]';
                    input.value = checkbox.value;
                    inputsContainer.appendChild(input);
                });

                form.submit();
            });

            // Bulk delete
            document.getElementById('bulk-delete-btn')?.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.application-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    alert('Please select at least one application.');
                    return;
                }

                if (!confirm(`Are you sure you want to delete ${checkedBoxes.length} selected application(s)? This action cannot be undone.`)) {
                    return;
                }

                const form = document.getElementById('bulk-delete-form');
                const inputsContainer = document.getElementById('bulk-delete-inputs');
                inputsContainer.innerHTML = '';

                checkedBoxes.forEach(checkbox => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'application_ids[]';
                    input.value = checkbox.value;
                    inputsContainer.appendChild(input);
                });

                form.submit();
            });
        });
    </script>
    @endpush
@endsection








