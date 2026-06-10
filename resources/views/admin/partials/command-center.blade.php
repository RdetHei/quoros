<div class="max-w-7xl mx-auto space-y-8">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-0.5 rounded-md bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-widest">
                    Control Panel
                </span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">v2.4.0 Stable</p>
            </div>
            <h2 class="text-3xl md:text-4xl font-black tracking-tight text-slate-900 dark:text-white">
                Command <span class="text-indigo-600 dark:text-indigo-400">Center</span>
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-2xl leading-relaxed">
                Platform-wide overview. Monitoring activity, moderation queues, and catalog health in real-time.
            </p>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-500 transition-all shadow-lg shadow-indigo-500/20 hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14m-7-7v14"/>
                </svg>
                Announcement
            </a>
            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-all hover:-translate-y-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                Moderation
            </a>
            <a href="{{ route('admin.genres.create') }}" class="inline-flex items-center justify-center h-10 w-10 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all hover:-translate-y-0.5" title="Add New Genre">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 5v14m-7-7h14"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Top Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
        <x-admin.admin-card class="p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 dark:bg-indigo-900/10 rounded-full blur-2xl group-hover:bg-indigo-100 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Revenue</p>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-slate-100">
                    {{ number_format($totalRevenuePoints) }}
                </p>
                <div class="mt-2 flex items-center gap-1.5">
                    <span class="flex items-center text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20 px-1.5 py-0.5 rounded-lg italic">
                        POINTS
                    </span>
                    <p class="text-[10px] font-medium text-slate-500">Platform earnings placeholder</p>
                </div>
            </div>
        </x-admin.admin-card>

        <x-admin.admin-card class="p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 dark:bg-blue-900/10 rounded-full blur-2xl group-hover:bg-blue-100 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 rounded-xl bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Active Users</p>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-slate-100">
                    {{ number_format($totalActiveUsers) }}
                </p>
                <p class="mt-2 text-[10px] font-medium text-slate-500">Non-banned registered users</p>
            </div>
        </x-admin.admin-card>

        <x-admin.admin-card class="p-6 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/10 rounded-full blur-2xl group-hover:bg-emerald-100 transition-colors"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                        </svg>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Total Novels</p>
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-slate-100">
                    {{ number_format($totalNovels) }}
                </p>
                <p class="mt-2 text-[10px] font-medium text-slate-500">Catalogued works across genres</p>
            </div>
        </x-admin.admin-card>

        <x-admin.admin-card class="p-6 relative overflow-hidden group">
            <div @class([
                'absolute -right-4 -top-4 w-24 h-24 rounded-full blur-2xl transition-colors',
                'bg-rose-50 dark:bg-rose-900/10 group-hover:bg-rose-100' => $pendingReports > 0,
                'bg-emerald-50 dark:bg-emerald-900/10 group-hover:bg-emerald-100' => $pendingReports <= 0,
            ])></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div @class([
                        'p-2 rounded-xl',
                        'bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400' => $pendingReports > 0,
                        'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' => $pendingReports <= 0,
                    ])>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    @if($pendingReports > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 text-[10px] font-black uppercase">
                            Action Needed
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase">
                            Secure
                        </span>
                    @endif
                </div>
                <p class="text-3xl font-black text-slate-900 dark:text-slate-100">
                    {{ number_format($pendingReports) }}
                </p>
                <p class="mt-2 text-[10px] font-bold uppercase tracking-widest text-slate-500">Pending Reports</p>
            </div>
        </x-admin.admin-card>
    </div>

    <!-- Activity Feed -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-admin.admin-card class="p-0 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/20">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-800 dark:text-slate-100">Recent Registrations</h3>
                </div>
                <a href="{{ route('admin.users.index') }}" class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest hover:underline">View All</a>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($latestUsers as $u)
                    <div class="px-6 py-4 hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors flex items-center justify-between group">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 font-bold text-slate-500 group-hover:border-indigo-200 dark:group-hover:border-indigo-900/50 transition-colors">
                                {{ strtoupper(substr($u->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $u->name }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">{{ $u->role }}</span>
                                    <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                    <span class="text-[10px] font-medium text-slate-500 italic">{{ $u->email }}</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 whitespace-nowrap bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded-lg">
                            {{ $u->created_at->diffForHumans(null, true) }}
                        </p>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-slate-500 dark:text-slate-400">No recent activity found.</p>
                    </div>
                @endforelse
            </div>
        </x-admin.admin-card>

        <x-admin.admin-card class="p-0 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/20">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-800 dark:text-slate-100">Latest Updates</h3>
                </div>
                <a href="{{ route('admin.content-logs.index') }}" class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest hover:underline">Full Logs</a>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($latestNovelUpdates as $chapter)
                    <div class="px-6 py-4 hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors flex items-center justify-between group">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-900/50 flex items-center justify-center shrink-0 text-emerald-600 dark:text-emerald-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                    {{ $chapter->title }}
                                </p>
                                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-tight mt-0.5">
                                    {{ $chapter->novel?->title ?? 'Unknown' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right whitespace-nowrap">
                            <p class="text-[10px] font-bold text-slate-800 dark:text-slate-200">{{ $chapter->novel?->author?->name ?? 'System' }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $chapter->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm text-slate-500 dark:text-slate-400">No recent updates found.</p>
                    </div>
                @endforelse
            </div>
        </x-admin.admin-card>
    </div>
</div>


