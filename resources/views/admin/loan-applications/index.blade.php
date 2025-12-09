@extends('layouts.admin')

@section('title', 'Loan Applications')
@section('header-description', 'Review and manage incoming loan applications.')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Loan Applications</h1>
                <p class="text-sm text-slate-500 mt-1">All loan requests submitted from the website.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                <div class="flex flex-wrap gap-2 text-xs sm:text-sm">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-slate-50 text-slate-700">
                        Pending: <strong>{{ $statusCounts['pending'] }}</strong>
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-50 text-blue-700">
                        In Review: <strong>{{ $statusCounts['in_review'] }}</strong>
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700">
                        Approved: <strong>{{ $statusCounts['approved'] }}</strong>
                    </span>
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-rose-50 text-rose-700">
                        Rejected: <strong>{{ $statusCounts['rejected'] }}</strong>
                    </span>
                </div>

                <form method="GET" class="flex items-center gap-2">
                    <select name="status" onchange="this.form.submit()"
                            class="text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                        <option value="">All statuses</option>
                        <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                        <option value="in_review" @selected(request('status') === 'in_review')>In review</option>
                        <option value="approved" @selected(request('status') === 'approved')>Approved</option>
                        <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                    </select>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                    <tr class="border-b border-slate-100 text-left text-slate-500">
                        <th class="py-2 pr-4 font-medium">Applicant</th>
                        <th class="py-2 px-4 font-medium">Contact</th>
                        <th class="py-2 px-4 font-medium">Loan Details</th>
                        <th class="py-2 px-4 font-medium">Status</th>
                        <th class="py-2 pl-4 font-medium text-right">Submitted</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                    @forelse($applications as $application)
                        <tr>
                            <td class="py-3 pr-4 align-top">
                                <a href="{{ route('admin.loan-applications.show', $application) }}" class="font-semibold text-slate-900 hover:text-teal-700">
                                    {{ $application->full_name }}
                                </a>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $application->client_type ? ucfirst($application->client_type) : 'N/A' }}
                                    @if($application->town)
                                        • {{ $application->town }}
                                    @endif
                                </p>
                            </td>
                            <td class="py-3 px-4 align-top">
                                <p class="text-sm text-slate-800">{{ $application->phone }}</p>
                                <p class="text-xs text-slate-500">{{ $application->email ?? 'No email' }}</p>
                            </td>
                            <td class="py-3 px-4 align-top">
                                <p class="text-sm text-slate-800">{{ $application->loan_type }}</p>
                                <p class="text-xs text-slate-500">
                                    KES {{ number_format($application->amount_requested, 0) }}
                                    • {{ $application->repayment_period }}
                                </p>
                            </td>
                            <td class="py-3 px-4 align-top">
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
                            <td class="py-3 pl-4 align-top text-right text-xs text-slate-500 whitespace-nowrap">
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

            <div class="mt-4">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
@endsection







