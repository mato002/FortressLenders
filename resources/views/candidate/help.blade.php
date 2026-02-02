@extends('layouts.candidate')

@section('title', 'Help & FAQ')
@section('header-description', 'Find answers and get support')

@section('content')
<div class="w-full">
    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 mb-6">
        <div class="relative">
            <input type="text" id="searchFAQ" placeholder="Search FAQs..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            <svg class="absolute right-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
    </div>

    <!-- FAQ Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow cursor-pointer" onclick="filterCategory('applications')">
            <div class="flex items-center gap-3 mb-2">
                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                    <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Applications</h3>
            </div>
            <p class="text-sm text-gray-600">Questions about job applications</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow cursor-pointer" onclick="filterCategory('tests')">
            <div class="flex items-center gap-3 mb-2">
                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-amber-100">
                    <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Tests & Interviews</h3>
            </div>
            <p class="text-sm text-gray-600">About aptitude and self interviews</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow cursor-pointer" onclick="filterCategory('profile')">
            <div class="flex items-center gap-3 mb-2">
                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-purple-100">
                    <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Profile</h3>
            </div>
            <p class="text-sm text-gray-600">Manage your profile & documents</p>
        </div>

        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow cursor-pointer" onclick="filterCategory('account')">
            <div class="flex items-center gap-3 mb-2">
                <div class="flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                    <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900">Account</h3>
            </div>
            <p class="text-sm text-gray-600">Account settings & security</p>
        </div>
    </div>

    <!-- FAQs -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Applications FAQ -->
        <div class="faq-category" data-category="applications">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">How do I apply for a job?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>To apply for a job, browse available positions on the Careers page, click "View Job Details", and then click the "Apply Now" button. Fill in any required information and submit your application.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">Can I withdraw my application?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>You can withdraw your application by visiting the application details page and clicking "Withdraw Application". This action cannot be undone.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">How will I know the status of my application?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>You can track your application status on your dashboard. We'll also send you email updates as your application progresses through each stage.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">How many applications can I submit?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>You can apply to as many positions as you're interested in. However, we recommend focusing on roles that match your skills and experience for better success rates.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">What happens after I submit my application?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>After submission, your application goes through AI sieving, then manual review. If you pass, you'll be invited to take an aptitude test, followed by a self interview if successful.</p>
                </div>
            </div>
        </div>

        <!-- Tests & Interviews FAQ -->
        <div class="faq-category hidden" data-category="tests">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">What is the aptitude test?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>The aptitude test is an online assessment designed to evaluate your skills and knowledge relevant to the position. It typically takes 30-60 minutes and includes multiple-choice questions.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">How long do I have to complete the aptitude test?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>You'll have a specific timeframe (usually 7-14 days) to complete the test after it becomes available. You can take it at any time during this period.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">What is the self interview?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>The self interview is a video-based assessment where you answer pre-recorded questions. Record your responses and submit them for evaluation.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">Can I retake the aptitude test if I fail?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>Unfortunately, you cannot retake the aptitude test for the same application. However, you can apply to other positions and take their respective tests.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">What happens if I don't complete the test on time?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>If you don't complete the test within the given timeframe, your application may be automatically rejected. We recommend completing tests as soon as they become available.</p>
                </div>
            </div>
        </div>

        <!-- Profile FAQ -->
        <div class="faq-category hidden" data-category="profile">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">How do I update my profile?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>Click on "Bio Data" in your dashboard to update your personal information. Keep it current to improve your chances with employers.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">What documents do I need to upload?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>Typically, you'll need to upload a resume/CV and a cover letter. Some positions may require additional documents. Check the job posting for specific requirements.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">Can I delete my profile?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>You can request account deletion in your account settings. This will remove all your data and is permanent.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">What file formats are accepted for documents?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>We accept PDF, DOC, DOCX, and image formats (JPG, PNG) for documents. Maximum file size is 10MB per document.</p>
                </div>
            </div>
        </div>

        <!-- Account FAQ -->
        <div class="faq-category hidden" data-category="account">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">How do I reset my password?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>Click on "Forgot Password" on the login page, enter your email address, and follow the instructions sent to your email.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">How do I verify my email address?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>Check your email inbox for a verification link. Click the link to verify your email address. If you don't see it, check your spam folder.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">How do I contact support?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>Email us at support@fortresslenders.com or use the contact form in your profile settings. We typically respond within 24 hours.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden faq-item">
                <button class="w-full px-6 py-4 text-left hover:bg-gray-50 transition-colors flex items-center justify-between" onclick="toggleFAQ(this)">
                    <h3 class="font-semibold text-gray-900">How do I change my password?</h3>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                </button>
                <div class="hidden px-6 pb-4 text-gray-600 border-t border-gray-200">
                    <p>Go to Profile Settings and use the "Update Password" section. You'll need to enter your current password and then set a new one.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Support -->
    <div class="bg-teal-50 border border-teal-200 rounded-2xl shadow-lg p-8 text-center mt-8">
        <h2 class="text-2xl font-bold text-teal-900 mb-2">Didn't find what you're looking for?</h2>
        <p class="text-teal-800 mb-6">Our support team is here to help.</p>
        <a href="mailto:support@fortresslenders.com" class="inline-flex items-center px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-semibold transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            Contact Support
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleFAQ(button) {
    const answer = button.nextElementSibling;
    const icon = button.querySelector('svg');
    
    answer.classList.toggle('hidden');
    icon.style.transform = answer.classList.contains('hidden') ? 'rotate(0)' : 'rotate(180deg)';
}

function filterCategory(category) {
    // Hide all categories
    document.querySelectorAll('.faq-category').forEach(cat => {
        cat.classList.add('hidden');
    });
    
    // Show selected category
    document.querySelector(`[data-category="${category}"]`).classList.remove('hidden');
    
    // Scroll to FAQs
    document.querySelector('.faq-category').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function searchFAQ() {
    const query = document.getElementById('searchFAQ').value.toLowerCase();
    
    if (query === '') {
        document.querySelectorAll('.faq-category').forEach(cat => {
            cat.classList.remove('hidden');
        });
        document.querySelectorAll('.faq-item').forEach(item => {
            item.classList.remove('hidden');
        });
        return;
    }
    
    document.querySelectorAll('.faq-category').forEach(cat => {
        cat.classList.remove('hidden');
    });
    
    document.querySelectorAll('.faq-item').forEach(item => {
        const title = item.querySelector('h3').textContent.toLowerCase();
        const answer = item.querySelector('div').textContent.toLowerCase();
        
        if (title.includes(query) || answer.includes(query)) {
            item.classList.remove('hidden');
        } else {
            item.classList.add('hidden');
        }
    });
}

document.getElementById('searchFAQ').addEventListener('keyup', searchFAQ);
</script>
@endpush
