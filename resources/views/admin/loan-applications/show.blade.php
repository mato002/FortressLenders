@extends('layouts.admin')

@section('title', 'Loan Application Details')
@section('header-description', 'Review the full details of this loan application.')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-semibold text-slate-900">Loan Application</h1>
                <p class="text-sm text-slate-500 mt-1">
                    Submitted {{ $loanApplication->created_at->format('M d, Y H:i') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.loan-applications.index') }}" class="px-3 py-2 text-sm rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50">
                    Back to list
                </a>
            </div>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main details -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Client Personal Information</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-slate-500">Full Name</dt>
                            <dd class="text-slate-900 font-medium">{{ $loanApplication->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Phone</dt>
                            <dd class="text-slate-900 font-medium">{{ $loanApplication->phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Email</dt>
                            <dd class="text-slate-900">{{ $loanApplication->email ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Date of Birth</dt>
                            <dd class="text-slate-900">
                                {{ optional($loanApplication->date_of_birth)->format('d M Y') ?? 'Not provided' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Town</dt>
                            <dd class="text-slate-900">{{ $loanApplication->town ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Residence</dt>
                            <dd class="text-slate-900">{{ $loanApplication->residence ?? 'Not provided' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Client Type</dt>
                            <dd class="text-slate-900">
                                {{ $loanApplication->client_type ? ucfirst($loanApplication->client_type) : 'Not specified' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Loan Information</h2>
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-slate-500">Loan Type</dt>
                            <dd class="text-slate-900 font-medium">{{ $loanApplication->loan_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Amount Requested</dt>
                            <dd class="text-slate-900 font-medium">KES {{ number_format($loanApplication->amount_requested, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Repayment Period</dt>
                            <dd class="text-slate-900">{{ $loanApplication->repayment_period }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Agreed to Terms</dt>
                            <dd class="text-slate-900">
                                {{ $loanApplication->agreed_to_terms ? 'Yes' : 'No' }}
                            </dd>
                        </div>
                    </dl>

                    <div class="mt-4">
                        <dt class="text-slate-500 text-sm mb-1">Purpose of Loan</dt>
                        <dd class="text-slate-900 text-sm whitespace-pre-line bg-slate-50 rounded-xl p-3 min-h-[60px]">
                            {{ $loanApplication->purpose ?: 'Not provided' }}
                        </dd>
                    </div>
                </div>
            </div>

            <!-- Status / admin notes -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6">
                    <h2 class="text-sm font-semibold text-slate-700 uppercase tracking-wide mb-4">Status</h2>

                    @php
                        $status = $loanApplication->status;
                        $statusClasses = match($status) {
                            'pending' => 'bg-amber-50 text-amber-700',
                            'in_review' => 'bg-blue-50 text-blue-700',
                            'approved' => 'bg-emerald-50 text-emerald-700',
                            'rejected' => 'bg-rose-50 text-rose-700',
                            default => 'bg-slate-50 text-slate-700',
                        };
                    @endphp

                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClasses }}">
                            {{ Str::headline($status) }}
                        </span>
                        @if($loanApplication->handled_at)
                            <p class="text-xs text-slate-500">
                                Updated {{ $loanApplication->handled_at->diffForHumans() }}
                            </p>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.loan-applications.update', $loanApplication) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div class="space-y-1">
                            <label for="status" class="text-xs font-medium text-slate-600">Update Status</label>
                            <select id="status" name="status"
                                    class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent">
                                <option value="pending" @selected($loanApplication->status === 'pending')>Pending</option>
                                <option value="in_review" @selected($loanApplication->status === 'in_review')>In review</option>
                                <option value="approved" @selected($loanApplication->status === 'approved')>Approved</option>
                                <option value="rejected" @selected($loanApplication->status === 'rejected')>Rejected</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label for="admin_notes" class="text-xs font-medium text-slate-600">Internal Notes</label>
                            <textarea id="admin_notes" name="admin_notes" rows="4"
                                      class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-600 focus:border-transparent"
                                      placeholder="Add any internal comments for your team">{{ old('admin_notes', $loanApplication->admin_notes) }}</textarea>
                        </div>

                        <button type="submit"
                                class="w-full inline-flex justify-center items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-teal-700 hover:bg-teal-800 focus:ring-2 focus:ring-offset-1 focus:ring-teal-600">
                            Save Changes
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-rose-100 p-4">
                    <form method="POST" action="{{ route('admin.loan-applications.destroy', $loanApplication) }}"
                          onsubmit="return confirm('Are you sure you want to delete this application? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full inline-flex justify-center items-center gap-2 px-3 py-2 rounded-xl text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200">
                            Delete Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection



