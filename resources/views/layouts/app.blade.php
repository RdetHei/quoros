<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Mural') }} - Modern Reading Platform</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 transition-colors duration-300">
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav id="navbar" class="sticky top-0 z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-8">
                        <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                            <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent group-hover:from-indigo-500 group-hover:to-violet-500 transition-all">Mural</span>
                        </a>
                        
                        <div class="hidden md:flex items-center gap-6">
                            <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-indigo-600' : 'text-slate-500 dark:text-slate-400' }} hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Katalog</a>
                            <a href="{{ route('novels.updated') }}" class="text-sm font-medium {{ request()->routeIs('novels.updated') ? 'text-indigo-600' : 'text-slate-500 dark:text-slate-400' }} hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Updated</a>
                            <a href="{{ route('genres.index') }}" class="text-sm font-medium {{ request()->routeIs('genres.index') ? 'text-indigo-600' : 'text-slate-500 dark:text-slate-400' }} hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Genre</a>
                            <a href="{{ route('tags.index') }}" class="text-sm font-medium {{ request()->routeIs('tags.index') ? 'text-indigo-600' : 'text-slate-500 dark:text-slate-400' }} hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Tag</a>
                            @auth
                                <a href="{{ route('bookmarks.index') }}" class="text-sm font-medium {{ request()->routeIs('bookmarks.index') ? 'text-indigo-600' : 'text-slate-500 dark:text-slate-400' }} hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Bookmark</a>
                                <a href="{{ route('history.index') }}" class="text-sm font-medium {{ request()->routeIs('history.index') ? 'text-indigo-600' : 'text-slate-500 dark:text-slate-400' }} hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">History</a>
                            @endauth
                            <a href="{{ route('requests.index') }}" class="text-sm font-medium {{ request()->routeIs('requests.index') ? 'text-indigo-600' : 'text-slate-500 dark:text-slate-400' }} hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Request</a>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- Search Toggle (Mobile) -->
                        <button class="md:hidden p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        </button>

                        <!-- Dark Mode Toggle -->
                        <button id="theme-toggle" type="button" class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                        </button>

                        <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>

                        @guest
                            <div class="flex items-center gap-2">
                                <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Masuk</a>
                                <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm shadow-indigo-200 dark:shadow-none transition-all">Daftar</a>
                            </div>
                        @else
                            <div class="relative group">
                                <button class="flex items-center gap-2 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-sm">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="hidden sm:block text-sm font-medium">{{ Auth::user()->name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>
                                
                                <!-- Dropdown -->
                                <div class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-800 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform origin-top-right translate-y-2 group-hover:translate-y-0 z-50">
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
                    <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Mural</span>
                    <p class="text-slate-500 dark:text-slate-400 text-sm max-w-xs text-center md:text-left">Platform baca novel modern dengan pengalaman yang clean dan user-friendly.</p>
                </div>
                
                <div class="flex flex-wrap justify-center gap-8 text-sm font-medium text-slate-600 dark:text-slate-400">
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Tentang Kami</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Hubungi Kami</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Kebijakan Privasi</a>
                </div>

                <div class="text-slate-400 text-xs text-center md:text-right">
                    &copy; {{ date('Y') }} Mural. All rights reserved.
                </div>
            </div>
        </footer>
    </div>

    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {
            // toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

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
    </script>
</body>
</html>
