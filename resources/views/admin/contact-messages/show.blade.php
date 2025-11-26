@php use Illuminate\Support\Str; @endphp
@extends('layouts.admin')

@section('title', 'Message from '.$contactMessage->name)

@section('header-description', $contactMessage->created_at->format('M d, Y g:i A'))

@section('header-actions')
    <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50">
        ← Back to Messages
    </a>
@endsection

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Email</p>
                    <p class="font-semibold text-gray-900">{{ $contactMessage->email }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Phone</p>
                    <p class="font-semibold text-gray-900">{{ $contactMessage->phone ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Subject</p>
                    <p class="font-semibold text-gray-900">{{ $contactMessage->subject ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Status</p>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                        @class([
                            'bg-gray-200 text-gray-700' => $contactMessage->status === 'new',
                            'bg-amber-100 text-amber-800' => $contactMessage->status === 'in_progress',
                            'bg-green-100 text-green-800' => $contactMessage->status === 'handled',
                        ])">
                        {{ Str::headline($contactMessage->status) }}
                    </span>
                </div>
            </div>

            <div>
                <p class="text-gray-500 text-sm mb-2">Message</p>
                <div class="rounded-xl border border-gray-100 bg-gray-50 p-4 text-gray-800 whitespace-pre-line">
                    {{ $contactMessage->message }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
            <h2 class="text-lg font-semibold text-gray-900">Update Status</h2>
            <form method="POST" action="{{ route('admin.contact-messages.update', $contactMessage) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">
                            @foreach (['new' => 'New', 'in_progress' => 'In Progress', 'handled' => 'Handled'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('status', $contactMessage->status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Admin Notes</label>
                        <textarea name="admin_notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-600">{{ old('admin_notes', $contactMessage->admin_notes) }}</textarea>
                        @error('admin_notes')<p class="text-sm text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-teal-700 text-white rounded-lg text-sm font-semibold">Save Changes</button>
                </div>
            </form>

            <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST" onsubmit="return confirm('Delete this message?')" class="text-right">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-sm text-red-600 font-semibold">Delete message</button>
            </form>
        </div>
    </div>
@endsection

