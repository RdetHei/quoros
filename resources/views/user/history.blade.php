@extends('layouts.app')

@section('title', 'Reading History · Quoros')

@push('styles')
<style>
    .rh-serif { font-family: 'Cormorant Garamond', Georgia, serif; }
    .rh-page {
        --rh-bg: #0a0a0a;
        --rh-surface: #121212;
        --rh-card: #131313;
        --rh-panel: #171717;
        --rh-border: rgba(255, 255, 255, 0.10);
        --rh-gold: #c7a64a;
        --rh-gold-soft: #e8d39a;
        --rh-gold-pale: #ead79f;
        --rh-ink: #eeeae1;
        --rh-title: #f2efe8;
        --rh-muted: #a3a3a3;
        --rh-subtle: #737373;
        background: var(--rh-bg);
    }
    body:has(.rh-page) { background-color: var(--rh-bg) !important; }

    .rh-kicker {
        font-size: 9px;
        letter-spacing: 0.22em;
        font-weight: 650;
        text-transform: uppercase;
        color: var(--rh-gold);
    }
    .rh-kicker .sep { color: var(--rh-subtle); margin: 0 8px; }

    .rh-hero-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 56px;
        line-height: 1;
        letter-spacing: 0.015em;
        text-transform: uppercase;
        color: var(--rh-title);
    }
    @media (max-width: 639px) {
        .rh-hero-title { font-size: 40px; }
    }

    .rh-hero-sub {
        font-size: 12px;
        line-height: 1.7;
        color: var(--rh-muted);
        max-width: 480px;
    }

    .rh-divider-grad {
        height: 1px;
        background: linear-gradient(to right, var(--rh-gold), transparent);
    }

    .rh-week-count-num {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 32px;
        line-height: 1;
        color: var(--rh-gold);
        letter-spacing: 0.01em;
    }
    .rh-week-count-label {
        font-size: 8px;
        letter-spacing: 0.14em;
        font-weight: 650;
        text-transform: uppercase;
        color: var(--rh-muted);
    }

    /* Filter bar */
    .rh-filter-wrap {
        border: 1px solid var(--rh-border);
        background: #111111;
        border-radius: 6px;
        padding: 14px;
    }

    .rh-input-wrap {
        position: relative;
    }
    .rh-input-wrap svg {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--rh-subtle);
        pointer-events: none;
    }
    .rh-search-input {
        width: 100%;
        height: 40px;
        border-radius: 4px;
        border: 1px solid var(--rh-border);
        background: var(--rh-panel);
        color: #e6e1d6;
        font-size: 11px;
        padding: 0 14px 0 40px;
        outline: none;
        transition: border-color .15s ease;
    }
    .rh-search-input::placeholder { color: #5a5a5a; font-size: 11px; }
    .rh-search-input:hover { border-color: rgba(199, 166, 74, 0.4); }
    .rh-search-input:focus { border-color: rgba(199, 166, 74, 0.7); color: #eeeae1; }

    .rh-select-wrap {
        position: relative;
        min-width: 0;
    }
    .rh-select-label {
        position: absolute;
        left: 12px;
        top: 4px;
        pointer-events: none;
        font-size: 7px;
        letter-spacing: 0.17em;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--rh-subtle);
    }
    .rh-select {
        -webkit-appearance: none;
        appearance: none;
        width: 100%;
        height: 40px;
        border-radius: 4px;
        border: 1px solid var(--rh-border);
        background: var(--rh-panel);
        color: #d4d4d4;
        font-size: 11px;
        font-weight: 500;
        padding: 9px 32px 0 12px;
        outline: none;
        transition: border-color .15s ease, color .15s ease;
        cursor: pointer;
    }
    .rh-select:hover { border-color: rgba(199, 166, 74, 0.45); color: #eeeae1; }
    .rh-select:focus { border-color: rgba(199, 166, 74, 0.7); }
    .rh-select-wrap::after {
        content: '';
        position: absolute;
        right: 13px;
        top: 50%;
        width: 8px;
        height: 8px;
        border-right: 1.5px solid var(--rh-subtle);
        border-bottom: 1.5px solid var(--rh-subtle);
        transform: translateY(calc(-50% + 1px)) rotate(45deg);
        pointer-events: none;
    }
    .rh-select-wrap:hover::after { border-color: var(--rh-gold); }
    .rh-select option { background: var(--rh-panel); color: #d4d4d4; }

    .rh-clear-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        height: 40px;
        padding: 0 16px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: transparent;
        color: var(--rh-muted);
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 650;
        text-transform: uppercase;
        border-radius: 4px;
        white-space: nowrap;
        transition: border-color .15s ease, color .15s ease, background-color .15s ease;
    }
    .rh-clear-btn:hover {
        border-color: rgba(220, 38, 38, 0.5);
        color: #fca5a5;
        background: rgba(220, 38, 38, 0.05);
    }
    .rh-clear-btn svg { flex-shrink: 0; }

    /* Note lines */
    .rh-note-line {
        font-size: 9.5px;
        color: var(--rh-subtle);
        letter-spacing: 0.02em;
    }
    .rh-note-line .val { color: var(--rh-muted); }
    .rh-note-line--right { text-align: right; letter-spacing: 0.12em; font-weight: 600; text-transform: uppercase; }

    /* Date group */
    .rh-date-group {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin: 28px 0 12px;
    }
    .rh-date-left {
        display: flex;
        align-items: center;
        gap: 14px;
        flex: 1;
        min-width: 0;
    }
    .rh-date-label {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 18px;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #e6e1d6;
        white-space: nowrap;
    }
    .rh-date-sub {
        font-size: 8.5px;
        letter-spacing: 0.08em;
        color: var(--rh-subtle);
        white-space: nowrap;
        font-weight: 550;
    }
    .rh-date-rule {
        flex: 1;
        height: 1px;
        background: rgba(255, 255, 255, 0.08);
        min-width: 20px;
    }
    .rh-date-count {
        font-size: 8.5px;
        letter-spacing: 0.1em;
        font-weight: 650;
        text-transform: uppercase;
        color: var(--rh-subtle);
        white-space: nowrap;
    }
    .rh-date-count .num { color: #d4d4d4; }
    .rh-date-count .lbl { color: var(--rh-subtle); margin-left: 2px; }

    /* History entry card */
    .rh-entry {
        background: var(--rh-card);
        border: 1px solid var(--rh-border);
        border-radius: 6px;
        padding: 14px 16px;
        margin-bottom: 8px;
        position: relative;
        transition: border-color .2s ease, background-color .2s ease;
    }
    .rh-entry:hover {
        background: #171717;
        border-color: rgba(199, 166, 74, 0.28);
    }

    .rh-entry-cover {
        width: 56px;
        height: 76px;
        border-radius: 2px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: #1f1f1f;
        flex-shrink: 0;
    }
    @media (max-width: 639px) { .rh-entry-cover { width: 48px; height: 66px; } }

    .rh-badge-lastread {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2px 7px;
        background: var(--rh-gold);
        color: #101010;
        font-size: 7px;
        letter-spacing: 0.14em;
        font-weight: 750;
        text-transform: uppercase;
        border-radius: 2px;
    }

    .rh-entry-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 18px;
        line-height: 1.15;
        letter-spacing: 0.01em;
        text-transform: uppercase;
        color: var(--rh-title);
        transition: color .15s ease;
    }
    .rh-entry:hover .rh-entry-title { color: #e2c56f; }

    .rh-entry-author {
        font-size: 8.5px;
        letter-spacing: 0.12em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--rh-subtle);
    }
    .rh-entry-author .val { color: var(--rh-muted); }

    .rh-chapter-line {
        font-size: 10px;
        color: var(--rh-ink);
        display: flex;
        align-items: baseline;
        gap: 8px;
    }
    .rh-chapter-line .num {
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--rh-gold);
        font-size: 9.5px;
    }
    .rh-chapter-line .dash { color: #555; flex-shrink: 0; }
    .rh-chapter-line .name {
        color: #d8d3c8;
        line-clamp: 1;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .rh-entry-meta-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 4px;
    }
    .rh-meta-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 8px;
        letter-spacing: 0.03em;
        color: var(--rh-subtle);
        font-weight: 550;
    }
    .rh-meta-tag svg { flex-shrink: 0; }
    .rh-meta-tag--progress {
        padding: 2px 7px;
        border-radius: 999px;
        background: rgba(199, 166, 74, 0.1);
        color: var(--rh-gold);
        border: 1px solid rgba(199, 166, 74, 0.25);
        font-weight: 650;
    }
    .rh-meta-tag--finished {
        padding: 2px 7px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.04);
        color: var(--rh-muted);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .rh-entry-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 8px;
        flex-shrink: 0;
    }
    .rh-entry-time {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 15px;
        font-weight: 600;
        letter-spacing: 0.02em;
        color: var(--rh-gold-pale);
        font-variant-numeric: tabular-nums;
    }
    .rh-entry-upnext {
        font-size: 7.5px;
        letter-spacing: 0.1em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--rh-subtle);
        text-align: right;
    }
    .rh-entry-upnext .val { color: var(--rh-muted); }

    .rh-btn-resume-solid {
        background: var(--rh-gold);
        color: #101010;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 13px;
        font-size: 8.5px;
        letter-spacing: 0.16em;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 2px;
        transition: filter .15s ease, transform .12s ease;
        white-space: nowrap;
    }
    .rh-btn-resume-solid:hover { filter: brightness(1.08); }
    .rh-btn-resume-solid:active { transform: translateY(1px); }

    .rh-btn-resume-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 13px;
        font-size: 8.5px;
        letter-spacing: 0.16em;
        font-weight: 650;
        text-transform: uppercase;
        border-radius: 2px;
        border: 1px solid rgba(199, 166, 74, 0.5);
        color: var(--rh-gold);
        transition: background-color .15s ease, border-color .15s ease, color .15s ease;
        white-space: nowrap;
    }
    .rh-btn-resume-outline:hover {
        background: rgba(199, 166, 74, 0.08);
        border-color: var(--rh-gold);
        color: var(--rh-gold-soft);
    }

    .rh-entry-close {
        position: absolute;
        top: 14px;
        right: 16px;
        background: transparent;
        border: 0;
        padding: 3px;
        color: var(--rh-subtle);
        cursor: pointer;
        transition: color .15s ease, transform .12s ease;
        border-radius: 3px;
    }
    .rh-entry-close:hover { color: #fca5a5; transform: scale(1.1); }

    /* Load more */
    .rh-load-more {
        margin: 36px auto 0;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        border: 1px solid rgba(199, 166, 74, 0.5);
        color: var(--rh-gold);
        background: transparent;
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 650;
        text-transform: uppercase;
        border-radius: 2px;
        transition: background-color .15s ease, border-color .15s ease, color .15s ease;
    }
    .rh-load-more:hover {
        background: rgba(199, 166, 74, 0.08);
        border-color: var(--rh-gold);
        color: var(--rh-gold-soft);
    }
    .rh-load-more-wrap { text-align: center; }

    .rh-foot-note {
        text-align: center;
        margin-top: 20px;
        font-size: 9.5px;
        color: var(--rh-subtle);
        line-height: 1.6;
    }

    /* Footer */
    .rh-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        background: #080808;
        margin-top: 56px;
    }

    .rh-foot-brand {
        font-size: 15px;
        letter-spacing: 0.28em;
        font-weight: 600;
        color: var(--rh-title);
    }
    .rh-foot-tagline {
        font-size: 10.5px;
        color: var(--rh-subtle);
        line-height: 1.6;
        margin-top: 8px;
        max-width: 320px;
    }
    .rh-foot-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 22px 28px;
        justify-content: flex-end;
    }
    .rh-foot-nav a {
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--rh-muted);
        transition: color .15s ease;
    }
    .rh-foot-nav a:hover { color: var(--rh-gold); }
    .rh-foot-nav a.is-active { color: var(--rh-gold); }

    .rh-foot-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        font-size: 8.5px;
        letter-spacing: 0.08em;
        color: var(--rh-subtle);
    }
    .rh-foot-bottom .links a {
        color: var(--rh-muted);
        transition: color .15s ease;
    }
    .rh-foot-bottom .links a:hover { color: var(--rh-gold); }
    .rh-foot-bottom .links .sep { margin: 0 7px; color: #2e2e2e; }

    @media (prefers-reduced-motion: reduce) {
        .rh-entry, .rh-btn-resume-solid, .rh-btn-resume-outline, .rh-clear-btn, .rh-load-more { transition: none !important; }
    }
</style>
@endpush

@section('content')
<main class="rh-page min-h-screen w-full overflow-x-hidden pt-24 sm:pt-28 pb-12">
    <div class="mx-auto w-full max-w-[1200px] px-5 sm:px-7 lg:px-10">

        {{-- ─── HERO HEADER ─────────────────────────────────────────────── --}}
        <header class="mb-8 border-b border-white/[.08] pb-9 sm:mb-10 sm:pb-10">
            <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end sm:gap-8">
                <div class="min-w-0 max-w-2xl">
                    <p class="mb-3 rh-kicker">
                        Your Chronicle <span class="sep">/</span> Reading Archive
                    </p>
                    <h1 class="rh-hero-title">Reading History</h1>
                    <p class="mt-4 rh-hero-sub">
                        Retrace your journey through the realms. Every chapter leaves a mark.
                    </p>
                    <div class="mt-6 rh-divider-grad w-56"></div>
                </div>
                <div class="flex shrink-0 flex-col items-end justify-end gap-1">
                    <div class="rh-week-count-num">{{ str_pad((string) max(0, (int) ($novelsThisWeek ?? 0)), 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="rh-week-count-label">Novels visited this week</div>
                </div>
            </div>
        </header>

        {{-- ─── FILTER BAR ──────────────────────────────────────────────── --}}
        <form action="{{ route('history.index') }}" method="GET" class="mb-4">
            <div class="rh-filter-wrap">
                <div class="grid grid-cols-1 items-stretch gap-2.5 sm:grid-cols-[1fr_160px_160px_auto] sm:gap-2.5">
                    <div class="rh-input-wrap">
                        <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg>
                        <label for="rh-q" class="sr-only">Search novels or chapters</label>
                        <input id="rh-q" name="q" type="search" value="{{ e($search ?? '') }}"
                               placeholder="Search novels or chapters…"
                               class="rh-search-input">
                    </div>
                    <div class="rh-select-wrap">
                        <span class="rh-select-label">Date Range</span>
                        <select name="date_range" class="rh-select" onchange="this.form.submit()">
                            @foreach($dateRangeOptions ?? [] as $val => $lbl)
                                <option value="{{ $val }}" @selected(($dateRange ?? 'all') === $val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="rh-select-wrap">
                        <span class="rh-select-label">Novel</span>
                        <select name="novel" class="rh-select" onchange="this.form.submit()">
                            <option value="">All novels</option>
                            @foreach($userNovels ?? [] as $n)
                                <option value="{{ $n->id }}" @selected((string) ($filterNovelId ?? '') === (string) $n->id)>{{ \Illuminate\Support\Str::limit($n->title, 36) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button"
                            class="rh-clear-btn"
                            onclick="if(confirm('Clear all reading history? This cannot be undone but will not remove your bookmarks or reading progress.')) { /* TODO: POST clear endpoint */ }">
                        <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M3 6h18"/>
                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/>
                        </svg>
                        Clear History
                    </button>
                </div>
            </div>
        </form>

        {{-- ─── NOTES ROW ──────────────────────────────────────────────── --}}
        <div class="mb-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="rh-note-line">Your reading history is visible <span class="val">only to you</span>.</div>
            <div class="rh-note-line rh-note-line--right">All times in <span class="val">UTC+7</span></div>
        </div>

        {{-- ─── SHOWING / SORT ROW ─────────────────────────────────────── --}}
        <div class="mb-1 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div class="rh-note-line" style="letter-spacing:.12em;text-transform:uppercase;font-weight:600;">
                Showing <span class="val">{{ $paginated->count() ? '1–' . $paginated->count() : '0' }}</span> of <span class="val">{{ $totalEntries ?? 0 }}</span> reading entries
            </div>
            <div class="rh-note-line rh-note-line--right">
                Most recent first <span style="color:#525252;margin-left:2px;">↓</span>
            </div>
        </div>

        {{-- ─── GROUPED ENTRIES ────────────────────────────────────────── --}}
        @if(!empty($paginatedGroups) && count($paginatedGroups) > 0)
            @foreach($paginatedGroups as $dateKey => $group)
                @php
                    $gDate = $group['date'];
                    $gLabel = $group['label'];
                    $gCount = $group['count'];
                @endphp
                <div class="rh-date-group">
                    <div class="rh-date-left">
                        <span class="rh-date-label">{{ $gLabel }}</span>
                        <span class="rh-date-sub">{{ strtoupper($gDate->format('j F Y')) }}</span>
                        <div class="rh-date-rule"></div>
                    </div>
                    <span class="rh-date-count">
                        <span class="num">{{ str_pad((string) $gCount, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="lbl">ENTRIES</span>
                    </span>
                </div>

                <div class="space-y-2">
                    @foreach($group['items'] as $idx => $h)
                        @php
                            $novel = $h->novel;
                            $chapter = $h->chapter;
                            $coverUrl = $novel->cover_image_url ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
                            $author = $novel->author?->name ?? 'Unknown';
                            $chapterNum = $chapter->chapter_number ?? ($chapter->order ?? '—');
                            $nextCh = $h->next_chapter;
                            $progress = (float) ($h->progress_percentage ?? 0);
                            $readChCount = (int) ($h->read_chapters_count ?? 0);
                            $totalChCount = (int) ($h->total_chapters ?? 0);
                            $timeSpent = (int) ($h->time_spent_minutes ?? 12);
                            $isBookmarked = (bool) ($h->is_bookmarked ?? false);
                            $chapterFinished = $chapter?->order && ($nextCh?->order ?? 0) > (int) $chapter->order;
                            $isLastReadOfDay = $idx === 0;
                            $resumeCh = $nextCh ?? $chapter;
                            $resumeHref = $resumeCh
                                ? route('chapters.show', [$novel->slug, $resumeCh->route_identifier])
                                : route('novels.show', $novel->slug);
                            $isSolidResume = $idx === 0 && $gLabel === 'TODAY';
                        @endphp
                        <article class="rh-entry">
                            <button type="button" class="rh-entry-close" aria-label="Remove entry" title="Remove from history">
                                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>

                            <div class="flex items-start gap-4">
                                {{-- Cover --}}
                                <a href="{{ route('novels.show', $novel->slug) }}" class="rh-entry-cover" tabindex="-1" aria-hidden="true">
                                    @if($coverUrl)
                                        <img src="{{ $coverUrl }}" alt="{{ e($novel->title) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null;this.src='/error.png'">
                                    @else
                                        <img src="/error.png" alt="" class="w-full h-full object-cover" loading="lazy">
                                    @endif
                                </a>

                                {{-- Info --}}
                                <div class="min-w-0 flex-1 pr-8">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
                                        @if($isLastReadOfDay && $gLabel === 'TODAY')
                                            <span class="rh-badge-lastread">Last Read</span>
                                        @endif
                                        <a href="{{ route('novels.show', $novel->slug) }}" class="block min-w-0 flex-1">
                                            <h3 class="rh-entry-title line-clamp-1 pr-4">{{ $novel->title }}</h3>
                                        </a>
                                    </div>
                                    <div class="rh-entry-author mb-2.5">
                                        By <span class="val">{{ $author }}</span>
                                    </div>

                                    <div class="rh-chapter-line mb-2.5">
                                        <span class="num">Chapter {{ $chapterNum }}</span>
                                        <span class="dash">—</span>
                                        <span class="name line-clamp-1">{{ \Illuminate\Support\Str::limit($chapter->title ?? '', 52) }}</span>
                                    </div>

                                    <div class="rh-entry-meta-row">
                                        @if($progress > 0 && $progress < 99 && !$chapterFinished)
                                            <span class="rh-meta-tag rh-meta-tag--progress">
                                                <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2" stroke-linecap="round"/></svg>
                                                {{ number_format($progress, 0) }}% of chapter
                                            </span>
                                        @else
                                            <span class="rh-meta-tag rh-meta-tag--finished">
                                                <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22c5.5 0 10-4.5 10-10S17.5 2 12 2 2 6.5 2 12s4.5 10 10 10Z" stroke-linecap="round" stroke-linejoin="round"/><path d="m8 12 3 3 5-6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                Chapter Finished
                                            </span>
                                        @endif
                                        <span class="rh-meta-tag">
                                            <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2" stroke-linecap="round"/></svg>
                                            {{ $timeSpent }} min read
                                        </span>
                                        @if($isBookmarked)
                                            <span class="rh-meta-tag">
                                                <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="currentColor" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M19 21 12 16l-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2Z"/></svg>
                                                Bookmarked
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Right side: time + CTA --}}
                                <div class="rh-entry-right hidden sm:flex">
                                    <div class="text-right">
                                        <div class="rh-entry-time">{{ $h->created_at->format('H:i') }}</div>
                                        <div class="rh-entry-upnext mt-0.5">
                                            @if($nextCh)
                                                Up next · <span class="val">Chapter {{ $nextCh->chapter_number ?? ($nextCh->order ?? $chapterNum + 1) }}</span>
                                            @else
                                                Continue · <span class="val">Chapter {{ $chapterNum }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <a href="{{ $resumeHref }}" class="{{ $isSolidResume ? 'rh-btn-resume-solid' : 'rh-btn-resume-outline' }}">
                                        Resume Reading
                                        <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                                    </a>
                                </div>
                            </div>

                            {{-- Mobile: time + CTA row --}}
                            <div class="rh-entry-right sm:hidden mt-4 pt-3 border-t border-white/5 flex-row justify-between items-center w-full flex">
                                <div>
                                    <div class="rh-entry-time">{{ $h->created_at->format('H:i') }}</div>
                                </div>
                                <a href="{{ $resumeHref }}" class="{{ $isSolidResume ? 'rh-btn-resume-solid' : 'rh-btn-resume-outline' }}">
                                    Resume
                                    <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endforeach
        @else
            <section class="mt-10 rounded-lg border border-white/10 bg-[#121212] px-5 py-20 text-center">
                <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full border border-white/10 text-[#c7a64a]">
                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 8v4"/>
                        <path d="M12 16h.01"/>
                    </svg>
                </div>
                <h2 class="rh-serif text-2xl uppercase text-[#eeeae1]">No footprints on the path yet.</h2>
                <p class="mx-auto mt-2 max-w-sm text-xs leading-6 text-neutral-500">
                    Start reading a novel, and your chronicle will begin here.
                </p>
                <a href="{{ route('welcome') }}" class="mt-6 inline-flex items-center gap-1.5 rounded border border-[#c7a64a]/45 px-4 py-2.5 text-[9px] font-semibold uppercase tracking-[.14em] text-[#c7a64a] transition hover:bg-[#c7a64a]/10">
                    Explore Novels
                </a>
            </section>
        @endif

        {{-- ─── PAGINATION / LOAD MORE ─────────────────────────────────── --}}
        @if(!empty($paginatedGroups) && $paginated->hasPages())
            <div class="rh-load-more-wrap mt-10">
                @if($paginated->hasMorePages())
                    {{ $paginated->onEachSide(1)->links('vendor.pagination.tailwind') }}
                @endif
                <div class="mt-5">
                    <a href="{{ $paginated->nextPageUrl() ?? '#' }}" class="rh-load-more" @if(!$paginated->hasMorePages()) style="opacity:.4;pointer-events:none;" @endif>
                        Load Earlier Activity
                        <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                    </a>
                </div>
            </div>
        @endif

        {{-- ─── FOOTER NOTE ────────────────────────────────────────────── --}}
        @if(!empty($paginatedGroups))
            <p class="rh-foot-note">
                Clearing history won't remove your bookmarks or reading progress.
            </p>
        @endif

    </div>
</main>
@endsection
