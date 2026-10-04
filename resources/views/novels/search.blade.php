@extends('layouts.app')

@section('title', 'Novel Navigation — Quoros')
@section('meta_description', 'Temukan novel favorit Anda dengan fitur pencarian lanjutan. Filter berdasarkan genre, status, tipe, dan rating untuk mendapatkan bacaan terbaik di Quoros.')

@push('styles')
<style>
    .search-page { --s-gold: #c7a64a; }
    .search-serif { font-family: 'Cormorant Garamond', Georgia, serif; }
    .s-filter-trigger {
        transition: border-color .18s ease, background-color .18s ease, color .18s ease;
    }
    .s-filter-trigger:hover { border-color: rgba(199, 166, 74, 0.55); }
    .s-filter-trigger[aria-expanded="true"] {
        border-color: rgba(199, 166, 74, 0.7);
        background: #171717;
    }
    .s-dropdown-panel {
        position: absolute;
        z-index: 60;
        top: calc(100% + 6px);
        left: 0;
        min-width: 100%;
        max-height: 22rem;
        overflow: hidden;
        border-radius: 0.5rem;
        border: 1px solid rgba(255, 255, 255, 0.10);
        background: #121212;
        box-shadow: 0 20px 40px -18px rgba(0, 0, 0, 0.85);
        transform-origin: top left;
        transform: translateY(-4px) scale(0.985);
        opacity: 0;
        pointer-events: none;
        transition: opacity .15s ease, transform .15s ease;
    }
    .s-dropdown-panel.is-open {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }
    .s-dropdown-list {
        max-height: 22rem;
        overflow-y: auto;
        padding: 0.35rem;
    }
    .s-dropdown-list::-webkit-scrollbar { width: 6px; }
    .s-dropdown-list::-webkit-scrollbar-thumb { background: #2a2a2a; border-radius: 999px; }
    .s-dropdown-list::-webkit-scrollbar-thumb:hover { background: #3a3a3a; }
    .s-option {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.6rem;
        padding: 0.42rem 0.6rem;
        border-radius: 0.3rem;
        cursor: pointer;
        user-select: none;
        font-size: 11.5px;
        font-weight: 500;
        letter-spacing: 0.03em;
        color: #d4d4d4;
        transition: background-color .12s ease, color .12s ease;
        white-space: nowrap;
    }
    .s-option:hover { background: rgba(255, 255, 255, 0.04); color: #eeeae1; }
    .s-option.is-selected {
        background: rgba(199, 166, 74, 0.08);
        color: #e8d39a;
    }
    .s-option.is-selected:hover { background: rgba(199, 166, 74, 0.13); }
    .s-check {
        flex-shrink: 0;
        width: 13px;
        height: 13px;
        border-radius: 3px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border-color .12s ease, background-color .12s ease;
    }
    .s-option.is-selected .s-check {
        background: var(--s-gold);
        border-color: var(--s-gold);
    }
    .s-dot {
        flex-shrink: 0;
        width: 6px;
        height: 6px;
        border-radius: 999px;
        background: transparent;
        transition: background-color .12s ease;
    }
    .s-option.is-selected .s-dot { background: var(--s-gold); }
    .s-filter-count {
        pointer-events: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 16px;
        height: 16px;
        padding: 0 4px;
        border-radius: 999px;
        background: rgba(199, 166, 74, 0.18);
        color: var(--s-gold);
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.05em;
    }
    .s-filter-count:empty { display: none; }
    .s-chevron {
        flex-shrink: 0;
        color: #737373;
        transition: transform .18s ease, color .18s ease;
    }
    .s-filter-trigger[aria-expanded="true"] .s-chevron {
        transform: rotate(180deg);
        color: var(--s-gold);
    }
    .s-card {
        transition: border-color .2s ease, background-color .2s ease, transform .2s ease;
    }
    .s-card:hover {
        transform: translateY(-1px);
        background: #171717;
        border-color: rgba(199, 166, 74, 0.35);
    }
    .s-card-title { transition: color .18s ease; }
    .s-card:hover .s-card-title { color: #e2c56f; }
    .s-active-chip {
        transition: border-color .15s ease, background-color .15s ease, color .15s ease;
    }
    .s-active-chip:hover {
        background: rgba(199, 166, 74, 0.1);
        color: #fff;
    }
    .s-active-chip .s-chip-x {
        transition: color .12s ease, background-color .12s ease;
    }
    .s-active-chip:hover .s-chip-x {
        background: rgba(199, 166, 74, 0.25);
        color: #fff;
    }
    @media (prefers-reduced-motion: reduce) {
        .s-dropdown-panel, .s-card, .s-option, .s-filter-trigger, .s-active-chip {
            transition: none !important;
        }
    }
</style>
@endpush

@section('content')
<main class="search-page mx-auto w-full max-w-[1240px] px-4 pb-16 pt-24 sm:px-6 lg:px-8">
    @php
        $genreJson = $genres->map(fn($g) => ['slug' => $g->slug, 'name' => $g->name])->values()->toJson();
        $tagJson = $tags->map(fn($t) => ['slug' => $t->slug, 'name' => $t->name])->values()->toJson();
        $statusJson = json_encode($statuses, JSON_FORCE_OBJECT);
        $typeJson = json_encode($types, JSON_FORCE_OBJECT);
        $regionJson = json_encode((object)$regionList, JSON_FORCE_OBJECT);
        $ratingJson = json_encode($ratingOptions, JSON_FORCE_OBJECT);
        $sortJson = json_encode($sortOptions, JSON_FORCE_OBJECT);
        $selGenreJson = json_encode($genreSlugs);
        $selTagJson = json_encode($tagSlugs);
    @endphp

    <header class="mb-7 border-b border-white/10 pb-7 sm:mb-8 sm:pb-8">
        <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
            <div>
                <p class="mb-2 text-[9px] font-semibold uppercase tracking-[.24em] text-[#c7a64a]">Codex <span class="mx-1 text-neutral-600">/</span> Directory</p>
                <h1 class="search-serif text-[2.5rem] font-semibold uppercase leading-none tracking-[.015em] text-[#f2efe8] sm:text-5xl">Novel Navigation</h1>
                <p class="mt-3 max-w-xl text-xs leading-6 text-neutral-400 sm:text-sm">Telusuri seluruh arkip — saring berdasarkan genre, tag, status, region, dan rating.</p>
            </div>
            <div class="flex shrink-0 flex-col gap-3 sm:items-end">
                <div class="flex items-center gap-2 text-[9px] uppercase tracking-[.14em] text-neutral-500">
                    <span class="relative flex h-1.5 w-1.5"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#c7a64a] opacity-60"></span><span class="relative inline-flex h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span></span>
                    <span>Browse directory</span>
                </div>
                <div class="font-mono text-[9px] tracking-[.12em] text-neutral-500">{{ now()->format('d M Y') }} <span class="mx-1 text-neutral-700">·</span> UTC+7</div>
            </div>
        </div>
        <div class="mt-7 h-px w-40 bg-gradient-to-r from-[#c7a64a] to-transparent sm:mt-8 sm:w-56"></div>
    </header>

    <section class="mb-7 rounded-xl border border-white/10 bg-[#121212] p-3 sm:mb-8 sm:p-4" aria-label="Pencarian dan filter novel">
        <form action="{{ route('novels.search') }}" method="GET" id="s-search-form" class="space-y-3">
            <div class="relative">
                <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-500">
                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg>
                </span>
                <label for="s-q" class="sr-only">Search novels</label>
                <input id="s-q"
                       name="q"
                       type="search"
                       value="{{ $search ?? '' }}"
                       placeholder="Title, alternative title, author…"
                       class="h-11 w-full rounded-md border border-white/10 bg-[#171717] pl-10 pr-32 text-xs text-neutral-200 outline-none placeholder:text-neutral-600 focus:border-[#c7a64a]/70 sm:h-12 sm:text-sm"
                       autocomplete="off">
                <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1.5">
                    <button type="submit" class="h-8 rounded-md px-3.5 text-[10px] font-semibold uppercase tracking-[.12em] text-[#111] bg-[#c7a64a] transition hover:brightness-110 sm:h-9 sm:px-4">Search</button>
                    <a href="{{ route('novels.search') }}" type="button" id="s-clear-all" class="hidden h-8 items-center justify-center rounded-md border border-white/10 px-3 text-[9px] font-medium uppercase tracking-[.12em] text-neutral-500 transition hover:text-[#c7a64a] hover:border-[#c7a64a]/40 sm:inline-flex sm:h-9">Clear</a>
                </div>
            </div>

            <div id="s-filter-row"
                 class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-5 xl:grid-cols-7"
                 data-genres='{{ $genreJson }}'
                 data-tags='{{ $tagJson }}'
                 data-statuses='{{ $statusJson }}'
                 data-types='{{ $typeJson }}'
                 data-regions='{{ $regionJson }}'
                 data-ratings='{{ $ratingJson }}'
                 data-sorts='{{ $sortJson }}'
                 data-selected-genres='{{ $selGenreJson }}'
                 data-selected-tags='{{ $selTagJson }}'
                 data-selected-status="{{ $status ?? '' }}"
                 data-selected-type="{{ $type ?? '' }}"
                 data-selected-region="{{ $region ?? '' }}"
                 data-selected-rating="{{ $minRating ?? '' }}"
                 data-selected-sort="{{ $sort ?? 'latest' }}"
                 data-base-url="{{ route('novels.search') }}">

                <div class="s-dropdown-wrap relative min-w-0" data-filter="genres" data-multi="1">
                    <button type="button" class="s-filter-trigger flex h-11 w-full items-center justify-between gap-2 rounded-md border border-white/10 bg-[#171717] px-3 pr-2.5 text-left text-xs text-neutral-200 outline-none sm:h-12 sm:text-[11.5px]" aria-haspopup="listbox" aria-expanded="false">
                        <div class="flex min-w-0 items-center gap-2">
                            <span class="text-neutral-500 font-semibold uppercase tracking-[.16em] text-[9px] shrink-0">Genre</span>
                            <span class="s-filter-label text-neutral-200 min-w-0 truncate">All genres</span>
                        </div>
                        <div class="flex shrink-0 items-center gap-1.5">
                            <span class="s-filter-count"></span>
                            <svg class="s-chevron h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </button>
                    <div class="s-dropdown-panel" role="listbox" aria-multiselectable="true">
                        <div class="s-dropdown-list"></div>
                    </div>
                </div>

                <div class="s-dropdown-wrap relative min-w-0" data-filter="tags" data-multi="1">
                    <button type="button" class="s-filter-trigger flex h-11 w-full items-center justify-between gap-2 rounded-md border border-white/10 bg-[#171717] px-3 pr-2.5 text-left text-xs text-neutral-200 outline-none sm:h-12 sm:text-[11.5px]" aria-haspopup="listbox" aria-expanded="false">
                        <div class="flex min-w-0 items-center gap-2">
                            <span class="text-neutral-500 font-semibold uppercase tracking-[.16em] text-[9px] shrink-0">Tag</span>
                            <span class="s-filter-label text-neutral-200 min-w-0 truncate">All tags</span>
                        </div>
                        <div class="flex shrink-0 items-center gap-1.5">
                            <span class="s-filter-count"></span>
                            <svg class="s-chevron h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </button>
                    <div class="s-dropdown-panel" role="listbox" aria-multiselectable="true">
                        <div class="s-dropdown-list"></div>
                    </div>
                </div>

                <div class="s-dropdown-wrap relative min-w-0" data-filter="status" data-multi="0">
                    <button type="button" class="s-filter-trigger flex h-11 w-full items-center justify-between gap-2 rounded-md border border-white/10 bg-[#171717] px-3 pr-2.5 text-left text-xs text-neutral-200 outline-none sm:h-12 sm:text-[11.5px]" aria-haspopup="listbox" aria-expanded="false">
                        <div class="flex min-w-0 items-center gap-2">
                            <span class="text-neutral-500 font-semibold uppercase tracking-[.16em] text-[9px] shrink-0">Status</span>
                            <span class="s-filter-label text-neutral-200 min-w-0 truncate">All statuses</span>
                        </div>
                        <div class="flex shrink-0 items-center gap-1.5">
                            <span class="s-filter-count"></span>
                            <svg class="s-chevron h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </button>
                    <div class="s-dropdown-panel" role="listbox" aria-multiselectable="false">
                        <div class="s-dropdown-list"></div>
                    </div>
                </div>

                <div class="s-dropdown-wrap relative min-w-0" data-filter="region" data-multi="0">
                    <button type="button" class="s-filter-trigger flex h-11 w-full items-center justify-between gap-2 rounded-md border border-white/10 bg-[#171717] px-3 pr-2.5 text-left text-xs text-neutral-200 outline-none sm:h-12 sm:text-[11.5px]" aria-haspopup="listbox" aria-expanded="false">
                        <div class="flex min-w-0 items-center gap-2">
                            <span class="text-neutral-500 font-semibold uppercase tracking-[.16em] text-[9px] shrink-0">Region</span>
                            <span class="s-filter-label text-neutral-200 min-w-0 truncate">All regions</span>
                        </div>
                        <div class="flex shrink-0 items-center gap-1.5">
                            <span class="s-filter-count"></span>
                            <svg class="s-chevron h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </button>
                    <div class="s-dropdown-panel" role="listbox" aria-multiselectable="false">
                        <div class="s-dropdown-list"></div>
                    </div>
                </div>

                <div class="s-dropdown-wrap relative min-w-0" data-filter="type" data-multi="0">
                    <button type="button" class="s-filter-trigger flex h-11 w-full items-center justify-between gap-2 rounded-md border border-white/10 bg-[#171717] px-3 pr-2.5 text-left text-xs text-neutral-200 outline-none sm:h-12 sm:text-[11.5px]" aria-haspopup="listbox" aria-expanded="false">
                        <div class="flex min-w-0 items-center gap-2">
                            <span class="text-neutral-500 font-semibold uppercase tracking-[.16em] text-[9px] shrink-0">Type</span>
                            <span class="s-filter-label text-neutral-200 min-w-0 truncate">All types</span>
                        </div>
                        <div class="flex shrink-0 items-center gap-1.5">
                            <span class="s-filter-count"></span>
                            <svg class="s-chevron h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </button>
                    <div class="s-dropdown-panel" role="listbox" aria-multiselectable="false">
                        <div class="s-dropdown-list"></div>
                    </div>
                </div>

                <div class="s-dropdown-wrap relative min-w-0" data-filter="min_rating" data-multi="0">
                    <button type="button" class="s-filter-trigger flex h-11 w-full items-center justify-between gap-2 rounded-md border border-white/10 bg-[#171717] px-3 pr-2.5 text-left text-xs text-neutral-200 outline-none sm:h-12 sm:text-[11.5px]" aria-haspopup="listbox" aria-expanded="false">
                        <div class="flex min-w-0 items-center gap-2">
                            <span class="text-neutral-500 font-semibold uppercase tracking-[.16em] text-[9px] shrink-0">Rating</span>
                            <span class="s-filter-label text-neutral-200 min-w-0 truncate">Any rating</span>
                        </div>
                        <div class="flex shrink-0 items-center gap-1.5">
                            <span class="s-filter-count"></span>
                            <svg class="s-chevron h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </button>
                    <div class="s-dropdown-panel" role="listbox" aria-multiselectable="false">
                        <div class="s-dropdown-list"></div>
                    </div>
                </div>

                <div class="s-dropdown-wrap relative min-w-0" data-filter="sort" data-multi="0">
                    <button type="button" class="s-filter-trigger flex h-11 w-full items-center justify-between gap-2 rounded-md border border-white/10 bg-[#171717] px-3 pr-2.5 text-left text-xs text-neutral-200 outline-none sm:h-12 sm:text-[11.5px]" aria-haspopup="listbox" aria-expanded="false">
                        <div class="flex min-w-0 items-center gap-2">
                            <span class="text-neutral-500 font-semibold uppercase tracking-[.16em] text-[9px] shrink-0">Sort</span>
                            <span class="s-filter-label text-neutral-200 min-w-0 truncate">Newest</span>
                        </div>
                        <div class="flex shrink-0 items-center gap-1.5">
                            <span class="s-filter-count"></span>
                            <svg class="s-chevron h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                    </button>
                    <div class="s-dropdown-panel" role="listbox" aria-multiselectable="false">
                        <div class="s-dropdown-list"></div>
                    </div>
                </div>

                <input type="hidden" name="genres_raw" value="">
                <input type="hidden" name="tags_raw" value="">
                <input type="hidden" name="status_raw" value="">
                <input type="hidden" name="region_raw" value="">
                <input type="hidden" name="type_raw" value="">
                <input type="hidden" name="min_rating_raw" value="">
                <input type="hidden" name="sort_raw" value="">
            </div>
        </form>
    </section>

    <section id="s-active-section"
             class="mb-6 flex flex-wrap items-center justify-between gap-3 border-b border-white/[.07] pb-3.5 sm:mb-7 sm:gap-4 sm:pb-4"
             aria-label="Active filters">
        <div class="flex min-w-0 flex-1 items-center gap-3 sm:gap-4">
            <h2 class="search-serif shrink-0 text-sm font-semibold uppercase tracking-[.1em] text-[#eeeae1] sm:text-base">Active Filters</h2>
            <span class="h-px w-5 shrink-0 bg-white/[.10] sm:w-8"></span>
            <div id="s-active-chips" class="flex min-w-0 flex-wrap items-center gap-1.5 sm:gap-2">
                <span id="s-no-active" class="text-[9px] uppercase tracking-[.14em] text-neutral-600">No filters applied</span>
            </div>
        </div>
        <div class="flex shrink-0 items-center gap-3 sm:gap-4">
            <p class="text-[9px] uppercase tracking-[.13em] text-neutral-500">
                Showing <span id="s-showing" class="text-neutral-300">{{ $novels->count() ? '1–'.$novels->count() : '0' }}</span> of <span class="text-neutral-300">{{ $novels->total() }}</span>
            </p>
            @if(count($activeFilters) || filled($search ?? null))
                <a href="{{ route('novels.search') }}" id="s-reset-top" class="whitespace-nowrap text-[9px] font-semibold uppercase tracking-[.12em] text-[#c7a64a] transition hover:text-[#ead79f]">Reset All</a>
            @endif
        </div>
    </section>

    @if($novels->isNotEmpty())
        <div id="s-results" class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($novels as $novel)
                @php
                    $coverUrl = $novel->cover_image_url ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
                    $genreItems = $novel->genres->take(2);
                    $chapterCount = $novel->chapters_count ?? $novel->chapters()->count();
                    $ratingVal = $novel->rating_avg ? number_format($novel->rating_avg, 1) : null;
                @endphp
                <article class="s-card group rounded-lg border border-white/[.10] bg-[#131313] p-3 sm:p-3.5">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <a href="{{ route('novels.show', $novel->slug) }}" class="h-[6.9rem] w-[5rem] shrink-0 overflow-hidden rounded border border-white/10 bg-[#202020] sm:h-[7.4rem] sm:w-[5.3rem]" tabindex="-1" aria-hidden="true">
                            @if($coverUrl)
                                <img src="{{ $coverUrl }}" alt="" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.04]" loading="lazy" onerror="this.onerror=null;this.src='/error.png'">
                            @else
                                <img src="/error.png" alt="" class="h-full w-full object-cover" loading="lazy">
                            @endif
                        </a>
                        <div class="min-w-0 flex-1">
                            <div class="mb-2 flex min-w-0 items-center gap-1.5">
                                @foreach($genreItems as $g)
                                    <span class="rounded-sm border border-white/[.10] px-1.5 py-0.5 text-[7px] uppercase tracking-[.08em] text-neutral-400">{{ $g->name }}</span>
                                @endforeach
                            </div>
                            <div class="mb-2 flex items-start justify-between gap-2">
                                <a href="{{ route('novels.show', $novel->slug) }}" class="s-card-title search-serif line-clamp-2 text-lg font-semibold uppercase leading-tight tracking-[.015em] text-[#eeeae1] sm:text-[1.12rem] min-w-0">
                                    {{ $novel->title }}
                                </a>
                                <button type="button" aria-label="Bookmark" class="shrink-0 -mr-0.5 mt-0.5 text-neutral-400 transition hover:scale-110 hover:text-[#c7a64a] active:scale-95">
                                    <svg viewBox="0 0 24 24" class="h-[18px] w-[18px] sm:h-5 sm:w-5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21 12 16l-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2Z"/></svg>
                                </button>
                            </div>
                            <p class="mt-1 line-clamp-2 text-[10px] leading-5 text-neutral-400 sm:text-[11px]">
                                {{ Str::limit(strip_tags($novel->description ?? ''), 120) }}
                            </p>
                            <div class="mt-2.5 flex flex-wrap items-center gap-3 text-[10px] sm:text-[11px]">
                                @if($ratingVal)
                                    <div class="flex items-center gap-1 text-[#e5d7ac]">
                                        <svg viewBox="0 0 20 20" class="h-3.5 w-3.5" fill="currentColor" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 0 0 .95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 0 0-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 0 0-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 0 0-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 0 0 .951-.69l1.07-3.292z"/></svg>
                                        <span class="font-semibold tabular-nums">{{ $ratingVal }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center gap-1 text-neutral-400">
                                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M5 4h14v16H5z M9 8h6 M9 12h6 M9 16h4"/></svg>
                                    <span class="font-semibold">{{ $chapterCount }} Chapters</span>
                                </div>
                                @if($novel->author)
                                    <div class="flex min-w-0 items-center gap-1 text-neutral-500">
                                        <span class="truncate font-medium">by {{ $novel->author->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div id="s-empty" class="hidden rounded-lg border border-white/10 bg-[#121212] px-5 py-16 text-center">
            <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full border border-white/10 text-[#c7a64a]">
                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg>
            </div>
            <h2 class="search-serif text-xl uppercase text-[#eeeae1]">No matching novels</h2>
            <p class="mt-2 text-xs text-neutral-500">Coba ubah filter atau kata pencarian.</p>
            <a href="{{ route('novels.search') }}" class="mt-5 inline-flex items-center gap-1.5 text-[9px] font-semibold uppercase tracking-[.14em] text-[#c7a64a] transition hover:text-[#ead79f]">
                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                Clear filters
            </a>
        </div>

        @if($novels->hasPages())
            <nav class="mt-9 flex flex-wrap items-center justify-center gap-1.5" aria-label="Pagination">
                @if($novels->onFirstPage())
                    <span aria-disabled="true" class="flex h-9 min-w-9 cursor-not-allowed items-center justify-center rounded border border-white/10 px-3 text-xs text-neutral-500 opacity-40" aria-label="Halaman sebelumnya">‹</span>
                @else
                    <a href="{{ $novels->previousPageUrl() }}" class="flex h-9 min-w-9 items-center justify-center rounded border border-white/10 px-3 text-xs text-neutral-400 transition hover:border-[#c7a64a]/60 hover:text-[#e8d39a]" aria-label="Halaman sebelumnya">‹</a>
                @endif
                @foreach($novels->getUrlRange(max(1, $novels->currentPage() - 2), min($novels->lastPage(), $novels->currentPage() + 2)) as $page => $url)
                    <a href="{{ $url }}" @if($page === $novels->currentPage()) aria-current="page" @endif class="flex h-9 min-w-9 items-center justify-center rounded border px-3 text-[10px] transition {{ $page === $novels->currentPage() ? 'border-[#c7a64a] bg-[#c7a64a] font-bold text-[#111]' : 'border-white/10 text-neutral-400 hover:border-[#c7a64a]/60 hover:text-[#e8d39a]' }}">{{ $page }}</a>
                @endforeach
                @if(!$novels->hasMorePages())
                    <span aria-disabled="true" class="flex h-9 min-w-9 cursor-not-allowed items-center justify-center rounded border border-white/10 px-3 text-xs text-neutral-500 opacity-40" aria-label="Halaman berikutnya">›</span>
                @else
                    <a href="{{ $novels->nextPageUrl() }}" class="flex h-9 min-w-9 items-center justify-center rounded border border-white/10 px-3 text-xs text-neutral-400 transition hover:border-[#c7a64a]/60 hover:text-[#e8d39a]" aria-label="Halaman berikutnya">›</a>
                @endif
            </nav>
        @endif
    @else
        <div class="rounded-lg border border-white/10 bg-[#121212] px-5 py-20 text-center">
            <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full border border-white/10 text-[#c7a64a]">
                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg>
            </div>
            <h2 class="search-serif text-2xl uppercase text-[#eeeae1]">Directory is empty</h2>
            <p class="mx-auto mt-2 max-w-sm text-xs leading-5 text-neutral-500">Belum ada novel yang memenuhi kriteria pencarian ini.</p>
            <a href="{{ route('novels.search') }}" class="mt-5 inline-flex rounded border border-[#c7a64a]/40 px-4 py-2 text-[9px] font-semibold uppercase tracking-[.12em] text-[#c7a64a] transition hover:bg-[#c7a64a]/10">Reset directory</a>
        </div>
    @endif
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const row = document.getElementById('s-filter-row');
    if (!row) return;

    const baseUrl = row.dataset.baseUrl || window.location.pathname;
    const searchInput = document.getElementById('s-q');
    const form = document.getElementById('s-search-form');
    const activeWrap = document.getElementById('s-active-chips');
    const noActive = document.getElementById('s-no-active');
    const showingEl = document.getElementById('s-showing');

    const state = {
        q: (searchInput?.value || '').trim(),
        genres: JSON.parse(row.dataset.selectedGenres || '[]'),
        tags: JSON.parse(row.dataset.selectedTags || '[]'),
        status: row.dataset.selectedStatus || '',
        region: row.dataset.selectedRegion || '',
        type: row.dataset.selectedType || '',
        min_rating: row.dataset.selectedRating || '',
        sort: row.dataset.selectedSort || 'latest',
    };

    const FILTER_LABELS = {
        genres: { single: 'Genre', all: 'All genres' },
        tags: { single: 'Tag', all: 'All tags' },
        status: { single: 'Status', all: 'All statuses' },
        region: { single: 'Region', all: 'All regions' },
        type: { single: 'Type', all: 'All types' },
        min_rating: { single: 'Rating', all: 'Any rating' },
        sort: { single: 'Sort by', all: 'Newest First' },
    };

    const KIND_LABELS = { genres: 'genre', tags: 'tag', status: 'status', region: 'region', type: 'type', min_rating: 'rating' };

    const dataMap = {
        genres: JSON.parse(row.dataset.genres || '[]').map(x => ({ value: x.slug, label: x.name })),
        tags: JSON.parse(row.dataset.tags || '[]').map(x => ({ value: x.slug, label: x.name })),
        status: Object.entries(JSON.parse(row.dataset.statuses || '{}')).map(([v,l]) => ({ value: v, label: l })),
        region: Object.entries(JSON.parse(row.dataset.regions || '{}')).map(([v,l]) => ({ value: v, label: l })),
        type: Object.entries(JSON.parse(row.dataset.types || '{}')).map(([v,l]) => ({ value: v, label: l })),
        min_rating: Object.entries(JSON.parse(row.dataset.ratings || '{}')).map(([v,l]) => ({ value: v, label: l })),
        sort: Object.entries(JSON.parse(row.dataset.sorts || '{}')).map(([v,l]) => ({ value: v, label: l })),
    };

    function getLabel(filter, value) {
        const item = (dataMap[filter] || []).find(x => x.value === value);
        return item ? item.label : value;
    }

    const wraps = [...row.querySelectorAll('.s-dropdown-wrap')];

    wraps.forEach(wrap => {
        const filter = wrap.dataset.filter;
        const multi = wrap.dataset.multi === '1';
        const trigger = wrap.querySelector('.s-filter-trigger');
        const panel = wrap.querySelector('.s-dropdown-panel');
        const list = wrap.querySelector('.s-dropdown-list');
        const countBadge = wrap.querySelector('.s-filter-count');
        const labelEl = wrap.querySelector('.s-filter-label');

        const options = dataMap[filter] || [];

        list.innerHTML = '';
        if (options.length === 0) {
            const empty = document.createElement('div');
            empty.className = 'px-2.5 py-3 text-[10px] uppercase tracking-[.1em] text-neutral-600';
            empty.textContent = `No ${FILTER_LABELS[filter].single.toLowerCase()} available`;
            list.appendChild(empty);
        } else {
            options.forEach(opt => {
                const el = document.createElement('div');
                el.className = 's-option';
                el.dataset.value = opt.value;
                el.setAttribute('role', multi ? 'option' : 'option');
                el.tabIndex = 0;

                const left = document.createElement('div');
                left.className = 'flex min-w-0 items-center gap-2';

                const marker = document.createElement('span');
                marker.className = multi ? 's-check' : 's-dot';
                if (multi) {
                    marker.innerHTML = '<svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="#111" stroke-width="3" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5 9-11"/></svg>';
                }
                left.appendChild(marker);

                const txt = document.createElement('span');
                txt.className = 'min-w-0 truncate';
                txt.textContent = opt.label;
                left.appendChild(txt);

                el.appendChild(left);

                list.appendChild(el);

                const toggle = (e) => {
                    e.preventDefault();
                    if (multi) {
                        const idx = state[filter].indexOf(opt.value);
                        if (idx >= 0) state[filter].splice(idx, 1);
                        else state[filter].push(opt.value);
                    } else {
                        if (filter === 'sort') {
                            state.sort = opt.value;
                        } else {
                            state[filter] = (state[filter] === opt.value) ? '' : opt.value;
                        }
                        closeAll(wrap);
                    }
                    updateAll();
                };
                el.addEventListener('click', toggle);
                el.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') toggle(e);
                });
            });
        }

        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const isOpen = trigger.getAttribute('aria-expanded') === 'true';
            closeAll(isOpen ? wrap : null);
            if (isOpen) {
                trigger.setAttribute('aria-expanded', 'false');
                panel.classList.remove('is-open');
            } else {
                trigger.setAttribute('aria-expanded', 'true');
                panel.classList.add('is-open');
            }
        });
    });

    function closeAll(except) {
        wraps.forEach(w => {
            if (w === except) return;
            const t = w.querySelector('.s-filter-trigger');
            const p = w.querySelector('.s-dropdown-panel');
            if (t) t.setAttribute('aria-expanded', 'false');
            if (p) p.classList.remove('is-open');
        });
    }

    document.addEventListener('click', (e) => {
        if (e.target.closest('.s-dropdown-wrap')) return;
        closeAll(null);
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll(null);
    });

    function getSelectionState(filter) {
        if (filter === 'genres' || filter === 'tags') {
            return state[filter].slice();
        }
        return state[filter] ? [state[filter]] : [];
    }

    function updateFilterVisuals() {
        wraps.forEach(wrap => {
            const filter = wrap.dataset.filter;
            const multi = wrap.dataset.multi === '1';
            const opts = [...wrap.querySelectorAll('.s-option')];
            const countBadge = wrap.querySelector('.s-filter-count');
            const labelEl = wrap.querySelector('.s-filter-label');
            const selected = getSelectionState(filter);

            opts.forEach(o => {
                const isSel = selected.includes(o.dataset.value);
                o.classList.toggle('is-selected', isSel);
            });

            if (countBadge) {
                countBadge.textContent = (multi && selected.length) ? String(selected.length) : '';
            }
            if (labelEl) {
                if (selected.length === 0) {
                    labelEl.textContent = FILTER_LABELS[filter].all;
                } else if (selected.length === 1) {
                    labelEl.textContent = getLabel(filter, selected[0]);
                } else {
                    labelEl.textContent = `${selected.length} selected`;
                }
            }
        });
    }

    function updateActiveChips() {
        const chips = [];
        state.genres.forEach(v => chips.push({ group: 'genres', value: v, kind: 'genre', label: getLabel('genres', v) }));
        state.tags.forEach(v => chips.push({ group: 'tags', value: v, kind: 'tag', label: getLabel('tags', v) }));
        if (state.status) chips.push({ group: 'status', value: state.status, kind: 'status', label: getLabel('status', state.status) });
        if (state.region) chips.push({ group: 'region', value: state.region, kind: 'region', label: getLabel('region', state.region) });
        if (state.type) chips.push({ group: 'type', value: state.type, kind: 'type', label: getLabel('type', state.type) });
        if (state.min_rating) chips.push({ group: 'min_rating', value: state.min_rating, kind: 'rating', label: getLabel('min_rating', state.min_rating) });

        const existing = activeWrap.querySelectorAll('.s-active-chip');
        existing.forEach(e => e.remove());

        if (chips.length === 0) {
            if (noActive) noActive.style.display = '';
        } else {
            if (noActive) noActive.style.display = 'none';
            chips.forEach(chip => {
                const el = document.createElement('button');
                el.type = 'button';
                el.className = 's-active-chip inline-flex items-center gap-1.5 rounded-sm border border-[#c7a64a]/40 px-2.5 py-1 text-[8px] font-semibold uppercase tracking-[.1em] text-[#c7a64a] bg-transparent';
                el.dataset.group = chip.group;
                el.dataset.value = chip.value;
                const span = document.createElement('span');
                span.textContent = chip.label.toUpperCase();
                const x = document.createElement('span');
                x.className = 's-chip-x -mr-1 ml-0.5 inline-flex h-3.5 w-3.5 items-center justify-center rounded-sm text-[#c7a64a]/80';
                x.innerHTML = '<svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.4" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>';
                el.appendChild(span);
                el.appendChild(x);
                el.title = `Remove ${chip.label}`;
                el.addEventListener('click', () => {
                    if (chip.group === 'genres' || chip.group === 'tags') {
                        const idx = state[chip.group].indexOf(chip.value);
                        if (idx >= 0) state[chip.group].splice(idx, 1);
                    } else {
                        state[chip.group] = '';
                    }
                    updateAll();
                });
                activeWrap.appendChild(el);
            });
        }

        const resetTop = document.getElementById('s-reset-top');
        const hasAny = chips.length > 0 || (state.q && state.q.length > 0);
        if (resetTop) resetTop.style.display = hasAny ? 'inline-flex' : 'none';
        const clearBtn = document.getElementById('s-clear-all');
        if (clearBtn) clearBtn.classList.toggle('hidden', !hasAny);
    }

    function buildParams() {
        const params = new URLSearchParams();
        if (state.q) params.set('q', state.q);
        state.genres.forEach(v => params.append('genres[]', v));
        state.tags.forEach(v => params.append('tags[]', v));
        if (state.status) params.set('status', state.status);
        if (state.region) params.set('region', state.region);
        if (state.type) params.set('type', state.type);
        if (state.min_rating) params.set('min_rating', state.min_rating);
        if (state.sort && state.sort !== 'latest') params.set('sort', state.sort);
        return params;
    }

    let debounceTimer = null;
    function updateAll() {
        updateFilterVisuals();
        updateActiveChips();
        const params = buildParams();
        const url = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
        window.history.replaceState(null, '', url);

        if (showingEl) {
            const countChip = state.genres.length + state.tags.length + (state.status?1:0) + (state.region?1:0) + (state.type?1:0) + (state.min_rating?1:0);
        }

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            window.location.href = url;
        }, 700);
    }

    function updateAllNoNavigate() {
        updateFilterVisuals();
        updateActiveChips();
        const params = buildParams();
        const url = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
        window.history.replaceState(null, '', url);
    }

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            state.q = searchInput.value.trim();
            updateAllNoNavigate();
            updateActiveChips();
        });
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                state.q = searchInput.value.trim();
                clearTimeout(debounceTimer);
            }
        });
    }

    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            state.q = (searchInput?.value || '').trim();
            clearTimeout(debounceTimer);
            const params = buildParams();
            window.location.href = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
        });
    }

    updateFilterVisuals();
    updateActiveChips();

    const resultsEl = document.getElementById('s-results');
    const emptyEl = document.getElementById('s-empty');
    if (resultsEl && emptyEl) {
        const cards = resultsEl.querySelectorAll('.s-card').length;
        if (cards === 0) {
            resultsEl.style.display = 'none';
            emptyEl.classList.remove('hidden');
        }
    }

    const total = parseInt('{{ $novels->total() }}', 10) || 0;
    const perPage = parseInt('{{ $novels->perPage() }}', 10) || 20;
    const currentPage = parseInt('{{ $novels->currentPage() }}', 10) || 1;
    const shown = parseInt('{{ $novels->count() }}', 10) || 0;
    if (showingEl && shown > 0) {
        const start = (currentPage - 1) * perPage + 1;
        const end = start + shown - 1;
        showingEl.textContent = `${start}–${Math.min(end, total)}`;
    }
});
</script>
@endsection
