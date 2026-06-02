@extends('layouts.app')

@section('content')
@php
    $featuredCarouselData = $featuredNovels->map(fn ($n) => [
        'id' => $n->id,
        'slug' => $n->slug,
        'url' => route('novels.show', $n->slug),
        'title' => $n->title,
        'author' => $n->author->name,
        'description' => Str::limit(strip_tags($n->description ?? ''), 140),
        'genre' => $n->genres->first()?->name,
        'chapters' => $n->chapters_count,
        'cover' => $n->cover_image_url ?: ($n->cover_image ? asset('storage/' . $n->cover_image) : null),
        'is_bookmarked' => (bool) ($n->is_bookmarked ?? false),
    ])->values();
@endphp

<div class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100">

    {{-- Hero --}}
    <section class="relative overflow-hidden border-b border-slate-200 dark:border-slate-800"
             @if($featuredCarouselData->isNotEmpty()) x-data="landingHero(@js($featuredCarouselData))" @endif>
        <div class="absolute inset-0">
            <img src="{{ asset('storage/banners/landingBanner.png') }}"
                 alt=""
                 class="w-full h-full object-cover opacity-30 dark:opacity-25 scale-105"
                 onerror="this.style.display='none'">
            <div class="absolute inset-0 bg-gradient-to-b from-white/90 via-white/80 to-slate-50 dark:from-slate-950/95 dark:via-slate-950/90 dark:to-slate-950"></div>
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_60%_at_50%_-10%,rgba(99,102,241,0.12),transparent)]"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20 lg:py-24">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                <div class="max-w-xl text-center lg:text-left">
                    <p class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] sm:text-[11px] font-semibold uppercase tracking-wider bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20 mb-6">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                        Premium novel platform
                    </p>
                    <h1 class="text-3xl sm:text-5xl lg:text-[3.25rem] font-bold tracking-tight text-slate-900 dark:text-white leading-[1.1] mb-5">
                        Read the best stories,<br>
                        <span class="text-indigo-600 dark:text-indigo-400">translated with passion.</span>
                    </h1>
                    <p class="text-sm sm:text-lg text-slate-600 dark:text-slate-400 leading-relaxed mb-8 max-w-md mx-auto lg:mx-0">
                        Complete novel catalog, regular chapter updates, and a comfortable reading experience on any device.
                    </p>
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3">
                        <a href="{{ route('home') }}"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl shadow-lg shadow-indigo-600/25 transition-colors">
                            Explore Catalog
                        </a>
                        @guest
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-colors">
                            Create Free Account
                        </a>
                        @else
                        <a href="{{ route('novels.updated') }}"
                           class="inline-flex items-center px-6 py-3 text-sm font-semibold rounded-xl border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition-colors">
                            Latest Chapters
                        </a>
                        @endguest
                    </div>
                </div>

                @if($featuredCarouselData->isNotEmpty())
                <div class="relative"
                     @mouseenter="paused = true"
                     @mouseleave="paused = false">
                    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl border border-slate-200/80 dark:border-slate-800 p-4 sm:p-6 shadow-xl shadow-slate-900/5">
                        <div class="flex gap-4 sm:gap-5">
                            <a :href="current.url" class="shrink-0 w-24 sm:w-32 aspect-[2/3] rounded-xl overflow-hidden bg-slate-200 dark:bg-slate-800 ring-1 ring-slate-200 dark:ring-slate-700">
                                <template x-for="(novel, index) in novels" :key="novel.id">
                                    <img x-show="activeSlide === index"
                                         :src="novel.cover || '/error.png'"
                                         :alt="novel.title"
                                         class="w-full h-full object-cover"
                                         x-transition:enter="transition ease-out duration-400"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100">
                                </template>
                            </a>
                            <div class="flex flex-col min-w-0 flex-1 py-0.5">
                                <p class="text-[9px] sm:text-[10px] font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 mb-1.5 sm:mb-2" x-text="current.genre || 'Featured'"></p>
                                <h2 class="text-base sm:text-xl font-bold text-slate-900 dark:text-white line-clamp-2 leading-snug mb-0.5 sm:mb-1" x-text="current.title"></h2>
                                <p class="text-[11px] sm:text-xs text-slate-500 dark:text-slate-400 mb-2 sm:mb-3" x-text="current.author"></p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 line-clamp-2 sm:line-clamp-3 leading-relaxed flex-grow" x-text="current.description"></p>
                                <div class="flex items-center justify-between gap-3 mt-3 sm:mt-4 pt-3 sm:pt-4 border-t border-slate-200 dark:border-slate-800">
                                    <span class="text-[10px] sm:text-xs font-medium text-slate-500" x-text="current.chapters + ' chapters'"></span>
                                    <div class="flex items-center gap-2">
                                        @if($featuredCarouselData->count() > 1)
                                        <div class="flex gap-1">
                                            <template x-for="(novel, index) in novels" :key="'dot-'+novel.id">
                                                <button type="button" @click="goTo(index)"
                                                    class="h-1 rounded-full transition-all"
                                                    :class="activeSlide === index ? 'w-4 bg-indigo-500' : 'w-1 bg-slate-300 dark:bg-slate-600'"></button>
                                            </template>
                                        </div>
                                        @endif
                                        <a :href="current.url" class="text-[11px] sm:text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 whitespace-nowrap">
                                            Read
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>

    {{-- Statistik --}}
    <section class="border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                @foreach([
                    ['label' => 'Novels', 'value' => number_format($stats['novels'])],
                    ['label' => 'Chapters', 'value' => number_format($stats['chapters'])],
                    ['label' => 'Genres', 'value' => number_format($stats['genres'])],
                    ['label' => 'Chapters this week', 'value' => number_format($stats['updates_week'])],
                ] as $stat)
                <div class="text-center lg:text-left px-2">
                    <p class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tabular-nums tracking-tight">{{ $stat['value'] }}</p>
                    <p class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">{{ $stat['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Unggulan --}}
    @if($featuredNovels->count() > 0)
    <section class="py-14 sm:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('partials.section-header', [
                'title' => "Editor's Choice",
                'description' => 'Handpicked novels with top-tier translation quality.',
                'accent' => 'indigo',
                'href' => route('home'),
            ])

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($featuredNovels->take(6) as $novel)
                <a href="{{ route('novels.show', $novel->slug) }}"
                   class="group flex gap-4 p-4 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 hover:border-indigo-300 dark:hover:border-indigo-700 transition-colors">
                    <div class="shrink-0 w-16 aspect-[2/3] rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.src='/error.png'">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.src='/error.png'">
                        @endif
                    </div>
                    <div class="min-w-0 flex flex-col justify-center">
                        @if($novel->genres->first())
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-indigo-600 dark:text-indigo-400 mb-1">{{ $novel->genres->first()->name }}</span>
                        @endif
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $novel->title }}</h3>
                        <p class="text-xs text-slate-500 mt-1 truncate">{{ $novel->author->name }}</p>
                        <p class="text-[11px] text-slate-400 mt-2">{{ $novel->chapters_count }} chapters</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Pembaruan terbaru --}}
    @if($recentlyUpdated->count() > 0)
    <section class="py-14 sm:py-16 bg-white dark:bg-slate-900/40 border-y border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @include('partials.section-header', [
                'title' => 'Latest Updates',
                'description' => 'Newly released chapters from your favorite series.',
                'accent' => 'emerald',
                'href' => route('novels.updated'),
            ])

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4">
                @foreach($recentlyUpdated as $novel)
                <article class="group relative flex flex-col bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm hover:border-indigo-200 dark:hover:border-indigo-900/50 transition-all duration-300 overflow-hidden">
                    {{-- Cover & Image Overlay --}}
                    <div class="relative aspect-[16/11] overflow-hidden">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" onerror="this.src='/error.png'">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" onerror="this.src='/error.png'">
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent opacity-40"></div>
                        
                        {{-- Genre Badge on Image --}}
                        <div class="absolute top-2 left-2">
                            @foreach($novel->genres->take(1) as $genre)
                            <span class="px-1.5 py-0.5 text-[8px] font-bold uppercase tracking-wider bg-white/90 dark:bg-slate-950/90 text-indigo-600 dark:text-indigo-400 rounded">
                                {{ $genre->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex flex-col flex-grow p-2.5">
                        <div class="mb-auto">
                            <div class="flex items-center justify-between gap-1.5 mb-1.5">
                                <span class="text-[9px] font-medium text-slate-400 dark:text-slate-500">
                                    {{ $novel->chapters_max_created_at ? \Illuminate\Support\Carbon::parse($novel->chapters_max_created_at)->diffForHumans(null, true) : '—' }}
                                </span>
                                <span class="text-[9px] font-medium text-slate-400 dark:text-slate-500">
                                    {{ number_format($novel->view_count) }} views
                                </span>
                            </div>
                            
                            <h3 class="text-[13px] font-bold text-slate-900 dark:text-white line-clamp-1 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors leading-tight">
                                <a href="{{ route('novels.show', $novel->slug) }}">{{ $novel->title }}</a>
                            </h3>
                        </div>

                        {{-- Latest Chapter CTA --}}
                        @if($novel->chapters->isNotEmpty())
                        <a href="{{ route('chapters.show', [$novel->slug, $novel->chapters->first()->slug]) }}" 
                           class="mt-2 inline-flex items-center justify-center w-full px-2 py-1.5 bg-slate-50 dark:bg-slate-800/50 hover:bg-indigo-50 dark:hover:bg-indigo-950/30 rounded-lg border border-slate-100 dark:border-slate-800/50 group/btn transition-all duration-300">
                            <span class="text-[9px] font-bold text-slate-600 dark:text-slate-400 group-hover/btn:text-indigo-600 dark:group-hover/btn:text-indigo-400 truncate">
                                {{ $novel->chapters->first()->title }}
                            </span>
                        </a>
                        @endif
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Fitur --}}
    <section class="py-14 sm:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-12">
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Why Quoros?</h2>
                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 mt-3 leading-relaxed">
                    Built for readers who prioritize translation quality and long-term reading comfort.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @foreach([
                    ['title' => 'Quality Translations', 'desc' => "Our team preserves the story's nuances and the author's original style.", 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'color' => 'indigo'],
                    ['title' => 'Regular Updates', 'desc' => 'New chapters are available as soon as the translation process is complete.', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'color' => 'emerald'],
                    ['title' => 'Reading Experience', 'desc' => 'Bookmarks, reading history, and dark mode for a more comfortable experience.', 'icon' => 'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z', 'color' => 'amber'],
                ] as $feature)
                <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-4
                        @if($feature['color'] === 'indigo') bg-indigo-500/10 text-indigo-600 dark:text-indigo-400
                        @elseif($feature['color'] === 'emerald') bg-emerald-500/10 text-emerald-600 dark:text-emerald-400
                        @else bg-amber-500/10 text-amber-600 dark:text-amber-400 @endif">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $feature['icon'] }}"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-2">{{ $feature['title'] }}</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="pb-16 sm:pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-2xl bg-slate-900 dark:bg-slate-900 border border-slate-800 px-6 py-10 sm:px-10 sm:py-12">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(99,102,241,0.2),transparent_50%)]"></div>
                <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight">Ready to start reading?</h2>
                        <p class="text-sm text-slate-400 mt-2 max-w-md">Save your reading progress, bookmarks, and favorite novels all in one place.</p>
                    </div>
                    <div class="flex flex-wrap gap-3 shrink-0">
                        <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold rounded-xl transition-colors">
                            Open Catalog
                        </a>
                        @guest
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white/10 hover:bg-white/15 text-white text-sm font-semibold rounded-xl border border-white/20 transition-colors">
                            Sign Up Now
                        </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@push('scripts')
<script>
    function landingHero(initialNovels) {
        return {
            activeSlide: 0,
            novels: initialNovels,
            paused: false,
            timer: null,
            get current() { return this.novels[this.activeSlide] || {}; },
            get slideCount() { return this.novels.length; },
            init() {
                if (this.slideCount > 1) this.startTimer();
            },
            goTo(index) {
                this.activeSlide = index;
                this.resetTimer();
            },
            next() {
                this.activeSlide = (this.activeSlide + 1) % this.slideCount;
            },
            startTimer() {
                this.timer = setInterval(() => {
                    if (!this.paused) this.next();
                }, 6000);
            },
            resetTimer() {
                clearInterval(this.timer);
                if (this.slideCount > 1) this.startTimer();
            },
        };
    }
</script>
@endpush
