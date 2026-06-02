<div class="max-w-7xl mx-auto space-y-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Admin Command Center</p>
            <h2 class="mt-1 text-2xl md:text-3xl font-black tracking-tight text-slate-900 dark:text-slate-100">
                Overview & Moderation Console
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300 max-w-2xl">
                Monitor platform activity, manage moderation workload, and keep your catalogs healthy.
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-500 transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Announcement
            </a>
            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                View Reports
            </a>
            <a href="{{ route('admin.genres.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-200 text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Genre
            </a>
        </div>
    </div>

    <!-- Top Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <x-admin.admin-card class="p-5">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Revenue / Points</p>
            <p class="mt-2 text-3xl font-black text-slate-900 dark:text-slate-100">
                {{ number_format($totalRevenuePoints) }}
            </p>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-300">
                Placeholder metric (no points schema yet).
            </p>
        </x-admin.admin-card>

        <x-admin.admin-card class="p-5">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Total Active Users</p>
            <p class="mt-2 text-3xl font-black text-slate-900 dark:text-slate-100">
                {{ number_format($totalActiveUsers) }}
            </p>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-300">
                Active = not banned
            </p>
        </x-admin.admin-card>

        <x-admin.admin-card class="p-5">
            <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Total Novels</p>
            <p class="mt-2 text-3xl font-black text-slate-900 dark:text-slate-100">
                {{ number_format($totalNovels) }}
            </p>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-300">
                Entire catalog size
            </p>
        </x-admin.admin-card>

        <x-admin.admin-card class="p-5">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Pending Reports</p>
                    <p class="mt-2 text-3xl font-black text-slate-900 dark:text-slate-100">
                        {{ number_format($pendingReports) }}
                    </p>
                </div>
                @if($pendingReports > 0)
                    <x-admin.status-badge label="Needs Review" variant="warning" class="shrink-0"/>
                @else
                    <x-admin.status-badge label="All Clear" variant="success" class="shrink-0"/>
                @endif
            </div>
            <p class="mt-2 text-xs text-slate-500 dark:text-slate-300">
                Reports awaiting admin action
            </p>
        </x-admin.admin-card>
    </div>

    <!-- Activity Feed -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <x-admin.admin-card class="p-5">
            <div class="flex items-center justify-between gap-4">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Latest Registered Users</h3>
                <span class="text-xs font-bold text-slate-500 dark:text-slate-300">{{ $latestUsers->count() }} items</span>
            </div>

            <div class="mt-4 space-y-3">
                @forelse($latestUsers as $u)
                    <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900/30 px-3 py-2">
                        <div class="min-w-0">
                            <p class="font-semibold text-slate-800 dark:text-slate-100 truncate">{{ $u->name }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-300 capitalize">{{ $u->role }}</p>
                        </div>
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 whitespace-nowrap">
                            {{ $u->created_at->diffForHumans() }}
                        </p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 dark:text-slate-300 mt-4">No user activity found.</p>
                @endforelse
            </div>
        </x-admin.admin-card>

        <x-admin.admin-card class="p-5">
            <div class="flex items-center justify-between gap-4">
                <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100">Latest Novel Updates</h3>
                <span class="text-xs font-bold text-slate-500 dark:text-slate-300">{{ $latestNovelUpdates->count() }} items</span>
            </div>

            <div class="mt-4 space-y-3">
                @forelse($latestNovelUpdates as $chapter)
                    <div class="rounded-xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-900/30 px-3 py-2">
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-100 truncate">
                            {{ $chapter->title }} <span class="text-xs font-bold text-slate-500">·</span>
                            {{ $chapter->novel?->title ?? 'Unknown novel' }}
                        </p>
                        <p class="text-xs text-slate-500 dark:text-slate-300">
                            Updated {{ $chapter->created_at->diffForHumans() }} · {{ $chapter->novel?->author?->name ?? 'Unknown author' }}
                        </p>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 dark:text-slate-300 mt-4">No recent chapter updates found.</p>
                @endforelse
            </div>
        </x-admin.admin-card>
    </div>
</div>

