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
        <aside class="hidden lg:flex lg:flex-col w-72 h-screen bg-gradient-to-b from-teal-900 via-teal-800 to-teal-900 text-white border-r border-teal-900/40 shadow-2xl lg:fixed lg:inset-y-0 lg:left-0">
            <div class="px-6 py-8 border-b border-white/10 flex items-center gap-3">
                @if(isset($logoPath) && $logoPath)
                    <img src="{{ asset('storage/'.$logoPath) }}" alt="Fortress Lenders" class="h-12 w-auto object-contain">
                @else
                    <div class="w-12 h-12 rounded-2xl bg-amber-400/20 text-xl font-bold flex items-center justify-center text-amber-300">FL</div>
                @endif
                <div>
                    <p class="text-xs uppercase tracking-[0.4em] text-white/70">Admin</p>
                    <p class="text-xl font-bold">Fortress Lenders</p>
                </div>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.dashboard') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}">Dashboard</a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.products.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}">Products</a>
                <a href="{{ route('admin.team-members.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.team-members.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}">Team</a>
                <a href="{{ route('admin.branches.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.branches.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}">Branches</a>
                <a href="{{ route('admin.home.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.home.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}">Home Page</a>
                <a href="{{ route('admin.about.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.about.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}">About Page</a>
                <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.contact-messages.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}">Contact Messages</a>
                <a href="{{ route('admin.contact.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.contact.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}">Contact Page</a>
                <a href="{{ route('admin.logo.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold transition border border-transparent {{ request()->routeIs('admin.logo.*') ? 'bg-amber-400/20 border-amber-200/40 text-white shadow-inner' : 'text-white/75 hover:bg-white/10' }}">Logo Settings</a>
            </nav>
            <div class="px-6 py-6 border-t border-white/10 space-y-3">
                <a href="{{ route('home') }}" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition text-sm font-semibold">View Website</a>
            </div>
        </aside>

        <div class="lg:pl-72 flex flex-col min-h-screen">
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
                        <a href="{{ route('admin.contact.edit') }}" class="block px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('admin.contact.*') ? 'bg-amber-400/20 text-white border border-amber-200/40' : 'text-white/80 hover:bg-white/10' }}">Contact Page</a>
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

    <script>
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
