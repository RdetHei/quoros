@php
    use App\Enums\ReportStatus;
    use App\Models\Report;

    $adminBreadcrumbs = $adminBreadcrumbs ?? ['Admin'];
    $pendingReports = $pendingReports ?? Report::where('status', ReportStatus::Pending->value)->count();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Quoros') }} - Admin</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/logo/quorosLogo.png') }}">
    <link rel="manifest" href="{{ route('pwa.manifest') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>

    @stack('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Keep dark mode consistent across layouts
        if (localStorage.getItem('color-theme') === 'dark' || !('color-theme' in localStorage) || window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen antialiased">
<div class="h-screen flex overflow-hidden" x-data="{ sidebarOpen: false, profileOpen: false }">
    <!-- Mobile Overlay -->
    <div
        class="fixed inset-0 bg-slate-950/50 z-40 lg:hidden"
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
    ></div>

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-50 w-72 lg:static lg:translate-x-0 bg-slate-950 border-r border-white/5 transition-transform flex flex-col"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <div class="p-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 bg-white rounded-2xl flex items-center justify-center group-hover:rotate-6 transition-transform shadow-lg shadow-white/10">
                    <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros" class="h-6 w-auto" onerror="this.onerror=null; this.src='/error.png'">
                </div>
                <div>
                    <h2 class="text-xl font-black tracking-tighter text-white uppercase">QUOROS</h2>
                    <p class="text-[9px] font-black text-indigo-500 uppercase tracking-[0.2em] leading-none mt-0.5">Control Tower</p>
                </div>
            </a>
        </div>

        <nav class="px-6 pb-10 space-y-10 overflow-y-auto custom-scrollbar flex-grow">
            <div class="space-y-4">
                <p class="px-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500/80">Core Overview</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'bg-white text-slate-900 shadow-xl' => request()->routeIs('admin.dashboard'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.dashboard'),
                       ])>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7m-9 9h6a2 2 0 002-2v-6a2 2 0 00-2-2h-6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
                        </svg>
                        Main Console
                    </a>
                </div>
            </div>

            <div class="space-y-4">
                <p class="px-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500/80">Human Resources</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.users.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'bg-white text-slate-900 shadow-xl' => request()->routeIs('admin.users.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.users.*'),
                       ])>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Identity Matrix
                    </a>
                </div>
            </div>

            <div class="space-y-4">
                <p class="px-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500/80">Moderation Desk</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.requests.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'bg-white text-slate-900 shadow-xl' => request()->routeIs('admin.requests.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.requests.*'),
                       ])>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6l4 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Novel Queue
                    </a>
                    <a href="{{ route('admin.content-logs.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'bg-white text-slate-900 shadow-xl' => request()->routeIs('admin.content-logs.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.content-logs.*'),
                       ])>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 14l6-6m-6 6l6 6M4 6h16M4 18h16"/>
                        </svg>
                        Global Activity
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'bg-white text-slate-900 shadow-xl' => request()->routeIs('admin.reports.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.reports.*'),
                       ])>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Security Flags
                        @if($pendingReports > 0)
                            <span class="ml-auto inline-flex items-center rounded-lg bg-rose-500 text-white px-2 py-0.5 text-[9px] font-black">
                                {{ $pendingReports }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            <div class="space-y-4">
                <p class="px-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500/80">Curation Tools</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.genres.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'bg-white text-slate-900 shadow-xl' => request()->routeIs('admin.genres.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.genres.*'),
                       ])>Genres</a>
                    <a href="{{ route('admin.tags.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'bg-white text-slate-900 shadow-xl' => request()->routeIs('admin.tags.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.tags.*'),
                       ])>Tags</a>
                    <a href="{{ route('admin.carousel.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'bg-white text-slate-900 shadow-xl' => request()->routeIs('admin.carousel.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.carousel.*'),
                       ])>Spotlight Slider</a>
                </div>
            </div>

            <div class="space-y-4">
                <p class="px-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500/80">Site OS</p>
                <div class="space-y-1">
                    <a href="{{ route('settings.v2') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'bg-white text-slate-900 shadow-xl' => request()->routeIs('settings.v2'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('settings.v2'),
                       ])>Preferences</a>
                    <a href="{{ route('admin.maintenance') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'bg-white text-slate-900 shadow-xl' => request()->routeIs('admin.maintenance'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.maintenance'),
                       ])>Protocol Lock</a>
                </div>
            </div>
        </nav>

        <div class="p-6 mt-auto">
            <a href="{{ route('home') }}"
               class="flex items-center gap-3 px-4 py-4 rounded-[1.5rem] text-xs font-black text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-all group border border-white/5 bg-white/5">
                <div class="w-8 h-8 rounded-xl bg-slate-900 flex items-center justify-center border border-white/10 shrink-0 group-hover:bg-rose-500 group-hover:text-white transition-colors text-slate-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <span>EJECT SESSION</span>
            </a>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 min-w-0 w-full h-full overflow-y-auto overflow-x-hidden">
        <div class="h-full bg-white dark:bg-slate-900 border-l border-slate-200 dark:border-slate-800 overflow-y-auto custom-scrollbar">
            <!-- Top Bar -->
        <header class="sticky top-0 z-30 bg-white/80 dark:bg-slate-900/90 backdrop-blur border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3 gap-4">
                <div class="flex items-center gap-3 min-w-0">
                    <button
                        class="lg:hidden inline-flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200"
                        @click="sidebarOpen = true"
                        aria-label="Open admin sidebar"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div class="min-w-0">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 truncate">
                            {{ implode(' / ', $adminBreadcrumbs) }}
                        </p>
                        <h1 class="text-lg md:text-xl font-bold truncate">
                            {{ $adminTitle ?? 'Admin Dashboard' }}
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <form method="GET" action="{{ route('novels.search') }}" class="hidden md:block">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </span>
                            <input
                                type="search"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Admin search..."
                                class="w-72 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 pl-9 pr-3 py-2 text-sm text-slate-700 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                        </div>
                    </form>

                    <div class="relative" @click.away="profileOpen = false">
                        <button
                            @click="profileOpen = !profileOpen"
                            class="inline-flex items-center gap-2 h-10 px-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-100"
                        >
                            <span class="text-sm font-semibold truncate max-w-[140px]">{{ auth()->user()->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div
                            x-show="profileOpen"
                            x-transition
                            class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl overflow-hidden z-50"
                        >
                            <a href="{{ route('profile.show', auth()->user()->username ?? auth()->user()->id) }}"
                               class="block px-4 py-3 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">Profile</a>
                            <a href="{{ route('settings.v2') }}"
                               class="block px-4 py-3 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800">Settings</a>
                            <div class="h-px bg-slate-100 dark:bg-slate-800"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-sm text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="px-4 sm:px-6 lg:px-8 py-8 bg-slate-50 dark:bg-slate-950 min-h-[calc(100vh-80px)]">
            @yield('content')
        </main>
    </div>
</div>

@auth
    @include('partials.report-modal')
@endauth

@stack('scripts')
</body>
</html>

