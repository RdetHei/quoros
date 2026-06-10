@extends(request()->is('writer*') ? 'layouts.writer' : 'layouts.app', [
    'title' => 'Notifications',
    'subtitle' => 'Manage your activity and stay updated.'
])

@section('content')
<div class="{{ request()->is('writer*') ? '' : 'max-w-4xl mx-auto' }} pb-20">
    @if(!request()->is('writer*'))
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-6 mb-12 px-2">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="w-8 h-1 bg-indigo-600 rounded-full text-indigo-600"></span>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-400">Activity Center</p>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight">Inbox</h1>
        </div>
        
        @if($hasUnread ?? false)
            <form action="{{ route('notifications.read-all') }}" method="POST">
                @csrf
                <button type="submit" class="group flex items-center gap-2 px-6 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-xs font-black uppercase tracking-widest text-slate-600 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Mark all read
                </button>
            </form>
        @endif
    </div>
    @endif

    <div class="space-y-12">
        @php
            $grouped = $notifications->groupBy(function($n) {
                if ($n->created_at->isToday()) return 'Today';
                if ($n->created_at->isYesterday()) return 'Yesterday';
                return 'Earlier';
            });
        @endphp

        @forelse($grouped as $group => $items)
            <div class="space-y-6">
                <div class="flex items-center gap-4 px-2">
                    <h2 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400">{{ $group }}</h2>
                    <div class="flex-1 h-px bg-slate-200 dark:bg-slate-800/50"></div>
                </div>

                <div class="grid grid-cols-1 gap-3">
                    @foreach($items as $notification)
                        @php
                            $type = $notification->type;
                            $icon = match($type) {
                                \App\Enums\NotificationType::ChapterNew => '<path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>',
                                \App\Enums\NotificationType::CommentReply => '<path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/>',
                                \App\Enums\NotificationType::RequestFulfilled => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
                                \App\Enums\NotificationType::RequestRejected => '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>',
                                default => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>',
                            };
                            $color = match($type) {
                                \App\Enums\NotificationType::ChapterNew => 'text-emerald-500 bg-emerald-500/10',
                                \App\Enums\NotificationType::CommentReply => 'text-blue-500 bg-blue-500/10',
                                \App\Enums\NotificationType::RequestFulfilled => 'text-indigo-500 bg-indigo-500/10',
                                \App\Enums\NotificationType::RequestRejected => 'text-rose-500 bg-rose-500/10',
                                default => 'text-slate-500 bg-slate-500/10',
                            };
                        @endphp
                        <div class="relative group">
                            <form action="{{ route('notifications.read', $notification) }}" method="POST" class="block h-full">
                                @csrf
                                @method('PATCH')
                                <button type="submit" @class([
                                    'w-full text-left p-5 sm:p-6 rounded-3xl border transition-all flex items-start gap-5 h-full relative overflow-hidden',
                                    'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 shadow-sm hover:border-slate-300 dark:hover:border-slate-700' => !$notification->isUnread(),
                                    'bg-indigo-50/30 dark:bg-indigo-500/5 border-indigo-100 dark:border-indigo-900/30 shadow-md shadow-indigo-500/5' => $notification->isUnread(),
                                ])>
                                    @if($notification->isUnread())
                                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-12 bg-indigo-600 rounded-r-full"></div>
                                    @endif

                                    <div class="shrink-0 relative mt-1">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center {{ $color }} border border-transparent dark:border-slate-800 transition-transform group-hover:scale-105">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                {!! $icon !!}
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center justify-between gap-4 mb-1">
                                            <p @class([
                                                'text-sm truncate leading-none',
                                                'font-black text-slate-900 dark:text-white' => $notification->isUnread(),
                                                'font-bold text-slate-600 dark:text-slate-300' => !$notification->isUnread(),
                                            ])>{{ $notification->title() }}</p>
                                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight whitespace-nowrap">{{ $notification->created_at->diffForHumans(null, true) }}</span>
                                        </div>
                                        <p @class([
                                            'text-sm line-clamp-2 leading-relaxed mt-2',
                                            'text-slate-600 dark:text-slate-300 font-medium' => $notification->isUnread(),
                                            'text-slate-500 dark:text-slate-400' => !$notification->isUnread(),
                                        ])>{{ $notification->body() }}</p>
                                        
                                        @if($notification->url())
                                            <div class="mt-4 flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-400">
                                                <span>View Action</span>
                                                <div class="w-4 h-px bg-indigo-200 dark:bg-indigo-900 group-hover:w-8 transition-all"></div>
                                            </div>
                                        @endif
                                    </div>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="py-32 flex flex-col items-center justify-center text-center px-4">
                <div class="w-32 h-32 bg-slate-100 dark:bg-slate-900 rounded-full flex items-center justify-center mb-8 relative">
                    <div class="absolute inset-0 bg-indigo-500/5 rounded-full animate-pulse"></div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 dark:text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">All caught up!</h3>
                <p class="text-slate-500 dark:text-slate-400 mt-3 max-w-sm leading-relaxed font-medium">Your inbox is clean. We'll notify you here when something important happens.</p>
                <a href="{{ route('home') }}" class="mt-8 px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl text-xs font-black uppercase tracking-widest hover:scale-105 active:scale-95 transition-all">Back to browse</a>
            </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
        <div class="mt-16">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
