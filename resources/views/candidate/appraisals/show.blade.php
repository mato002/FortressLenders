@extends('layouts.candidate')

@section('title', $appraisal->title)
@section('header-description', 'View appraisal details')

@section('content')
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $appraisal->title }}</h2>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                            @if($appraisal->type === 'performance_review') bg-blue-100 text-blue-800
                            @elseif($appraisal->type === 'hr_communication') bg-teal-100 text-teal-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst(str_replace('_', ' ', $appraisal->type)) }}
                        </span>
                        @if($appraisal->type === 'warning' && $appraisal->severity)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                @if($appraisal->severity === 'high') bg-red-200 text-red-900
                                @elseif($appraisal->severity === 'medium') bg-orange-200 text-orange-900
                                @else bg-yellow-200 text-yellow-900
                                @endif">
                                {{ ucfirst($appraisal->severity) }} Severity
                            </span>
                        @endif
                        @if($appraisal->is_acknowledged)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                Acknowledged
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                Pending Acknowledgment
                            </span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('candidate.appraisals.index') }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors text-sm font-semibold">
                    Back to List
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="space-y-6">
                <!-- Details -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Details</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Created by:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $appraisal->createdBy->name ?? 'HR' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Date:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $appraisal->created_at->format('F d, Y \a\t g:i A') }}</span>
                        </div>
                        @if($appraisal->review_date)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Review Date:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $appraisal->review_date->format('F d, Y') }}</span>
                            </div>
                        @endif
                        @if($appraisal->is_acknowledged && $appraisal->acknowledged_at)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Acknowledged:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $appraisal->acknowledged_at->format('F d, Y \a\t g:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Content</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="prose max-w-none">
                            {!! nl2br(e($appraisal->content)) !!}
                        </div>
                    </div>
                </div>

                <!-- File Attachment -->
                @if($appraisal->file_path)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Attachment</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-600 mb-2">File attached: {{ basename($appraisal->file_path) }}</p>
                            <p class="text-xs text-gray-500">Please contact HR to access the attachment.</p>
                        </div>
                    </div>
                @endif

                <!-- Acknowledge Button -->
                @if(!$appraisal->is_acknowledged)
                    <div class="pt-4 border-t border-gray-200">
                        <form method="POST" action="{{ route('candidate.appraisals.acknowledge', $appraisal) }}" id="acknowledge-appraisal-form">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                                Acknowledge
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle acknowledge appraisal form with SweetAlert
        const acknowledgeForm = document.getElementById('acknowledge-appraisal-form');
        if (acknowledgeForm) {
            acknowledgeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formElement = this;
                
                Swal.fire({
                    title: 'Acknowledge Appraisal?',
                    text: 'Are you sure you want to acknowledge this appraisal?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#14b8a6',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, acknowledge',
                    cancelButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Processing...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => Swal.showLoading()
                        });
                        formElement.submit();
                    }
                });
            });
        }
    });
</script>
@endpush
