@extends('layouts.app')

@section('content')
<div>
    <div class="flex items-center gap-4 mb-8">
        <div class="w-1 h-10 bg-emerald-600 rounded-full shrink-0"></div>
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Recently Updated</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Novels with the latest chapter updates, sorted by activity.</p>
        </div>
    </div>

    <div class="space-y-3 sm:space-y-4">
        @forelse($novels as $novel)
            <article class="group bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-all duration-300 overflow-hidden">
                {{-- Desktop View --}}
                <div class="hidden md:flex items-center gap-4 p-4">
                    {{-- Cover --}}
                    <a href="{{ route('novels.show', $novel->slug) }}" class="shrink-0 w-16 h-24 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 shadow-sm ring-1 ring-slate-200/50 dark:ring-slate-700/50 group-hover:scale-[1.02] transition-transform duration-300">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @endif
                    </a>

                    {{-- Info --}}
                    <div class="flex-grow min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            @foreach($novel->genres->take(1) as $genre)
                                <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 rounded-md border border-emerald-100 dark:border-emerald-900/50">{{ $genre->name }}</span>
                            @endforeach
                            @if($novel->rating_avg > 0)
                                <span class="flex items-center gap-0.5 text-[10px] font-bold text-amber-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    {{ number_format($novel->rating_avg, 1) }}
                                </span>
                            @endif
                        </div>
                        
                        <a href="{{ route('novels.show', $novel->slug) }}" class="text-base font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate block mb-0.5">
                            {{ $novel->title }}
                        </a>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2.5">by <span class="font-medium text-slate-600 dark:text-slate-300">{{ $novel->author->name }}</span></p>
                        
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col">
                                <span class="text-[9px] font-medium text-slate-400 dark:text-slate-500 uppercase tracking-wider">Chapters</span>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ number_format($novel->chapters_count) }}</span>
                            </div>
                            <div class="flex flex-col border-l border-slate-100 dark:border-slate-800 pl-4">
                                <span class="text-[9px] font-medium text-slate-400 dark:text-slate-500 uppercase tracking-wider">Views</span>
                                <span class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ number_format($novel->view_count) }}</span>
                            </div>
                            <div class="flex flex-col border-l border-slate-100 dark:border-slate-800 pl-4">
                                <span class="text-[9px] font-medium text-slate-400 dark:text-slate-500 uppercase tracking-wider">Last Update</span>
                                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                    {{ $novel->chapters_max_created_at ? \Illuminate\Support\Carbon::parse($novel->chapters_max_created_at)->diffForHumans(null, true) : '—' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Action --}}
                    <div class="shrink-0">
                        <a href="{{ route('novels.show', $novel->slug) }}" class="flex items-center justify-center w-9 h-9 rounded-xl bg-slate-50 dark:bg-slate-800/50 text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                {{-- Mobile View --}}
                <a href="{{ route('novels.show', $novel->slug) }}" class="md:hidden flex gap-4 p-4 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                    <div class="shrink-0 w-20 h-28 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 shadow-sm ring-1 ring-slate-200/50 dark:ring-slate-700/50">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @endif
                    </div>
                    <div class="flex-grow min-w-0 flex flex-col justify-center">
                        <div class="flex items-center gap-2 mb-1">
                            @foreach($novel->genres->take(1) as $genre)
                                <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 rounded-md">{{ $genre->name }}</span>
                            @endforeach
                        </div>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white line-clamp-1 mb-0.5">{{ $novel->title }}</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">by {{ $novel->author->name }}</p>
                        <div class="flex items-center gap-3 text-[10px] font-medium text-slate-400 dark:text-slate-500">
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
