@extends('layouts.app', ['title' => 'My Reading Lists · Quoros'])

@push('styles')
<style>
    .ul-page {
        --ul-bg: #0a0a0a;
        --ul-card: #131313;
        --ul-border: rgba(255, 255, 255, 0.10);
        --ul-gold: #c7a64a;
        --ul-gold-soft: #e8d39a;
        --ul-title: #f2efe8;
        --ul-ink: #eeeae1;
        --ul-muted: #a3a3a3;
        --ul-subtle: #737373;
        background: var(--ul-bg);
    }
    body:has(.ul-page) { background-color: var(--ul-bg) !important; }

    .ul-kicker { font-size: 9px; letter-spacing: 0.22em; font-weight: 650; text-transform: uppercase; color: var(--ul-gold); }
    .ul-kicker .sep { color: var(--ul-subtle); margin: 0 8px; }

    .ul-hero-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 44px;
        line-height: 1;
        letter-spacing: 0.015em;
        text-transform: uppercase;
        color: var(--ul-title);
    }
    @media (max-width: 639px) { .ul-hero-title { font-size: 30px; } }

    .ul-hero-sub { font-size: 12px; line-height: 1.7; color: var(--ul-muted); max-width: 560px; }

    .ul-divider-grad { height: 1px; background: linear-gradient(to right, var(--ul-gold), transparent); }

    .ul-count-num {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600; font-size: 28px; line-height: 1; letter-spacing: .01em; color: var(--ul-gold);
    }
    .ul-count-label {
        font-size: 8px; letter-spacing: .14em; font-weight: 650; text-transform: uppercase; color: var(--ul-muted); margin-top: 5px;
    }

    .ul-btn-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 14px;
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 650;
        text-transform: uppercase;
        border-radius: 2px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: var(--ul-muted);
        background: transparent;
        transition: border-color .15s ease, color .15s ease, background-color .15s ease;
    }
    .ul-btn-outline:hover {
        border-color: rgba(199, 166, 74, 0.55);
        color: var(--ul-gold-soft);
        background: rgba(199, 166, 74, 0.06);
    }

    .ul-btn-outline--danger:hover {
        border-color: rgba(220, 38, 38, 0.45);
        color: #fca5a5;
        background: rgba(220, 38, 38, 0.05);
    }

    .ul-btn-solid {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 8px 14px;
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 2px;
        background: var(--ul-gold);
        color: #101010;
        transition: filter .15s ease, transform .12s ease;
        white-space: nowrap;
        border: 1px solid transparent;
    }
    .ul-btn-solid:hover { filter: brightness(1.08); }
    .ul-btn-solid:active { transform: translateY(1px); }

    .ul-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 14px;
    }
    .ul-section-title {
        display: flex;
        align-items: center;
        gap: 16px;
        flex: 1;
        min-width: 0;
    }
    .ul-section-title-text {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 20px;
        line-height: 1;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        color: var(--ul-title);
        white-space: nowrap;
    }
    .ul-section-rule {
        flex: 1;
        height: 1px;
        background: rgba(255, 255, 255, 0.08);
        min-width: 24px;
    }

    /* List Cards */
    .ul-list-card {
        background: var(--ul-card);
        border: 1px solid var(--ul-border);
        border-radius: 6px;
        padding: 16px 18px;
        transition: border-color .2s ease, background-color .2s ease, transform .18s ease;
    }
    .ul-list-card:hover {
        background: #171717;
        border-color: rgba(199,166,74,0.3);
        transform: translateY(-1px);
    }

    .ul-list-thumbs {
        display: flex;
        align-items: stretch;
        gap: 0;
        width: 104px;
    }
    .ul-list-thumb {
        flex: 1;
        height: 132px;
        background: #1a1a1a;
        border: 1px solid rgba(255,255,255,0.08);
        overflow: hidden;
        border-radius: 2px;
    }
    .ul-list-thumb + .ul-list-thumb { margin-left: -1px; }
    .ul-list-thumb:nth-child(1) { z-index: 3; transform: rotate(-2deg) translateY(2px); }
    .ul-list-thumb:nth-child(2) { z-index: 2; }
    .ul-list-thumb:nth-child(3) { z-index: 1; transform: rotate(2deg) translateY(2px); }
    .ul-list-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }

    .ul-list-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 2.5px 8px;
        font-size: 7.5px;
        letter-spacing: 0.2em;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 2px;
    }
    .ul-list-badge--public { background: rgba(199,166,74,.12); color: var(--ul-gold); border: 1px solid rgba(199,166,74,.4); }
    .ul-list-badge--private { background: rgba(255,255,255,.04); color: var(--ul-muted); border: 1px solid rgba(255,255,255,.1); }

    .ul-list-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 18px;
        line-height: 1.15;
        letter-spacing: 0.015em;
        text-transform: uppercase;
        color: var(--ul-ink);
        transition: color .15s ease;
    }
    .ul-list-card:hover .ul-list-title { color: #e2c56f; }

    .ul-list-count {
        font-size: 8.5px;
        letter-spacing: 0.14em;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ul-gold);
    }

    .ul-list-desc {
        font-size: 11px;
        line-height: 1.65;
        color: var(--ul-muted);
    }

    .ul-list-visibility {
        font-size: 7.5px;
        letter-spacing: 0.14em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--ul-subtle);
    }

    /* Empty state */
    .ul-empty {
        border-radius: 6px;
        border: 1px dashed rgba(255, 255, 255, 0.12);
        background: #101010;
        padding: 56px 20px;
        text-align: center;
    }
    .ul-empty-icon {
        width: 54px; height: 54px; margin: 0 auto 16px;
        border-radius: 999px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(199,166,74,0.25);
        background: rgba(199,166,74,0.05);
        color: var(--ul-gold);
    }
    .ul-empty-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 22px;
        text-transform: uppercase;
        font-weight: 600;
        color: var(--ul-title);
    }
    .ul-empty-sub { margin-top: 8px; color: var(--ul-muted); font-size: 11.5px; line-height: 1.7; max-width: 420px; margin-left: auto; margin-right: auto; }

    /* Quick stats strip */
    .ul-stat-pill {
        display: inline-flex; align-items: baseline; gap: 7px;
        padding: 6px 12px;
        background: #101010;
        border: 1px solid rgba(255,255,255,0.07);
        border-radius: 999px;
    }
    .ul-stat-pill .num {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 18px; font-weight: 600; line-height: 1; color: var(--ul-gold);
    }
    .ul-stat-pill .lbl { font-size: 7.5px; letter-spacing: .16em; font-weight: 650; text-transform: uppercase; color: var(--ul-muted); }
