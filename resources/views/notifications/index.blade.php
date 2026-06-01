@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-1 h-10 bg-indigo-600 rounded-full shrink-0"></div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Notifikasi</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Pembaruan bab bookmark, permintaan novel, dan lainnya.</p>
            </div>
        </div>
        @if($hasUnread ?? false)
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 text-sm font-bold rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:opacity-90 transition-opacity">
                    Tandai semua dibaca
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        @forelse($notifications as $notification)
            <div @class([
                'border-b border-slate-100 dark:border-slate-800 last:border-b-0',
                'bg-indigo-50/40 dark:bg-indigo-900/10' => $notification->isUnread(),
            ])>
                <form action="{{ route('notifications.read', $notification) }}" method="POST" class="block">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="w-full text-left p-4 sm:p-5 hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors">
                        <div class="flex items-start gap-3">
                            @if($notification->isUnread())
                                <span class="mt-1.5 w-2 h-2 rounded-full bg-indigo-500 shrink-0" aria-hidden="true"></span>
                            @else
                                <span class="mt-1.5 w-2 h-2 shrink-0" aria-hidden="true"></span>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $notification->title() }}</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ $notification->body() }}</p>
                                <p class="text-xs text-slate-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </button>
                </form>
            </div>
        @empty
            <div class="p-12 text-center">
                <p class="text-slate-500 dark:text-slate-400">Belum ada notifikasi untuk ditampilkan.</p>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
