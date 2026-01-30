@extends('layouts.admin')

@section('title', 'Create Email Template')
@section('header-description', 'Create a new email response template.')

@section('header-actions')
    <a href="{{ route('admin.email-templates.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back
    </a>
@endsection

@section('content')
    <form action="{{ route('admin.email-templates.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Template Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Template Name</label>
                    <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="e.g., Application Approved">
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Slug</label>
                    <input type="text" name="slug" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="e.g., application-approved">
                    @error('slug')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Template Type</label>
                <select name="template_type" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">-- Select Type --</option>
                    @foreach($templateTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('template_type')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Email Content</h3>
            <p class="text-sm text-gray-600 mb-4">Use {{variable}} syntax for placeholders. Example: {{candidate_name}}, {{job_title}}</p>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Subject Line</label>
                <input type="text" name="subject" required maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" placeholder="Email subject">
                @error('subject')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Body</label>
                <textarea name="body" required rows="12" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent font-mono text-sm" placeholder="Email body content..."></textarea>
                @error('body')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                <label class="ml-3 text-sm font-medium text-gray-700">Activate this template immediately</label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2 rounded-lg text-sm font-semibold text-white bg-teal-800 hover:bg-teal-900">
                Create Template
            </button>
            <a href="{{ route('admin.email-templates.index') }}" class="px-6 py-2 rounded-lg text-sm font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50">
                Cancel
            </a>
        </div>
    </form>
@endsection
