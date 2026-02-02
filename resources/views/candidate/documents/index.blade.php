@extends('layouts.candidate')

@section('title', 'Documents')
@section('header-description', 'Manage your documents and forms')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-blue-100 text-sm font-medium mb-1">Total Documents</p>
            <p class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</p>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-green-100 text-sm font-medium mb-1">Approved</p>
            <p class="text-3xl font-bold">{{ $stats['approved'] ?? 0 }}</p>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-amber-100 text-sm font-medium mb-1">Pending Review</p>
            <p class="text-3xl font-bold">{{ $stats['pending'] ?? 0 }}</p>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-red-100 text-sm font-medium mb-1">Rejected</p>
            <p class="text-3xl font-bold">{{ $stats['rejected'] ?? 0 }}</p>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-purple-100 text-sm font-medium mb-1">HR Uploaded</p>
            <p class="text-3xl font-bold">{{ $stats['hr_uploaded'] ?? 0 }}</p>
        </div>

        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="p-2 bg-white/20 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                </div>
            </div>
            <p class="text-teal-100 text-sm font-medium mb-1">My Uploads</p>
            <p class="text-3xl font-bold">{{ $stats['candidate_uploaded'] ?? 0 }}</p>
        </div>
    </div>

    <!-- Offer Letter Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-md">
        <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-blue-50 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Offer Letter</h2>
                            <p class="text-gray-600 mt-1">Download, fill, and submit your offer letter</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-8">
            @if($groupedDocuments['offer_letter'])
                <div class="mb-6 p-6 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">Offer Letter (Uploaded by HR)</h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    HR Document
                                </span>
                            </div>
                            <div class="flex items-center gap-6 text-sm text-gray-600 mt-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Uploaded: {{ $groupedDocuments['offer_letter']->created_at->format('M d, Y') }}</span>
                                </div>
                                @if($groupedDocuments['offer_letter']->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Approved</span>
                                @elseif($groupedDocuments['offer_letter']->status === 'rejected')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('candidate.documents.download', $groupedDocuments['offer_letter']) }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold shadow-sm hover:shadow-md">
                            Download
                        </a>
                    </div>
                </div>
            @else
                <div class="p-6 bg-gray-50 border border-gray-200 rounded-xl text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-600 font-medium">No offer letter uploaded yet by HR</p>
                </div>
            @endif

            @if($groupedDocuments['filled_offer_letter'])
                <div class="mt-6 p-6 bg-green-50 border border-green-200 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">Filled Offer Letter (Submitted)</h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                    @if($groupedDocuments['filled_offer_letter']->status === 'approved') bg-green-100 text-green-800
                                    @elseif($groupedDocuments['filled_offer_letter']->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($groupedDocuments['filled_offer_letter']->status) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-6 text-sm text-gray-600 mt-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Submitted: {{ $groupedDocuments['filled_offer_letter']->submitted_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('candidate.documents.download', $groupedDocuments['filled_offer_letter']) }}" class="px-5 py-3 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors font-semibold">
                                View
                            </a>
                            <form method="POST" action="{{ route('candidate.documents.destroy', $groupedDocuments['filled_offer_letter']) }}" class="delete-document-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-5 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @elseif($groupedDocuments['offer_letter'])
                <div class="mt-6 p-6 bg-white border border-gray-200 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Filled Offer Letter</h3>
                    <form method="POST" action="{{ route('candidate.documents.upload') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="hidden" name="document_type" value="filled_offer_letter">
                        <div>
                            <label for="filled_offer_letter" class="block text-sm font-medium text-gray-700 mb-2">Select File</label>
                            <input type="file" name="file" id="filled_offer_letter" accept=".pdf,.doc,.docx" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <p class="text-xs text-gray-500 mt-2">Accepted formats: PDF, DOC, DOCX (Max: 10MB)</p>
                        </div>
                        <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold shadow-sm hover:shadow-md">
                            Upload & Submit
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Contract Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-md">
        <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-purple-50 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Contract Form</h2>
                            <p class="text-gray-600 mt-1">Download, fill, and submit your contract</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-8">
            @if($groupedDocuments['contract'])
                <div class="mb-6 p-6 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">Contract Form (Uploaded by HR)</h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                    HR Document
                                </span>
                            </div>
                            <div class="flex items-center gap-6 text-sm text-gray-600 mt-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Uploaded: {{ $groupedDocuments['contract']->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('candidate.documents.download', $groupedDocuments['contract']) }}" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold shadow-sm hover:shadow-md">
                            Download
                        </a>
                    </div>
                </div>
            @else
                <div class="p-6 bg-gray-50 border border-gray-200 rounded-xl text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-600 font-medium">No contract form uploaded yet by HR</p>
                </div>
            @endif

            @if($groupedDocuments['filled_contract'])
                <div class="mt-6 p-6 bg-green-50 border border-green-200 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">Filled Contract (Submitted)</h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                    @if($groupedDocuments['filled_contract']->status === 'approved') bg-green-100 text-green-800
                                    @elseif($groupedDocuments['filled_contract']->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($groupedDocuments['filled_contract']->status) }}
                                </span>
                            </div>
                            <div class="flex items-center gap-6 text-sm text-gray-600 mt-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Submitted: {{ $groupedDocuments['filled_contract']->submitted_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('candidate.documents.download', $groupedDocuments['filled_contract']) }}" class="px-5 py-3 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors font-semibold">
                                View
                            </a>
                            <form method="POST" action="{{ route('candidate.documents.destroy', $groupedDocuments['filled_contract']) }}" class="delete-document-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-5 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-semibold">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @elseif($groupedDocuments['contract'])
                <div class="mt-6 p-6 bg-white border border-gray-200 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload Filled Contract</h3>
                    <form method="POST" action="{{ route('candidate.documents.upload') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="hidden" name="document_type" value="filled_contract">
                        <div>
                            <label for="filled_contract" class="block text-sm font-medium text-gray-700 mb-2">Select File</label>
                            <input type="file" name="file" id="filled_contract" accept=".pdf,.doc,.docx" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <p class="text-xs text-gray-500 mt-2">Accepted formats: PDF, DOC, DOCX (Max: 10MB)</p>
                        </div>
                        <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-semibold shadow-sm hover:shadow-md">
                            Upload & Submit
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Personal Documents Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-200 hover:shadow-md">
        <div class="px-8 py-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-teal-50 rounded-lg">
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Personal Documents</h2>
                            <p class="text-gray-600 mt-1">Upload your ID, KRA, and SHA documents</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-8 space-y-6">
            <!-- ID Document -->
            <div class="border border-gray-200 rounded-xl p-6 hover:border-teal-300 transition-colors">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                    </svg>
                    ID Document
                </h3>
                @if($groupedDocuments['id'])
                    <div class="flex items-center justify-between mb-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $groupedDocuments['id']->original_filename }}</p>
                            <p class="text-xs text-gray-500 mt-1">Uploaded: {{ $groupedDocuments['id']->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('candidate.documents.download', $groupedDocuments['id']) }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors text-sm font-semibold">
                                View
                            </a>
                            <form method="POST" action="{{ route('candidate.documents.destroy', $groupedDocuments['id']) }}" class="delete-document-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
                <form method="POST" action="{{ route('candidate.documents.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="document_type" value="id">
                    <div class="flex gap-3">
                        <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" @if($groupedDocuments['id']) class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" @else required class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" @endif>
                        <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                            {{ $groupedDocuments['id'] ? 'Replace' : 'Upload' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- KRA Document -->
            <div class="border border-gray-200 rounded-xl p-6 hover:border-teal-300 transition-colors">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    KRA Document
                </h3>
                @if($groupedDocuments['kra'])
                    <div class="flex items-center justify-between mb-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $groupedDocuments['kra']->original_filename }}</p>
                            <p class="text-xs text-gray-500 mt-1">Uploaded: {{ $groupedDocuments['kra']->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('candidate.documents.download', $groupedDocuments['kra']) }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors text-sm font-semibold">
                                View
                            </a>
                            <form method="POST" action="{{ route('candidate.documents.destroy', $groupedDocuments['kra']) }}" class="delete-document-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
                <form method="POST" action="{{ route('candidate.documents.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="document_type" value="kra">
                    <div class="flex gap-3">
                        <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" @if($groupedDocuments['kra']) class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" @else required class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" @endif>
                        <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
                            {{ $groupedDocuments['kra'] ? 'Replace' : 'Upload' }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- SHA Document -->
            <div class="border border-gray-200 rounded-xl p-6 hover:border-teal-300 transition-colors">
                <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    SHA Document
                </h3>
                @if($groupedDocuments['sha'])
                    <div class="flex items-center justify-between mb-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $groupedDocuments['sha']->original_filename }}</p>
                            <p class="text-xs text-gray-500 mt-1">Uploaded: {{ $groupedDocuments['sha']->created_at->format('M d, Y') }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('candidate.documents.download', $groupedDocuments['sha']) }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors text-sm font-semibold">
                                View
                            </a>
                            <form method="POST" action="{{ route('candidate.documents.destroy', $groupedDocuments['sha']) }}" class="delete-document-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-semibold">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
                <form method="POST" action="{{ route('candidate.documents.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="document_type" value="sha">
                    <div class="flex gap-3">
                        <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" @if($groupedDocuments['sha']) class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" @else required class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 text-sm" @endif>
                        <button type="submit" class="px-6 py-3 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-semibold">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
