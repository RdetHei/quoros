@extends('layouts.app')

@section('title', 'Bookmark — Your Library · Quoros')

@push('styles')
<style>
    .bm-serif { font-family: 'Cormorant Garamond', Georgia, serif; }
    .bm-page {
        --bm-bg: #0a0a0a;
        --bm-surface: #121212;
        --bm-card: #131313;
        --bm-panel: #171717;
        --bm-border: rgba(255, 255, 255, 0.10);
        --bm-gold: #c7a64a;
        --bm-gold-soft: #e8d39a;
        --bm-ink: #eeeae1;
        --bm-title: #f2efe8;
        --bm-muted: #a3a3a3;
        --bm-subtle: #737373;
        background: var(--bm-bg);
        color: var(--bm-ink);
    }

    body:has(.bm-page) { background-color: var(--bm-bg) !important; color: var(--bm-ink); }

    .bm-card {
        transition: border-color .2s ease, background-color .2s ease, transform .2s ease;
    }
    .bm-card:hover {
        transform: translateY(-1px);
        background: #171717;
        border-color: rgba(199, 166, 74, 0.35);
    }

    .bm-btn-solid {
        background: var(--bm-gold);
        color: #101010;
        transition: filter .15s ease, transform .12s ease;
    }
    .bm-btn-solid:hover { filter: brightness(1.08); }
    .bm-btn-solid:active { transform: translateY(1px); }

    .bm-btn-outline {
        border: 1px solid rgba(199, 166, 74, 0.5);
        color: var(--bm-gold);
        transition: background-color .15s ease, border-color .15s ease, color .15s ease;
    }
    .bm-btn-outline:hover {
        background: rgba(199, 166, 74, 0.08);
        border-color: var(--bm-gold);
        color: var(--bm-gold-soft);
    }

    .bm-progress-bar {
        background: rgba(255, 255, 255, 0.08);
    }
    .bm-progress-fill {
        background: var(--bm-gold);
        height: 100%;
        border-radius: 999px;
        transition: width .45s cubic-bezier(0.22, 1, 0.36, 1);
        box-shadow: 0 0 6px 0 rgba(199, 166, 74, 0.35);
    }

    .bm-status-badge {
        font-size: 8px;
        letter-spacing: 0.11em;
        text-transform: uppercase;
        padding: 2px 7px;
        border-radius: 2px;
        font-weight: 650;
        white-space: nowrap;
    }

    .bm-status-badge--reading   { background: rgba(199, 166, 74, 0.14); color: var(--bm-gold); border: 1px solid rgba(199, 166, 74, 0.30); }
    .bm-status-badge--plan      { background: rgba(163, 163, 163, 0.08); color: #d4d4d4; border: 1px solid rgba(255, 255, 255, 0.12); }
    .bm-status-badge--completed { background: rgba(199, 166, 74, 0.24); color: #ead79f; border: 1px solid rgba(199, 166, 74, 0.45); }

    .bm-tab-link {
        padding: 0.7rem 0;
        font-size: 10px;
        letter-spacing: 0.14em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--bm-muted);
        border-bottom: 2px solid transparent;
        transition: color .15s ease, border-color .15s ease;
    }
    .bm-tab-link:hover { color: #d4d4d4; }
    .bm-tab-link.is-active {
        color: var(--bm-gold);
        border-bottom-color: var(--bm-gold);
    }
    .bm-tab-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 16px;
        padding: 0 5px;
        height: 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.06);
        color: #d4d4d4;
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 0;
        margin-left: 7px;
    }
    .bm-tab-link.is-active .bm-tab-count {
        background: rgba(199, 166, 74, 0.22);
        color: var(--bm-gold);
    }

    .bm-ghost-btn {
        font-size: 9px;
        letter-spacing: 0.14em;
        font-weight: 650;
        text-transform: uppercase;
        color: var(--bm-subtle);
        transition: color .15s ease;
    }
    .bm-ghost-btn:hover { color: var(--bm-gold); }

    .bm-select {
        -webkit-appearance: none;
        appearance: none;
        background: var(--bm-panel);
        color: #d4d4d4;
        border: 1px solid var(--bm-border);
        border-radius: 4px;
        font-size: 11px;
        font-weight: 500;
        padding: 0 30px 0 14px;
        height: 38px;
        outline: none;
        transition: border-color .15s ease, color .15s ease;
        cursor: pointer;
        width: 100%;
    }
    .bm-select:hover { border-color: rgba(199, 166, 74, 0.45); color: #eeeae1; }
    .bm-select:focus { border-color: rgba(199, 166, 74, 0.7); }

    .bm-select-wrap {
        position: relative;
        min-width: 0;
    }
    .bm-select-label {
        position: absolute;
        left: 14px;
        top: 4px;
        pointer-events: none;
        font-size: 7px;
        letter-spacing: 0.17em;
        text-transform: uppercase;
        color: #737373;
        font-weight: 700;
    }
    .bm-select-wrap::after {
        content: '';
        position: absolute;
        right: 13px;
        top: 50%;
        width: 8px;
        height: 8px;
        border-right: 1.5px solid #737373;
        border-bottom: 1.5px solid #737373;
        transform: translateY(calc(-50% + 1px)) rotate(45deg);
        pointer-events: none;
    }
    .bm-select-wrap:hover::after { border-color: var(--bm-gold); }

    .bm-page select,
    .bm-page select option { background-color: var(--bm-panel); color: #d4d4d4; }

    .bm-badge-new {
        background: rgba(199, 166, 74, 0.16);
        color: var(--bm-gold);
        font-size: 7px;
        font-weight: 800;
        letter-spacing: 0.14em;
        padding: 1px 6px;
        text-transform: uppercase;
        border-radius: 999px;
        border: 1px solid rgba(199, 166, 74, 0.35);
    }

    .bm-new-count {
        background: rgba(199, 166, 74, 0.12);
        color: var(--bm-gold);
        font-size: 8px;
        font-weight: 700;
        letter-spacing: 0.05em;
        padding: 2px 7px;
        border-radius: 2px;
    }

    .bm-remove-btn {
        position: absolute;
        top: 14px;
        right: 15px;
        z-index: 10;
        color: #a3a3a3;
        transition: color .15s ease, transform .12s ease;
        background: transparent;
        border: 0;
        cursor: pointer;
        padding: 2px;
    }
    .bm-remove-btn:hover { color: var(--bm-gold); transform: scale(1.12); }
    .bm-remove-btn.is-bookmarked { color: var(--bm-gold); }
    .bm-remove-btn.is-bookmarked svg { fill: rgba(199, 166, 74, 0.92); }
</style>
@endpush

@section('content')
<main class="bm-page min-h-screen w-full overflow-x-hidden pb-20 pt-24 sm:pt-28">
    <div class="mx-auto w-full max-w-[1200px] px-5 sm:px-7 lg:px-10">

        {{-- ─── HERO ─────────────────────────────────────────────────────────────── --}}
        <header class="mb-8 border-b border-white/[.08] pb-9 sm:mb-10 sm:pb-10">
            <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end sm:gap-8">
                <div class="min-w-0 max-w-2xl">
                    <p class="mb-3 flex items-center gap-1.5 text-[9px] font-semibold uppercase tracking-[.22em] text-[#c7a64a]">
                        <span class="relative flex h-1.5 w-1.5 shrink-0">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-[#c7a64a] opacity-60"></span>
                            <span class="relative inline-flex h-1.5 w-1.5 shrink-0 rounded-full bg-[#c7a64a]"></span>
                        </span>
                        Your Library <span class="text-neutral-700">/</span> Saved Legends
                    </p>
                    <h1 class="bm-serif text-5xl font-semibold uppercase leading-none tracking-[.015em] text-[#f2efe8] sm:text-6xl md:text-[4.4rem]">
                        Bookmark
                    </h1>
                    <p class="mt-4 max-w-lg text-xs leading-6 text-neutral-400 sm:text-[12.5px] sm:leading-7">
                        The stories you keep close. Pick up where you left off, or begin a new journey.
                    </p>
                    <div class="mt-6 h-px w-40 bg-gradient-to-r from-[#c7a64a] to-transparent sm:w-56"></div>
                </div>
                <div class="flex shrink-0 flex-col items-end justify-end gap-2">
                    <div class="bm-serif text-[2.7rem] font-semibold leading-none tracking-[.01em] text-[#c7a64a] sm:text-[3.2rem]">
                        {{ str_pad((string) max(0, (int) ($counts['all'] ?? 0)), 2, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="text-[8.5px] uppercase tracking-[.18em] text-neutral-500">Novels in your library</div>
                </div>
            </div>

            {{-- TABS --}}
            <nav class="mt-9 flex flex-wrap items-center gap-x-7 sm:mt-10 sm:gap-x-10" role="tablist" aria-label="Bookmark lists">
                @php
                    $tabDefs = [
                        ['id' => 'all',       'label' => 'All Novels',  'count' => $counts['all'] ?? 0],
                        ['id' => 'reading',   'label' => 'Reading',    'count' => $counts['reading'] ?? 0],
                        ['id' => 'plan',      'label' => 'Plan to Read','count' => $counts['plan'] ?? 0],
                        ['id' => 'completed', 'label' => 'Completed',  'count' => $counts['completed'] ?? 0],
                    ];
                @endphp
                @foreach($tabDefs as $t)
                    @php
                        $qs = request()->query();
                        $qs['tab'] = $t['id'];
                        if ($t['id'] === 'all') unset($qs['tab']);
                        $qs['page'] = 1;
                        $url = route('bookmarks.index') . '?' . http_build_query($qs);
                    @endphp
                    <a href="{{ $url }}"
                       role="tab"
                       aria-selected="{{ ($tab ?? 'all') === $t['id'] ? 'true' : 'false' }}"
                       class="bm-tab-link @if(($tab ?? 'all') === $t['id']) is-active @endif">
                        {{ $t['label'] }}
                        <span class="bm-tab-count">{{ $t['count'] }}</span>
                    </a>
                @endforeach
            </nav>
        </header>

        {{-- ─── FILTER BAR ───────────────────────────────────────────────────────── --}}
        <form action="{{ route('bookmarks.index') }}" method="GET" class="mb-7 flex flex-col gap-3 sm:mb-8">
            <div class="rounded-lg border border-white/10 bg-[#121212] p-3 sm:p-3.5">
                <div class="grid grid-cols-1 items-stretch gap-2.5 sm:grid-cols-[1fr_170px_170px_170px_auto] sm:gap-2.5">
                    {{-- Search --}}
                    <div class="relative">
                        <span class="pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-neutral-500">
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="11" cy="11" r="7"></circle><path d="m20 20-4-4"></path></svg>
                        </span>
                        <label for="bm-q" class="sr-only">Search saved novels</label>
                        <input id="bm-q" name="q" type="search" value="{{ e($search ?? '') }}"
                               placeholder="Search your saved novels…"
                               class="h-[38px] w-full rounded-md border border-white/10 bg-[#171717] pl-10 pr-4 text-[11px] text-neutral-200 outline-none placeholder:text-neutral-600 focus:border-[#c7a64a]/70 sm:text-[11.5px]">
                    </div>

                    {{-- Genre --}}
                    <div class="bm-select-wrap">
                        <span class="bm-select-label">Genre</span>
                        <select name="genre" class="bm-select pt-[9px]" onchange="this.form.submit()">
                            <option value="">All genres</option>
                            @foreach($genres ?? [] as $g)
                                <option value="{{ $g->slug }}" @selected(($genre ?? '') === $g->slug)>{{ $g->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="bm-select-wrap">
                        <span class="bm-select-label">Novel Status</span>
                        <select name="filter_status" class="bm-select pt-[9px]" onchange="this.form.submit()">
                            <option value="">All statuses</option>
                            @foreach($statuses ?? [] as $val => $lbl)
                                <option value="{{ $val }}" @selected(($filterStatus ?? '') === $val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sort by --}}
                    <div class="bm-select-wrap">
                        <span class="bm-select-label">Sort By</span>
                        <select name="sort" class="bm-select pt-[9px]" onchange="this.form.submit()">
                            @foreach($sortOptions ?? [] as $val => $lbl)
                                <option value="{{ $val }}" @selected(($sort ?? 'recently_read') === $val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Hidden passthrough for tab + keep values --}}
                    @if(!empty($tab) && $tab !== 'all') <input type="hidden" name="tab" value="{{ e($tab) }}"> @endif

                    {{-- Clear filters --}}
                    <a href="{{ route('bookmarks.index') }}" type="button"
                       class="bm-ghost-btn inline-flex h-[38px] items-center justify-center px-3 rounded-md sm:px-4 whitespace-nowrap">
                        Clear Filters
                    </a>
                </div>
            </div>

            {{-- Stats row + view switch --}}
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-[9px] uppercase tracking-[.14em] text-neutral-500">
                    Showing <span class="font-semibold text-neutral-300">{{ $paginated->count() ? '1–'.$paginated->count() : '0' }}</span> of
                    <span class="font-semibold text-neutral-300">{{ $paginated->total() }}</span> saved novels
                </p>
                <div class="flex items-center gap-1 text-[9px] uppercase tracking-[.13em] text-neutral-500">
                    <span class="mr-1 hidden sm:inline">View</span>
                    <div class="flex items-center gap-1 rounded border border-white/10 bg-[#121212] p-1">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded bg-[#1a1a1a] text-[#c7a64a]" title="Grid view">
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                        </span>
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded text-neutral-500 hover:text-neutral-300" title="List view">
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>
                        </span>
                    </div>
                </div>
            </div>
        </form>

        {{-- ─── CARD GRID ───────────────────────────────────────────────────────── --}}
        @if($paginated->isNotEmpty())
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:gap-5">
                @foreach($paginated as $bm)
                    @php
                        $novel = $bm->novel;
                        $coverUrl = $novel->cover_image_url ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
                        $readingStatus = $bm->reading_status ?? 'plan';
                        $progress = (float) ($bm->progress_percentage ?? 0);
                        $readCount = (int) ($bm->read_chapters_count ?? 0);
                        $totalChapters = (int) ($bm->total_chapters ?? 0);
                        $lastChapter = $bm->last_read_chapter;
                        $lastReadAt = $bm->last_read_at;
                        $newCount = (int) ($bm->new_chapters_count ?? 0);
                        $genre = $novel->genres?->first()?->name ?? 'General';
                        $author = $novel->author?->name ?? 'Unknown';
                        $ctaLabel = match($readingStatus) {
                            'reading' => 'Continue Reading',
                            'plan'    => 'Start Reading',
                            default   => 'Read Again',
                        };
                        $ctaSolid = $readingStatus === 'reading';
                        if ($readingStatus === 'plan') $newCount = 0;
                        if ($readingStatus === 'completed') $newCount = 0;
                    @endphp
                    <article class="bm-card group relative rounded-lg border border-white/[.10] bg-[#131313] p-4 sm:p-5">

                        {{-- Remove (bookmark toggle) --}}
                        <form action="{{ route('bookmarks.toggle', $novel->id) }}" method="POST" class="bm-remove-btn is-bookmarked" title="Remove from library" onsubmit="return confirm('Remove this novel from bookmarks?')">
                            @csrf
                            <button type="submit" aria-label="Remove from bookmarks" class="text-inherit">
                                <svg viewBox="0 0 24 24" class="h-[18px] w-[18px] sm:h-5 sm:w-5" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21 12 16l-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2Z"/></svg>
                            </button>
                        </form>

                        {{-- Status badge (top-left of card content area) --}}
                        <div class="mb-3 flex items-center justify-between gap-3">
                            <span class="bm-status-badge bm-status-badge--{{ $readingStatus }}">
                                {{ match($readingStatus) { 'reading' => 'Reading', 'plan' => 'Plan to Read', default => 'Completed' } }}
                            </span>
                            @if($newCount > 0)
                                <span class="bm-new-count">{{ $newCount }} new chapter{{ $newCount === 1 ? '' : 's' }}</span>
                            @elseif($readingStatus === 'reading' && $lastChapter)
                                <span class="bm-new-count" style="background: rgba(255,255,255,.04); color: #d4d4d4; border: 1px solid rgba(255,255,255,.08);">
                                    1 current read
                                </span>
                            @endif
                        </div>

                        <div class="flex items-start gap-4 sm:gap-5">
                            {{-- Cover --}}
                            <a href="{{ route('novels.show', $novel->slug) }}" class="relative h-[7.4rem] w-[5.3rem] shrink-0 overflow-hidden rounded-sm border border-white/10 bg-[#202020] sm:h-[8.3rem] sm:w-[6rem]" tabindex="-1" aria-hidden="true">
                                @if($coverUrl)
                                    <img src="{{ $coverUrl }}" alt="{{ e($novel->title) }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-[1.04]" loading="lazy" onerror="this.onerror=null;this.src='/error.png'">
                                @else
                                    <img src="/error.png" alt="" class="h-full w-full object-cover" loading="lazy">
                                @endif
                            </a>

                            {{-- Info --}}
                            <div class="min-w-0 flex-1">
                                <a href="{{ route('novels.show', $novel->slug) }}" class="bm-serif block text-[1.02rem] font-semibold uppercase leading-tight tracking-[.015em] text-[#eeeae1] transition-colors hover:text-[#e2c56f] line-clamp-1 sm:text-[1.1rem]">
                                    {{ $novel->title }}
                                </a>
                                <div class="mt-1.5 text-[8.5px] font-semibold uppercase tracking-[.11em] text-neutral-500">
                                    By <span class="text-neutral-400">{{ $author }}</span>
                                    <span class="mx-1.5 text-neutral-700">·</span>
                                    <span class="text-neutral-400">{{ $genre }}</span>
                                </div>

                                {{-- Divider + Last chapter read --}}
                                <div class="my-3 h-px bg-white/[.07]"></div>
                                <div class="text-[10px] leading-5 text-neutral-400 sm:text-[10.5px]">
                                    @if($lastChapter && $readingStatus !== 'plan')
                                        <span class="font-semibold text-[#d4d4d4]">Ch. {{ $lastChapter->chapter_number ?? ($lastChapter->order ?? '—') }}</span>
                                        <span class="text-neutral-500"> — {{ \Illuminate\Support\Str::limit($lastChapter->title ?? '', 38) }}</span>
                                    @else
                                        <span class="font-semibold text-[#d4d4d4]">Ch. 1</span>
                                        <span class="text-neutral-500"> — Ready when you are.</span>
                                    @endif
                                </div>

                                {{-- Progress --}}
                                <div class="mt-3">
                                    <div class="mb-1.5 flex items-center justify-between gap-3 text-[8.5px] font-semibold uppercase tracking-[.1em]">
                                        <span class="text-neutral-500">
                                            <span class="tabular-nums text-neutral-300">{{ $readCount }}</span>
                                            <span class="text-neutral-600"> / </span>
                                            <span class="tabular-nums text-neutral-400">{{ $totalChapters }}</span>
                                            <span class="ml-1 text-neutral-500">chapters read</span>
                                        </span>
                                        <span class="tabular-nums text-[#c7a64a]">{{ number_format($progress, 0) }}%</span>
                                    </div>
                                    <div class="bm-progress-bar h-[3px] w-full overflow-hidden rounded-full sm:h-[4px]">
                                        <div class="bm-progress-fill" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- CTA + date row --}}
                        <div class="mt-4 flex items-end justify-between gap-3 sm:mt-5">
                            <div class="text-[8px] leading-4 uppercase tracking-[.09em] text-neutral-500">
                                @if($lastReadAt && $readingStatus !== 'plan')
                                    Read {!! $lastReadAt->isToday() ? '<span class="text-[#d4d4d4] font-semibold">Today</span>' : ($lastReadAt->isYesterday() ? '<span class="text-[#d4d4d4] font-semibold">Yesterday</span>' : '<span class="text-neutral-400 font-semibold">'.$lastReadAt->format('j M').'</span>') !!}
                                    <span class="mx-1 text-neutral-600">·</span>
                                    <span class="tabular-nums text-neutral-400">{{ $lastReadAt->format('H:i') }}</span>
                                @elseif($readingStatus === 'completed')
                                    Finished <span class="text-neutral-400 font-semibold">{{ $bm->updated_at?->format('j M Y') ?? '' }}</span>
                                @else
                                    Saved <span class="text-neutral-400 font-semibold">{{ $bm->created_at?->format('j M Y') ?? '' }}</span>
                                @endif
                                @if($readingStatus === 'reading' && $newCount > 0)
                                    <div class="mt-1 bm-badge-new w-fit">{{ $newCount }} New Chapter{{ $newCount > 1 ? 's' : '' }}</div>
                                @endif
                            </div>
                            @php
                                $qsCta = [];
                                if ($lastChapter) $qsCta['chapter'] = $lastChapter->id;
                                $ctaHref = route('novels.show', $novel->slug) . ($qsCta ? '#chapter' : '');
                            @endphp
                            <a href="{{ $ctaHref }}"
                               class="inline-flex items-center gap-1.5 rounded-sm px-3.5 py-2 text-[8.5px] font-semibold uppercase tracking-[.14em] sm:px-4 sm:py-2.5 {{ $ctaSolid ? 'bm-btn-solid' : 'bm-btn-outline' }}">
                                {{ $ctaLabel }}
                                <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                            </a>
                        </div>

                    </article>
                @endforeach
            </section>

            {{-- Empty state (if filtered only --}}
        @else
            <section class="rounded-lg border border-white/10 bg-[#121212] px-5 py-20 text-center">
                <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full border border-white/10 text-[#c7a64a]">
                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21 12 16l-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2Z"/></svg>
                </div>
                <h2 class="bm-serif text-2xl uppercase text-[#eeeae1]">Shelf is quiet here.</h2>
                <p class="mx-auto mt-2 max-w-sm text-xs leading-6 text-neutral-500">
                    @if(($counts['all'] ?? 0) > 0)
                        Nothing matches your current filters — try widening the scope.
                    @else
                        Add your first legend to the shelf, and begin.
                    @endif
                </p>
                <a href="{{ route('welcome') }}" class="mt-6 inline-flex items-center gap-1.5 rounded border border-[#c7a64a]/45 px-4 py-2.5 text-[9px] font-semibold uppercase tracking-[.14em] text-[#c7a64a] transition hover:bg-[#c7a64a]/10">
                    Discover more novels
                    <svg viewBox="0 0 24 24" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                </a>
            </section>
        @endif

        {{-- ─── PAGINATION ──────────────────────────────────────────────────────── --}}
        @if($paginated->hasPages())
            <nav class="mt-11 flex flex-wrap items-center justify-center gap-1.5" aria-label="Pagination">
                @if($paginated->onFirstPage())
                    <span aria-disabled="true" class="flex h-9 min-w-9 cursor-not-allowed items-center justify-center rounded border border-white/10 px-3 text-xs text-neutral-500 opacity-40" aria-label="Halaman sebelumnya">‹</span>
                @else
                    <a href="{{ $paginated->previousPageUrl() }}" class="flex h-9 min-w-9 items-center justify-center rounded border border-white/10 px-3 text-xs text-neutral-400 transition hover:border-[#c7a64a]/60 hover:text-[#e8d39a]" aria-label="Halaman sebelumnya">‹</a>
                @endif
                @foreach($paginated->getUrlRange(max(1, $paginated->currentPage() - 2), min($paginated->lastPage(), $paginated->currentPage() + 2)) as $page => $url)
                    <a href="{{ $url }}" @if($page === $paginated->currentPage()) aria-current="page" @endif class="flex h-9 min-w-9 items-center justify-center rounded border px-3 text-[10px] transition {{ $page === $paginated->currentPage() ? 'border-[#c7a64a] bg-[#c7a64a] font-bold text-[#111]' : 'border-white/10 text-neutral-400 hover:border-[#c7a64a]/60 hover:text-[#e8d39a]' }}">{{ $page }}</a>
                @endforeach
                @if(!$paginated->hasMorePages())
                    <span aria-disabled="true" class="flex h-9 min-w-9 cursor-not-allowed items-center justify-center rounded border border-white/10 px-3 text-xs text-neutral-500 opacity-40" aria-label="Halaman berikutnya">›</span>
                @else
                    <a href="{{ $paginated->nextPageUrl() }}" class="flex h-9 min-w-9 items-center justify-center rounded border border-white/10 px-3 text-xs text-neutral-400 transition hover:border-[#c7a64a]/60 hover:text-[#e8d39a]" aria-label="Halaman berikutnya">›</a>
                @endif
            </nav>
        @endif

        {{-- ─── SIGNATURE CTA ───────────────────────────────────────────────────── --}}
        @if(($counts['all'] ?? 0) > 0)
            <section class="mt-16 border-t border-white/[.06] pt-10 text-center sm:mt-20 sm:pt-12">
                <p class="bm-serif text-[15px] font-medium italic leading-6 tracking-[.01em] text-neutral-500">
                    Every legend has a place on your shelf.
                </p>
                <a href="{{ route('welcome') }}" class="mt-5 inline-flex items-center gap-2 rounded-sm border border-[#c7a64a]/45 px-5 py-2.5 text-[9px] font-semibold uppercase tracking-[.14em] text-[#c7a64a] transition hover:bg-[#c7a64a]/10">
                    Discover more novels
                    <svg viewBox="0 0 24 24" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                </a>
            </section>
        @endif

    </div>
</main>
@endsection
