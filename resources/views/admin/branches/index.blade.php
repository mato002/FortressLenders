@extends('layouts.admin')

@section('title', 'Branches')
@section('header-description', 'Manage the branch cards shown on the contact page.')

@section('header-actions')
    <a href="{{ route('admin.branches.create') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-white bg-teal-700 hover:bg-teal-800">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/></svg>
        Add Branch
    </a>
@endsection

@section('content')
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide text-xs">
                <tr>
                    <th class="px-6 py-3">Branch</th>
                    <th class="px-6 py-3">Location</th>
                    <th class="px-6 py-3">Phones</th>
                    <th class="px-6 py-3">Order</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($branches as $branch)
                    <tr>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $branch->name }}</p>
                            <p class="text-xs text-gray-400">Updated {{ $branch->updated_at->diffForHumans() }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            <p>{{ $branch->address_line1 }}</p>
                            @if($branch->address_line2)<p>{{ $branch->address_line2 }}</p>@endif
                            @if($branch->city)<p>{{ $branch->city }}</p>@endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            @if($branch->phone_primary)<p>{{ $branch->phone_primary }}</p>@endif
                            @if($branch->phone_secondary)<p>{{ $branch->phone_secondary }}</p>@endif
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $branch->display_order }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $branch->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ $branch->is_active ? 'Visible' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-4">
                            <a href="{{ route('admin.branches.edit', $branch) }}" class="text-teal-700 font-semibold text-sm">Edit</a>
                            <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete this branch?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 font-semibold text-sm">Delete</button>
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

    <div class="mt-6">
        {{ $branches->links() }}
    </div>
@endsection

