@auth
<div x-data="{ open: false }" class="relative">
    <button type="button"
            @click="open = !open"
            aria-label="Toggle notifications"
            class="relative h-8 w-8 inline-flex items-center justify-center text-slate-300 hover:text-white hover:bg-white/5 rounded-lg transition-colors ring-1 ring-white/5">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        @if(($unreadNotificationsCount ?? 0) > 0)
            <span class="absolute -top-1 -right-1 min-w-[1rem] h-[1rem] px-0.5 flex items-center justify-center text-[8px] font-bold text-white bg-rose-500 rounded-full ring-2 ring-slate-950">
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
         class="absolute right-0 top-full mt-2 w-80 sm:w-96 bg-[#05070b] rounded-2xl border border-white/10 shadow-[0_16px_40px_rgba(0,0,0,0.45)] z-50 overflow-hidden"
         style="display: none;">
        <div class="flex items-center justify-between px-4 py-3 border-b border-white/10 bg-white/[0.02]">
            <p class="text-sm font-bold text-white">Notifikasi</p>
            @if(($unreadNotificationsCount ?? 0) > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-[10px] font-semibold uppercase tracking-[0.12em] text-slate-300 hover:text-white transition-colors">
                        Baca semua
                    </button>
                </form>
            @endif
        </div>

        <div class="max-h-80 overflow-y-auto bg-[#070b12]">
            @forelse($recentNotifications ?? [] as $notification)
                <a href="{{ route('notifications.show', $notification) }}" class="block px-4 py-3 border-b border-white/5 transition-colors {{ $notification->isUnread() ? 'bg-white/[0.03]' : 'bg-transparent' }} hover:bg-white/[0.04]">
                    <div class="flex items-start gap-3">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full {{ $notification->isUnread() ? 'bg-indigo-400 shadow-[0_0_14px_rgba(129,140,248,0.8)]' : 'bg-slate-600' }}"></span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-white line-clamp-1">{{ $notification->title() }}</p>
                            <p class="text-xs text-slate-400 mt-1 line-clamp-2">{{ $notification->body() }}</p>
                            <div class="mt-2 flex items-center justify-between gap-2">
                                <span class="text-[10px] text-slate-500">{{ $notification->created_at->diffForHumans() }}</span>
                                @if($notification->type === \App\Enums\NotificationType::Announcement && $notification->url())
                                    <span class="text-[9px] uppercase tracking-[0.14em] text-indigo-300">Detail</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <p class="px-4 py-8 text-center text-sm text-slate-400">Belum ada notifikasi.</p>
            @endforelse
        </div>

        <div class="p-2 border-t border-white/10 bg-[#080d14]">
            <a href="{{ route('notifications.index') }}" class="block text-center text-xs font-bold text-slate-300 py-2 rounded-xl hover:bg-white/5 transition-colors">
                Lihat semua
            </a>
        </div>
    </div>
</div>
@endauth
