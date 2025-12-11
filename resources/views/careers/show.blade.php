@extends('layouts.website')

@section('title', $job->title . ' - Careers - Fortress Lenders Ltd')

@section('content')
    <!-- Hero Section -->
    <section
        class="relative text-white py-12 sm:py-16 md:py-20 overflow-hidden"
        style="background-image: linear-gradient(to bottom right, rgba(4, 120, 87, 0.9), rgba(6, 78, 59, 0.9)), url('https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80'); background-size: cover; background-position: center;"
    >
        <div class="absolute inset-0 bg-black opacity-10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4">{{ $job->title }}</h1>
                <div class="flex flex-wrap gap-4 text-teal-100">
                    @if($job->location)
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $job->location }}
                        </span>
                    @endif
                    @if($job->department)
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $job->department }}
                        </span>
                    @endif
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ ucfirst(str_replace('-', ' ', $job->employment_type)) }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Job Details Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white">
        <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
            <div class="max-w-4xl mx-auto">
                @if(session('error'))
                    <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="flex flex-col sm:flex-row gap-4 mb-8">
                    @php
                        $deadlinePassed = $job->application_deadline && $job->application_deadline->isPast();
                    @endphp
                    @if($deadlinePassed)
                        <button disabled class="flex-1 px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-semibold text-center">
                            Application Deadline Passed
                        </button>
                    @else
                        <a href="{{ route('careers.apply', $job->slug) }}" class="flex-1 px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold text-center">
                            I'm Interested - Apply Now
                        </a>
                    @endif
                    <div class="relative">
                        <button type="button" onclick="toggleShareMenu()" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-semibold flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                            Share
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="share-menu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 z-50 overflow-hidden">
                            <a href="#" onclick="shareOnFacebook(event)" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                                <span class="text-gray-700 font-medium">Facebook</span>
                            </a>
                            <a href="#" onclick="shareOnTwitter(event)" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                                <span class="text-gray-700 font-medium">Twitter</span>
                            </a>
                            <a href="#" onclick="shareOnLinkedIn(event)" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-blue-700" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                                <span class="text-gray-700 font-medium">LinkedIn</span>
                            </a>
                            <a href="#" onclick="shareOnWhatsApp(event)" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                <span class="text-gray-700 font-medium">WhatsApp</span>
                            </a>
                            <a href="#" onclick="shareViaEmail(event)" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-700 font-medium">Email</span>
                            </a>
                            <div class="border-t border-gray-200"></div>
                            <a href="#" onclick="copyLink(event)" class="flex items-center px-4 py-3 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <span class="text-gray-700 font-medium">Copy Link</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="prose max-w-none mb-8">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Job Description</h2>
                        <div class="text-gray-700">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>

                    @if($job->responsibilities)
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Key Responsibilities</h2>
                            <div class="text-gray-700">
                                {!! nl2br(e($job->responsibilities)) !!}
                            </div>
                        </div>
                    @endif

                    @if($job->requirements)
                        <div class="mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Requirements</h2>
                            <div class="text-gray-700">
                                {!! nl2br(e($job->requirements)) !!}
                            </div>
                        </div>
                    @endif

                    @if($job->experience_level)
                        <div class="mb-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Experience Level</h3>
                            <p class="text-gray-700">{{ $job->experience_level }}</p>
                        </div>
                    @endif

                    @if($job->application_deadline)
                        @php
                            $deadlinePassed = $job->application_deadline->isPast();
                        @endphp
                        <div class="mb-6 p-4 {{ $deadlinePassed ? 'bg-red-50 border-red-200' : 'bg-amber-50 border-amber-200' }} rounded-lg">
                            <p class="{{ $deadlinePassed ? 'text-red-800' : 'text-amber-800' }}">
                                <strong>Application Deadline:</strong> {{ $job->application_deadline->format('F d, Y') }}
                                @if($deadlinePassed)
                                    <span class="block mt-1 text-sm font-semibold">This deadline has passed. Applications are no longer being accepted.</span>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    @php
                        $deadlinePassed = $job->application_deadline && $job->application_deadline->isPast();
                    @endphp
                    @if($deadlinePassed)
                        <button disabled class="flex-1 px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-semibold text-center">
                            Application Deadline Passed
                        </button>
                    @else
                        <a href="{{ route('careers.apply', $job->slug) }}" class="flex-1 px-6 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold text-center">
                            Apply for This Position
                        </a>
                    @endif
                    <a href="{{ route('careers.index') }}" class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-semibold text-center">
                        Back to All Jobs
                    </a>
                </div>
            </div>
        </div>
    </section>

    @if($relatedJobs->count() > 0)
        <!-- Related Jobs Section -->
        <section class="py-12 sm:py-16 bg-gray-50">
            <div class="w-full px-4 sm:px-6 lg:px-8 xl:px-16 2xl:px-32">
                <div class="max-w-6xl mx-auto">
                    <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8">Related Positions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedJobs as $relatedJob)
                            <div class="bg-white border border-gray-200 rounded-xl shadow-lg p-6 hover:shadow-2xl transition-all">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $relatedJob->title }}</h3>
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                    {{ Str::limit(strip_tags($relatedJob->description), 100) }}
                                </p>
                                <a href="{{ route('careers.show', $relatedJob->slug) }}" class="text-teal-800 font-semibold hover:text-teal-900">
                                    View Details →
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    @push('scripts')
    <script>
        (function() {
            'use strict';
            
            const url = window.location.href;
            const title = {!! json_encode($job->title) !!};
            const text = 'Check out this job opportunity at Fortress Lenders: ' + title;
            
            function toggleShareMenu() {
                const menu = document.getElementById('share-menu');
                if (menu) {
                    menu.classList.toggle('hidden');
                }
            }
            
            function shareOnFacebook(e) {
                e.preventDefault();
                const shareUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url);
                window.open(shareUrl, '_blank', 'width=600,height=400');
                closeShareMenu();
            }
            
            function shareOnTwitter(e) {
                e.preventDefault();
                const shareUrl = 'https://twitter.com/intent/tweet?text=' + encodeURIComponent(text) + '&url=' + encodeURIComponent(url);
                window.open(shareUrl, '_blank', 'width=600,height=400');
                closeShareMenu();
            }
            
            function shareOnLinkedIn(e) {
                e.preventDefault();
                const shareUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(url);
                window.open(shareUrl, '_blank', 'width=600,height=400');
                closeShareMenu();
            }
            
            function shareOnWhatsApp(e) {
                e.preventDefault();
                const shareUrl = 'https://wa.me/?text=' + encodeURIComponent(text + ' ' + url);
                window.open(shareUrl, '_blank');
                closeShareMenu();
            }
            
            function shareViaEmail(e) {
                e.preventDefault();
                const subject = encodeURIComponent('Job Opportunity: ' + title);
                const body = encodeURIComponent(text + '\n\n' + url);
                const mailtoUrl = 'mailto:?subject=' + subject + '&body=' + body;
                window.location.href = mailtoUrl;
                closeShareMenu();
            }
            
            function copyLink(e) {
                e.preventDefault();
                copyToClipboard(url);
                closeShareMenu();
            }
            
            function closeShareMenu() {
                const menu = document.getElementById('share-menu');
                if (menu) {
                    menu.classList.add('hidden');
                }
            }
            
            function copyToClipboard(text) {
                // Try modern clipboard API first
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(text).then(() => {
                        showNotification('Link copied to clipboard!', 'success');
                    }).catch((err) => {
                        console.error('Clipboard API failed:', err);
                        // Fallback to old method
                        fallbackCopyToClipboard(text);
                    });
                } else {
                    // Fallback for older browsers
                    fallbackCopyToClipboard(text);
                }
            }
            
            function fallbackCopyToClipboard(text) {
                const textArea = document.createElement('textarea');
                textArea.value = text;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                textArea.style.opacity = '0';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                
                try {
                    const successful = document.execCommand('copy');
                    if (successful) {
                        showNotification('Link copied to clipboard!', 'success');
                    } else {
                        showNotification('Unable to copy. Please copy manually: ' + text, 'error');
                    }
                } catch (err) {
                    console.error('Fallback copy failed:', err);
                    showNotification('Unable to copy. Please copy manually: ' + text, 'error');
                }
                
                document.body.removeChild(textArea);
            }
            
            function showNotification(message, type = 'success') {
                // Remove any existing notifications
                const existing = document.querySelector('.share-notification');
                if (existing) {
                    existing.remove();
                }
                
                // Create a simple notification
                const notification = document.createElement('div');
                notification.className = 'share-notification';
                notification.style.cssText = 'position: fixed; top: 20px; right: 20px; background: ' + 
                    (type === 'error' ? '#ef4444' : '#10b981') + 
                    '; color: white; padding: 16px 24px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 10000; font-weight: 500; max-width: 300px; opacity: 1; transition: opacity 0.3s;';
                notification.textContent = message;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        if (notification.parentNode) {
                            notification.parentNode.removeChild(notification);
                        }
                    }, 300);
                }, 3000);
            }
            
            // Make functions available globally
            window.toggleShareMenu = toggleShareMenu;
            window.shareOnFacebook = shareOnFacebook;
            window.shareOnTwitter = shareOnTwitter;
            window.shareOnLinkedIn = shareOnLinkedIn;
            window.shareOnWhatsApp = shareOnWhatsApp;
            window.shareViaEmail = shareViaEmail;
            window.copyLink = copyLink;
            
            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                const shareButton = event.target.closest('button[onclick="toggleShareMenu()"]');
                const shareMenu = document.getElementById('share-menu');
                
                if (!shareButton && !shareMenu?.contains(event.target)) {
                    closeShareMenu();
                }
            });
        })();
    </script>
    @endpush
@endsection

