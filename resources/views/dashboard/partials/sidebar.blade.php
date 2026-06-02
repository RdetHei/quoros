@php($currentUser = auth()->user())

<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100 dark:border-slate-800">
        <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-slate-600 dark:text-slate-300 ring-1 ring-slate-200 dark:ring-slate-700">
            @if($currentUser->profile_photo_url)
                <img src="{{ $currentUser->profile_photo_url }}" alt="{{ $currentUser->name }}" class="w-full h-full object-cover">
            @else
                {{ strtoupper(substr($currentUser->name, 0, 1)) }}
            @endif
        </div>
        <div class="min-w-0">
            <p class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ $currentUser->name }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 capitalize">
                {{ $currentUser->role === 'admin' ? 'Admin' : ($currentUser->role === 'writer' ? 'Writer' : 'Reader') }}
            </p>
        </div>
    </div>

    <nav class="pt-4 space-y-5">
        <div class="space-y-1.5">
            <p class="px-2 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Main</p>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Dashboard
            </a>
            <a href="{{ route('profile.show', $currentUser->username ?? $currentUser->id) }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                Profile
            </a>
            <a href="{{ route('notifications.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('notifications.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                Notifications
            </a>
        </div>

        @if(in_array($currentUser->role, ['writer', 'admin'], true))
            <div class="space-y-1.5">
                <p class="px-2 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Writer Tools</p>
                <a href="{{ route('writer.novels.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('writer.novels.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    My Novels
                </a>
                <a href="{{ route('writer.analytics.pro') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('writer.analytics.pro') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14"/></svg>
                    Analytics
                </a>
                <a href="{{ route('writer.feedback.hub') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('writer.feedback.hub') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                    Feedback Hub
                </a>
            </div>
        @endif

        @if($currentUser->role === 'admin')
            <div class="space-y-1.5">
                <p class="px-2 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Admin Tools</p>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    User Control
                </a>
                <a href="{{ route('admin.moderation.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.moderation.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/></svg>
                    Content Moderation
                </a>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.reports.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 0L9.172 3.515m5.656 5.657l-5.657 5.657m7.071-7.071a4 4 0 11-5.657 5.657"/></svg>
                    Reports
                </a>
                <a href="{{ route('admin.content-logs.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.content-logs.*') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 0h6m2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v12a2 2 0 01-2 2z"/></svg>
                    Content Logs
                </a>
            </div>
        @endif

        <div class="space-y-1.5">
            <p class="px-2 text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Misc</p>
            <a href="{{ route('guides.index') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">
                Guides
            </a>
            <a href="{{ route('home') }}" class="block px-3 py-2 rounded-lg text-sm font-medium text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800">
                Site Home
            </a>
            <a href="{{ route('settings.v2') }}" class="block px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('settings.v2') ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-200' : 'text-slate-600 hover:bg-slate-50 dark:text-slate-300 dark:hover:bg-slate-800' }}">
                Settings
            </a>
        </div>
    </nav>
</div>
