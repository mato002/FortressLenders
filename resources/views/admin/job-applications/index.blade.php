@extends('layouts.admin')

@section('title', 'Job Applications')

@section('header-description', 'Review and manage job applications.')

@section('header-actions')
    <button class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-1.5 sm:py-2 border border-slate-200 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-slate-600 hover:bg-slate-50 whitespace-nowrap"
        onclick="window.location.reload()">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 12h15m-7.5 7.5v-15"/></svg>
        <span class="hidden sm:inline">Refresh</span>
    </button>
@endsection

@section('content')
    <div class="bg-white border border-gray-100 rounded-xl sm:rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left text-sm min-w-[640px]">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide text-xs">
                <tr>
                    <th class="px-3 sm:px-6 py-3">Applicant</th>
                    <th class="px-3 sm:px-6 py-3 hidden sm:table-cell">Job Position</th>
                    <th class="px-3 sm:px-6 py-3 hidden md:table-cell">Contact</th>
                    <th class="px-3 sm:px-6 py-3">Status</th>
                    <th class="px-3 sm:px-6 py-3 hidden sm:table-cell">Applied</th>
                    <th class="px-3 sm:px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($applications as $application)
                    <tr>
                        <td class="px-3 sm:px-6 py-4">
                            <p class="font-semibold text-gray-900 text-sm sm:text-base">{{ $application->name }}</p>
                            <p class="text-xs text-gray-400 truncate max-w-[150px] sm:max-w-none">{{ $application->email }}</p>
                        </td>
                        <td class="px-3 sm:px-6 py-4 hidden sm:table-cell">
                            <p class="text-gray-900 text-xs sm:text-sm truncate max-w-[200px]">{{ optional($application->jobPost)->title ?? 'Unknown Position' }}</p>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm hidden md:table-cell">{{ $application->phone }}</td>
                        <td class="px-3 sm:px-6 py-4">
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
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm hidden sm:table-cell">{{ $application->created_at->diffForHumans() }}</td>
                        <td class="px-3 sm:px-6 py-4 text-right space-x-2 sm:space-x-3">
                            <a href="{{ route('admin.job-applications.show', $application) }}" class="text-blue-600 font-semibold text-xs sm:text-sm">View</a>
                            <form action="{{ route('admin.job-applications.destroy', $application) }}" method="POST" class="inline-block delete-form" data-id="{{ $application->id }}" data-name="{{ $application->name }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 font-semibold text-xs sm:text-sm hover:text-red-700">Delete</button>
                            </form>
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
    </div>

    <div class="mt-4 sm:mt-6">
        {{ $applications->links() }}
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle delete forms with SweetAlert
        document.querySelectorAll('.delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formElement = this;
                const applicantName = formElement.getAttribute('data-name') || 'this application';
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete the application from ${applicantName}. This action cannot be undone!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait while we delete the application.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Submit the form
                        formElement.submit();
                    }
                });
            });
        });
    });
</script>
@endpush


