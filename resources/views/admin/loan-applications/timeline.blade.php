@extends('layouts.admin')

@section('title', 'Loan Application Timeline')
@section('header-description', 'View detailed loan application progression and documents.')

@section('header-actions')
    <a href="{{ route('admin.loan-applications.index') }}" class="px-4 py-2 border border-slate-200 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Applications
    </a>
@endsection

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Timeline -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Application Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Application Details</h3>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 font-semibold">Full Name</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $application->full_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold">Email</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $application->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold">Loan Amount</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">KES {{ number_format($application->amount_requested, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-semibold">Loan Type</p>
                        <p class="text-sm font-medium text-gray-900 mt-1">{{ $application->loan_type }}</p>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Application Timeline</h3>
                
                <div class="space-y-6">
                    <!-- Submitted -->
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            @if($application->status != 'pending')
                                <div class="w-0.5 h-16 bg-green-200 mt-2"></div>
                            @endif
                        </div>
                        <div class="pt-2">
                            <p class="font-semibold text-gray-900">Application Submitted</p>
                            <p class="text-sm text-gray-600">{{ $application->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Under Review -->
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full {{ $application->status != 'pending' ? 'bg-green-100' : 'bg-yellow-100' }} flex items-center justify-center">
                                <svg class="w-5 h-5 {{ $application->status != 'pending' ? 'text-green-600' : 'text-yellow-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            @if(in_array($application->status, ['approved', 'rejected', 'disbursed']))
                                <div class="w-0.5 h-16 bg-green-200 mt-2"></div>
                            @endif
                        </div>
                        <div class="pt-2">
                            <p class="font-semibold text-gray-900">Under Review</p>
                            <p class="text-sm text-gray-600">
                                @if($application->sla_due_date)
                                    Due {{ $application->sla_due_date->format('M d, Y') }}
                                    @if($application->sla_breached)
                                        <span class="text-red-600 font-semibold">• SLA BREACHED</span>
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Approved/Rejected -->
                    @if($application->status != 'pending')
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full {{ $application->status == 'approved' || $application->status == 'disbursed' ? 'bg-green-100' : 'bg-red-100' }} flex items-center justify-center">
                                    @if($application->status == 'approved' || $application->status == 'disbursed')
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    @endif
                                </div>
                                @if($application->status == 'disbursed')
                                    <div class="w-0.5 h-16 bg-green-200 mt-2"></div>
                                @endif
                            </div>
                            <div class="pt-2">
                                <p class="font-semibold text-gray-900">
                                    {{ $application->status == 'approved' || $application->status == 'disbursed' ? 'Approved' : 'Rejected' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ ($application->status == 'approved' || $application->status == 'disbursed' ? $application->approved_at : $application->rejected_at)?->format('M d, Y H:i') }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Disbursed -->
                    @if($application->status == 'disbursed')
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <div class="pt-2">
                                <p class="font-semibold text-gray-900">Loan Disbursed</p>
                                <p class="text-sm text-gray-600">{{ $application->disbursed_at?->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Required Documents</h3>
                
                <div class="space-y-3">
                    @forelse($application->documents ?? [] as $doc)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $doc->document_type)) }}</p>
                                    <p class="text-xs text-gray-500">{{ $doc->file_name }}</p>
                                </div>
                            </div>
                            <span class="inline-flex px-2 py-1 rounded text-xs font-semibold {{ $doc->is_verified() ? 'bg-green-100 text-green-700' : ($doc->is_rejected() ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                                {{ ucfirst($doc->verification_status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No documents uploaded yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar: Status & Actions -->
        <div class="space-y-6">
            <!-- Current Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Current Status</h3>
                
                <div class="mb-6">
                    <span class="inline-flex px-4 py-2 rounded-full text-sm font-semibold {{ $application->status == 'approved' || $application->status == 'disbursed' ? 'bg-green-100 text-green-700' : ($application->status == 'rejected' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                    </span>
                </div>

                <!-- SLA Status -->
                @if($application->sla_due_date)
                    <div class="p-4 rounded-lg {{ $application->sla_breached ? 'bg-red-50 border border-red-200' : 'bg-blue-50 border border-blue-200' }}">
                        <p class="text-xs font-semibold {{ $application->sla_breached ? 'text-red-700' : 'text-blue-700' }}">
                            {{ $application->sla_breached ? 'SLA BREACHED' : 'SLA ON TRACK' }}
                        </p>
                        <p class="text-sm font-medium {{ $application->sla_breached ? 'text-red-900' : 'text-blue-900' }} mt-1">
                            Due {{ $application->sla_due_date->format('M d, Y') }}
                        </p>
                        <p class="text-xs {{ $application->sla_breached ? 'text-red-700' : 'text-blue-700' }} mt-1">
                            {{ $application->sla_due_date->diffForHumans() }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- Document Checklist -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Document Checklist</h3>
                
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="doc_id" class="w-4 h-4 rounded" {{ $application->documents?->where('document_type', 'id')->first()?->is_verified() ? 'checked' : '' }}>
                        <label for="doc_id" class="text-sm text-gray-700">National ID / Passport</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="doc_income" class="w-4 h-4 rounded" {{ $application->documents?->where('document_type', 'proof_of_income')->first()?->is_verified() ? 'checked' : '' }}>
                        <label for="doc_income" class="text-sm text-gray-700">Proof of Income</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="doc_bank" class="w-4 h-4 rounded" {{ $application->documents?->where('document_type', 'bank_statement')->first()?->is_verified() ? 'checked' : '' }}>
                        <label for="doc_bank" class="text-sm text-gray-700">Bank Statement</label>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="doc_ref" class="w-4 h-4 rounded" {{ $application->documents?->where('document_type', 'references')->first()?->is_verified() ? 'checked' : '' }}>
                        <label for="doc_ref" class="text-sm text-gray-700">Character References</label>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-xs text-gray-600">
                        <strong>{{ $application->documents?->where('verification_status', 'verified')->count() ?? 0 }} of {{ $application->documents?->count() ?? 0 }}</strong> documents verified
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
