@extends('layouts.admin')

@section('title', 'Standard Onboarding Forms')
@section('header-description', 'Upload the blank offer letter and contract templates once for all candidates.')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Offer Letter Template -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Offer Letter Template</h2>
                        <p class="text-xs text-slate-600">Blank agreement form used by all new staff.</p>
                    </div>
                </div>
                @if(($templates['offer_letter'] ?? null))
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700">
                        Updated {{ optional($templates['offer_letter']->updated_at)->diffForHumans() }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-700">
                        Not uploaded
                    </span>
                @endif
            </div>

            @if(($templates['offer_letter'] ?? null))
                <div class="mb-4 text-xs text-slate-600">
                    <p class="font-semibold">Current file:</p>
                    <p class="mt-0.5">{{ $templates['offer_letter']->original_filename }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.document-templates.store') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <input type="hidden" name="document_type" value="offer_letter">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Upload new template (PDF/DOC/DOCX)</label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx" required class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2">
                    @error('file')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg text-xs font-semibold hover:bg-teal-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16M9 12l3 3 3-3m-3-8v11"/>
                    </svg>
                    Save Template
                </button>
            </form>
        </div>

        <!-- Contract Template -->
        <div class="bg-white rounded-2xl shadow-md border border-slate-200/60 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-50 flex items-center justify-center text-purple-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Contract Template</h2>
                        <p class="text-xs text-slate-600">Standard contract form for all hires.</p>
                    </div>
                </div>
                @if(($templates['contract'] ?? null))
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700">
                        Updated {{ optional($templates['contract']->updated_at)->diffForHumans() }}
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-700">
                        Not uploaded
                    </span>
                @endif
            </div>

            @if(($templates['contract'] ?? null))
                <div class="mb-4 text-xs text-slate-600">
                    <p class="font-semibold">Current file:</p>
                    <p class="mt-0.5">{{ $templates['contract']->original_filename }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.document-templates.store') }}" enctype="multipart/form-data" class="space-y-3">
                @csrf
                <input type="hidden" name="document_type" value="contract">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Upload new template (PDF/DOC/DOCX)</label>
                    <input type="file" name="file" accept=".pdf,.doc,.docx" required class="w-full text-xs border border-slate-200 rounded-lg px-3 py-2">
                    @error('file')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-teal-600 text-white rounded-lg text-xs font-semibold hover:bg-teal-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16M9 12l3 3 3-3m-3-8v11"/>
                    </svg>
                    Save Template
                </button>
            </form>
        </div>
    </div>
@endsection

