@extends('layouts.admin')

@section('title', 'Job Applications')

@section('header-description', 'Review and manage job applications.')

@section('header-actions')
    <button class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50"
        onclick="window.location.reload()">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 12h15m-7.5 7.5v-15"/></svg>
        Refresh
    </button>
@endsection

@section('content')
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide text-xs">
                <tr>
                    <th class="px-6 py-3">Applicant</th>
                    <th class="px-6 py-3">Job Position</th>
                    <th class="px-6 py-3">Contact</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Applied</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($applications as $application)
                    <tr>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $application->name }}</p>
                            <p class="text-xs text-gray-400">{{ $application->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-900">{{ optional($application->jobPost)->title ?? 'Unknown Position' }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $application->phone }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusClasses = match($application->status) {
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'reviewed' => 'bg-blue-100 text-blue-800',
                                    'shortlisted' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'interview_scheduled' => 'bg-purple-100 text-purple-800',
                                    'interview_passed' => 'bg-emerald-100 text-emerald-800',
                                    'interview_failed' => 'bg-red-100 text-red-800',
                                    'hired' => 'bg-teal-100 text-teal-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusClasses }}">
                                {{ Str::headline(str_replace('_', ' ', $application->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $application->created_at->diffForHumans() }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.job-applications.show', $application) }}" class="text-blue-600 font-semibold text-sm">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-500">No applications yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $applications->links() }}
    </div>
@endsection


