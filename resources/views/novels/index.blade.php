@extends('layouts.app')

@section('content')
{{-- Featured carousel --}}
@if($featuredNovels->count() > 0)
@php
    $featuredCarouselData = $featuredNovels->map(fn ($n) => [
        'id' => $n->id,
        'slug' => $n->slug,
        'url' => route('novels.show', $n->slug),
        'is_bookmarked' => (bool) ($n->is_bookmarked ?? false),
    ])->values();
@endphp
<div class="relative mb-10 md:mb-12 rounded-b-2xl overflow-hidden border-b border-slate-200 dark:border-slate-800 shadow-sm"
     x-data="featuredCarousel()"
     @mouseenter="paused = true"
     @mouseleave="paused = false">

    <div class="relative h-[380px] sm:h-[420px] md:h-[460px] bg-slate-950">
        {{-- Background layers per slide --}}
        @foreach($featuredNovels as $index => $novel)
            <div x-show="activeSlide === {{ $index }}"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-400"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 w-full h-full pointer-events-none">
                <div class="absolute inset-0 overflow-hidden">
                    @if($novel->cover_image_url)
                        <img src="{{ $novel->cover_image_url }}" alt="" class="w-full h-full object-cover opacity-25 blur-sm scale-105" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                    @elseif($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="" class="w-full h-full object-cover opacity-25 blur-sm scale-105" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/90 to-slate-950/40"></div>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                </div>
            </div>
        @endforeach

        {{-- Konten + tombol tetap --}}
        <div class="relative z-10 h-full max-w-7xl mx-auto px-5 sm:px-8 md:px-12 flex items-center">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center w-full">
                <div class="lg:col-span-7 flex flex-col min-h-[360px] sm:min-h-[400px] justify-start pt-8">
                    {{-- Teks berubah per slide (tinggi tetap) --}}
                    <div class="relative flex-grow min-h-[220px] sm:min-h-[240px] mb-4">
                        @foreach($featuredNovels as $index => $novel)
                            <div x-show="activeSlide === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0 translate-y-4"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 -translate-y-4"
                                 class="absolute inset-0 flex flex-col justify-start">
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="px-2.5 py-0.5 bg-indigo-600 text-white text-[10px] font-semibold uppercase tracking-wide rounded-md">Featured</span>
                                    @foreach($novel->genres->take(2) as $genre)
                                        <span class="px-2.5 py-0.5 bg-white/10 text-white/90 text-[10px] font-medium rounded-md border border-white/10">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white leading-tight tracking-tight line-clamp-2 min-h-[3.5rem] sm:min-h-[4.5rem] mb-2">
                                    {{ $novel->title }}
                                </h2>
                                <p class="text-sm text-slate-400 mb-2 min-h-[1.25rem]">{{ $novel->author->name }}</p>
                                <p class="text-sm text-slate-500 leading-relaxed line-clamp-2 max-w-xl min-h-[2.75rem]">
                                    {{ $novel->description ?? 'Discover interesting stories from this author.' }}
                                </p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Tombol aksi: posisi tetap --}}
                    <div class="flex flex-wrap items-center gap-3 shrink-0 h-11">
                        <a :href="current.url"
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl transition-colors">
                            Read Now
                        </a>

                        <button type="button"
                            @click="toggleBookmark()"
                            :disabled="bookmarkLoading"
                            :class="isBookmarked ? 'text-rose-400 bg-rose-500/20 border-rose-500/30' : 'text-white/80 bg-white/10 border-white/20 hover:bg-white/15'"
                            class="inline-flex items-center justify-center w-11 h-11 text-sm font-semibold rounded-xl border transition-colors shrink-0"
                            title="Bookmark">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" :class="isBookmarked ? 'fill-current' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                        </button>

                        @if($featuredNovels->count() > 1)
                        <div class="flex items-center gap-1 p-1 bg-white/10 rounded-xl border border-white/10 ml-auto">
                            <button type="button" @click="prev(); resetTimer()" class="p-2 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition-colors" aria-label="Previous">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                            </button>
                            @foreach($featuredNovels as $dotIndex => $dotNovel)
                                <button type="button" @click="activeSlide = {{ $dotIndex }}; resetTimer()"
                                    class="h-1.5 rounded-full transition-all"
                                    :class="activeSlide === {{ $dotIndex }} ? 'w-5 bg-indigo-500' : 'w-1.5 bg-white/30'"></button>
                            @endforeach
                            <button type="button" @click="next(); resetTimer()" class="p-2 text-white/70 hover:text-white rounded-lg hover:bg-white/10 transition-colors" aria-label="Next">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Cover: berubah per slide --}}
                <div class="hidden lg:flex lg:col-span-5 justify-center items-center relative h-[300px]">
                    @foreach($featuredNovels as $index => $novel)
                        <a x-show="activeSlide === {{ $index }}"
                           x-transition:enter="transition ease-out duration-500"
                           x-transition:enter-start="opacity-0 scale-95"
                           x-transition:enter-end="opacity-100 scale-100"
                           href="{{ route('novels.show', $novel->slug) }}"
                           class="absolute block w-52 h-[300px] rounded-xl overflow-hidden ring-1 ring-white/20 shadow-2xl">
                            @if($novel->cover_image_url)
                                <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                            @elseif($novel->cover_image)
                                <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Recently updated --}}
