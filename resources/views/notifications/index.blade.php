@php
    $useWriterShell = auth()->check() && auth()->user()->role === 'writer';
@endphp

@extends($useWriterShell ? 'layouts.writer' : 'layouts.app', [
    'title' => 'Notifications',
    'subtitle' => 'Manage your activity and stay updated.'
])

@section('content')
<div class="{{ $useWriterShell ? '' : 'max-w-4xl mx-auto' }} pb-10">
    <div class="mb-8 px-2">
        <div class="flex items-center gap-3 mb-3">
            <span class="inline-flex h-2 w-2 rounded-full bg-indigo-400 shadow-[0_0_18px_rgba(129,140,248,0.9)]"></span>
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Inbox</p>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">Notifikasi</h1>
            </div>
            @if($hasUnread ?? false)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-white/10 bg-white/[0.03] px-4 py-2 text-[10px] font-bold uppercase tracking-[0.14em] text-slate-300 transition hover:border-indigo-400/40 hover:text-white">
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        @php
            $grouped = $notifications->groupBy(function($n) {
                if ($n->created_at->isToday()) return 'Hari ini';
                if ($n->created_at->isYesterday()) return 'Kemarin';
                return 'Sebelumnya';
            });
        @endphp

        @forelse($grouped as $group => $items)
            <div class="space-y-3">
                <div class="flex items-center gap-3 px-2">
                    <h2 class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400">{{ $group }}</h2>
                    <div class="flex-1 h-px bg-white/5"></div>
                </div>

                <div class="space-y-2">
                    @foreach($items as $notification)
                        <a href="{{ route('notifications.show', $notification) }}" class="block rounded-2xl border {{ $notification->isUnread() ? 'border-indigo-400/30 bg-white/[0.03]' : 'border-white/5 bg-[#0b1017]' }} px-4 py-4 transition hover:border-white/15 hover:bg-white/[0.04]">
                            <div class="flex items-start gap-3">
                                <div class="mt-0.5 h-2.5 w-2.5 rounded-full {{ $notification->isUnread() ? 'bg-indigo-400 shadow-[0_0_16px_rgba(129,140,248,0.9)]' : 'bg-slate-600' }}"></div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-white truncate">{{ $notification->title() }}</p>
                                            <p class="mt-1 text-sm text-slate-300 leading-relaxed">{{ $notification->body() }}</p>
                                        </div>
                                        <span class="shrink-0 text-[10px] text-slate-500 uppercase tracking-[0.12em]">{{ $notification->created_at->diffForHumans(null, true) }}</span>
                                    </div>

                                    @if($notification->url())
                                        <div class="mt-3 flex items-center gap-2 text-[10px] font-bold uppercase tracking-[0.16em] text-indigo-300">
                                            <span>Detail announcement</span>
                                            <span class="h-px w-6 bg-indigo-400/60"></span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="rounded-3xl border border-dashed border-white/10 bg-[#0b1017] px-6 py-16 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full border border-white/10 bg-white/[0.02] text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                </div>
                <h3 class="mt-6 text-xl font-bold text-white">Belum ada notifikasi</h3>
                <p class="mt-2 text-sm text-slate-400">Semua kabar terbaru akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-12">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
