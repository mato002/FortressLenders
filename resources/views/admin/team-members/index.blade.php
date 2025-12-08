@extends('layouts.admin')

@section('title', 'Team Members')
@section('header-description', 'Manage the leadership and branch teams displayed on the website.')

@section('header-actions')
    <a href="{{ route('admin.team-members.create') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/></svg>
        Add Member
    </a>
@endsection

@section('content')
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide text-xs">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Contact</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Order</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($teamMembers as $member)
                    <tr>
                        <td class="px-6 py-4 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 border border-gray-200">
                                @if ($member->photo_path)
                                    <img src="{{ asset('storage/'.$member->photo_path) }}" alt="{{ $member->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-xs text-gray-500">{{ strtoupper(substr($member->name, 0, 2)) }}</div>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $member->name }}</p>
                                <p class="text-xs text-gray-400">Updated {{ $member->updated_at->diffForHumans() }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $member->role ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-600">
                            @if($member->email)<p>{{ $member->email }}</p>@endif
                            @if($member->phone)<p>{{ $member->phone }}</p>@endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $member->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ $member->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $member->display_order }}</td>
                        <td class="px-6 py-4 text-right space-x-3">
                            <a href="{{ route('admin.team-members.edit', $member) }}" class="text-teal-700 font-semibold text-sm">Edit</a>
                            <form action="{{ route('admin.team-members.destroy', $member) }}" method="POST" class="inline-block" onsubmit="return confirm('Remove this member?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 font-semibold text-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-500">No team members yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $teamMembers->links() }}
    </div>
@endsection







