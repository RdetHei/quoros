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
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;500;600&family=DM+Mono:wght@400;500&family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        .admin-console { color:#ddd9d0; }
        .admin-kicker { color:#77746e; font:500 9px 'DM Mono',monospace; letter-spacing:.12em; text-transform:uppercase; }
        .admin-display,.admin-section-title { color:#e5e0d7; font-family:'Playfair Display',serif; font-weight:500; }
        .admin-display { font-size:30px; line-height:1.15; text-transform:uppercase; }
        .admin-section-title { margin-top:5px; font-size:19px; }
        .console-card { border:1px solid #242424; border-radius:8px; background:#131315; }
        .metric-icon { display:grid; width:32px;height:32px;place-items:center;border:1px solid #59491e;border-radius:4px;color:#c4a13e; }
        .activity-chart { height:175px;position:relative;overflow:hidden;border:1px solid #242424;border-radius:4px;background:#111113; }
        .chart-lines { position:absolute;inset:0;background:repeating-linear-gradient(to bottom,transparent 0,transparent 35px,#28282a 36px,#28282a 37px); }
        .activity-chart svg {position:relative}
        .badge { border:1px solid #65501f;padding:3px 7px;color:#c5a449;font:10px 'DM Mono',monospace; }
        .admin-link { color:#c4a13e;font:10px 'DM Mono',monospace;text-transform:uppercase; }
        .console-table th {padding:10px 8px;color:#77746e;font:9px 'DM Mono',monospace;text-transform:uppercase;border-bottom:1px solid #29292b;}
        .console-table td {padding:13px 8px;color:#bdbab4;font:12px 'Playfair Display',serif;border-bottom:1px solid #242426;}
        .status-dot {color:#70b58c;font:10px 'DM Mono',monospace;}
        .health-line {height:3px;background:#62aa80;border-radius:4px;}
        .maintenance-link {border:1px solid #51441f;border-radius:4px;background:#211e16;padding:11px;color:#c5a449;font:10px 'DM Mono',monospace;}
        .admin-sidebar { width:232px!important;flex:0 0 232px;background:#101011!important;border-color:#242426!important; }
        .admin-sidebar > div:first-child {padding:22px 16px 17px!important;border-bottom:1px solid #242426;margin:0 8px 14px;}
        .admin-brand {display:flex;align-items:center;justify-content:space-between;gap:8px!important;}
        .admin-brand h2 {color:#e8e3da!important;font:500 31px/.9 'Cormorant Garamond',serif!important;letter-spacing:-.04em!important;}
        .admin-brand .admin-brand-badge {margin:0!important;padding:5px 10px;border:1px solid #786126;color:#c5a13c!important;font:11px 'DM Mono',monospace!important;letter-spacing:.04em!important;}
        .admin-brand-meta {margin-top:14px!important;color:#77746e!important;font:10px 'DM Mono',monospace!important;letter-spacing:.08em!important;}
        .admin-sidebar nav {padding:6px 12px 17px!important;}
        .admin-sidebar nav {scrollbar-width:none;-ms-overflow-style:none;}
        .admin-sidebar nav::-webkit-scrollbar {display:none;width:0;height:0;}
        .admin-sidebar nav > div {margin:0 0 16px!important;}
        .admin-sidebar nav > div > :not(:first-child) {margin-top:0!important;}
        .admin-sidebar nav > div > div {display:grid;gap:1px;}
        .admin-sidebar nav > div > div > a {margin:0!important;}
        .admin-sidebar nav p {font:600 9px 'DM Mono',monospace!important;letter-spacing:.08em!important;color:#777!important;padding-left:6px!important;margin-bottom:5px;}
        .admin-sidebar nav a {min-height:35px;border-radius:5px!important;padding:7px 9px!important;gap:9px!important;font:16px 'Cormorant Garamond',serif!important;}
        .admin-sidebar nav a svg {width:15px!important;height:15px!important;}
        .admin-sidebar nav a.admin-nav-active {position:relative;background:#201e18!important;color:#c4a13e!important;border-left:3px solid #514528!important;box-shadow:none!important;}
        .admin-sidebar nav a.admin-nav-active::after {content:"";position:absolute;right:10px;top:50%;width:6px;height:6px;transform:translateY(-50%);border-radius:50%;background:#c6a23c;}
        .admin-sidebar nav a.admin-nav-active svg {color:#c4a13e!important;}
        .admin-sidebar nav a:not(.admin-nav-active) {background:transparent!important;border-left:3px solid transparent!important;box-shadow:none!important;}
        .admin-sidebar nav a:not(.admin-nav-active)::after {content:none!important;}
        .admin-sidebar nav a[class*="text-slate-400"] {color:#aaa7a0!important;}
        .admin-topbar {height:44px;min-height:44px;background:#0d0d0e!important;border-color:#232325!important;backdrop-filter:none!important;}
        .admin-topbar-inner {height:43px;padding:0 18px!important;gap:12px!important;}
        .admin-crumbs {font:9px 'DM Mono',monospace!important;letter-spacing:.08em;text-transform:uppercase;color:#a5a19a!important;}
        .admin-crumbs strong {color:#c3a03c;font-weight:500;}
        .admin-topbar h1 {display:none;}
        .admin-search input {width:245px!important;height:26px!important;padding:4px 28px!important;border:1px solid #28282a!important;border-radius:4px!important;background:#121214!important;font:10px 'Playfair Display',serif!important;}
        .admin-topbar-actions {gap:11px!important;}
        .admin-notify {height:29px!important;width:29px!important;padding:0!important;display:grid!important;place-items:center;border:1px solid #29292b!important;border-radius:4px!important;background:transparent!important;color:#aaa!important;}
        .admin-user-button {height:32px!important;padding:0!important;gap:8px!important;border:0!important;background:transparent!important;color:#ddd!important;}
        .admin-avatar {width:25px;height:25px;border:1px solid #c4a13e;border-radius:50%;}
        .admin-user-name {font:11px 'Playfair Display',serif;line-height:1.1;text-align:left;}
        .admin-user-role {display:block;color:#777;font:8px 'DM Mono',monospace;text-transform:uppercase;}
        .admin-sidebar-footer {padding-bottom:8px!important;}
        .admin-main-content {padding:22px 14px!important;background:#0d0d0e!important;min-height:calc(100vh - 36px)!important;}
    </style>

    <!-- Styles & Scripts -->
    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-900 dark:bg-slate-950 text-slate-900 dark:text-slate-100 min-h-screen antialiased">
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
        class="admin-sidebar fixed inset-y-0 left-0 z-50 w-72 lg:static lg:translate-x-0 bg-slate-950 border-r border-white/5 transition-transform flex flex-col"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
        <div class="p-8">
            <a href="{{ route('welcome') }}" class="admin-brand group">
                <h2 class="uppercase">QUOROS</h2>
                <span class="admin-brand-badge">ADMIN</span>
            </a>
            <p class="admin-brand-meta">ARCHIVE OPERATIONS / V4.8</p>
        </div>

        <nav class="px-6 pb-10 space-y-10 overflow-y-auto custom-scrollbar flex-grow">
            <div class="space-y-4">
                <p class="px-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500/80">Overview</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'admin-nav-active' => request()->routeIs('admin.dashboard'),
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
                <p class="px-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500/80">Management</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.users.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'admin-nav-active' => request()->routeIs('admin.users.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.users.*'),
                       ])>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        User Matrix
                    </a>
                </div>
            </div>

            <div class="space-y-4">
                <p class="px-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500/80">Moderation</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.requests.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'admin-nav-active' => request()->routeIs('admin.requests.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.requests.*'),
                       ])>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6l4 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Novel Approval Queue
                    </a>
                    <a href="{{ route('admin.content-logs.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'admin-nav-active' => request()->routeIs('admin.content-logs.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.content-logs.*'),
                       ])>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 14l6-6m-6 6l6 6M4 6h16M4 18h16"/>
                        </svg>
                        Global Logs
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'admin-nav-active' => request()->routeIs('admin.reports.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.reports.*'),
                       ])>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 opacity-70 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Security Reports
                        @if($pendingReports > 0)
                            <span class="ml-auto inline-flex items-center rounded-lg bg-rose-500 text-white px-2 py-0.5 text-[9px] font-black">
                                {{ $pendingReports }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            <div class="space-y-4">
                <p class="px-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500/80">Curation</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.genres.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'admin-nav-active' => request()->routeIs('admin.genres.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.genres.*'),
                       ])>Genres</a>
                    <a href="{{ route('admin.tags.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'admin-nav-active' => request()->routeIs('admin.tags.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.tags.*'),
                       ])>Tags</a>
                    <a href="{{ route('admin.carousel.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'admin-nav-active' => request()->routeIs('admin.carousel.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.carousel.*'),
                       ])>Spotlight Slider</a>
                </div>
            </div>

            <div class="space-y-4">
                <p class="px-3 text-[10px] font-black uppercase tracking-[0.2em] text-slate-500/80">System</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.announcements.index') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'admin-nav-active' => request()->routeIs('admin.announcements.*'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.announcements.*'),
                       ])>Announcements</a>
                    <a href="{{ route('admin.maintenance') }}"
                       @class([
                           'flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all group relative overflow-hidden',
                           'admin-nav-active' => request()->routeIs('admin.maintenance'),
                           'text-slate-400 hover:text-white hover:bg-white/5' => !request()->routeIs('admin.maintenance'),
                       ])>Maintenance Lock</a>
                </div>
            </div>
        </nav>

        <div class="admin-sidebar-footer mt-auto px-3 pb-12">
            <div class="flex items-center gap-2 rounded border border-white/10 bg-white/[.03] px-2 py-2">
                <span class="admin-avatar shrink-0"></span>
                <span class="min-w-0 truncate font-serif text-[11px] text-zinc-300">{{ auth()->user()->name }}<small class="admin-user-role">Super administrator</small></span>
                <span class="ml-auto text-zinc-500">···</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">@csrf<button type="submit" class="flex w-full items-center gap-2 px-2 py-1 text-[10px] uppercase tracking-wider text-zinc-400 hover:text-white"><span>↪</span> Logout</button></form>
        </div>
    </aside>

    <!-- Main -->
    <div class="flex-1 min-w-0 w-full h-full overflow-y-auto overflow-x-hidden">
        <div class="h-full bg-slate-800 dark:bg-slate-900 border-l border-slate-200 dark:border-slate-800 overflow-y-auto custom-scrollbar">
            <!-- Top Bar -->
        <header class="admin-topbar sticky top-0 z-30 bg-white/80 dark:bg-slate-800/90 backdrop-blur border-b border-slate-200 dark:border-slate-800">
            <div class="admin-topbar-inner flex items-center justify-between px-4 sm:px-6 lg:px-8 py-3 gap-4">
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
                        <p class="admin-crumbs truncate">
                            @foreach($adminBreadcrumbs as $index => $crumb)
                                @if($index > 0)<span class="px-1 text-zinc-600">/</span>@endif
                                @if($index === count($adminBreadcrumbs) - 1)<strong>{{ $crumb }}</strong>@else{{ $crumb }}@endif
                            @endforeach
                        </p>
                        <h1 class="text-lg md:text-xl font-bold truncate">
                            {{ $adminTitle ?? 'Admin Dashboard' }}
                        </h1>
                    </div>
                </div>

                <div class="admin-topbar-actions flex items-center gap-3">
                    <form method="GET" action="{{ route('novels.search') }}" class="admin-search hidden md:block">
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
                                placeholder="Search novels, users, logs..."
                                class="w-72 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 pl-9 pr-3 py-2 text-sm text-slate-700 dark:text-slate-100 placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                        </div>
                    </form>

                    <button type="button" class="admin-notify" aria-label="Notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 00-4.5-5.8V4a1.5 1.5 0 00-3 0v1.2A6 6 0 006 11v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0m6 0H9"/></svg>
                    </button>
                    <div class="relative" @click.away="profileOpen = false">
                        <button
                            @click="profileOpen = !profileOpen"
                            class="admin-user-button inline-flex items-center gap-2 h-10 px-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-100"
                        >
                            <span class="admin-avatar"></span>
                            <span class="text-sm font-semibold truncate max-w-[140px] admin-user-name">{{ auth()->user()->name }}<small class="admin-user-role">Admin</small></span>
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
        <main class="admin-main-content px-4 sm:px-6 lg:px-8 py-8 bg-slate-800 dark:bg-slate-950 min-h-[calc(100vh-80px)]">
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

