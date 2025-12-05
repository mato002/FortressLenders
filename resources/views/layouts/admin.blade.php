<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin · Fortress Lenders')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-[#F0F9F8] text-teal-950 antialiased">
    <div class="min-h-screen bg-gradient-to-br from-white via-[#F0F9F8] to-[#E1F4F2]">
        <aside id="sidebar" class="hidden lg:flex lg:flex-col h-screen bg-gradient-to-b from-teal-900 via-teal-800 to-teal-900 text-white border-r border-teal-900/40 shadow-2xl lg:fixed lg:inset-y-0 lg:left-0 transition-all duration-300 ease-in-out" style="width: 288px;">
            <div class="px-4 py-6 border-b border-white/10 flex items-center gap-3 flex-shrink-0 relative">
                @if(isset($logoPath) && $logoPath)
                    <img src="{{ asset('storage/'.$logoPath) }}" alt="Fortress Lenders" class="h-10 w-auto object-contain sidebar-logo flex-shrink-0">
                @else
                    <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-lg font-bold flex items-center justify-center text-amber-300 sidebar-logo flex-shrink-0">FL</div>
                @endif
                <div class="sidebar-text flex-1 min-w-0">
                    <p class="text-xs uppercase tracking-[0.4em] text-white/70">Admin</p>
                    <p class="text-lg font-bold truncate">Fortress Lenders</p>
                </div>
                <button id="sidebar-toggle" class="p-2 rounded-lg hover:bg-white/10 transition-colors flex-shrink-0 z-10" onclick="toggleSidebarCollapse()" title="Toggle sidebar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto overflow-x-hidden sidebar-nav" style="scrollbar-width: thin; scrollbar-color: rgba(255,255,255,0.3) transparent;">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Dashboard">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span class="sidebar-text">Dashboard</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.products.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Products">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span class="sidebar-text">Products</span>
                </a>
                <a href="{{ route('admin.team-members.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.team-members.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Team">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="sidebar-text">Team</span>
                </a>
                <a href="{{ route('admin.branches.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.branches.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Branches">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span class="sidebar-text">Branches</span>
                </a>
                
                <!-- Pages Dropdown -->
                <div class="nav-dropdown">
                    <button type="button" onclick="toggleDropdown('pages-dropdown')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.home.*') || request()->routeIs('admin.about.*') || request()->routeIs('admin.contact.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Pages">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span class="sidebar-text flex-1 text-left">Pages</span>
                        <svg id="pages-dropdown-arrow" class="w-4 h-4 flex-shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="pages-dropdown" class="dropdown-menu hidden ml-4 mt-1 space-y-1">
                        <a href="{{ route('admin.home.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.home.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="Home Page">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span class="sidebar-text">Home Page</span>
                        </a>
                        <a href="{{ route('admin.about.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.about.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="About Page">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="sidebar-text">About Page</span>
                        </a>
                        <a href="{{ route('admin.contact.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.contact.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="Contact Page">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="sidebar-text">Contact Page</span>
                        </a>
                    </div>
                </div>
                
                <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.contact-messages.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Contact Messages">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span class="sidebar-text">Contact Messages</span>
                </a>
                <a href="{{ route('admin.loan-applications.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.loan-applications.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Loan Applications">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="sidebar-text">Loan Applications</span>
                </a>
                
                <!-- Careers Dropdown -->
                <div class="nav-dropdown">
                    <button type="button" onclick="toggleDropdown('careers-dropdown')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.jobs.*') || request()->routeIs('admin.job-applications.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Careers">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="sidebar-text flex-1 text-left">Careers</span>
                        <svg id="careers-dropdown-arrow" class="w-4 h-4 flex-shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="careers-dropdown" class="dropdown-menu hidden ml-4 mt-1 space-y-1">
                        <a href="{{ route('admin.jobs.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.jobs.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="Job Posts">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="sidebar-text">Job Posts</span>
                        </a>
                        <a href="{{ route('admin.job-applications.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.job-applications.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="Job Applications">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span class="sidebar-text">Job Applications</span>
                        </a>
                    </div>
                </div>
                
                <!-- Settings Dropdown -->
                <div class="nav-dropdown">
                    <button type="button" onclick="toggleDropdown('settings-dropdown')" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.logo.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}" title="Settings">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="sidebar-text flex-1 text-left">Settings</span>
                        <svg id="settings-dropdown-arrow" class="w-4 h-4 flex-shrink-0 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="settings-dropdown" class="dropdown-menu hidden ml-4 mt-1 space-y-1">
                        <a href="{{ route('admin.logo.edit') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm transition {{ request()->routeIs('admin.logo.*') ? 'bg-amber-400/20 text-white' : 'text-white/75 hover:bg-white/10' }}" title="Logo Settings">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="sidebar-text">Logo Settings</span>
                        </a>
                    </div>
                </div>
            </nav>
            <div class="px-6 py-6 border-t border-white/10 space-y-3 flex-shrink-0 sidebar-footer">
                <a href="{{ route('home') }}" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition text-sm font-semibold sidebar-link">
                    <span class="sidebar-text">View Website</span>
                </a>
            </div>
        </aside>

        <div id="main-content" class="flex flex-col min-h-screen" style="padding-left: 288px;">
            <div class="lg:hidden">
                <div id="mobile-backdrop" class="fixed inset-0 bg-black/50 hidden z-40" onclick="toggleSidebar()"></div>
                <aside id="mobile-menu" class="fixed inset-y-0 left-0 w-72 bg-gradient-to-b from-teal-900 via-teal-800 to-teal-900 text-white z-50 transform -translate-x-full transition-transform duration-300 shadow-2xl">
                    <div class="px-6 py-6 border-b border-white/10 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            @if(isset($logoPath) && $logoPath)
                                <img src="{{ asset('storage/'.$logoPath) }}" alt="Fortress Lenders" class="h-10 w-auto object-contain">
                            @else
                                <div class="w-10 h-10 rounded-2xl bg-amber-400/20 text-lg font-bold flex items-center justify-center text-amber-300">FL</div>
                            @endif
                            <div>
                                <p class="text-xs uppercase tracking-[0.4em] text-white/70">Admin</p>
                                <p class="text-lg font-bold">Fortress Lenders</p>
                            </div>
                        </div>
                        <button class="p-2 rounded-lg border border-white/20" onclick="toggleSidebar()">✕</button>
                    </div>
                    <nav class="px-4 py-6 space-y-2">
                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">Dashboard</a>
                        <a href="{{ route('admin.products.index') }}" class="block px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.products.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">Products</a>
                        <a href="{{ route('admin.team-members.index') }}" class="block px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.team-members.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">Team</a>
                        <a href="{{ route('admin.branches.index') }}" class="block px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.branches.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">Branches</a>
                        <a href="{{ route('admin.home.edit') }}" class="block px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.home.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">Home Page</a>
                        <a href="{{ route('admin.about.edit') }}" class="block px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.about.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">About Page</a>
                        <a href="{{ route('admin.contact-messages.index') }}" class="block px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.contact-messages.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">Contact Messages</a>
                        <a href="{{ route('admin.loan-applications.index') }}" class="block px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.loan-applications.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">Loan Applications</a>
                        <a href="{{ route('admin.contact.edit') }}" class="block px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.contact.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">Contact Page</a>
                        <a href="{{ route('admin.jobs.index') }}" class="block px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.jobs.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">Job Posts</a>
                        <a href="{{ route('admin.job-applications.index') }}" class="block px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.job-applications.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">Job Applications</a>
                        <a href="{{ route('admin.logo.edit') }}" class="block px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.logo.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">Logo Settings</a>
                    </nav>
                </aside>
            </div>

            <div class="flex-1 flex flex-col min-h-screen">
                <header class="bg-white/80 backdrop-blur border-b border-teal-100 shadow-sm sticky top-0 z-20">
                <div class="px-4 sm:px-6 lg:px-10 py-5 space-y-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                                <button class="lg:hidden p-2 rounded-lg border border-teal-200 text-teal-700" onclick="toggleSidebar()">☰</button>
                            <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-teal-500">Admin Panel</p>
                                    <h1 class="text-2xl font-bold text-teal-900">@yield('title', 'Dashboard')</h1>
                                    <p class="text-sm text-teal-600">@yield('header-description', "Monitor everything that's happening today.")</p>
                            </div>
                        </div>
                            <div class="hidden sm:flex items-center gap-4">
                                <a href="{{ route('admin.contact-messages.index') }}" class="relative p-2 rounded-full border border-teal-100 text-teal-500 hover:text-amber-500 bg-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.4-4.2A2 2 0 0 0 16.7 11H15V7a3 3 0 1 0-6 0v4H7.3a2 2 0 0 0-1.9 1.8L4 17h5"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 21h6"/>
                                    </svg>
                                    @if(($adminUnreadMessagesCount ?? 0) > 0)
                                        <span class="absolute -top-1 -right-1 bg-amber-500 text-white text-xs rounded-full px-1">
                                            {{ $adminUnreadMessagesCount > 9 ? '9+' : $adminUnreadMessagesCount }}
                                        </span>
                                    @endif
                                </a>
                                <div class="relative">
                                    <button id="profile-menu-trigger" class="flex items-center gap-3 bg-white border border-teal-100 rounded-2xl px-3 py-1 shadow-sm hover:border-amber-200 transition" onclick="toggleProfileMenu()" aria-haspopup="true" aria-expanded="false">
                                        <div class="text-right leading-tight hidden md:block">
                                            <p class="text-xs text-teal-500">Welcome back</p>
                                            <p class="text-sm font-semibold text-teal-800">{{ auth()->user()->name ?? 'Admin User' }}</p>
                                        </div>
                                        <span class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-600 to-emerald-500 text-white font-semibold flex items-center justify-center">
                                            {{ strtoupper(substr(auth()->user()->name ?? 'AU', 0, 2)) }}
                                        </span>
                                        <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 9l6 6 6-6" />
                                        </svg>
                                    </button>
                                    <div id="profile-menu" class="hidden absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-teal-50 overflow-hidden z-30">
                                        <div class="px-4 py-3 border-b border-teal-50">
                                            <p class="text-sm font-semibold text-teal-900">{{ auth()->user()->name ?? 'Admin User' }}</p>
                                            <p class="text-xs text-teal-500 truncate">{{ auth()->user()->email ?? 'admin@example.com' }}</p>
                                        </div>
                                        <a href="{{ route('admin.profile') }}" class="flex items-center gap-2 px-4 py-3 text-sm text-teal-800 hover:bg-teal-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5.121 17.804A4 4 0 0 1 4 15.172V5a2 2 0 0 1 2-2h6.172a4 4 0 0 1 2.829 1.172l4.828 4.828A4 4 0 0 1 20 11.828V19a2 2 0 0 1-2 2h-4"/>
                                            </svg>
                                            Profile
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" class="border-t border-teal-50">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-sm text-rose-600 hover:bg-rose-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12H3m0 0 4-4m-4 4 4 4m8-10h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4"/>
                                                </svg>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative flex-1 min-w-[220px] max-w-md">
                            <input type="text" placeholder="Search..." class="w-full pl-10 pr-4 py-2 rounded-2xl border border-slate-200 focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <svg class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m21 21-4.35-4.35M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z"/>
                            </svg>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @hasSection('header-actions')
                                @yield('header-actions')
                            @else
                                <button class="inline-flex items-center gap-2 px-4 py-2 border border-teal-200 rounded-xl text-sm font-semibold text-teal-700 hover:bg-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.5 12h15m-7.5 7.5v-15"/>
                                    </svg>
                                    Refresh
                                </button>
                                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-teal-900 bg-amber-400 hover:bg-amber-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    New Entry
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                </header>

                <main class="flex-1 px-4 sm:px-6 lg:px-10 py-8">
                    @if (session('status'))
                        <div class="mb-6 rounded-xl border border-teal-200 bg-white px-4 py-3 text-teal-900 shadow-sm">
                            {{ session('status') }}
                        </div>
                    @endif
                    @yield('content')
                </main>

                <footer class="bg-white/80 border-t border-teal-100 py-4">
                    <div class="px-4 sm:px-6 lg:px-10 text-sm text-teal-600 flex justify-between items-center flex-wrap gap-2">
                        <span>© {{ now()->year }} Fortress Lenders Ltd.</span>
                        <span>Need help? <a href="{{ route('contact') }}" class="text-amber-600 font-semibold">Contact support</a></span>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar for sidebar */
        .sidebar-nav::-webkit-scrollbar {
            width: 6px;
        }
        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Collapsed sidebar styles */
        #sidebar.collapsed {
            width: 80px !important;
        }
        #sidebar.collapsed .sidebar-text,
        #sidebar.collapsed .sidebar-link span:not(.icon-only) {
            opacity: 0;
            width: 0;
            overflow: hidden;
            display: none;
        }
        #sidebar.collapsed nav a {
            justify-content: center;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }
        #sidebar.collapsed #sidebar-toggle {
            position: absolute;
            top: 1rem;
            right: 0.5rem;
            margin: 0;
            z-index: 20;
        }
        #sidebar.collapsed #sidebar-toggle svg {
            transform: rotate(180deg);
        }
        #sidebar:not(.collapsed) #sidebar-toggle svg {
            transform: rotate(0deg);
        }
        #sidebar.collapsed > div:first-child {
            justify-content: center;
            padding: 1rem 0.5rem;
            position: relative;
        }
        #sidebar.collapsed .sidebar-logo {
            margin: 0 auto;
        }
        
        /* Dropdown styles */
        .dropdown-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, opacity 0.2s ease-out;
            opacity: 0;
        }
        .dropdown-menu.open {
            max-height: 500px;
            opacity: 1;
        }
        .dropdown-menu.hidden {
            display: none;
            max-height: 0;
            opacity: 0;
        }
        #sidebar.collapsed .dropdown-menu {
            display: none !important;
        }
        #sidebar.collapsed .nav-dropdown button {
            justify-content: center;
        }
        .dropdown-arrow-rotated {
            transform: rotate(180deg);
        }
        
        /* Main content padding */
        @media (min-width: 1024px) {
            #main-content {
                transition: padding-left 0.3s ease-in-out;
            }
        }
    </style>

    <script>
        // Sidebar collapse functionality
        function toggleSidebarCollapse() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const isCollapsed = sidebar.classList.contains('collapsed');
            
            if (isCollapsed) {
                // Expanding sidebar
                sidebar.classList.remove('collapsed');
                mainContent.style.paddingLeft = '288px';
                localStorage.setItem('sidebarCollapsed', 'false');
            } else {
                // Collapsing sidebar
                sidebar.classList.add('collapsed');
                mainContent.style.paddingLeft = '80px';
                localStorage.setItem('sidebarCollapsed', 'true');
            }
        }

        // Dropdown toggle functionality
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            const arrow = document.getElementById(dropdownId + '-arrow');
            
            if (!dropdown) return;
            
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                if (menu.id !== dropdownId) {
                    menu.classList.add('hidden');
                    menu.classList.remove('open');
                }
            });
            document.querySelectorAll('[id$="-arrow"]').forEach(arr => {
                if (arr.id !== dropdownId + '-arrow') {
                    arr.classList.remove('dropdown-arrow-rotated');
                }
            });
            
            // Toggle current dropdown
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                setTimeout(() => dropdown.classList.add('open'), 10);
                if (arrow) arrow.classList.add('dropdown-arrow-rotated');
            } else {
                dropdown.classList.remove('open');
                setTimeout(() => dropdown.classList.add('hidden'), 300);
                if (arrow) arrow.classList.remove('dropdown-arrow-rotated');
            }
        }

        // Auto-open dropdown if current page is in it
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            if (isCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.style.paddingLeft = '80px';
            } else {
                mainContent.style.paddingLeft = '288px';
            }
            
            // Auto-open relevant dropdowns
            @if(request()->routeIs('admin.home.*') || request()->routeIs('admin.about.*') || request()->routeIs('admin.contact.*'))
                if (!sidebar.classList.contains('collapsed')) {
                    toggleDropdown('pages-dropdown');
                }
            @endif
            @if(request()->routeIs('admin.jobs.*') || request()->routeIs('admin.job-applications.*'))
                if (!sidebar.classList.contains('collapsed')) {
                    toggleDropdown('careers-dropdown');
                }
            @endif
            @if(request()->routeIs('admin.logo.*'))
                if (!sidebar.classList.contains('collapsed')) {
                    toggleDropdown('settings-dropdown');
                }
            @endif
        });

        function toggleSidebar() {
            const menu = document.getElementById('mobile-menu');
            const backdrop = document.getElementById('mobile-backdrop');
            const isOpen = !menu.classList.contains('-translate-x-full');
            menu.classList.toggle('-translate-x-full', isOpen);
            backdrop.classList.toggle('hidden', isOpen);
        }

        function toggleProfileMenu() {
            const menu = document.getElementById('profile-menu');
            const trigger = document.getElementById('profile-menu-trigger');
            if (!menu || !trigger) return;

            const isOpen = !menu.classList.contains('hidden');
            menu.classList.toggle('hidden', isOpen);
            trigger.setAttribute('aria-expanded', (!isOpen).toString());
        }

        document.addEventListener('click', (event) => {
            const menu = document.getElementById('profile-menu');
            const trigger = document.getElementById('profile-menu-trigger');
            if (!menu || !trigger) return;

            if (trigger.contains(event.target)) {
                return;
            }

            if (!menu.contains(event.target)) {
                menu.classList.add('hidden');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
