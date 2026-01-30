@extends('layouts.admin')

@section('title', 'Email Templates')
@section('header-description', 'Manage email response templates for candidates and applicants.')

@section('header-actions')
    <a href="{{ route('admin.email-templates.create') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-white bg-teal-800 hover:bg-teal-900">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Template
    </a>
@endsection

@section('content')
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50">
                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Template Name</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Type</th>
                        <th class="text-left py-4 px-6 font-semibold text-gray-700">Subject</th>
                        <th class="text-center py-4 px-6 font-semibold text-gray-700">Status</th>
                        <th class="text-right py-4 px-6 font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($templates as $template)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <p class="font-medium text-gray-900">{{ $template->name }}</p>
                                <p class="text-sm text-gray-500">{{ $template->slug }}</p>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                                    {{ ucfirst(str_replace('_', ' ', $template->template_type)) }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-600">{{ Str::limit($template->subject, 50) }}</td>
                            <td class="py-4 px-6 text-center">
                                @if($template->is_active)
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Active</span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">Inactive</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.email-templates.preview', $template) }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">Preview</a>
                                    <a href="{{ route('admin.email-templates.edit', $template) }}" class="text-teal-600 hover:text-teal-700 text-sm font-medium">Edit</a>
                                    <form action="{{ route('admin.email-templates.destroy', $template) }}" method="POST" class="inline" onsubmit="return confirm('Delete this template?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $templates->links() }}
        </div>
    </div>
@endsection
