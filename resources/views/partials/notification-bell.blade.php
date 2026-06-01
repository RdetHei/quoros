@auth
<div x-data="{ open: false }" class="relative">
    <button type="button"
            @click="open = !open"
            class="relative p-2 text-slate-500 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors"
            aria-label="Notifikasi">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if(($unreadNotificationsCount ?? 0) > 0)
            <span class="absolute top-1 right-1 min-w-[1.125rem] h-[1.125rem] px-1 flex items-center justify-center text-[10px] font-bold text-white bg-rose-600 rounded-full ring-2 ring-white dark:ring-slate-900">
                {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
            </span>
        @endif
    </button>

    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 top-full mt-2 w-80 sm:w-96 bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 z-50 overflow-hidden"
         style="display: none;">
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-800">
            <p class="text-sm font-bold text-slate-900 dark:text-white">Notifikasi</p>
            @if(($unreadNotificationsCount ?? 0) > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse($recentNotifications ?? [] as $notification)
                <form action="{{ route('notifications.read', $notification) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full text-left px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-colors border-b border-slate-50 dark:border-slate-800/80 last:border-b-0 {{ $notification->isUnread() ? 'bg-indigo-50/50 dark:bg-indigo-900/10' : '' }}">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white line-clamp-1">{{ $notification->title() }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-2">{{ $notification->body() }}</p>
                        <p class="text-[10px] text-slate-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </button>
                </form>
            @empty
                <p class="px-4 py-8 text-center text-sm text-slate-500 dark:text-slate-400">Belum ada notifikasi.</p>
            @endforelse
        </div>

        <div class="p-2 border-t border-slate-100 dark:border-slate-800">
            <a href="{{ route('notifications.index') }}" class="block text-center text-xs font-bold text-slate-600 dark:text-slate-300 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                Lihat semua
            </a>
        </div>
    </div>
</div>
@endauth
