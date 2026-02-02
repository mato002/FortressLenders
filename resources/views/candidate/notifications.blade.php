@extends('layouts.candidate')

@section('title', 'Notifications')
@section('header-description', 'Stay updated on your applications')

@section('content')
<div class="w-full">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">All Notifications</h2>
                <p class="text-sm text-gray-600 mt-1">Stay updated on your job applications, tests, and interviews</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button class="px-4 py-2 text-sm font-semibold text-teal-600 bg-teal-50 hover:bg-teal-100 rounded-lg transition-colors" onclick="filterNotifications('all')">
                    All
                </button>
                <button class="px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" onclick="filterNotifications('unread')">
                    Unread
                </button>
                <button class="px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" onclick="filterNotifications('applications')">
                    Applications
                </button>
                <button class="px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" onclick="filterNotifications('tests')">
                    Tests
                </button>
                <button class="px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100 rounded-lg transition-colors" onclick="markAllAsRead()">
                    Mark All Read
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="space-y-4">
        <!-- Application Status Update -->
        <div class="notification-item bg-white rounded-2xl shadow border border-gray-100 p-6 hover:shadow-lg transition-shadow cursor-pointer" data-type="applications" data-read="false">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 mt-1">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-1">Application Status Updated</h3>
                    <p class="text-sm text-gray-600 mb-2">Your application for Senior Developer has passed the initial screening. You're eligible to take the aptitude test.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">2 hours ago</span>
                        <a href="#" class="text-sm font-semibold text-teal-600 hover:text-teal-700">View Application →</a>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Unread</span>
                </div>
            </div>
        </div>

        <!-- Test Reminder -->
        <div class="notification-item bg-white rounded-2xl shadow border border-gray-100 p-6 hover:shadow-lg transition-shadow cursor-pointer" data-type="tests" data-read="false">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 mt-1">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-amber-100">
                        <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-1">Aptitude Test Reminder</h3>
                    <p class="text-sm text-gray-600 mb-2">Don't forget! Your aptitude test for Product Manager expires in 3 days. Complete it to progress to the next stage.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">1 day ago</span>
                        <a href="#" class="text-sm font-semibold text-teal-600 hover:text-teal-700">Take Test →</a>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <span class="px-2 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded-full">Unread</span>
                </div>
            </div>
        </div>

        <!-- Interview Invitation -->
        <div class="notification-item bg-white rounded-2xl shadow border border-gray-100 p-6 hover:shadow-lg transition-shadow cursor-pointer" data-type="tests" data-read="true">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 mt-1">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-purple-100">
                        <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-1">Self Interview Invitation</h3>
                    <p class="text-sm text-gray-600 mb-2">Congratulations! You've passed the aptitude test for Marketing Manager. Record your self interview responses to continue.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">3 days ago</span>
                        <a href="#" class="text-sm font-semibold text-teal-600 hover:text-teal-700">Start Interview →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Request -->
        <div class="notification-item bg-white rounded-2xl shadow border border-gray-100 p-6 hover:shadow-lg transition-shadow cursor-pointer" data-type="applications" data-read="true">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 mt-1">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                        <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-1">Missing Documents Required</h3>
                    <p class="text-sm text-gray-600 mb-2">Your application for Financial Analyst requires an updated portfolio. Please upload it within 5 days.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">5 days ago</span>
                        <a href="#" class="text-sm font-semibold text-teal-600 hover:text-teal-700">Upload Documents →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Congratulations -->
        <div class="notification-item bg-white rounded-2xl shadow border border-gray-100 p-6 hover:shadow-lg transition-shadow cursor-pointer" data-type="applications" data-read="true">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 mt-1">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                        <svg class="h-5 w-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-1">Congratulations!</h3>
                    <p class="text-sm text-gray-600 mb-2">You've been selected for the final round interview for Backend Developer. Our HR team will contact you shortly with interview details.</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">1 week ago</span>
                        <a href="#" class="text-sm font-semibold text-teal-600 hover:text-teal-700">View Details →</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Recommendation -->
        <div class="notification-item bg-white rounded-2xl shadow border border-gray-100 p-6 hover:shadow-lg transition-shadow cursor-pointer" data-type="applications" data-read="true">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 mt-1">
                    <div class="flex items-center justify-center h-10 w-10 rounded-full bg-cyan-100">
                        <svg class="h-5 w-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-gray-900 mb-1">Job Recommendation</h3>
                    <p class="text-sm text-gray-600 mb-2">Based on your profile, we recommend the Full Stack Developer position. It matches 92% with your skills!</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">2 weeks ago</span>
                        <a href="#" class="text-sm font-semibold text-teal-600 hover:text-teal-700">View Job →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- Statistics Section -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Notifications</p>
                    <p class="text-2xl font-bold text-gray-900" id="total-count">6</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Unread</p>
                    <p class="text-2xl font-bold text-amber-600" id="unread-count">2</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Applications</p>
                    <p class="text-2xl font-bold text-teal-600" id="applications-count">4</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Tests & Interviews</p>
                    <p class="text-2xl font-bold text-purple-600" id="tests-count">2</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterNotifications(type) {
    const items = document.querySelectorAll('.notification-item');
    const buttons = document.querySelectorAll('[onclick^="filterNotifications"]');
    
    // Update button states
    buttons.forEach(btn => {
        if (btn.textContent.trim() === type.charAt(0).toUpperCase() + type.slice(1) || 
            (type === 'all' && btn.textContent.trim() === 'All')) {
            btn.classList.add('text-teal-600', 'bg-teal-50');
            btn.classList.remove('text-gray-600');
        } else {
            btn.classList.remove('text-teal-600', 'bg-teal-50');
            btn.classList.add('text-gray-600');
        }
    });
    
    if (type === 'all') {
        items.forEach(item => item.classList.remove('hidden'));
    } else if (type === 'unread') {
        items.forEach(item => {
            const isUnread = item.dataset.read === 'false';
            item.classList.toggle('hidden', !isUnread);
        });
    } else {
        items.forEach(item => {
            item.classList.toggle('hidden', item.dataset.type !== type);
        });
    }
    
    updateCounts();
}

function markAllAsRead() {
    const items = document.querySelectorAll('.notification-item[data-read="false"]');
    items.forEach(item => {
        item.dataset.read = 'true';
        const badge = item.querySelector('[class*="bg-"]');
        if (badge && badge.textContent.includes('Unread')) {
            badge.remove();
        }
    });
    updateCounts();
    Swal.fire({
        icon: 'success',
        title: 'All notifications marked as read',
        timer: 2000,
        showConfirmButton: false
    });
}

function updateCounts() {
    const items = document.querySelectorAll('.notification-item:not(.hidden)');
    const unreadItems = document.querySelectorAll('.notification-item[data-read="false"]:not(.hidden)');
    const applicationItems = document.querySelectorAll('.notification-item[data-type="applications"]:not(.hidden)');
    const testItems = document.querySelectorAll('.notification-item[data-type="tests"]:not(.hidden)');
    
    document.getElementById('total-count').textContent = items.length;
    document.getElementById('unread-count').textContent = unreadItems.length;
    document.getElementById('applications-count').textContent = applicationItems.length;
    document.getElementById('tests-count').textContent = testItems.length;
}

// Initialize counts on page load
document.addEventListener('DOMContentLoaded', function() {
    updateCounts();
});
</script>
@endpush
