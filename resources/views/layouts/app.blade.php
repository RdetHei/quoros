<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Quoros') }} - Where Story Lives</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/quorosLogo.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
    </style>

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <link rel="stylesheet" href="{{ asset('build/assets/app-CiwESEF6.css') }}">
	<script type="module" src="{{ asset('build/assets/app-BFoTbDAf.js') }}"></script> -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || !('color-theme' in localStorage) || window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="font-sans antialiased bg-slate-950 text-slate-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav id="navbar" class="sticky top-0 z-50 bg-slate-900/90 backdrop-blur-md border-b border-slate-800"
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-4 md:gap-8 flex-1">
                        <!-- Mobile Menu Button -->
                        <button @click="mobileMenuOpen ? closeMobileMenu() : openMobileMenu()"
                                class="lg:hidden p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-colors"
                                aria-label="Buka navigasi"
                                :aria-expanded="mobileMenuOpen.toString()">
                            <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                            <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>

                        <a href="{{ url('/') }}" class="flex items-center gap-2 group shrink-0">
                            <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-8 md:h-10 w-auto group-hover:opacity-80 transition-opacity">
                        </a>
                        
                        <div class="hidden lg:flex items-center gap-6">
                            <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-indigo-600' : 'text-slate-500 dark:text-slate-400' }} hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Katalog</a>
                            <a href="{{ route('novels.updated') }}" class="text-sm font-medium {{ request()->routeIs('novels.updated') ? 'text-indigo-600' : 'text-slate-500 dark:text-slate-400' }} hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Updated</a>
                            <a href="{{ route('genres.index') }}" class="text-sm font-medium {{ request()->routeIs('genres.index') ? 'text-indigo-600' : 'text-slate-500 dark:text-slate-400' }} hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Genre</a>
                            <a href="{{ route('tags.index') }}" class="text-sm font-medium {{ request()->routeIs('tags.index') ? 'text-indigo-600' : 'text-slate-500 dark:text-slate-400' }} hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Tag</a>
                            @auth
                                <a href="{{ route('bookmarks.index') }}" class="text-sm font-medium {{ request()->routeIs('bookmarks.index') ? 'text-indigo-600' : 'text-slate-500 dark:text-slate-400' }} hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Bookmark</a>
                                <a href="{{ route('history.index') }}" class="text-sm font-medium {{ request()->routeIs('history.index') ? 'text-indigo-600' : 'text-slate-500 dark:text-slate-400' }} hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">History</a>
                            @endauth
                        </div>
                    </div>

                    <div class="flex items-center gap-2 md:gap-4">
                        <!-- Search Bar (Desktop - Live Search) -->
                        @include('partials.live-search-partial', [
                            'id'          => 'desktop-search',
                            'placeholder' => 'Cari novel...',
                            'classes'     => 'hidden md:block w-64 lg:w-80',
                        ])
                        <!-- Search Toggle (Mobile) -->
                        <div x-data="{ open: false }" class="md:hidden">
                            <button @click="open = !open" class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </button>

                
                            <!-- Mobile Search Overlay (Live Search) -->
                            <div x-show="open"
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute left-0 right-0 top-full bg-slate-900 border-b border-slate-800 p-4 shadow-xl z-50">
                                @include('partials.live-search-partial', [
                                    'id'          => 'mobile-search',
                                    'placeholder' => 'Cari novel...',
                                ])
                            </div>
                        </div>

                        <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>

                        @guest
                            <div class="flex items-center gap-1 md:gap-2">
                                <a href="{{ route('login') }}" class="px-3 md:px-4 py-2 text-xs md:text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Masuk</a>
                                <a href="{{ route('register') }}" class="px-3 md:px-4 py-2 text-xs md:text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm shadow-indigo-200 dark:shadow-none transition-all">Daftar</a>
                            </div>
                        @else
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                    <div class="w-8 h-8 rounded-full overflow-hidden bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-sm">
                                        @if(Auth::user()->profile_photo)
                                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <span class="hidden sm:block text-sm font-medium max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>

                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 z-50">
                                    <div class="p-2 border-b border-slate-100 dark:border-slate-800">
                                        <p class="px-3 py-1 text-xs font-semibold text-slate-400 uppercase tracking-wider">Menu</p>
                                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Dashboard</a>
                                        @if(Auth::user()->role === 'writer' || Auth::user()->role === 'admin')
                                            <a href="{{ route('writer.novels.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Novel Saya</a>
                                        @endif
                                        @if(Auth::user()->role === 'admin')
                                            <div class="mt-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                                                <p class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Admin</p>
                                                <a href="{{ route('admin.genres.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Kelola Genre</a>
                                                <a href="{{ route('admin.tags.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Kelola Tag</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-2">
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">Keluar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>

            <!-- Mobile Sidebar Overlay -->
            <div x-show="mobileMenuOpen"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @keydown.escape.window="closeMobileMenu()"
                 class="fixed inset-0 z-[60] lg:hidden" style="display: none;">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm"
                     @click="closeMobileMenu()"
                     @touchmove.prevent></div>
                
                <!-- Content -->
                <div x-show="mobileMenuOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="relative w-[84vw] max-w-[340px] h-[100dvh] bg-white dark:bg-slate-900 shadow-2xl flex flex-col rounded-r-3xl overflow-hidden border-r border-slate-100 dark:border-slate-800"
                     @click.stop>
                    <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-8 w-auto">
                            <div class="leading-tight">
                                <p class="text-xs font-black uppercase tracking-widest text-slate-400">Menu</p>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">Navigasi</p>
                            </div>
                        </div>
                        <button @click="closeMobileMenu()" class="p-2 text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition-colors" aria-label="Tutup navigasi">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto overscroll-contain touch-pan-y p-6 space-y-6" style="-webkit-overflow-scrolling: touch;">
                        @auth
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest truncate">{{ Auth::user()->role }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mt-4">
                                <a href="{{ route('dashboard') }}" class="px-4 py-2.5 text-center text-xs font-bold rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
                                    Dashboard
                                </a>
                                <a href="{{ route('profile.show', Auth::user()->username ?? Auth::user()->id) }}" class="px-4 py-2.5 text-center text-xs font-bold rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
                                    Profil
                                </a>
                            </div>
                        </div>
                        @endauth

                        <div class="space-y-1">
                            <p class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Navigasi Utama</p>
                            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                <span>Katalog</span>
                            </a>
                            <a href="{{ route('novels.updated') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('novels.updated') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Updated</span>
                            </a>
                            <a href="{{ route('genres.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('genres.index') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                <span>Genre</span>
                            </a>
                            <a href="{{ route('tags.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('tags.index') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" /></svg>
                                <span>Tag</span>
                            </a>
                        </div>

                        @auth
                        <div class="space-y-1">
                            <p class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Aktivitas</p>
                            <a href="{{ route('bookmarks.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('bookmarks.index') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                                <span>Bookmark</span>
                            </a>
                            <a href="{{ route('history.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('history.index') ? 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>History</span>
                            </a>
                        </div>
                        @endauth
                    </div>

                    <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                        @guest
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('login') }}" class="px-4 py-3 text-center text-sm font-bold text-slate-900 dark:text-white bg-slate-100 dark:bg-slate-800 rounded-xl">Masuk</a>
                                <a href="{{ route('register') }}" class="px-4 py-3 text-center text-sm font-bold text-white bg-indigo-600 rounded-xl">Daftar</a>
                            </div>
                        @else
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 text-center text-sm font-bold text-red-600 bg-red-50 dark:bg-red-900/20 rounded-xl transition-colors">Keluar dari Akun</button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow py-8 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-slate-950 border-t border-slate-200 dark:border-slate-800 py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="flex flex-col items-center md:items-start gap-4">
                    <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-10 w-auto">
                    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-xs text-center md:text-left">Platform baca novel modern dengan pengalaman yang clean dan user-friendly.</p>
                </div>
                
                <div class="flex flex-wrap justify-center gap-8 text-sm font-medium text-slate-600 dark:text-slate-400">
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Tentang Kami</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Hubungi Kami</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Kebijakan Privasi</a>
                </div>

                <div class="text-slate-400 text-xs text-center md:text-right">
                    &copy; {{ date('Y') }} Quoros. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (themeToggleDarkIcon && themeToggleLightIcon) {
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            // toggle icons inside button
            if (themeToggleDarkIcon && themeToggleLightIcon) {
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');
            }

            // if set via local storage previously
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }

            // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
        }
    
