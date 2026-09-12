@extends('layouts.app')

@section('content')
<div>
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
        <div class="flex items-center">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-neutral-900 dark:text-white tracking-tight">Trending</h1>
                <p class="text-sm text-neutral-500 dark:text-neutral-400 mt-0.5">Novel paling banyak dibaca — {{ $periodLabel }}.</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('novels.trending', ['days' => 7]) }}"
               class="px-4 py-2 text-xs font-bold uppercase tracking-widest rounded-xl border transition-all {{ $days === 7 ? 'bg-neutral-900 dark:bg-white text-white dark:text-neutral-900 border-transparent' : 'border-neutral-200 dark:border-neutral-700 text-neutral-600 dark:text-neutral-400' }}">
                7 Hari
            </a>
            <a href="{{ route('novels.trending', ['days' => 30]) }}"
               class="px-4 py-2 text-xs font-bold uppercase tracking-widest rounded-xl border transition-all {{ $days === 30 ? 'bg-neutral-900 dark:bg-white text-white dark:text-neutral-900 border-transparent' : 'border-neutral-200 dark:border-neutral-700 text-neutral-600 dark:text-neutral-400' }}">
                30 Hari
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 shadow-sm overflow-hidden">
                @forelse($novels as $index => $novel)
                    <a href="{{ route('novels.show', $novel->slug) }}" class="flex items-center gap-4 p-4 sm:p-5 border-b border-neutral-100 dark:border-neutral-800 last:border-b-0 hover:bg-neutral-50/80 dark:hover:bg-neutral-800/30 transition-colors">
                        <span class="w-8 text-center text-lg font-black {{ $index < 3 ? 'text-neutral-400' : 'text-neutral-400' }}">{{ $novels->firstItem() + $index }}</span>
                        <div class="w-14 h-[4.5rem] shrink-0 rounded-lg overflow-hidden bg-neutral-100 dark:bg-neutral-800 ring-1 ring-neutral-200 dark:ring-neutral-700">
                            @if($novel->cover_image_url)
                                <img src="{{ $novel->cover_image_url }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                            @elseif($novel->cover_image)
                                <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                            @endif
                        </div>
                        <div class="min-w-0 flex-grow">
                            <p class="text-base font-semibold text-neutral-900 dark:text-white line-clamp-1">{{ $novel->title }}</p>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">{{ $novel->author->name }}</p>
                            <div class="flex flex-wrap gap-2 mt-1 text-xs text-neutral-500">
                                <span class="font-semibold text-neutral-400 dark:text-neutral-300">{{ number_format($novel->period_views ?? 0) }} views ({{ $days }}h)</span>
                                <span>{{ number_format($novel->view_count) }} total</span>
                                <span>{{ $novel->chapters_count }} ch</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="py-16 px-6 text-center text-neutral-500 dark:text-neutral-400">
                        <p class="font-medium">No view data for this period yet.</p>
                        <p class="text-sm mt-2">Open a novel page to start collecting daily statistics.</p>
                    </div>
                @endforelse
            </div>
            @if($novels->hasPages())
                <div class="mt-8 flex justify-center">{{ $novels->links() }}</div>
            @endif
        </div>

        <aside class="lg:col-span-4">
            <div class="bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-200 dark:border-neutral-800 p-5 sticky top-24">
                <h2 class="text-sm font-bold text-neutral-900 dark:text-white mb-4">Top 5 This Week</h2>
                <div class="space-y-2">
                    @foreach($weeklyTop as $index => $novel)
                        <a href="{{ route('novels.show', $novel->slug) }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition-colors">
                            <span class="w-5 text-sm font-bold text-neutral-400">{{ $index + 1 }}</span>
                            <div class="min-w-0 flex-grow">
                                <p class="text-xs font-semibold text-neutral-900 dark:text-white line-clamp-1">{{ $novel->title }}</p>
                                <p class="text-[10px] text-neutral-500">{{ number_format($novel->period_views ?? 0) }} views</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