<section class="mb-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @include('partials.section-header', [
        'title' => 'Recently Updated',
        'description' => "The latest chapters from readers' favorite novels.",
        'accent' => 'emerald',
        'href' => route('novels.updated'),
    ])

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2.5 sm:gap-3">
        @foreach($recentlyUpdated as $novel)
            <article data-novel-id="{{ $novel->id }}" class="group bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-all duration-300">
                <div class="flex gap-2.5 p-2">
                    {{-- Cover --}}
                    <a href="{{ route('novels.show', $novel->slug) }}" class="shrink-0 w-12 h-18 rounded-md overflow-hidden bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200/50 dark:ring-slate-700/50">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @endif
                    </a>

                    {{-- Info --}}
                    <div class="flex-grow min-w-0 flex flex-col">
                        <div class="mb-auto">
                            <div class="flex items-center gap-1.5 mb-0.5">
                                @foreach($novel->genres->take(1) as $genre)
                                    <span class="text-[8px] font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">{{ $genre->name }}</span>
                                @endforeach
                            </div>
                            <a href="{{ route('novels.show', $novel->slug) }}" class="text-[13px] font-bold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 line-clamp-1 leading-tight transition-colors">{{ $novel->title }}</a>
                        </div>

                        {{-- Latest Chapters --}}
                        @if($novel->chapters->count() > 0)
                        <div class="mt-2 space-y-0.5 pt-1.5 border-t border-slate-100 dark:border-slate-800/50">
                            @foreach($novel->chapters->take(2) as $chapter)
                                <a href="{{ route('chapters.show', [$novel->slug, $chapter->slug]) }}" class="flex items-center justify-between gap-2 group/ch">
                                    <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 group-hover/ch:text-indigo-600 dark:group-hover/ch:text-indigo-400 truncate transition-colors">{{ $chapter->title }}</span>
                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 shrink-0">{{ $chapter->created_at->diffForHumans(null, true) }}</span>
                                </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>

