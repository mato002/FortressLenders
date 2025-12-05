@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header-description', "Welcome back! Here's a quick overview of the platform.")

@section('header-actions')
    <button class="inline-flex items-center gap-2 px-4 py-2 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 hover:bg-slate-50" onclick="window.location.reload()">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 12h15m-7.5 7.5v-15"/></svg>
        Refresh
    </button>
    <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700">
        Manage Products
    </a>
@endsection

@section('content')
    <div class="space-y-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <p class="text-sm text-gray-500">Total Products</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['products'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <p class="text-sm text-gray-500">Active Products</p>
                <p class="text-3xl font-bold text-teal-700 mt-2">{{ $stats['active_products'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <p class="text-sm text-gray-500">Unread Messages</p>
                <p class="text-3xl font-bold text-amber-600 mt-2">{{ $stats['unread_messages'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <p class="text-sm text-gray-500">Total Messages</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_messages'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <p class="text-sm text-gray-500">Pending Loan Applications</p>
                <p class="text-3xl font-bold text-amber-600 mt-2">{{ $stats['pending_applications'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <p class="text-sm text-gray-500">Total Loan Applications</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_applications'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <p class="text-sm text-gray-500">Total Branches</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['branches'] }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5 border border-gray-100">
                <p class="text-sm text-gray-500">Active Branches</p>
                <p class="text-3xl font-bold text-teal-700 mt-2">{{ $stats['active_branches'] }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Contact Messages</h2>
                    <a href="{{ route('admin.contact-messages.index') }}" class="text-sm text-teal-700 font-medium">View all</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($recentMessages as $message)
                        <a href="{{ route('admin.contact-messages.show', $message) }}" class="py-4 block hover:bg-gray-50 rounded-lg px-2 -mx-2 transition">
                            <p class="font-semibold text-gray-900">{{ $message->name }}</p>
                            <p class="text-sm text-gray-500">{{ $message->subject ?? 'No subject' }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $message->created_at->format('M d, Y g:i A') }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-gray-500 py-4">No messages yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Latest Products</h2>
                    <a href="{{ route('admin.products.index') }}" class="text-sm text-teal-700 font-medium">Manage</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($latestProducts as $product)
                        <div class="py-4">
                            <p class="font-semibold text-gray-900">{{ $product->title }}</p>
                            <p class="text-sm text-gray-500">{{ $product->category ?? 'General' }}</p>
                            <p class="text-xs text-gray-400 mt-1">Updated {{ $product->updated_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 py-4">No products yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Recent Loan Applications</h2>
                    <a href="{{ route('admin.loan-applications.index') }}" class="text-sm text-teal-700 font-medium">View all</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($recentApplications as $application)
                        <a href="{{ route('admin.loan-applications.show', $application) }}" class="py-4 block hover:bg-gray-50 rounded-lg px-2 -mx-2 transition">
                            <p class="font-semibold text-gray-900">{{ $application->full_name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $application->loan_type }} • KES {{ number_format($application->amount_requested, 0) }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">{{ $application->created_at->format('M d, Y g:i A') }}</p>
                        </a>
                    @empty
                        <p class="text-sm text-gray-500 py-4">No loan applications yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

