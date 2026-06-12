@extends('layouts.app')

@section('title', 'Pembaruan Terbaru — Quoros')
@section('meta_description', 'Lihat novel-novel dengan bab terbaru yang baru saja diterbitkan di Quoros. Tetap update dengan seri favorit Anda setiap hari.')

@section('content')
<div>
    <div class="flex items-center mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Recently Updated</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Novels with the latest chapter updates, sorted by activity.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 sm:gap-3">
        @forelse($novels as $novel)
            <article class="group bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-all duration-300 overflow-hidden">
                {{-- Desktop View --}}
                <div class="hidden md:flex items-center gap-3 p-2.5">
                    {{-- Cover --}}
                    <a href="{{ route('novels.show', $novel->slug) }}" 
                       aria-label="View {{ $novel->title }}"
                       class="shrink-0 w-12 h-18 rounded-md overflow-hidden bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200/50 dark:ring-slate-700/50">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }} cover" class="w-full h-full object-cover" width="48" height="72" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }} cover" class="w-full h-full object-cover" width="48" height="72" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @endif
                    </a>

                    {{-- Info --}}
                    <div class="flex-grow min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            @foreach($novel->genres->take(1) as $genre)
                                <span class="text-[8px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">{{ $genre->name }}</span>
                            @endforeach
                        </div>
                        
                        <a href="{{ route('novels.show', $novel->slug) }}" class="text-sm font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate block">
                            {{ $novel->title }}
                        </a>
                        
                        <div class="flex items-center gap-3 mt-1.5">
                            <span class="text-[10px] text-slate-500 dark:text-slate-400">{{ number_format($novel->chapters_count) }} chapters</span>
                            <span class="w-0.5 h-0.5 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                            <span class="text-[10px] text-slate-500 dark:text-slate-400">{{ number_format($novel->view_count) }} views</span>
                            <span class="w-0.5 h-0.5 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                            <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $novel->chapters_max_created_at ? \Illuminate\Support\Carbon::parse($novel->chapters_max_created_at)->diffForHumans(null, true) : '—' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Mobile View --}}
                <a href="{{ route('novels.show', $novel->slug) }}" 
                   aria-label="View {{ $novel->title }}"
                   class="md:hidden flex gap-3 p-2.5 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                    <div class="shrink-0 w-12 h-18 rounded-md overflow-hidden bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200/50 dark:ring-slate-700/50">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }} cover" class="w-full h-full object-cover" width="48" height="72" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }} cover" class="w-full h-full object-cover" width="48" height="72" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @endif
                    </div>
                    <div class="flex-grow min-w-0 flex flex-col justify-center">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 mb-1">{{ $novel->title }}</h3>
                        <div class="flex items-center gap-2 text-[10px] font-medium text-slate-400 dark:text-slate-500">
                            <span>{{ number_format($novel->chapters_count) }} chapters</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                            <span class="text-emerald-600 dark:text-emerald-400">{{ $novel->chapters_max_created_at ? \Illuminate\Support\Carbon::parse($novel->chapters_max_created_at)->diffForHumans(null, true) : '—' }}</span>
                        </div>
                    </div>
                </a>
            </article>
        @empty
            <div class="py-20 px-6 text-center bg-white dark:bg-slate-900 rounded-3xl border border-dashed border-slate-300 dark:border-slate-800">
                <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800/50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No updates found</h3>
                <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto">Recently updated novels will appear here once chapters are published.</p>
            </div>
        @endforelse
    </div>

    @if($novels->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $novels->links() }}
        </div>
    @endif
</div>
@endsection
