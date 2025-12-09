@extends('layouts.admin')

@section('title', 'Manage Job Posts')

@section('header-description', 'Manage job postings and career opportunities.')

@section('header-actions')
    <button class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50"
        onclick="window.location.reload()">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 12h15m-7.5 7.5v-15"/></svg>
        Refresh
    </button>
    <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/></svg>
        New Job Post
    </a>
@endsection

@section('content')
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide text-xs">
                <tr>
                    <th class="px-6 py-3">Title</th>
                    <th class="px-6 py-3">Department</th>
                    <th class="px-6 py-3">Location</th>
                    <th class="px-6 py-3">Type</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Applications</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($jobs as $job)
                    <tr>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $job->title }}</p>
                            <p class="text-xs text-gray-400">Created {{ $job->created_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $job->department ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $job->location ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ ucfirst(str_replace('-', ' ', $job->employment_type)) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $job->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ $job->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $job->applications_count ?? 0 }}</td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('admin.jobs.show', $job) }}" class="text-blue-600 font-semibold text-sm">View</a>
                            <a href="{{ route('admin.jobs.edit', $job) }}" class="text-teal-700 font-semibold text-sm">Edit</a>
                            <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this job post?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 font-semibold text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500">No job posts yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $jobs->links() }}
    </div>
@endsection


