<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=yes">
    <meta name="description" content="Fortress Lenders Ltd - Microfinance and Microcredit services in Kenya. Empowering communities through accessible financial solutions.">
    <meta name="keywords" content="microfinance, loans, Kenya, Nakuru, financial services, credit institution">
    
    <title>@yield('title', 'Fortress Lenders Ltd - The Force Of Possibilities')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white shadow-md transition-all duration-300" id="navbar">
        <div class="w-full px-4 sm:px-6 lg:px-12">
            <div class="flex justify-between items-center h-16 md:h-20">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        @if(isset($logoPath) && $logoPath)
                            <img src="{{ asset('storage/'.$logoPath) }}" alt="Fortress Lenders" class="h-9 sm:h-10 md:h-12 w-auto object-contain">
                        @else
                            <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center shadow-lg">
                                <span class="text-amber-400 font-bold text-lg sm:text-xl">F</span>
                            </div>
                            <span class="text-lg sm:text-xl md:text-2xl font-bold text-gray-900 hidden sm:inline">Fortress Lenders</span>
                            <span class="text-base font-bold text-gray-900 sm:hidden">Fortress</span>
                        @endif
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'text-teal-700 font-semibold' : 'text-gray-700 hover:text-teal-700' }} transition-colors">Home</a>
                    <a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'text-teal-700 font-semibold' : 'text-gray-700 hover:text-teal-700' }} transition-colors">About Us</a>
                    <a href="{{ route('products') }}" class="nav-link {{ request()->routeIs('products') ? 'text-teal-700 font-semibold' : 'text-gray-700 hover:text-teal-700' }} transition-colors">Products</a>
                    <a href="{{ route('careers.index') }}" class="nav-link {{ request()->routeIs('careers.*') ? 'text-teal-700 font-semibold' : 'text-gray-700 hover:text-teal-700' }} transition-colors">Careers</a>
                    <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'text-teal-700 font-semibold' : 'text-gray-700 hover:text-teal-700' }} transition-colors">Contact</a>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('loan.apply') }}" class="px-4 py-2 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold">Apply for Loan</a>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" id="mobile-menu-button" class="text-gray-700 hover:text-teal-700 focus:outline-none p-2 -mr-2" aria-label="Toggle menu">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t shadow-lg">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ route('home') }}" class="block py-3 text-gray-700 hover:text-teal-700 transition-colors font-medium border-b border-gray-100">Home</a>
                <a href="{{ route('about') }}" class="block py-3 text-gray-700 hover:text-teal-700 transition-colors font-medium border-b border-gray-100">About Us</a>
                <a href="{{ route('products') }}" class="block py-3 text-gray-700 hover:text-teal-700 transition-colors font-medium border-b border-gray-100">Products</a>
                <a href="{{ route('careers.index') }}" class="block py-3 text-gray-700 hover:text-teal-700 transition-colors font-medium border-b border-gray-100">Careers</a>
                <a href="{{ route('contact') }}" class="block py-3 text-gray-700 hover:text-teal-700 transition-colors font-medium border-b border-gray-100">Contact</a>
                <div class="pt-2">
                    <a href="{{ route('loan.apply') }}" class="block text-center px-4 py-3 bg-teal-800 text-white rounded-lg hover:bg-teal-900 transition-colors font-semibold">Apply for Loan</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16 md:pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300">
        <div class="w-full px-4 sm:px-6 lg:px-12 py-8 sm:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-teal-700 to-teal-800 rounded-lg flex items-center justify-center">
                            <span class="text-amber-400 font-bold text-xl">F</span>
                        </div>
                        <span class="text-xl font-bold text-white">Fortress Lenders</span>
                    </div>
                    <p class="text-sm mb-4">The Force Of Possibilities! Empowering communities through accessible financial solutions.</p>
                    <p class="text-sm mb-4">
                        <strong>Head Office:</strong><br>
                        Fortress Hse, Nakuru County<br>
                        Barnabas Muguga Opp. Epic ridge Academy
                    </p>
                    <!-- Social Media -->
                    <div class="mt-4">
                        <h3 class="text-white font-semibold mb-3 text-sm">Follow Us</h3>
                        <div class="flex space-x-3">
                            <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer" 
                               class="w-9 h-9 bg-gray-800 hover:bg-blue-600 rounded-full flex items-center justify-center transition-colors group">
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer" 
                               class="w-9 h-9 bg-gray-800 hover:bg-black rounded-full flex items-center justify-center transition-colors group">
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>
                            <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer" 
                               class="w-9 h-9 bg-gray-800 hover:bg-gradient-to-r hover:from-purple-500 hover:to-pink-500 rounded-full flex items-center justify-center transition-colors group">
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <a href="https://www.linkedin.com/company/" target="_blank" rel="noopener noreferrer" 
                               class="w-9 h-9 bg-gray-800 hover:bg-blue-700 rounded-full flex items-center justify-center transition-colors group">
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            <a href="https://www.youtube.com/" target="_blank" rel="noopener noreferrer" 
                               class="w-9 h-9 bg-gray-800 hover:bg-red-600 rounded-full flex items-center justify-center transition-colors group">
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="{{ route('about') }}" class="hover:text-white transition-colors">About Us</a></li>
                        <li><a href="{{ route('products') }}" class="hover:text-white transition-colors">Products</a></li>
                        <li><a href="{{ route('careers.index') }}" class="hover:text-white transition-colors">Careers</a></li>
                        <li><a href="{{ route('contact') }}" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-white font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2 text-sm">
                        <li>+254 743 838 312</li>
                        <li>+254 722 295 194</li>
                        <li><a href="mailto:info@fortresslenders.com" class="hover:text-white transition-colors">info@fortresslenders.com</a></li>
                        <li>P.O BOX: 7214- 20110<br>Nakuru Town, KENYA</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm">
                <p>&copy; {{ date('Y') }} Fortress Lenders Ltd. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Floating Action Buttons -->
    <div class="fixed bottom-4 right-4 md:bottom-8 md:right-8 z-40 flex flex-col gap-3 md:gap-4">
        <!-- WhatsApp Button -->
        <a href="https://wa.me/254743838312" target="_blank" rel="noopener noreferrer" 
           class="bg-green-500 text-white p-3 md:p-4 rounded-full shadow-lg hover:shadow-xl transition-all transform hover:scale-110">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
            </svg>
        </a>
        <!-- Contact Button -->
        <a href="{{ route('contact') }}" 
           class="bg-gradient-to-r from-teal-700 to-teal-800 text-white p-3 md:p-4 rounded-full shadow-lg hover:shadow-xl transition-all transform hover:scale-110">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
        </a>
    </div>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Navbar scroll effect
        let lastScroll = 0;
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 100) {
                navbar.classList.add('shadow-lg');
            } else {
                navbar.classList.remove('shadow-lg');
            }
            
            lastScroll = currentScroll;
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href !== '#' && href.startsWith('#')) {
                    e.preventDefault();
                    const target = document.querySelector(href);
                    if (target) {
                        const offsetTop = target.offsetTop - 80;
                        window.scrollTo({
                            top: offsetTop,
                            behavior: 'smooth'
                        });
                        // Close mobile menu if open
                        document.getElementById('mobile-menu').classList.add('hidden');
                    }
                }
            });
        });

        // Animate elements on scroll
        const animateOnScroll = () => {
            const elements = document.querySelectorAll('.animate-fade-in-up, .animate-fade-in');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };

        window.addEventListener('scroll', animateOnScroll);
        animateOnScroll(); // Run once on load
    </script>

    @stack('scripts')
</body>
</html>

