@auth
<div x-data="{ open: false }" class="relative">
    <button type="button"
            @click="open = !open"
            aria-label="Toggle notifications"
            class="relative h-8 w-8 inline-flex items-center justify-center text-[#f5f1e8] hover:text-white hover:bg-white/5 rounded-md transition-colors">
        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
        </svg>
        @if(($unreadNotificationsCount ?? 0) > 0)
            <span class="absolute -top-[3px] -right-[3px] min-w-[15px] h-[15px] px-1 flex items-center justify-center text-[8px] font-bold text-[#0e0c0a] bg-[#c7a64a] rounded-full ring-2 ring-[#0a0a0a]">
                {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
            </span>
        @endif
    </button>

    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-120"
         x-transition:enter-start="transform opacity-0 scale-[0.985] translate-y-1"
         x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-70"
         x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="transform opacity-0 scale-[0.985] translate-y-1"
         class="absolute right-0 top-full mt-2 w-[340px] sm:w-[380px] bg-[#111111] rounded-lg border border-white/[0.08] shadow-[0_20px_44px_-20px_rgba(0,0,0,0.9)] z-50 overflow-hidden"
         style="display: none;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3.5 border-b border-white/[0.06] bg-[#0d0d0d]">
            <div class="flex items-center gap-2.5">
                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5 text-[#c7a64a]" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                    <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                </svg>
                <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-[#eeeae1]">Notifications</p>
                @if(($unreadNotificationsCount ?? 0) > 0)
                    <span class="inline-flex min-w-[16px] h-[15px] px-1 items-center justify-center text-[8px] font-bold text-[#0e0c0a] bg-[#c7a64a] rounded-sm">
                        {{ $unreadNotificationsCount > 9 ? '9+' : $unreadNotificationsCount }}
                    </span>
                @endif
            </div>
            @if(($unreadNotificationsCount ?? 0) > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-[8.5px] font-semibold uppercase tracking-[0.16em] text-[#a3a3a3] hover:text-[#e8d39a] transition-colors whitespace-nowrap">
                        Read all
                    </button>
                </form>
            @endif
        </div>

        {{-- List --}}
        <div class="max-h-[340px] overflow-y-auto bg-[#121212]">
            @forelse($recentNotifications ?? [] as $n)
                @php
                    $isUnread = $n->isUnread();
                    $nType = $n->type->value ?? 'general';
                    $nLabel = match(strtolower($nType)) {
                        'announcement' => 'Announcement',
                        'comment' => 'Comment',
                        'reply' => 'Reply',
                        'review' => 'Review',
                        'follow' => 'Follow',
                        'bookmark' => 'Bookmark',
                        'chapter' => 'New Chapter',
                        default => ucfirst($nType),
                    };
                @endphp
                <a href="{{ route('notifications.show', $n) }}"
                   class="block px-4 py-3 border-b border-white/[0.04] transition-colors {{ $isUnread ? 'bg-[#c7a64a]/[0.04]' : 'bg-transparent' }} hover:bg-[#171717]">
                    <div class="flex items-start gap-3">
                        {{-- Status dot --}}
                        <span class="mt-2 shrink-0 {{ $isUnread ? '' : '' }}">
                            @if($isUnread)
                                <span class="block w-[7px] h-[7px] rounded-full bg-[#c7a64a] shadow-[0_0_10px_rgba(199,166,74,0.65)]"></span>
                            @else
                                <span class="block w-[7px] h-[7px] rounded-full border border-[#2f2f2f] bg-transparent"></span>
                            @endif
                        </span>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-1">
                                <span class="text-[7.5px] font-semibold uppercase tracking-[0.16em] text-[#c7a64a]">
                                    {{ $nLabel }}
                                </span>
                                @if($isUnread)
                                    <span class="text-[7px] font-semibold uppercase tracking-[0.16em] text-[#c7a64a]/80">
                                        · New
                                    </span>
                                @endif
                            </div>
                            <p class="text-[12px] font-semibold text-[#eeeae1] line-clamp-1 leading-snug">{{ $n->title() }}</p>
                            <p class="text-[11px] text-[#8b857d] mt-1 line-clamp-2 leading-relaxed">{{ $n->body() }}</p>
                            <div class="mt-2 flex items-center justify-between gap-2">
                                <span class="text-[8.5px] font-medium text-[#6f6b63] uppercase tracking-[0.06em]">{{ $n->created_at->diffForHumans(null, true) }}</span>
                                @if($n->url())
                                    <span class="inline-flex items-center gap-1 text-[8px] font-semibold uppercase tracking-[0.16em] text-[#c7a64a]">
                                        Open
                                        <svg viewBox="0 0 24 24" class="h-2 w-2" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-12 text-center">
                    <div class="mx-auto mb-4 flex h-11 w-11 items-center justify-center rounded-full border border-white/[0.08] bg-[#0d0d0d] text-[#c7a64a]/70">
                        <svg viewBox="0 0 24 24" class="h-4.5 w-4.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                        </svg>
                    </div>
                    <p class="text-[11.5px] font-semibold text-[#d4d4d4]">Inbox is quiet</p>
                    <p class="mt-1.5 text-[10.5px] text-[#737373]">New updates will surface here.</p>
                </div>
            @endforelse
        </div>

        {{-- Footer CTA --}}
        <div class="px-3 py-2.5 border-t border-white/[0.06] bg-[#0d0d0d]">
            <a href="{{ route('notifications.index') }}" class="block text-center text-[9.5px] font-semibold uppercase tracking-[0.16em] text-[#c7a64a] py-2 rounded-sm border border-[#c7a64a]/30 hover:bg-[#c7a64a]/[0.08] hover:border-[#c7a64a]/60 transition-colors">
                View All Notifications
            </a>
        </div>
    </div>
</div>
@endauth
