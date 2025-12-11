@extends('layouts.admin')

@section('title', 'Loan Applications')
@section('header-description', 'Review and manage incoming loan applications.')

@section('content')
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

                <form method="GET" class="flex items-center gap-2">
                    <select name="status" onchange="this.form.submit()"
                            class="text-xs sm:text-sm border-slate-200 rounded-lg sm:rounded-xl px-2 sm:px-3 py-1.5 sm:py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent w-full sm:w-auto">
                        <option value="">All statuses</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="in_review" @selected(request('status') === 'in_review')>In review</option>
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-[640px]">
                    <thead>
                    <tr class="border-b border-slate-100 text-left text-slate-500">
                        <th class="py-2 px-3 sm:pr-4 font-medium text-xs sm:text-sm">Applicant</th>
                        <th class="py-2 px-3 sm:px-4 font-medium text-xs sm:text-sm hidden sm:table-cell">Contact</th>
                        <th class="py-2 px-3 sm:px-4 font-medium text-xs sm:text-sm hidden md:table-cell">Loan Details</th>
                        <th class="py-2 px-3 sm:px-4 font-medium text-xs sm:text-sm">Status</th>
                        <th class="py-2 px-3 sm:pl-4 font-medium text-xs sm:text-sm text-right hidden sm:table-cell">Submitted</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($applications as $application)
                        <tr>
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
                            <td colspan="5" class="py-8 text-center text-slate-500 text-sm">
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
@endsection








