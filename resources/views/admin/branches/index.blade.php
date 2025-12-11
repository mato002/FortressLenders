@extends('layouts.admin')

@section('title', 'Branches')
@section('header-description', 'Manage the branch cards shown on the contact page.')

@section('header-actions')
    <a href="{{ route('admin.branches.create') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 lg:px-5 py-1.5 sm:py-2 rounded-lg sm:rounded-xl text-xs sm:text-sm font-semibold text-white bg-teal-700 hover:bg-teal-800 whitespace-nowrap">
        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/></svg>
        <span class="hidden xs:inline">Add Branch</span>
        <span class="xs:hidden">Add</span>
    </a>
@endsection

@section('content')
    <div class="bg-white border border-gray-100 rounded-xl sm:rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-left text-sm min-w-[640px]">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide text-xs">
                <tr>
                    <th class="px-3 sm:px-6 py-3">Branch</th>
                    <th class="px-3 sm:px-6 py-3">Location</th>
                    <th class="px-3 sm:px-6 py-3 hidden sm:table-cell">Phones</th>
                    <th class="px-3 sm:px-6 py-3 hidden md:table-cell">Order</th>
                    <th class="px-3 sm:px-6 py-3">Status</th>
                    <th class="px-3 sm:px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($branches as $branch)
                    <tr>
                        <td class="px-3 sm:px-6 py-4">
                            <p class="font-semibold text-gray-900 text-sm sm:text-base">{{ $branch->name }}</p>
                            <p class="text-xs text-gray-400">Updated {{ $branch->updated_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm">
                            <p>{{ $branch->address_line1 }}</p>
                            @if($branch->address_line2)<p>{{ $branch->address_line2 }}</p>@endif
                            @if($branch->city)<p>{{ $branch->city }}</p>@endif
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm hidden sm:table-cell">
                            @if($branch->phone_primary)<p>{{ $branch->phone_primary }}</p>@endif
                            @if($branch->phone_secondary)<p>{{ $branch->phone_secondary }}</p>@endif
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-gray-600 text-xs sm:text-sm hidden md:table-cell">{{ $branch->display_order }}</td>
                        <td class="px-3 sm:px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $branch->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ $branch->is_active ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-3 sm:px-6 py-4 text-right space-x-2 sm:space-x-4">
                            <a href="{{ route('admin.branches.edit', $branch) }}" class="text-teal-700 font-semibold text-xs sm:text-sm">Edit</a>
                            <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" class="inline-block delete-form" data-id="{{ $branch->id }}" data-name="{{ $branch->name }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 font-semibold text-xs sm:text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-500">No branches yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-4 sm:mt-6">
        {{ $branches->links() }}
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
                const branchName = formElement.getAttribute('data-name') || 'this branch';
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to delete "${branchName}". This action cannot be undone!`,
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
                            text: 'Please wait while we delete the branch.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        formElement.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