</style>
@endpush

@section('content')
<main class="ul-page min-h-screen w-full overflow-x-hidden pt-24 sm:pt-28 pb-12">
    <div class="mx-auto w-full max-w-[1200px] px-5 sm:px-7 lg:px-10">

        @php
            $totalCount = $lists->count();
            $publicCount = $lists->where('is_public', true)->count();
            $privateCount = $lists->where('is_public', false)->count();
            $totalNovels = $lists->sum('novels_count');
        @endphp

        {{-- Breadcrumb / Back to Profile --}}
        <a href="{{ auth()->check() ? route('profile.show', auth()->user()->username ?? auth()->id()) : route('welcome') }}"
           class="inline-flex items-center gap-6 text-[9px] uppercase tracking-[.16em] font-semibold text-[#a3a3a3] mb-6 hover:text-[#e8d39a] transition-colors">
            <span class="inline-flex items-center gap-1.5">
                <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                Back to Profile
            </span>
        </a>

        {{-- ─── HEADER ─────────────────────────────────────────────────── --}}
        <header class="mb-9 border-b border-white/[.08] pb-9">
            <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between md:gap-10">
                <div class="min-w-0 max-w-3xl">
                    <p class="mb-3 ul-kicker">
                        Your Archive <span class="sep">/</span> Reading Lists
                    </p>
                    <h1 class="ul-hero-title">Curated Shelves</h1>
                    <p class="ul-hero-sub mt-4">
                        Group novels by mood, theme, or reading plan — then share a shelf with other readers or keep it private for the next quiet weekend.
                    </p>
                    <div class="mt-4 ul-divider-grad w-44"></div>

                    <div class="mt-6 flex flex-wrap items-center gap-2.5">
                        <span class="ul-stat-pill">
                            <span class="num">{{ str_pad((string) max($totalCount, 0), 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="lbl">Lists</span>
                        </span>
                        <span class="ul-stat-pill">
                            <span class="num">{{ str_pad((string) max($publicCount, 0), 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="lbl">Public</span>
                        </span>
                        <span class="ul-stat-pill">
                            <span class="num">{{ str_pad((string) max($privateCount, 0), 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="lbl">Private</span>
                        </span>
                        <span class="ul-stat-pill">
                            <span class="num">{{ str_pad((string) max((int) $totalNovels, 0), 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="lbl">Novels</span>
                        </span>
                    </div>
                </div>

                <div class="flex shrink-0 flex-col items-end gap-5">
                    <div class="text-right">
                        <div class="ul-count-num">{{ str_pad((string) max($totalCount, 0), 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="ul-count-label">Shelves owned</div>
                    </div>

                    <a href="{{ route('lists.create') }}" class="ul-btn-solid">
                        <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        New List
                    </a>
                </div>
            </div>
        </header>

        {{-- ─── LIST GRID ─────────────────────────────────────────────── --}}
        <section>
            <div class="ul-section-head">
                <div class="ul-section-title">
                    <span class="ul-section-title-text">All Shelves</span>
                    <div class="ul-section-rule"></div>
                </div>
                <span class="text-[8.5px] uppercase tracking-[.14em] font-semibold text-neutral-500 whitespace-nowrap">
                    {{ str_pad((string) max($totalCount, 0), 2, '0', STR_PAD_LEFT) }} entries
                </span>
            </div>

            @if($lists->isNotEmpty())
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">
                    @foreach($lists as $list)
                        @php
                            $listNovels = $list->novels ?? collect();
                            $listThumbs = $listNovels->take(3);
                            $listIsPublic = (bool) ($list->is_public ?? false);
                            $owner = $list->user ?? auth()->user();
                            $listHref = $listIsPublic
                                ? route('lists.public', [$owner?->username ?? $owner?->id ?? auth()->id(), $list])
                                : route('lists.show', $list);
                            $listCount = (int) ($list->novels_count ?? 0);
                        @endphp
                        <article class="ul-list-card">
                            <div class="flex flex-col sm:flex-row gap-5">
                                {{-- Thumbnails stack --}}
                                <a href="{{ $listHref }}" class="ul-list-thumbs shrink-0 block" tabindex="-1" aria-hidden="true">
                                    @for($i = 0; $i < 3; $i++)
                                        @php
                                            $tn = $listThumbs[$i] ?? null;
                                            $thumbUrl = $tn ? ($tn->cover_image_url ?: ($tn->cover_image ? asset('storage/' . $tn->cover_image) : null)) : null;
                                        @endphp
                                        <div class="ul-list-thumb">
                                            @if($thumbUrl)
                                                <img src="{{ $thumbUrl }}" alt="" loading="lazy" onerror="this.onerror=null;this.src='/error.png'">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <span class="text-[7px] text-[#5a5a5a] font-semibold tracking-wider uppercase">SHELF</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endfor
                                </a>

                                <div class="min-w-0 flex-1 flex flex-col">
                                    <div class="mb-2.5">
                                        <span class="ul-list-badge {{ $listIsPublic ? 'ul-list-badge--public' : 'ul-list-badge--private' }}">
                                            <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                                @if($listIsPublic)
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                                                @else
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a4 4 0 1 0-8 0v2"/>
                                                @endif
                                            </svg>
                                            {{ $listIsPublic ? 'Public' : 'Private' }}
                                        </span>
                                    </div>

                                    <a href="{{ $listHref }}" class="block">
                                        <h2 class="ul-list-title line-clamp-1">{{ $list->title }}</h2>
                                    </a>
                                    <div class="ul-list-count mt-2">{{ str_pad((string) $listCount, 2, '0', STR_PAD_LEFT) }} Novels</div>

                                    @if(!empty($list->description))
                                        <p class="ul-list-desc mt-3 line-clamp-2">{{ $list->description }}</p>
                                    @else
                                        <p class="ul-list-desc mt-3 italic text-[#6f6f6f] line-clamp-2">
                                            {{ $listIsPublic
                                                ? 'Forgotten gods, impossible bargains, and kingdoms on the brink.'
                                                : 'The stories I want to begin when the current chapter ends.' }}
                                        </p>
                                    @endif

                                    <div class="mt-auto pt-4 flex items-center justify-between gap-3">
                                        <span class="ul-list-visibility">
                                            {{ $listIsPublic ? 'Anyone can view' : 'Only you can view' }}
                                        </span>
                                        <a href="{{ $listHref }}" class="ul-btn-outline">
                                            Open Shelf
                                            <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="ul-empty">
                    <div class="ul-empty-icon">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="4" width="18" height="16" rx="2"/>
                            <path d="M3 10h18"/><path d="M8 4v16"/>
                        </svg>
                    </div>
                    <h2 class="ul-empty-title">No shelves yet</h2>
                    <p class="ul-empty-sub">Start a themed collection — a rainy-day stack, a villain-coup shelf, or the series you swear you'll finish this year.</p>
                    <a href="{{ route('lists.create') }}" class="mt-7 inline-flex ul-btn-solid">
                        <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        Create Your First List
                    </a>
                </div>
            @endif
        </section>

    </div>
</main>
@endsection
