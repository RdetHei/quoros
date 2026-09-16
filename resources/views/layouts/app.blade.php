<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Quoros') . ' - Where Story Lives')</title>
    <meta name="description" content="@yield('meta_description', 'Quoros adalah platform novel premium yang didedikasikan untuk menghadirkan cerita terbaik dengan pengalaman membaca yang nyaman.')">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name', 'Quoros'))">
    <meta property="og:description" content="@yield('meta_description', 'Quoros adalah platform novel premium.')">
    <meta property="og:image" content="{{ asset('storage/logo/quorosLogo.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', config('app.name', 'Quoros'))">
    <meta property="twitter:description" content="@yield('meta_description', 'Quoros adalah platform novel premium.')">
    <meta property="twitter:image" content="{{ asset('storage/logo/quorosLogo.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/quorosLogo.png') }}">
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <meta name="theme-color" content="#000000">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ asset('storage/logo/quorosLogo.png') }}">
    
    <!-- Preload Critical Assets -->
    @stack('preload')
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://res.cloudinary.com">
    
    <!-- Optimized Font Loading -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    </noscript>

    <!-- Styles & Scripts -->
    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-neutral-950 text-white selection:bg-white/20 selection:text-white">
    <div class="min-h-screen flex flex-col bg-neutral-950"
         x-data="{
            mobileMenuOpen: false,
            scrollY: 0,
            openMobileMenu() {
                this.scrollY = window.scrollY || document.documentElement.scrollTop || 0;
                document.body.style.position = 'fixed';
                document.body.style.top = `-${this.scrollY}px`;
                document.body.style.left = '0';
                document.body.style.right = '0';
                document.body.style.width = '100%';
                this.mobileMenuOpen = true;
            },
            closeMobileMenu() {
                this.mobileMenuOpen = false;
                const y = this.scrollY || 0;
                document.body.style.position = '';
                document.body.style.top = '';
                document.body.style.left = '';
                document.body.style.right = '';
                document.body.style.width = '';
                window.scrollTo(0, y);
            }
         }">
        <!-- Navbar -->
        <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-slate-950/95 backdrop-blur border-b border-white/5 h-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
                <div class="flex items-center justify-between h-full gap-2 sm:gap-3">
                    <!-- Left: Menu + Logo + Nav -->
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                        <!-- Mobile Menu Button -->
                        <button @click="mobileMenuOpen ? closeMobileMenu() : openMobileMenu()"
                                class="lg:hidden h-8 w-8 inline-flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors shrink-0"
                                aria-label="Open navigation"
                                :aria-expanded="mobileMenuOpen.toString()">
                            <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                            <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>

                        <a href="{{ url('/') }}" class="flex items-center gap-2 group shrink-0">
                            <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-7 w-auto group-hover:opacity-80 transition-opacity" fetchpriority="high">
                        </a>

                        <div class="hidden lg:flex items-center gap-0.5 ml-1.5">
                            @php
                                $navLinks = [];
                                $navLinks[] = ['route' => 'home', 'label' => 'Home', 'active' => request()->routeIs('home')];
                                $navLinks[] = ['route' => 'novels.updated', 'label' => 'Updated', 'active' => request()->routeIs('novels.updated')];
                                if (Auth::check()) {
                                    $navLinks[] = ['route' => 'bookmarks.index', 'label' => 'Bookmarks', 'active' => request()->routeIs('bookmarks.index')];
                                    $navLinks[] = ['route' => 'lists.index', 'label' => 'My Lists', 'active' => request()->routeIs('lists.*')];
                                    $navLinks[] = ['route' => 'history.index', 'label' => 'History', 'active' => request()->routeIs('history.index')];
                                }
                            @endphp
                            @foreach($navLinks as $link)
                                <a href="{{ route($link['route']) }}"
                                   class="h-8 inline-flex items-center px-2.5 rounded-lg text-[11px] font-semibold tracking-normal transition-colors whitespace-nowrap
                                          {{ $link['active']
                                              ? 'text-white bg-white/5'
                                              : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                                    {{ $link['label'] }}
                                </a>
                            @endforeach
                            @auth
                                @if(Auth::user()->role === 'user')
                                    <div class="w-px h-4 bg-white/10 mx-1.5"></div>
                                    <a href="{{ route('guides.index') }}"
                                       class="h-8 inline-flex items-center gap-1.5 px-2.5 bg-indigo-600/10 text-indigo-400 text-[10px] font-black uppercase tracking-[0.14em] rounded-lg hover:bg-indigo-600 hover:text-white transition-all border border-indigo-500/20 hover:border-indigo-500/40 group/write whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 group-hover/write:rotate-12 transition-transform shrink-0" style="width: 14px; height: 14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        Write
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <!-- Right: Search + Actions -->
                    <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                        @include('partials.live-search-partial', [
                            'id'          => 'desktop-search',
                            'placeholder' => 'Search novels...',
                            'classes'     => 'hidden md:block w-52 lg:w-60',
                        ])

                        <!-- Mobile Search Toggle -->
                        <div x-data="{ open: false }" class="md:hidden">
                            <button @click="open = !open"
                                    class="h-8 w-8 inline-flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors"
                                    aria-label="Search">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </button>
                            <div x-show="open"
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute left-0 right-0 top-full bg-slate-950/95 backdrop-blur border-b border-white/5 px-4 py-3 shadow-xl z-50">
                                @include('partials.live-search-partial', [ 'id' => 'mobile-search', 'placeholder' => 'Search novels...' ])
                            </div>
                        </div>

                        @auth
                            <div class="hidden sm:block w-px h-5 bg-white/10"></div>
                            @include('partials.notification-bell')
                        @endauth

                        @guest
                            <div class="flex items-center gap-1">
                                <a href="{{ route('login') }}"
                                   class="h-8 inline-flex items-center px-2.5 text-[11px] font-semibold text-slate-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors whitespace-nowrap">
                                    Login
                                </a>
                                <a href="{{ route('register') }}"
                                   class="h-8 inline-flex items-center px-3 text-[11px] font-bold bg-white text-slate-900 rounded-lg hover:bg-slate-100 shadow-sm transition-all whitespace-nowrap">
                                    Sign Up
                                </a>
                            </div>
                        @else
                            <!-- Profile Dropdown -->
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                        class="h-8 inline-flex items-center gap-1.5 pl-1 pr-1.5 rounded-lg hover:bg-white/5 transition-colors">
                                    <div class="w-6 h-6 rounded-full overflow-hidden bg-slate-800 ring-1 ring-white/10 flex items-center justify-center text-slate-300 font-bold text-[10px] shrink-0">
                                        @if(Auth::user()->profile_photo_url)
                                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                        @elseif(Auth::user()->profile_photo)
                                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                        @else
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <span class="hidden md:block text-[11px] font-semibold text-slate-200 max-w-[80px] truncate">{{ Auth::user()->name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-500 shrink-0 hidden sm:block" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>
                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95 translate-y-1"
                                     x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                     class="absolute right-0 top-full mt-2 w-52 bg-slate-900 rounded-2xl shadow-2xl shadow-black/40 border border-white/10 z-50 overflow-hidden">
                                    <div class="px-3 py-3 border-b border-white/5">
                                        <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 mb-1">Signed in as</p>
                                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    @if(Auth::user()->role === 'writer' || Auth::user()->role === 'admin')
                                        <div class="p-2">
                                            <a href="{{ route('dashboard') }}"
                                               class="flex items-center justify-center w-full h-10 px-3 bg-white text-slate-900 text-[10px] font-black uppercase tracking-[0.18em] rounded-xl hover:bg-slate-100 transition-all shadow-sm">
                                                Workspace
                                            </a>
                                        </div>
                                    @endif
                                    <div class="p-2 space-y-0.5">
                                        <a href="{{ route('profile.show', Auth::user()->username ?? Auth::user()->id) }}"
                                           class="h-9 flex items-center px-3 text-sm font-semibold text-slate-200 rounded-xl hover:bg-white/5 transition-all">
                                            My Profile
                                        </a>
                                        <a href="{{ route('settings') }}"
                                           class="h-9 flex items-center px-3 text-sm font-semibold text-slate-200 rounded-xl hover:bg-white/5 transition-all">
                                            Settings
                                        </a>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full h-9 flex items-center px-3 text-sm font-semibold text-rose-400 rounded-xl hover:bg-rose-500/10 transition-all text-left">
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Overlay -->
        <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-[60] lg:hidden">
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" @click="closeMobileMenu()"></div>
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 class="relative w-[82vw] max-w-[320px] h-full bg-slate-950 shadow-2xl flex flex-col border-r border-white/10">
                <div class="px-5 py-5 border-b border-white/5 flex items-center justify-between">
                    <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-8 w-auto">
                    <button @click="closeMobileMenu()" class="h-8 w-8 inline-flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors" aria-label="Close menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="p-3 space-y-1 flex-grow">
                    <a href="{{ route('home') }}"
                       class="h-10 flex items-center px-3 text-sm font-semibold text-slate-200 rounded-xl hover:bg-white/5 transition-colors {{ request()->routeIs('home') ? 'bg-white/5 text-white' : '' }}">
                        Home
                    </a>
                    <a href="{{ route('novels.updated') }}"
                       class="h-10 flex items-center px-3 text-sm font-semibold text-slate-200 rounded-xl hover:bg-white/5 transition-colors {{ request()->routeIs('novels.updated') ? 'bg-white/5 text-white' : '' }}">
                        Updated
                    </a>
                    @auth
                        <a href="{{ route('bookmarks.index') }}"
                           class="h-10 flex items-center px-3 text-sm font-semibold text-slate-200 rounded-xl hover:bg-white/5 transition-colors {{ request()->routeIs('bookmarks.index') ? 'bg-white/5 text-white' : '' }}">
                            Bookmarks
                        </a>
                        <a href="{{ route('lists.index') }}"
                           class="h-10 flex items-center px-3 text-sm font-semibold text-slate-200 rounded-xl hover:bg-white/5 transition-colors {{ request()->routeIs('lists.*') ? 'bg-white/5 text-white' : '' }}">
                            My Lists
                        </a>
                        <a href="{{ route('history.index') }}"
                           class="h-10 flex items-center px-3 text-sm font-semibold text-slate-200 rounded-xl hover:bg-white/5 transition-colors {{ request()->routeIs('history.index') ? 'bg-white/5 text-white' : '' }}">
                            History
                        </a>
                        @if(Auth::user()->role === 'user')
                            <div class="pt-2 mt-2 border-t border-white/5">
                                <a href="{{ route('guides.index') }}"
                                   class="h-10 flex items-center justify-center gap-2 px-3 bg-indigo-600/10 text-indigo-400 text-[11px] font-black uppercase tracking-[0.18em] rounded-xl hover:bg-indigo-600 hover:text-white transition-all border border-indigo-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    Write a Story
                                </a>
                            </div>
                        @endif
                    @endauth
                </div>
                <div class="p-3 border-t border-white/5 space-y-2">
                    @guest
                        <a href="{{ route('login') }}"
                           class="h-10 flex items-center justify-center w-full text-sm font-semibold text-slate-200 rounded-xl hover:bg-white/5 transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="h-10 flex items-center justify-center w-full text-sm font-bold bg-white text-slate-900 rounded-xl hover:bg-slate-100 shadow-sm transition-all">
                            Create Free Account
                        </a>
                    @else
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit"
                                    class="h-10 w-full flex items-center justify-center text-sm font-bold text-rose-400 rounded-xl hover:bg-rose-500/10 transition-all border border-rose-500/10">
                                Logout
                            </button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-grow pt-16 bg-neutral-950">
            <div class="{{ request()->routeIs('welcome', 'home')
                ? 'pt-4 pb-4'
                : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 pt-8' }}">
                @if(session('success'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="p-4 rounded-xl bg-indigo-900/20 border border-indigo-800 text-indigo-400 text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>

        <footer class="bg-neutral-900 border-t border-white/10 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                    <div class="md:col-span-2">
                        <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-10 w-auto mb-6 grayscale opacity-80" onerror="this.onerror=null; this.src='/error.png'">
                        <p class="text-sm text-slate-500 max-w-sm leading-relaxed">
                            Quoros adalah platform novel premium yang didedikasikan untuk menghadirkan cerita terbaik dari seluruh dunia dengan pengalaman membaca yang nyaman dan berkualitas.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-[0.2em] text-white mb-6">Navigation</h4>
                        <ul class="space-y-4">
                            <li><a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-white transition-colors">Home</a></li>
                            <li><a href="{{ route('novels.updated') }}" class="text-sm text-slate-500 hover:text-white transition-colors">Recently Updated</a></li>
                            <li><a href="{{ route('genres.index') }}" class="text-sm text-slate-500 hover:text-white transition-colors">All Genres</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-[0.2em] text-white mb-6">Community</h4>
                        <ul class="space-y-4">
                            <li><a href="{{ route('guides.index') }}" class="text-sm text-slate-500 hover:text-white transition-colors">Guides</a></li>
                            @guest
                                <li><a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-white transition-colors">Join Us</a></li>
                            @else
                                <li><a href="{{ route('dashboard') }}" class="text-sm text-slate-500 hover:text-white transition-colors">Writer Workspace</a></li>
                            @endguest
                        </ul>
                    </div>
                </div>
                <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">&copy; {{ date('Y') }} Quoros &mdash; Crafted for Readers</p>
                    <div class="flex items-center gap-6">
                        <a href="#" class="text-slate-500 hover:text-white transition-colors"><span class="sr-only">Twitter</span><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-1.015-2.174-1.648-3.594-1.648-2.72 0-4.925 2.205-4.925 4.925 0 .386.044.762.128 1.123-4.092-.205-7.719-2.165-10.148-5.144-.424.729-.666 1.576-.666 2.476 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.062c0 2.387 1.699 4.379 3.953 4.83-.414.113-.85.174-1.3.174-.317 0-.626-.03-.926-.086.626 1.956 2.444 3.379 4.6 3.419-1.685 1.321-3.808 2.108-6.115 2.108-.397 0-.79-.023-1.175-.068 2.179 1.397 4.768 2.212 7.548 2.212 9.057 0 13.996-7.502 13.996-13.996 0-.213-.005-.426-.014-.637 1.002-.72 1.815-1.558 2.43-2.527z"/></svg></a>
                        <a href="#" class="text-slate-500 hover:text-white transition-colors"><span class="sr-only">Discord</span><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.077 0 0 0 .084-.028 14.062 14.062 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.23 10.23 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/></svg></a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @auth @include('partials.report-modal') @endauth
    @include('partials.novel-hover-card')
    @stack('scripts')
</body>
</html>