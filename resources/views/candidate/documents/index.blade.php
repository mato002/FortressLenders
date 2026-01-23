@extends('layouts.candidate')

@section('title', 'Documents')
@section('header-description', 'Manage your documents and forms')

@section('content')
    <div class="space-y-6">
        <!-- Offer Letter Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Offer Letter</h2>
                <p class="text-sm text-gray-600 mt-1">Download, fill, and submit your offer letter</p>
            </div>
            <div class="p-6">
                @if($groupedDocuments['offer_letter'])
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">Offer Letter (Uploaded by HR)</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Uploaded: {{ $groupedDocuments['offer_letter']->created_at->format('M d, Y') }}
                                </p>
                                @if($groupedDocuments['offer_letter']->status === 'approved')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 mt-2">Approved</span>
                                @elseif($groupedDocuments['offer_letter']->status === 'rejected')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 mt-2">Rejected</span>
                                @endif
                            </div>
                            <a href="{{ route('candidate.documents.download', $groupedDocuments['offer_letter']) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                                Download
                            </a>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                        <p class="text-gray-600">No offer letter uploaded yet by HR</p>
                    </div>
                @endif

                @if($groupedDocuments['filled_offer_letter'])
                    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">Filled Offer Letter (Submitted)</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Submitted: {{ $groupedDocuments['filled_offer_letter']->submitted_at->format('M d, Y') }}
                                </p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold 
                                    @if($groupedDocuments['filled_offer_letter']->status === 'approved') bg-green-100 text-green-800
                                    @elseif($groupedDocuments['filled_offer_letter']->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif mt-2">
                                    {{ ucfirst($groupedDocuments['filled_offer_letter']->status) }}
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('candidate.documents.download', $groupedDocuments['filled_offer_letter']) }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors text-sm font-semibold">
                                    View
                                </a>
                                <form method="POST" action="{{ route('candidate.documents.destroy', $groupedDocuments['filled_offer_letter']) }}" class="delete-document-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseif($groupedDocuments['offer_letter'])
                    <div class="mt-4">
                        <form method="POST" action="{{ route('candidate.documents.upload') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="hidden" name="document_type" value="filled_offer_letter">
                            <div>
                                <label for="filled_offer_letter" class="block text-sm font-medium text-gray-700 mb-2">Upload Filled Offer Letter</label>
                                <input type="file" name="file" id="filled_offer_letter" accept=".pdf,.doc,.docx" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX (Max: 10MB)</p>
                            </div>
                            <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                                Upload & Submit
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Contract Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Contract Form</h2>
                <p class="text-sm text-gray-600 mt-1">Download, fill, and submit your contract</p>
            </div>
            <div class="p-6">
                @if($groupedDocuments['contract'])
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">Contract Form (Uploaded by HR)</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Uploaded: {{ $groupedDocuments['contract']->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <a href="{{ route('candidate.documents.download', $groupedDocuments['contract']) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-semibold">
                                Download
                            </a>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-lg text-center">
                        <p class="text-gray-600">No contract form uploaded yet by HR</p>
                    </div>
                @endif

                @if($groupedDocuments['filled_contract'])
                    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">Filled Contract (Submitted)</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Submitted: {{ $groupedDocuments['filled_contract']->submitted_at->format('M d, Y') }}
                                </p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold 
                                    @if($groupedDocuments['filled_contract']->status === 'approved') bg-green-100 text-green-800
                                    @elseif($groupedDocuments['filled_contract']->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif mt-2">
                                    {{ ucfirst($groupedDocuments['filled_contract']->status) }}
                                </span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('candidate.documents.download', $groupedDocuments['filled_contract']) }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors text-sm font-semibold">
                                    View
                                </a>
                                <form method="POST" action="{{ route('candidate.documents.destroy', $groupedDocuments['filled_contract']) }}" class="delete-document-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseif($groupedDocuments['contract'])
                    <div class="mt-4">
                        <form method="POST" action="{{ route('candidate.documents.upload') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <input type="hidden" name="document_type" value="filled_contract">
                            <div>
                                <label for="filled_contract" class="block text-sm font-medium text-gray-700 mb-2">Upload Filled Contract</label>
                                <input type="file" name="file" id="filled_contract" accept=".pdf,.doc,.docx" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <p class="text-xs text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX (Max: 10MB)</p>
                            </div>
                            <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold">
                                Upload & Submit
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <!-- Personal Documents Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-xl font-bold text-gray-900">Personal Documents</h2>
                <p class="text-sm text-gray-600 mt-1">Upload your ID, KRA, and SHA documents</p>
            </div>
            <div class="p-6 space-y-6">
                <!-- ID Document -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">ID Document</h3>
                    @if($groupedDocuments['id'])
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-sm text-gray-600">{{ $groupedDocuments['id']->original_filename }}</p>
                                <p class="text-xs text-gray-500">Uploaded: {{ $groupedDocuments['id']->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('candidate.documents.download', $groupedDocuments['id']) }}" class="px-3 py-1 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors text-sm font-semibold">
                                    View
                                </a>
                                <form method="POST" action="{{ route('candidate.documents.destroy', $groupedDocuments['id']) }}" class="delete-document-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('candidate.documents.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="document_type" value="id">
                        <div class="flex gap-2">
                            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" @if($groupedDocuments['id']) class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" @else required class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" @endif>
                            <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                                {{ $groupedDocuments['id'] ? 'Replace' : 'Upload' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- KRA Document -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">KRA Document</h3>
                    @if($groupedDocuments['kra'])
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-sm text-gray-600">{{ $groupedDocuments['kra']->original_filename }}</p>
                                <p class="text-xs text-gray-500">Uploaded: {{ $groupedDocuments['kra']->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('candidate.documents.download', $groupedDocuments['kra']) }}" class="px-3 py-1 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors text-sm font-semibold">
                                    View
                                </a>
                                <form method="POST" action="{{ route('candidate.documents.destroy', $groupedDocuments['kra']) }}" class="delete-document-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('candidate.documents.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="document_type" value="kra">
                        <div class="flex gap-2">
                            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" @if($groupedDocuments['kra']) class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" @else required class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" @endif>
                            <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                                {{ $groupedDocuments['kra'] ? 'Replace' : 'Upload' }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- SHA Document -->
                <div class="border border-gray-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">SHA Document</h3>
                    @if($groupedDocuments['sha'])
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <p class="text-sm text-gray-600">{{ $groupedDocuments['sha']->original_filename }}</p>
                                <p class="text-xs text-gray-500">Uploaded: {{ $groupedDocuments['sha']->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('candidate.documents.download', $groupedDocuments['sha']) }}" class="px-3 py-1 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors text-sm font-semibold">
                                    View
                                </a>
                                <form method="POST" action="{{ route('candidate.documents.destroy', $groupedDocuments['sha']) }}" class="delete-document-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('candidate.documents.upload') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="document_type" value="sha">
                        <div class="flex gap-2">
                            <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" @if($groupedDocuments['sha']) class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" @else required class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" @endif>
                            <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                                {{ $groupedDocuments['sha'] ? 'Replace' : 'Upload' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete document forms with SweetAlert
        document.querySelectorAll('.delete-document-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formElement = this;
                
                Swal.fire({
                    title: 'Delete Document?',
                    text: 'Are you sure you want to delete this document? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Deleting...',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => Swal.showLoading()
                        });
                        formElement.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
