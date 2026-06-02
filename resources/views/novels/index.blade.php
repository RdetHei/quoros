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
<div class="relative mb-10 md:mb-12 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-sm"
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
                <div class="lg:col-span-7 flex flex-col min-h-[280px] sm:min-h-[300px] justify-end">
                    {{-- Teks berubah per slide (tinggi tetap) --}}
                    <div class="relative flex-grow min-h-[180px] sm:min-h-[200px] mb-6">
                        @foreach($featuredNovels as $index => $novel)
                            <div x-show="activeSlide === {{ $index }}"
                                 x-transition:enter="transition ease-out duration-500"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-300"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="absolute inset-0 flex flex-col justify-end">
                                <div class="flex flex-wrap gap-2 mb-4 min-h-[28px]">
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
<section class="mb-12">
    @include('partials.section-header', [
        'title' => 'Recently Updated',
        'description' => "The latest chapters from readers' favorite novels.",
        'accent' => 'emerald',
        'href' => route('novels.updated'),
    ])

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2.5 sm:gap-3">
        @foreach($recentlyUpdated as $novel)
            <article class="group bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 overflow-hidden hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-all duration-300">
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
<section class="mb-12 scroll-mt-24">
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

{{-- Newest novels + leaderboard --}}
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-12">
    <section class="lg:col-span-8 xl:col-span-9">
        @include('partials.section-header', [
            'title' => 'Newest Novels',
            'description' => 'The latest works recently added to the catalog.',
            'accent' => 'slate',
        ])

        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @forelse($novels as $novel)
                <a href="{{ route('novels.show', $novel->slug) }}" class="group block">
                    <div class="relative aspect-[2/3] mb-2 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200 dark:ring-slate-700 group-hover:ring-indigo-400 dark:group-hover:ring-indigo-600 transition-all">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                        @else
                            <div class="w-full h-full flex items-center justify-center p-2">
                                <span class="text-[10px] font-medium text-slate-400 text-center line-clamp-3">{{ $novel->title }}</span>
                            </div>
                        @endif
                        @if($novel->rating_avg > 0)
                        <div class="absolute top-1.5 right-1.5 px-1.5 py-0.5 bg-black/60 backdrop-blur-sm rounded text-[10px] font-semibold text-white flex items-center gap-0.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5 text-amber-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            {{ number_format($novel->rating_avg, 1) }}
                        </div>
                        @endif
                    </div>
                    <h3 class="text-xs font-semibold text-slate-900 dark:text-white line-clamp-2 leading-snug group-hover:text-indigo-600 transition-colors">{{ $novel->title }}</h3>
                    <p class="text-[10px] text-slate-500 mt-0.5 truncate">{{ $novel->author->name }}</p>
                </a>
            @empty
                <div class="col-span-full py-12 text-center text-sm text-slate-500">No novels yet.</div>
            @endforelse
        </div>

        @if($novels->hasPages())
            <div class="mt-8 flex justify-center">{{ $novels->links() }}</div>
        @endif
    </section>

    <aside class="lg:col-span-4 xl:col-span-3" x-data="{ tab: 'weekly' }">
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden sticky top-24">
            <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                <h2 class="text-base font-semibold text-slate-900 dark:text-white">Top Novels</h2>
                <p class="text-xs text-slate-500 mt-0.5">Based on daily views</p>
                <a href="{{ route('novels.trending') }}" class="text-[10px] font-bold text-indigo-600 dark:text-indigo-400 hover:underline mt-1 inline-block">View all</a>
            </div>

            <div class="flex p-2 gap-1 border-b border-slate-100 dark:border-slate-800">
                <button @click="tab = 'weekly'" :class="tab === 'weekly' ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800'" class="flex-1 py-1.5 rounded-lg text-xs font-semibold transition-colors">Weekly</button>
                <button @click="tab = 'monthly'" :class="tab === 'monthly' ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900' : 'text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800'" class="flex-1 py-1.5 rounded-lg text-xs font-semibold transition-colors">Monthly</button>
            </div>

            <div class="p-2 max-h-[480px] overflow-y-auto">
                <div x-show="tab === 'weekly'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @forelse($weeklyTop as $index => $novel)
                        <a href="{{ route('novels.show', $novel->slug) }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <span class="w-6 text-center text-sm font-bold {{ $index < 3 ? 'text-amber-500' : 'text-slate-400' }}">{{ $index + 1 }}</span>
                            <div class="w-10 h-14 shrink-0 rounded-md overflow-hidden bg-slate-100 dark:bg-slate-800">
                                @if($novel->cover_image_url)
                                    <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                @elseif($novel->cover_image)
                                    <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                @endif
                            </div>
                            <div class="min-w-0 flex-grow">
                                <p class="text-xs font-semibold text-slate-900 dark:text-white line-clamp-1">{{ $novel->title }}</p>
                                <p class="text-[10px] text-slate-500 truncate">{{ number_format($novel->period_views ?? 0) }} views (7d)</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-center py-6 text-xs text-slate-400">No data yet.</p>
                    @endforelse
                </div>
                <div x-show="tab === 'monthly'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    @forelse($monthlyTop as $index => $novel)
                        <a href="{{ route('novels.show', $novel->slug) }}" class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <span class="w-6 text-center text-sm font-bold {{ $index < 3 ? 'text-amber-500' : 'text-slate-400' }}">{{ $index + 1 }}</span>
                            <div class="w-10 h-14 shrink-0 rounded-md overflow-hidden bg-slate-100 dark:bg-slate-800">
                                @if($novel->cover_image_url)
                                    <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                @elseif($novel->cover_image)
                                    <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                @endif
                            </div>
                            <div class="min-w-0 flex-grow">
                                <p class="text-xs font-semibold text-slate-900 dark:text-white line-clamp-1">{{ $novel->title }}</p>
                                <p class="text-[10px] text-slate-500 truncate">{{ number_format($novel->period_views ?? 0) }} views (30d)</p>
                            </div>
                        </a>
                    @empty
                        <p class="text-center py-6 text-xs text-slate-400">No data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </aside>
</div>

{{-- Populer --}}
<section class="mb-8">
    @include('partials.section-header', [
        'title' => 'Popular',
        'description' => 'Novels with the highest rating and engagement.',
        'accent' => 'rose',
    ])

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($novels->take(6) as $novel)
            <a href="{{ route('novels.show', $novel->slug) }}" class="flex gap-4 p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors group">
                <div class="w-16 h-[5.5rem] shrink-0 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200 dark:ring-slate-700">
                    @if($novel->cover_image_url)
                        <img src="{{ $novel->cover_image_url }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                    @elseif($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                    @endif
                </div>
                <div class="min-w-0 flex flex-col justify-center">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ $novel->title }}</h3>
                    <p class="text-xs text-slate-500 mt-0.5 mb-2">{{ $novel->author->name }}</p>
                    <div class="flex flex-wrap items-center gap-2 text-[10px] text-slate-500">
                        @if($novel->rating_avg > 0)
                            <span class="flex items-center gap-0.5 text-amber-600 font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                {{ number_format($novel->rating_avg, 1) }}
                            </span>
                        @endif
                        <span>{{ number_format($novel->view_count) }} views</span>
                        <span>{{ $novel->chapters->count() }} chapters</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endsection

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

@push('styles')
<style>[x-cloak] { display: none !important; }</style>
@endpush
