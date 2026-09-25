@extends('layouts.app')

@section('content')
@php
    $featuredCarouselData = $featuredNovels->map(fn ($n) => [
        'id'            => $n->id,
        'slug'          => $n->slug,
        'url'           => route('novels.show', $n->slug),
        'title'         => $n->title,
        'author'        => $n->author->name,
        'description'   => Str::limit(strip_tags($n->description ?? ''), 140),
        'genre'         => $n->genres->first()?->name,
        'chapters'      => $n->chapters_count,
        'rating'        => (float) ($n->rating_avg ?? 4.8),
        'cover'         => $n->cover_image_url ?: ($n->cover_image ? asset('storage/' . $n->cover_image) : null),
        'is_bookmarked' => (bool) ($n->is_bookmarked ?? false),
    ])->values();
@endphp



<div class="landing-page min-h-screen">

    {{-- ═══════════════════════════════════════════
         HERO — Featured Stack Visual
    ═══════════════════════════════════════════ --}}
    <section class="relative"
             @if($featuredCarouselData->isNotEmpty()) x-data="landingHero(@js($featuredCarouselData))" @endif>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 sm:pt-14 pb-12 sm:pb-16">

            <div class="hero-wrap border border-white/5 shadow-2xl shadow-black/40"
                 :style="{ borderColor: activeIndex ? 'transparent' : 'rgba(255,255,255,0.05)' }">

                @if($featuredCarouselData->isNotEmpty())
                <template x-for="(novel, index) in novels" :key="'bg'+novel.id">
                    <div class="hero-cover-bg"
                         x-show="activeIndex === index"
                         x-transition:enter="transition duration-700 ease-out"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-32"
                         x-transition:leave="transition duration-500 ease-in"
                         x-transition:leave-start="opacity-32"
                         x-transition:leave-end="opacity-0"
                         :style="`background-image: url('${novel.cover || '/error.png'}');`"></div>
                </template>
                @else
                <div class="hero-cover-bg" style="background-image: linear-gradient(135deg,#1e1b4b,#0f172a); opacity:0.6;"></div>
                @endif
                <div class="hero-bg-vignette"></div>
                <div class="hero-grid-overlay"></div>

                <div class="relative z-10 px-5 sm:px-8 lg:px-12 py-10 sm:py-14 lg:py-16">
                    <div class="grid lg:grid-cols-2 gap-10 lg:gap-14 items-center">

                        {{-- Kiri: Copy + Featured info terintegrasi --}}
                        <div>
                            <div class="eyebrow-tag mb-5">
                                <span class="eyebrow-dot"></span>
                                <span x-text="novels.length > 0 ? 'Pilihan Editor Minggu Ini' : 'Selamat Datang di Quoros'">Pilihan Editor Minggu Ini</span>
                            </div>

                            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black tracking-tight leading-[1.02] mb-4" style="color:#f8fafc;">
                                Temukan cerita yang<br>
                                <span style="background: linear-gradient(135deg,#a5b4fc 0%,#818cf8 50%,#34d399 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">
                                    akan membuatmu lupa waktu.
                                </span>
                            </h1>
                            <p class="text-sm sm:text-base leading-relaxed mb-7 max-w-lg" style="color:#64748b;">
                                Ribuan novel terjemahan & original berkualitas — diperbarui setiap hari, antarmuka bersih, tanpa gangguan.
                            </p>

                            {{-- Featured novel info block (tampilkan saat ada data) --}}
                            @if($featuredCarouselData->isNotEmpty())
                            <div class="rounded-xl border border-white/5 p-4 sm:p-5 mb-7 transition-all duration-300"
                                 style="background: rgba(255,255,255,0.02); backdrop-filter: blur(8px);">
                                <div class="flex gap-4 items-start">
                                    <div class="shrink-0">
                                        <a :href="current.url" class="block">
                                            <div class="w-16 sm:w-20 rounded-lg overflow-hidden bg-slate-800 ring-1 ring-white/10 shadow-lg shadow-black/40"
                                                 style="aspect-ratio: 2/3;">
                                                <template x-for="(novel, index) in novels" :key="'mini'+novel.id">
                                                    <img x-show="activeIndex === index"
                                                         :src="novel.cover || '/error.png'"
                                                         :alt="novel.title"
                                                         class="w-full h-full object-cover"
                                                         x-transition:enter="transition duration-300"
                                                         x-transition:enter-start="opacity-0 scale-95"
                                                         x-transition:enter-end="opacity-100 scale-100">
                                                </template>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap mb-1.5">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold"
                                                  style="background: rgba(99,102,241,0.14); color:#a5b4fc; border:1px solid rgba(99,102,241,0.22);"
                                                  x-text="current.genre || 'Featured'"></span>
                                            <span class="ec-rating">
                                                <svg style="width:11px;height:11px;" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                <span x-text="current.rating ? current.rating.toFixed(1) : '4.8'">4.8</span>
                                            </span>
                                        </div>
                                        <a :href="current.url" class="block group">
                                            <h3 class="text-base sm:text-lg font-extrabold leading-tight mb-1 transition-colors"
                                                style="color:#f1f5f9;"
                                                :class="'group-hover:text-indigo-300'"
                                                x-text="current.title"></h3>
                                        </a>
                                        <p class="text-[11px] mb-2" style="color:#475569;" x-text="'oleh ' + current.author"></p>
                                        <p class="text-xs leading-relaxed line-clamp-2" style="color:#64748b;" x-text="current.description"></p>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-4 pt-3.5" style="border-top:1px solid rgba(255,255,255,0.06);">
                                    <div class="hero-stat-pill">
                                        <svg style="width:12px;height:12px;" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" clip-rule="evenodd"/></svg>
                                        <span x-text="current.chapters + ' bab'"></span>
                                    </div>
                                    <div class="hero-stat-pill">
                                        <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        <span>Trending</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="flex flex-wrap gap-2.5">
                                <a href="{{ route('welcome') }}" class="hero-btn-primary">
                                    Jelajahi Katalog
                                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </a>
                                @guest
                                <a href="{{ route('register') }}" class="hero-btn-secondary">Daftar Gratis</a>
                                @else
                                <a href="{{ route('novels.updated') }}" class="hero-btn-secondary">Update Terbaru</a>
                                @endguest
                            </div>
                        </div>

                        {{-- Kanan: Stack Cover Visual 3D --}}
                        @if($featuredCarouselData->isNotEmpty())
                        <div @mouseenter="paused = true" @mouseleave="paused = false">
                            <div class="stack-wrap" x-ref="stack">
                                <template x-for="(novel, index) in novels" :key="'stk'+novel.id">
                                    <a :href="novel.url"
                                       class="stack-card"
                                       :style="stackStyle(index)">
                                        <img :src="novel.cover || '/error.png'"
                                             :alt="novel.title"
                                             loading="eager"
                                             onerror="this.src='/error.png'">
                                        <div class="stack-card-label">
                                            <div class="s-title line-clamp-2" x-text="novel.title"></div>
                                            <div class="s-sub line-clamp-1" x-text="'by ' + novel.author"></div>
                                        </div>
                                    </a>
                                </template>
                            </div>

                            {{-- Controls --}}
                            <div class="flex items-center justify-center gap-3 mt-5">
                                <button type="button" class="stack-arrow" @click="prev()" aria-label="Previous">
                                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <div class="stack-dots" @mouseenter="paused = true">
                                    <template x-for="(_, index) in novels" :key="'sd'+index">
                                        <button type="button"
                                                class="stack-dot"
                                                :class="{ active: activeIndex === index }"
                                                @click="goTo(index)"></button>
                                    </template>
                                </div>
                                <button type="button" class="stack-arrow" @click="next()" aria-label="Next">
                                    <svg style="width:14px;height:14px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.landing.stats')

    @include('partials.landing.reading-routes')

    @include('partials.landing.editors-choice')

    @include('partials.landing.scene-notes')

    @include('partials.landing.fresh-picks')


</div>
@endsection