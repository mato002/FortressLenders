@php use Illuminate\Support\Str; @endphp
@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('header-description', 'View and respond to inquiries submitted through the website.')

@section('header-actions')
    <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        Refresh
    </a>
@endsection

@section('content')

    <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="mb-4">
        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, phone, subject, or message..." 
                       class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-xl hover:bg-teal-700 transition-colors font-semibold">
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.contact-messages.index', request()->only('status')) }}" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                        Clear
                    </a>
                @endif
            </div>
        </div>
    </form>

    <div class="mb-4 flex items-center gap-3 text-sm">
        <a href="{{ route('admin.contact-messages.index', request()->only('search')) }}" class="px-3 py-1 rounded-full border {{ request('status') ? 'border-gray-200 text-gray-500' : 'border-teal-600 text-teal-700' }}">
            All ({{ array_sum($statusCounts) }})
        </a>
        @foreach ($statusCounts as $key => $count)
            <a href="{{ route('admin.contact-messages.index', array_merge(request()->only('search'), ['status' => $key])) }}" class="px-3 py-1 rounded-full border {{ request('status') === $key ? 'border-teal-600 text-teal-700' : 'border-gray-200 text-gray-500' }}">
                {{ Str::headline($key) }} ({{ $count }})
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 uppercase tracking-wide text-xs">
                <tr>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Subject</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($messages as $message)
                    <tr>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $message->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $message->email }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $message->subject ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                @class([
                                    'bg-gray-200 text-gray-700' => $message->status === 'new',
                                    'bg-amber-100 text-amber-800' => $message->status === 'in_progress',
                                    'bg-green-100 text-green-800' => $message->status === 'handled',
                                ])">
                                {{ Str::headline($message->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $message->created_at->format('M d, Y g:i A') }}</td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.contact-messages.show', $message) }}" class="text-teal-700 font-semibold">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-500">No messages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $messages->links() }}
    </div>
@endsection