document.addEventListener('DOMContentLoaded', function () {
    const DEBOUNCE_MS  = 220;
    const MIN_CHARS    = 2;
    const API_ENDPOINT = '/api/live-search';

    function buildResultCard(novel) {
        const cover = novel.cover_image
            ? `<img src="${novel.cover_image}" alt="${escHtml(novel.title)}" class="w-full h-full object-cover">`
            : `<div class="w-full h-full bg-slate-800 flex items-center justify-center p-1">
                   <span class="text-[8px] text-slate-500 font-bold text-center leading-tight">${escHtml(novel.title)}</span>
               </div>`;

        const genres = (novel.genres || [])
            .map(g => `<span class="text-[8px] font-bold uppercase tracking-wider text-indigo-400 bg-indigo-900/40 px-1.5 py-0.5 rounded border border-indigo-800/50">${escHtml(g)}</span>`)
            .join('');

        const statusDot = { ongoing: 'bg-emerald-500', complete: 'bg-indigo-500', hiatus: 'bg-amber-500' }[novel.status] || 'bg-slate-500';

        return `
        <a href="${novel.url}"
           class="live-search-result flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800/70 transition-colors group outline-none focus:bg-slate-800/70 focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500/30"
           tabindex="0">
            <div class="w-10 h-[3.35rem] flex-shrink-0 rounded-lg overflow-hidden bg-slate-800 ring-1 ring-slate-700/50 relative">
                ${cover}
                <span class="absolute top-0.5 left-0.5 w-1.5 h-1.5 rounded-full ${statusDot} ring-1 ring-black/40"></span>
            </div>
            <div class="flex-grow min-w-0">
                <p class="text-sm font-bold text-slate-100 group-hover:text-indigo-400 transition-colors line-clamp-1">${escHtml(novel.title)}</p>
                <p class="text-[10px] text-slate-500 mb-1 line-clamp-1">${escHtml(novel.author)}</p>
                <div class="flex flex-wrap items-center gap-1">${genres}</div>
            </div>
            <div class="flex-shrink-0 flex items-center gap-1 text-amber-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-[10px] font-bold text-slate-300 tabular-nums">${novel.rating_avg}</span>
            </div>
        </a>`;
    }

    function escHtml(str) {
        return String(str ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function initLiveSearch(wrapper) {
        const id       = wrapper.dataset.componentId;
        const input    = document.getElementById(`${id}-input`);
        const dropdown = document.getElementById(`${id}-dropdown`);
        const loading  = document.getElementById(`${id}-loading`);
        const results  = document.getElementById(`${id}-results`);
        const footer   = document.getElementById(`${id}-footer`);
        const empty    = document.getElementById(`${id}-empty`);
        const seeAll   = document.getElementById(`${id}-see-all`);
        const emptyLink= document.getElementById(`${id}-empty-link`);
        const form     = wrapper.querySelector('.live-search-form');

        if (!input || !dropdown) return;

        let debounceTimer = null;
        let lastQuery     = '';
        let abortCtrl     = null;
        let isOpen        = false;

        function showDropdown() { dropdown.style.display = 'block'; isOpen = true; }
        function hideDropdown() { dropdown.style.display = 'none';  isOpen = false; }
        function setLoading(on) {
            loading.classList.toggle('hidden', !on);
            loading.classList.toggle('flex', on);
        }
        function updateSeeAllLink(query) {
            const url = `${form.action}?q=${encodeURIComponent(query)}`;
            if (seeAll)    seeAll.href    = url;
            if (emptyLink) emptyLink.href = url;
        }

        async function fetchResults(query) {
            if (abortCtrl) abortCtrl.abort();
            abortCtrl = new AbortController();
            setLoading(true);
            results.innerHTML = '';
            footer.classList.add('hidden');
            empty.classList.add('hidden');

            try {
                const res  = await fetch(`${API_ENDPOINT}?q=${encodeURIComponent(query)}`, {
                    signal: abortCtrl.signal,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                });
                const data = await res.json();
                setLoading(false);
                if (!data || data.length === 0) {
                    empty.classList.remove('hidden');
                } else {
                    results.innerHTML = data.map(buildResultCard).join('');
                    footer.classList.remove('hidden');
                }
            } catch (err) {
                if (err.name !== 'AbortError') {
                    setLoading(false);
                    empty.classList.remove('hidden');
                }
            }
        }

        input.addEventListener('input', function () {
            const q = this.value.trim();
            updateSeeAllLink(q);
            clearTimeout(debounceTimer);
            if (q.length < MIN_CHARS) { hideDropdown(); lastQuery = ''; return; }
            showDropdown();
            if (q === lastQuery) return;
            lastQuery = q;
            debounceTimer = setTimeout(() => fetchResults(q), DEBOUNCE_MS);
        });

        input.addEventListener('focus', function () {
            if (this.value.trim().length >= MIN_CHARS) showDropdown();
        });

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            window.location.href = `${this.action}?q=${encodeURIComponent(input.value.trim())}`;
        });

        input.addEventListener('keydown', function (e) {
            if (!isOpen) return;
            const items = Array.from(dropdown.querySelectorAll('.live-search-result'));
            const idx   = items.indexOf(document.activeElement);
            if (e.key === 'ArrowDown') { e.preventDefault(); (items[idx + 1] || items[0])?.focus(); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); if (items[idx - 1]) items[idx - 1].focus(); else input.focus(); }
            else if (e.key === 'Escape') { hideDropdown(); input.blur(); }
            else if (e.key === 'Enter')  { e.preventDefault(); form.dispatchEvent(new Event('submit', { cancelable: true })); }
        });

        dropdown.addEventListener('keydown', function (e) {
            const items = Array.from(dropdown.querySelectorAll('.live-search-result'));
            const idx   = items.indexOf(document.activeElement);
            if (e.key === 'ArrowDown') { e.preventDefault(); items[idx + 1]?.focus(); }
            else if (e.key === 'ArrowUp') { e.preventDefault(); if (idx <= 0) input.focus(); else items[idx - 1].focus(); }
            else if (e.key === 'Escape') { hideDropdown(); input.focus(); }
        });

        document.addEventListener('click', function (e) {
            if (!wrapper.contains(e.target)) hideDropdown();
        });
    }

    document.querySelectorAll('.live-search-wrapper').forEach(initLiveSearch);
});
</script>
@stack('scripts')
</body>
</html>
