@auth
<div x-data="{ open: false }" class="relative">
    <button type="button"
            @click="open = !open"
            aria-label="Toggle notifications"
            class="relative h-8 w-8 inline-flex items-center justify-center text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if(($unreadNotificationsCount ?? 0) > 0)
            <span class="absolute top-0.5 right-0.5 min-w-[1rem] h-[1rem] px-0.5 flex items-center justify-center text-[8px] font-bold text-white bg-rose-500 rounded-full ring-2 ring-slate-950">
                {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
            </span>
        @endif
    </button>

    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95 translate-y-1"
         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="transform opacity-0 scale-95 translate-y-1"
         class="absolute right-0 top-full mt-2 w-80 sm:w-96 bg-slate-900 rounded-2xl shadow-2xl shadow-black/40 border border-white/10 z-50 overflow-hidden"
         style="display: none;">
        <div class="flex items-center justify-between px-4 py-3 border-b border-white/5">
            <p class="text-sm font-bold text-white">Notifikasi</p>
            @if(($unreadNotificationsCount ?? 0) > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-indigo-400 hover:text-indigo-300 transition-colors">
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
                    <button type="submit" class="w-full text-left px-4 py-3 hover:bg-white/5 transition-colors border-b border-white/5 last:border-b-0 {{ $notification->isUnread() ? 'bg-indigo-500/5' : '' }}">
                        <p class="text-sm font-semibold text-white line-clamp-1">{{ $notification->title() }}</p>
                        <p class="text-xs text-slate-400 mt-0.5 line-clamp-2">{{ $notification->body() }}</p>
                        <p class="text-[10px] text-slate-500 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </button>
                </form>
            @empty
                <p class="px-4 py-8 text-center text-sm text-slate-400">Belum ada notifikasi.</p>
            @endforelse
        </div>

        <div class="p-2 border-t border-white/5">
            <a href="{{ route('notifications.index') }}" class="block text-center text-xs font-bold text-slate-300 py-2 rounded-xl hover:bg-white/5 transition-colors">
                Lihat semua
            </a>
        </div>
    </div>
</div>
@endauth