{{-- Genre & Tag --}}
<section class="mb-12 scroll-mt-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div id="genres" class="scroll-mt-28">
            <div class="h-full bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 sm:p-6">
                @include('partials.section-header', [
                    'title' => 'Explore Genres',
                    'description' => 'Choose your favorite story category.',
                    'accent' => 'emerald',
                    'href' => route('genres.index'),
                    'linkText' => 'All genres',
                ])

                <div class="flex flex-wrap gap-2 max-h-[220px] sm:max-h-[280px] overflow-y-auto pr-1">
                    @forelse($genres as $genre)
                        <a href="{{ route('novels.search', ['genre' => $genre->slug]) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full text-xs font-semibold border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 hover:border-emerald-500/60 hover:bg-emerald-500/10 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                            <span>{{ $genre->name }}</span>
                            <span class="text-[10px] font-bold text-slate-400 tabular-nums">{{ $genre->novels_count }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500 py-4">No genres yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div id="tags" class="scroll-mt-28">
            <div class="h-full bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 sm:p-6">
                @include('partials.section-header', [
                    'title' => 'Popular Tags',
                    'description' => 'Filter stories by specific elements.',
                    'accent' => 'indigo',
                    'href' => route('tags.index'),
                    'linkText' => 'All tags',
                ])

                <div class="flex flex-wrap gap-2 max-h-[220px] sm:max-h-[280px] overflow-y-auto pr-1">
                    @forelse($popularTags as $tag)
                        <a href="{{ route('novels.search', ['tag' => $tag->slug]) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-2 rounded-full text-xs font-semibold border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/60 text-slate-600 dark:text-slate-300 hover:border-indigo-500/60 hover:bg-indigo-500/10 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            <span class="text-indigo-500 dark:text-indigo-400">#</span>
                            <span>{{ $tag->name }}</span>
                            <span class="text-[10px] font-bold text-slate-400 tabular-nums">{{ $tag->novels_count }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-slate-500 py-4">No tags yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Newest novels section --}}
<section class="mb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @include('partials.section-header', [
        'title' => 'Newest Novels',
        'description' => 'The latest works recently added to the catalog.',
        'accent' => 'indigo',
    ])

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5 sm:gap-6">
        @forelse($novels->take(12) as $novel)
            <article data-novel-id="{{ $novel->id }}" class="group flex flex-col">
                <a href="{{ route('novels.show', $novel->slug) }}" class="relative aspect-[2/3] w-full rounded-2xl overflow-hidden mb-3 bg-slate-100 dark:bg-slate-800 shadow-sm group-hover:shadow-xl group-hover:shadow-indigo-500/10 group-hover:-translate-y-1 transition-all duration-300">
                    @if($novel->cover_image_url)
                        <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                    @elseif($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                    @else
                        <div class="w-full h-full flex items-center justify-center p-4 bg-slate-50 dark:bg-slate-900">
                            <span class="text-[10px] font-medium text-slate-400 text-center line-clamp-3">{{ $novel->title }}</span>
                        </div>
                    @endif
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    
                    @if($novel->rating_avg > 0)
                    <div class="absolute top-2 right-2 px-1.5 py-0.5 bg-black/60 backdrop-blur-md rounded-lg text-[10px] font-bold text-white flex items-center gap-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 text-amber-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        {{ number_format($novel->rating_avg, 1) }}
                    </div>
                    @endif

                    <div class="absolute bottom-2 left-2 flex flex-wrap gap-1">
                        <span class="px-1.5 py-0.5 bg-indigo-600 text-white text-[8px] font-bold uppercase tracking-wider rounded-md">New</span>
                    </div>
                </a>
                
                <div class="flex flex-col flex-grow">
                    <div class="flex items-center gap-1.5 mb-1">
                        <span class="text-[9px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest truncate">
                            {{ $novel->genres->first()?->name ?? 'Novel' }}
                        </span>
                        <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                        <span class="text-[9px] font-medium text-slate-500 dark:text-slate-400">{{ $novel->chapters_count }} Chapters</span>
                    </div>
                    
                    <a href="{{ route('novels.show', $novel->slug) }}" class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 leading-snug hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                        {{ $novel->title }}
                    </a>
                    
                    <p class="text-[11px] text-slate-500 dark:text-slate-500 mt-1 font-medium truncate">{{ $novel->author->name }}</p>
                </div>
            </article>
        @empty
            <div class="col-span-full py-12 text-center text-sm text-slate-500">No novels yet.</div>
        @endforelse
    </div>
</section>

{{-- Top novels section --}}
<section class="mb-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ tab: 'weekly' }">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            @include('partials.section-header', [
                'title' => 'Top Leaderboard',
                'description' => 'The highest performing novels ranked by activity, rating, and member saves.',
                'accent' => 'indigo',
            ])
        </div>
        
        <div class="flex flex-wrap gap-2 p-1.5 bg-slate-100 dark:bg-slate-800/50 rounded-2xl w-fit border border-slate-200 dark:border-slate-800">
            <button @click="tab = 'weekly'" :class="tab === 'weekly' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">Weekly</button>
            <button @click="tab = 'monthly'" :class="tab === 'monthly' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">Monthly</button>
            <button @click="tab = 'rated'" :class="tab === 'rated' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">Top Rated</button>
            <button @click="tab = 'bookmarks'" :class="tab === 'bookmarks' ? 'bg-white dark:bg-slate-700 text-indigo-600 dark:text-white shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'" class="px-5 py-2 rounded-xl text-xs font-bold uppercase tracking-wider transition-all">Most Saved</button>
        </div>
    </div>

    <div>
        {{-- Weekly Leaderboard --}}
        <template x-if="tab === 'weekly'">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 w-full">
                @if($weeklyTop->isNotEmpty())
                    @php $firstWeekly = $weeklyTop->first(); @endphp
                    <div class="lg:col-span-5">
                        <a href="{{ route('novels.show', $firstWeekly->slug) }}" class="group relative flex flex-col h-full bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 p-6 sm:p-8 hover:border-indigo-500/50 hover:shadow-2xl hover:shadow-indigo-500/5 transition-all duration-300 overflow-hidden">
                            <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/10 transition-colors"></div>
                            
                            <div class="relative flex flex-col sm:flex-row gap-6 items-start">
                                <div class="relative shrink-0 aspect-[2/3] w-full sm:w-44 rounded-xl overflow-hidden shadow-2xl ring-1 ring-slate-200 dark:ring-slate-800 group-hover:scale-[1.02] transition-transform duration-500">
                                    @if($firstWeekly->cover_image_url)
                                        <img src="{{ $firstWeekly->cover_image_url }}" alt="{{ $firstWeekly->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @elseif($firstWeekly->cover_image)
                                        <img src="{{ asset('storage/' . $firstWeekly->cover_image) }}" alt="{{ $firstWeekly->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @endif
                                    <div class="absolute top-0 left-0 w-12 h-12 bg-indigo-600 flex items-center justify-center rounded-br-2xl shadow-lg">
                                        <span class="text-xl font-black text-white italic">#1</span>
                                    </div>
                                </div>
                                
                                <div class="flex-grow pt-2">
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @if($firstWeekly->genres->isNotEmpty())
                                            <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-wider rounded-md border border-indigo-100 dark:border-indigo-500/20">{{ $firstWeekly->genres->first()->name }}</span>
                                        @endif
                                        <span class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider rounded-md border border-slate-100 dark:border-slate-800">{{ $firstWeekly->type }}</span>
                                    </div>
                                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white line-clamp-2 leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors mb-2">{{ $firstWeekly->title }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mb-4">{{ $firstWeekly->author->name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-4 leading-relaxed">{{ $firstWeekly->description ?? 'No description available.' }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Views</span>
                                        <span class="text-lg font-black text-slate-900 dark:text-white tabular-nums">{{ number_format($firstWeekly->period_views ?? $firstWeekly->view_count) }}</span>
                                    </div>
                                    <div class="w-px h-8 bg-slate-100 dark:bg-slate-800"></div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Chapters</span>
                                        <span class="text-lg font-black text-slate-900 dark:text-white tabular-nums">{{ $firstWeekly->chapters_count }}</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-600 text-white group-hover:scale-110 transition-transform shadow-lg shadow-indigo-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </span>
                            </div>
                        </a>
                    </div>
                    
                    <div class="lg:col-span-7 flex flex-col gap-3">
                        @foreach($weeklyTop->slice(1, 4)->values() as $index => $novel)
                            <a href="{{ route('novels.show', $novel->slug) }}" class="flex items-center gap-4 p-3 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 hover:border-indigo-500/50 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-300 group">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl text-lg font-black shrink-0 transition-colors
                                    {{ $index == 0 ? 'bg-amber-100 dark:bg-amber-500/10 text-amber-600 dark:text-amber-500' : '' }}
                                    {{ $index == 1 ? 'bg-slate-100 dark:bg-slate-500/10 text-slate-600 dark:text-slate-400' : '' }}
                                    {{ $index > 1 ? 'text-slate-300 dark:text-slate-700 group-hover:text-indigo-400' : '' }}">
                                    {{ $index + 2 }}
                                </div>
                                <div class="w-14 h-20 shrink-0 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200/50 dark:ring-slate-700/50 group-hover:scale-[1.05] transition-transform duration-300">
                                    @if($novel->cover_image_url)
                                        <img src="{{ $novel->cover_image_url }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @elseif($novel->cover_image)
                                        <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @endif
                                </div>
                                <div class="min-w-0 flex-grow">
                                    <div class="flex items-center gap-2 mb-1">
                                        @if($novel->genres->isNotEmpty())
                                            <span class="text-[9px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ $novel->genres->first()->name }}</span>
                                        @endif
                                        <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                        <span class="text-[10px] font-medium text-slate-400">{{ $novel->type }}</span>
                                    </div>
                                    <h4 class="text-base font-bold text-slate-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-tight">{{ $novel->title }}</h4>
                                    <div class="flex items-center gap-3 mt-1.5">
                                        <p class="text-[12px] text-slate-500 font-medium truncate">{{ $novel->author->name }}</p>
                                        <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-bold tabular-nums">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            <span>{{ number_format($novel->period_views ?? $novel->view_count) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="col-span-full text-center py-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800">
                        <p class="text-slate-500 text-sm">No leaderboard data available yet.</p>
                    </div>
                @endif
            </div>
        </template>

        {{-- Monthly Leaderboard --}}
        <template x-if="tab === 'monthly'">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 w-full">
                @if($monthlyTop->isNotEmpty())
                    @php $firstMonthly = $monthlyTop->first(); @endphp
                    <div class="lg:col-span-5">
                        <a href="{{ route('novels.show', $firstMonthly->slug) }}" class="group relative flex flex-col h-full bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 p-6 sm:p-8 hover:border-indigo-500/50 hover:shadow-2xl hover:shadow-indigo-500/5 transition-all duration-300 overflow-hidden">
                            <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/10 transition-colors"></div>
                            
                            <div class="relative flex flex-col sm:flex-row gap-6 items-start">
                                <div class="relative shrink-0 aspect-[2/3] w-full sm:w-44 rounded-xl overflow-hidden shadow-2xl ring-1 ring-slate-200 dark:ring-slate-800 group-hover:scale-[1.02] transition-transform duration-500">
                                    @if($firstMonthly->cover_image_url)
                                        <img src="{{ $firstMonthly->cover_image_url }}" alt="{{ $firstMonthly->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @elseif($firstMonthly->cover_image)
                                        <img src="{{ asset('storage/' . $firstMonthly->cover_image) }}" alt="{{ $firstMonthly->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @endif
                                    <div class="absolute top-0 left-0 w-12 h-12 bg-indigo-600 flex items-center justify-center rounded-br-2xl shadow-lg">
                                        <span class="text-xl font-black text-white italic">#1</span>
                                    </div>
                                </div>
                                
                                <div class="flex-grow pt-2">
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @if($firstMonthly->genres->isNotEmpty())
                                            <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-wider rounded-md border border-indigo-100 dark:border-indigo-500/20">{{ $firstMonthly->genres->first()->name }}</span>
                                        @endif
                                        <span class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider rounded-md border border-slate-100 dark:border-slate-800">{{ $firstMonthly->type }}</span>
                                    </div>
                                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white line-clamp-2 leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors mb-2">{{ $firstMonthly->title }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mb-4">{{ $firstMonthly->author->name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-4 leading-relaxed">{{ $firstMonthly->description ?? 'No description available.' }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Views</span>
                                        <span class="text-lg font-black text-slate-900 dark:text-white tabular-nums">{{ number_format($firstMonthly->period_views ?? $firstMonthly->view_count) }}</span>
                                    </div>
                                    <div class="w-px h-8 bg-slate-100 dark:bg-slate-800"></div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Chapters</span>
                                        <span class="text-lg font-black text-slate-900 dark:text-white tabular-nums">{{ $firstMonthly->chapters_count }}</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-600 text-white group-hover:scale-110 transition-transform shadow-lg shadow-indigo-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </span>
                            </div>
                        </a>
                    </div>
                    
                    <div class="lg:col-span-7 flex flex-col gap-3">
                        @foreach($monthlyTop->slice(1, 4)->values() as $index => $novel)
                            <a href="{{ route('novels.show', $novel->slug) }}" class="flex items-center gap-4 p-3 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 hover:border-indigo-500/50 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-300 group">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl text-lg font-black shrink-0 transition-colors
                                    {{ $index == 0 ? 'bg-amber-100 dark:bg-amber-500/10 text-amber-600 dark:text-amber-500' : '' }}
                                    {{ $index == 1 ? 'bg-slate-100 dark:bg-slate-500/10 text-slate-600 dark:text-slate-400' : '' }}
                                    {{ $index > 1 ? 'text-slate-300 dark:text-slate-700 group-hover:text-indigo-400' : '' }}">
                                    {{ $index + 2 }}
                                </div>
                                <div class="w-14 h-20 shrink-0 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200/50 dark:ring-slate-700/50 group-hover:scale-[1.05] transition-transform duration-300">
                                    @if($novel->cover_image_url)
                                        <img src="{{ $novel->cover_image_url }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @elseif($novel->cover_image)
                                        <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @endif
                                </div>
                                <div class="min-w-0 flex-grow">
                                    <div class="flex items-center gap-2 mb-1">
                                        @if($novel->genres->isNotEmpty())
                                            <span class="text-[9px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ $novel->genres->first()->name }}</span>
                                        @endif
                                        <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                        <span class="text-[10px] font-medium text-slate-400">{{ $novel->type }}</span>
                                    </div>
                                    <h4 class="text-base font-bold text-slate-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-tight">{{ $novel->title }}</h4>
                                    <div class="flex items-center gap-3 mt-1.5">
                                        <p class="text-[12px] text-slate-500 font-medium truncate">{{ $novel->author->name }}</p>
                                        <div class="flex items-center gap-1.5 text-[11px] text-slate-400 font-bold tabular-nums">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                            <span>{{ number_format($novel->period_views ?? $novel->view_count) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="col-span-full text-center py-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800">
                        <p class="text-slate-500 text-sm">No leaderboard data available yet.</p>
                    </div>
                @endif
            </div>
        </template>

        {{-- Top Rated Leaderboard --}}
        <template x-if="tab === 'rated'">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 w-full">
                @if($topRated->isNotEmpty())
                    @php $firstRated = $topRated->first(); @endphp
                    <div class="lg:col-span-5">
                        <a href="{{ route('novels.show', $firstRated->slug) }}" class="group relative flex flex-col h-full bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 p-6 sm:p-8 hover:border-indigo-500/50 hover:shadow-2xl hover:shadow-indigo-500/5 transition-all duration-300 overflow-hidden">
                            <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/10 transition-colors"></div>
                            
                            <div class="relative flex flex-col sm:flex-row gap-6 items-start">
                                <div class="relative shrink-0 aspect-[2/3] w-full sm:w-44 rounded-xl overflow-hidden shadow-2xl ring-1 ring-slate-200 dark:ring-slate-800 group-hover:scale-[1.02] transition-transform duration-500">
                                    @if($firstRated->cover_image_url)
                                        <img src="{{ $firstRated->cover_image_url }}" alt="{{ $firstRated->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @elseif($firstRated->cover_image)
                                        <img src="{{ asset('storage/' . $firstRated->cover_image) }}" alt="{{ $firstRated->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @endif
                                    <div class="absolute top-0 left-0 w-12 h-12 bg-indigo-600 flex items-center justify-center rounded-br-2xl shadow-lg">
                                        <span class="text-xl font-black text-white italic">#1</span>
                                    </div>
                                </div>
                                
                                <div class="flex-grow pt-2">
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @if($firstRated->genres->isNotEmpty())
                                            <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-wider rounded-md border border-indigo-100 dark:border-indigo-500/20">{{ $firstRated->genres->first()->name }}</span>
                                        @endif
                                        <span class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider rounded-md border border-slate-100 dark:border-slate-800">{{ $firstRated->type }}</span>
                                    </div>
                                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white line-clamp-2 leading-tight group-hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors mb-2">{{ $firstRated->title }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mb-4">{{ $firstRated->author->name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-4 leading-relaxed">{{ $firstRated->description ?? 'No description available.' }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Rating</span>
                                        <span class="text-lg font-black text-amber-500 tabular-nums flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            {{ number_format($firstRated->rating_avg, 1) }}
                                        </span>
                                    </div>
                                    <div class="w-px h-8 bg-slate-100 dark:bg-slate-800"></div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Chapters</span>
                                        <span class="text-lg font-black text-slate-900 dark:text-white tabular-nums">{{ $firstRated->chapters_count }}</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-600 text-white group-hover:scale-110 transition-transform shadow-lg shadow-indigo-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </span>
                            </div>
                        </a>
                    </div>
                    
                    <div class="lg:col-span-7 flex flex-col gap-3">
                        @foreach($topRated->slice(1, 4)->values() as $index => $novel)
                            <a href="{{ route('novels.show', $novel->slug) }}" class="flex items-center gap-4 p-3 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 hover:border-indigo-500/50 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-300 group">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl text-lg font-black shrink-0 transition-colors
                                    {{ $index == 0 ? 'bg-amber-100 dark:bg-amber-500/10 text-amber-600 dark:text-amber-500' : '' }}
                                    {{ $index == 1 ? 'bg-slate-100 dark:bg-slate-500/10 text-slate-600 dark:text-slate-400' : '' }}
                                    {{ $index > 1 ? 'text-slate-300 dark:text-slate-700 group-hover:text-indigo-400' : '' }}">
                                    {{ $index + 2 }}
                                </div>
                                <div class="w-14 h-20 shrink-0 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200/50 dark:ring-slate-700/50 group-hover:scale-[1.05] transition-transform duration-300">
                                    @if($novel->cover_image_url)
                                        <img src="{{ $novel->cover_image_url }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @elseif($novel->cover_image)
                                        <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @endif
                                </div>
                                <div class="min-w-0 flex-grow">
                                    <div class="flex items-center gap-2 mb-1">
                                        @if($novel->genres->isNotEmpty())
                                            <span class="text-[9px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ $novel->genres->first()->name }}</span>
                                        @endif
                                        <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                        <span class="text-[10px] font-medium text-slate-400">{{ $novel->type }}</span>
                                    </div>
                                    <h4 class="text-base font-bold text-slate-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-tight">{{ $novel->title }}</h4>
                                    <div class="flex items-center gap-3 mt-1.5">
                                        <p class="text-[12px] text-slate-500 font-medium truncate">{{ $novel->author->name }}</p>
                                        <div class="flex items-center gap-1 text-[11px] text-amber-500 font-bold tabular-nums">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                            <span>{{ number_format($novel->rating_avg, 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="col-span-full text-center py-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800">
                        <p class="text-slate-500 text-sm">No leaderboard data available yet.</p>
                    </div>
                @endif
            </div>
        </template>

        {{-- Most Saved Leaderboard --}}
        <template x-if="tab === 'bookmarks'">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 w-full">
                @if($mostBookmarked->isNotEmpty())
                    @php $firstSaved = $mostBookmarked->first(); @endphp
                    <div class="lg:col-span-5">
                        <a href="{{ route('novels.show', $firstSaved->slug) }}" class="group relative flex flex-col h-full bg-white dark:bg-slate-900 rounded-[2rem] border border-slate-200 dark:border-slate-800 p-6 sm:p-8 hover:border-indigo-500/50 hover:shadow-2xl hover:shadow-indigo-500/5 transition-all duration-300 overflow-hidden">
                            <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/10 transition-colors"></div>
                            
                            <div class="relative flex flex-col sm:flex-row gap-6 items-start">
                                <div class="relative shrink-0 aspect-[2/3] w-full sm:w-44 rounded-xl overflow-hidden shadow-2xl ring-1 ring-slate-200 dark:ring-slate-800 group-hover:scale-[1.02] transition-transform duration-500">
                                    @if($firstSaved->cover_image_url)
                                        <img src="{{ $firstSaved->cover_image_url }}" alt="{{ $firstSaved->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @elseif($firstSaved->cover_image)
                                        <img src="{{ asset('storage/' . $firstSaved->cover_image) }}" alt="{{ $firstSaved->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @endif
                                    <div class="absolute top-0 left-0 w-12 h-12 bg-indigo-600 flex items-center justify-center rounded-br-2xl shadow-lg">
                                        <span class="text-xl font-black text-white italic">#1</span>
                                    </div>
                                </div>
                                
                                <div class="flex-grow pt-2">
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        @if($firstSaved->genres->isNotEmpty())
                                            <span class="px-2 py-0.5 bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-wider rounded-md border border-indigo-100 dark:border-indigo-500/20">{{ $firstSaved->genres->first()->name }}</span>
                                        @endif
                                        <span class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase tracking-wider rounded-md border border-slate-100 dark:border-slate-800">{{ $firstSaved->type }}</span>
                                    </div>
                                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white line-clamp-2 leading-tight group-hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors mb-2">{{ $firstSaved->title }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mb-4">{{ $firstSaved->author->name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-4 leading-relaxed">{{ $firstSaved->description ?? 'No description available.' }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Saves</span>
                                        <span class="text-lg font-black text-rose-500 tabular-nums flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" /></svg>
                                            {{ number_format($firstSaved->bookmarks_count) }}
                                        </span>
                                    </div>
                                    <div class="w-px h-8 bg-slate-100 dark:bg-slate-800"></div>
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Chapters</span>
                                        <span class="text-lg font-black text-slate-900 dark:text-white tabular-nums">{{ $firstSaved->chapters_count }}</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-600 text-white group-hover:scale-110 transition-transform shadow-lg shadow-indigo-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                                </span>
                            </div>
                        </a>
                    </div>
                    
                    <div class="lg:col-span-7 flex flex-col gap-3">
                        @foreach($mostBookmarked->slice(1, 4)->values() as $index => $novel)
                            <a href="{{ route('novels.show', $novel->slug) }}" class="flex items-center gap-4 p-3 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200/60 dark:border-slate-800/80 hover:border-indigo-500/50 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-300 group">
                                <div class="w-12 h-12 flex items-center justify-center rounded-xl text-lg font-black shrink-0 transition-colors
                                    {{ $index == 0 ? 'bg-amber-100 dark:bg-amber-500/10 text-amber-600 dark:text-amber-500' : '' }}
                                    {{ $index == 1 ? 'bg-slate-100 dark:bg-slate-500/10 text-slate-600 dark:text-slate-400' : '' }}
                                    {{ $index > 1 ? 'text-slate-300 dark:text-slate-700 group-hover:text-indigo-400' : '' }}">
                                    {{ $index + 2 }}
                                </div>
                                <div class="w-14 h-20 shrink-0 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200/50 dark:ring-slate-700/50 group-hover:scale-[1.05] transition-transform duration-300">
                                    @if($novel->cover_image_url)
                                        <img src="{{ $novel->cover_image_url }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @elseif($novel->cover_image)
                                        <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                    @endif
                                </div>
                                <div class="min-w-0 flex-grow">
                                    <div class="flex items-center gap-2 mb-1">
                                        @if($novel->genres->isNotEmpty())
                                            <span class="text-[9px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">{{ $novel->genres->first()->name }}</span>
                                        @endif
                                        <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                                        <span class="text-[10px] font-medium text-slate-400">{{ $novel->type }}</span>
                                    </div>
                                    <h4 class="text-base font-bold text-slate-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-tight">{{ $novel->title }}</h4>
                                    <div class="flex items-center gap-3 mt-1.5">
                                        <p class="text-[12px] text-slate-500 font-medium truncate">{{ $novel->author->name }}</p>
                                        <div class="flex items-center gap-1 text-[11px] text-rose-500 font-bold tabular-nums">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" /></svg>
                                            <span>{{ number_format($novel->bookmarks_count) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="col-span-full text-center py-16 bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800">
                        <p class="text-slate-500 text-sm">No leaderboard data available yet.</p>
                    </div>
                @endif
            </div>
        </template>
    </div>
</section>

{{-- Popular --}}
<section class="mb-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @include('partials.section-header', [
        'title' => 'Popular Showcase',
        'description' => 'Novels with the highest rating and reader engagement.',
        'accent' => 'rose',
    ])

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($popularNovels as $novel)
            <article class="group relative bg-white dark:bg-slate-900 rounded-3xl border border-slate-200/60 dark:border-slate-800/80 p-4 hover:border-rose-500/50 hover:shadow-2xl hover:shadow-rose-500/5 transition-all duration-300">
                <div class="flex gap-5">
                    <a href="{{ route('novels.show', $novel->slug) }}" class="relative shrink-0 aspect-[2/3] w-24 sm:w-28 rounded-2xl overflow-hidden shadow-lg group-hover:scale-105 transition-transform duration-300 bg-slate-100 dark:bg-slate-800">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                    </a>
                    
                    <div class="flex-grow min-w-0 flex flex-col py-1">
                        <div class="mb-auto">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 bg-rose-50 dark:bg-rose-500/10 text-rose-500 text-[9px] font-bold uppercase tracking-wider rounded-md border border-rose-100 dark:border-rose-500/20">
                                    {{ $novel->genres->first()?->name ?? 'Story' }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 tabular-nums">{{ number_format($novel->view_count) }} views</span>
                            </div>
                            
                            <a href="{{ route('novels.show', $novel->slug) }}" class="block">
                                <h3 class="text-base font-black text-slate-900 dark:text-white line-clamp-1 group-hover:text-rose-500 transition-colors leading-tight">{{ $novel->title }}</h3>
                            </a>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium truncate">{{ $novel->author->name }}</p>
                        </div>
                        
                        <div class="flex items-center gap-3 pt-3 border-t border-slate-100 dark:border-slate-800 mt-3">
                            @if($novel->rating_avg > 0)
                                <div class="flex items-center gap-1 text-amber-500 font-bold text-xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    {{ number_format($novel->rating_avg, 1) }}
                                </div>
                                <span class="w-1 h-1 rounded-full bg-slate-200 dark:bg-slate-700"></span>
                            @endif
                            <div class="text-[11px] text-slate-400 font-bold uppercase tracking-tight">
                                {{ $novel->chapters_count }} Chapters
                            </div>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('novels.show', $novel->slug) }}" class="absolute inset-0 z-10"></a>
            </article>
        @endforeach
    </div>
</section>
@endsection

@if($featuredNovels->count() > 0)
@push('scripts')
<script>
function featuredCarousel() {
    return {
        activeSlide: 0,
        slideCount: {{ $featuredNovels->count() }},
        novels: {!! json_encode($featuredCarouselData) !!},
        paused: false,
        timer: null,
        bookmarkLoading: false,
        isAuthenticated: {{ Auth::check() ? 'true' : 'false' }},
        loginUrl: @json(route('login')),
        csrfToken: @json(csrf_token()),
        get current() {
            return this.novels[this.activeSlide] ?? {};
        },
        get isBookmarked() {
            return !!this.current.is_bookmarked;
        },
        next() {
            this.activeSlide = (this.activeSlide + 1) % this.slideCount;
        },
        prev() {
            this.activeSlide = (this.activeSlide - 1 + this.slideCount) % this.slideCount;
        },
        init() {
            if (this.slideCount > 1) {
                this.startTimer();
            }
        },
        startTimer() {
            this.timer = setInterval(() => {
                if (!this.paused) {
                    this.next();
                }
            }, 6000);
        },
        resetTimer() {
            clearInterval(this.timer);
            this.startTimer();
        },
        toggleBookmark() {
            if (!this.isAuthenticated) {
                window.location.href = this.loginUrl;
                return;
            }
            if (this.bookmarkLoading) {
                return;
            }
            this.bookmarkLoading = true;
            const id = this.current.id;
            fetch(`/novels/${id}/bookmark`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
                .then((r) => r.json())
                .then((d) => {
                    const i = this.activeSlide;
                    if (this.novels[i]) {
                        this.novels[i] = { ...this.novels[i], is_bookmarked: d.status === 'added' };
                    }
                    this.bookmarkLoading = false;
                })
                .catch(() => {
                    this.bookmarkLoading = false;
                });
        },
    };
}
</script>
@endpush
@endif

@push('styles')
<style>[x-cloak] { display: none !important; }</style>
@endpush
