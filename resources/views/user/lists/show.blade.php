@extends('layouts.app', ['title' => $list->title . ' — Reading List · Quoros'])

@push('styles')
<style>
    .ul-serif { font-family: 'Cormorant Garamond', Georgia, serif; }
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
    .ul-divider-grad { height: 1px; background: linear-gradient(to right, var(--ul-gold), transparent); }

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

    .ul-meta { font-size: 9px; letter-spacing: 0.12em; font-weight: 550; text-transform: uppercase; color: var(--ul-subtle); }
    .ul-meta .val { color: var(--ul-muted); }
    .ul-meta .dot { color: #3a3a3a; margin: 0 7px; }

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
    .ul-btn-solid[disabled], .ul-btn-solid:disabled {
        background: #1f1f1f !important;
        color: #525252 !important;
        cursor: not-allowed !important;
        filter: none !important;
    }

    /* Add panel */
    .ul-add-panel {
        background: var(--ul-card);
        border: 1px solid rgba(199,166,74,0.22);
        border-radius: 6px;
        padding: 16px 18px;
        margin-bottom: 16px;
    }

    .ul-add-label {
        font-size: 8px;
        letter-spacing: 0.18em;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ul-gold);
        margin-bottom: 8px;
    }

    .ul-select-wrap { position: relative; }
    .ul-select-wrap::after {
        content: '';
        position: absolute;
        right: 14px;
        top: 50%;
        width: 8px;
        height: 8px;
        border-right: 1.5px solid var(--ul-subtle);
        border-bottom: 1.5px solid var(--ul-subtle);
        transform: translateY(calc(-50% + 1px)) rotate(45deg);
        pointer-events: none;
    }
    .ul-select-wrap:hover::after { border-color: var(--ul-gold); }

    .ul-novel-select {
        appearance: none;
        -webkit-appearance: none;
        width: 100%;
        height: 40px;
        padding: 0 40px 0 14px;
        border-radius: 4px;
        border: 1px solid var(--ul-border);
        background: #0f0f0f;
        color: #e6e1d6;
        font-size: 11.5px;
        font-weight: 500;
        outline: none;
        cursor: pointer;
        transition: border-color .15s ease;
    }
    .ul-novel-select:hover { border-color: rgba(199,166,74,0.4); }
    .ul-novel-select:focus { border-color: rgba(199,166,74,0.7); color: #fff; }
    .ul-novel-select option { background: #0f0f0f; color: #d4d4d4; padding: 4px 8px; }

    /* Novel entries */
    .ul-novel-card {
        background: var(--ul-card);
        border: 1px solid var(--ul-border);
        border-radius: 6px;
        padding: 12px 14px;
        transition: border-color .2s ease, background-color .2s ease, transform .18s ease;
    }
    .ul-novel-card:hover {
        background: #171717;
        border-color: rgba(199,166,74,0.3);
        transform: translateY(-1px);
    }

    .ul-cover {
        width: 44px; height: 60px;
        border-radius: 2px; overflow: hidden;
        border: 1px solid rgba(255,255,255,.1);
        background: #1f1f1f;
        flex-shrink: 0;
    }

    .ul-novel-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 16px;
        line-height: 1.15;
        letter-spacing: 0.01em;
        text-transform: uppercase;
        color: var(--ul-ink);
        transition: color .15s ease;
    }
    .ul-novel-card:hover .ul-novel-title { color: #e2c56f; }

    .ul-novel-meta {
        font-size: 8px;
        letter-spacing: 0.12em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--ul-subtle);
        margin-top: 2px;
    }
    .ul-novel-meta .val { color: var(--ul-muted); }

    .ul-genre-tag {
        display: inline-block;
        font-size: 7.5px;
        letter-spacing: 0.14em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--ul-subtle);
        padding: 2px 7px;
        border-radius: 999px;
        background: rgba(255,255,255,.03);
        border: 1px solid rgba(255,255,255,.06);
    }

    .ul-remove-btn {
        font-size: 8.5px;
        letter-spacing: 0.14em;
        font-weight: 650;
        text-transform: uppercase;
        color: var(--ul-subtle);
        padding: 5px 10px;
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 2px;
        background: transparent;
        transition: color .15s ease, border-color .15s ease, background-color .15s ease;
    }
    .ul-remove-btn:hover {
        color: #fca5a5;
        border-color: rgba(220,38,38,0.4);
        background: rgba(220,38,38,0.05);
    }

    /* Section head */
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

    .ul-empty {
        border-radius: 6px;
        border: 1px dashed rgba(255, 255, 255, 0.12);
        background: #101010;
        padding: 48px 20px;
        text-align: center;
    }
    .ul-empty-icon {
        width: 52px; height: 52px; margin: 0 auto 16px;
        border-radius: 999px;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(199,166,74,0.25);
        background: rgba(199,166,74,0.05);
        color: var(--ul-gold);
    }
    .ul-empty-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 20px;
        text-transform: uppercase;
        font-weight: 600;
        color: var(--ul-title);
    }
    .ul-empty-sub { margin-top: 6px; color: var(--ul-muted); font-size: 11.5px; line-height: 1.7; max-width: 400px; margin-left: auto; margin-right: auto; }

    /* Session flash bars */
    .ul-flash {
        border-radius: 4px;
        padding: 11px 14px;
        font-size: 10.5px;
        line-height: 1.55;
        margin-bottom: 16px;
        border: 1px solid;
    }
    .ul-flash--success { background: rgba(199,166,74,0.08); border-color: rgba(199,166,74,0.4); color: var(--ul-gold-soft); }
    .ul-flash--error { background: rgba(220,38,38,0.07); border-color: rgba(220,38,38,0.45); color: #fca5a5; }
    .ul-flash--info { background: rgba(59,130,246,0.07); border-color: rgba(59,130,246,0.4); color: #93c5fd; }
</style>
@endpush

@section('content')
<main class="ul-page min-h-screen w-full overflow-x-hidden pt-24 sm:pt-28 pb-12">
    <div class="mx-auto w-full max-w-[1200px] px-5 sm:px-7 lg:px-10">

        @php
            $owner = $list->user;
            $shareUrl = $list->is_public && $owner ? route('lists.public', [$owner->username ?? $owner->id, $list->slug]) : null;
            $novelsCount = $list->novels->count();
        @endphp

        {{-- Breadcrumb --}}
        <a href="{{ route('lists.index') }}" class="inline-flex items-center gap-6 text-[9px] uppercase tracking-[.16em] font-semibold text-[#a3a3a3] mb-6 hover:text-[#e8d39a] transition-colors">
            <span class="inline-flex items-center gap-1.5">
                <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                Back to Reading Lists
            </span>
        </a>

        {{-- Session messages --}}
        @foreach(['success' => 'success', 'error' => 'error', 'info' => 'info'] as $key => $type)
            @if(session($key))
                <div class="ul-flash ul-flash--{{ $type }}">
                    {{ session($key) }}
                </div>
            @endif
        @endforeach

        {{-- ─── HEADER ─────────────────────────────────────────────────── --}}
        <header class="mb-9 border-b border-white/[.08] pb-9">
            <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between md:gap-10">
                <div class="min-w-0 max-w-3xl">
                    <p class="mb-3 ul-kicker">
                        Your Archive <span class="sep">/</span> Reading List
                        <span class="sep">/</span>
                        <span style="color: var(--ul-subtle); letter-spacing:0.14em;">{{ $owner?->name ?? 'Reader' }}</span>
                    </p>
                    <div class="flex flex-wrap items-center gap-3 mb-3">
                        <h1 class="ul-hero-title">{{ $list->title }}</h1>
                        <span class="ul-list-badge {{ $list->is_public ? 'ul-list-badge--public' : 'ul-list-badge--private' }}">
                            <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                @if($list->is_public)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a4 4 0 1 0-8 0v2"/>
                                @endif
                            </svg>
                            {{ $list->is_public ? 'Public' : 'Private' }}
                        </span>
                    </div>
                    @if($list->description)
                        <p class="ul-hero-sub">{{ $list->description }}</p>
                    @else
                        <p class="ul-hero-sub italic text-[#737373]">A curated corner of your library — titles handpicked for a mood, a theme, or a future mood.</p>
                    @endif
                    <div class="mt-4 ul-divider-grad w-44"></div>
                    <div class="mt-5 flex flex-wrap items-center gap-2 ul-meta">
                        <span class="val">{{ $owner?->name ?? 'Reader' }}</span>
                        <span class="dot">·</span>
                        <span class="val">{{ str_pad((string) $novelsCount, 2, '0', STR_PAD_LEFT) }}</span> novels
                        <span class="dot">·</span>
                        Created <span class="val">{{ $list->created_at->format('M Y') }}</span>
                        @if($shareUrl)
                            <span class="dot">·</span>
                            <span>Share: <span class="val break-all">{{ $shareUrl }}</span></span>
                        @endif
                    </div>
                </div>

                <div class="flex shrink-0 flex-col items-end gap-5">
                    <div class="text-right">
                        <div class="ul-count-num">{{ str_pad((string) $novelsCount, 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="ul-count-label">Titles shelved</div>
                    </div>

                    @if($isOwner)
                        <div class="flex flex-wrap gap-2 justify-end">
                            <a href="{{ route('lists.edit', $list) }}" class="ul-btn-outline">
                                <svg viewBox="0 0 24 24" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 1 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                Edit List
                            </a>
                            <form action="{{ route('lists.destroy', $list) }}" method="POST" onsubmit="return confirm('Delete this list permanently?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ul-btn-outline ul-btn-outline--danger">
                                    <svg viewBox="0 0 24 24" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/></svg>
                                    Delete List
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </header>

        {{-- ─── ADD NOVEL PANEL (OWNER ONLY) ───────────────────────────── --}}
        @if($isOwner)
            <div class="ul-add-panel">
                <form action="{{ route('lists.novels.add', ['list' => $list]) }}" method="POST" id="ul-add-form">
                    @csrf
                    <div class="flex flex-col gap-2.5 sm:flex-row sm:items-end sm:gap-3">
                        <div class="min-w-0 flex-1">
                            <div class="ul-add-label">Add a novel to this list</div>
                            <div class="ul-select-wrap">
                                <label for="ul-novel-pick" class="sr-only">Select a novel</label>
                                <select id="ul-novel-pick" name="novel_id" class="ul-novel-select" required>
                                    <option value="" disabled selected>— Pick a novel from your shelf or recent releases —</option>
                                    @foreach($pickableNovels ?? [] as $pn)
                                        @continue(!$pn?->id)
                                        <option value="{{ $pn->id }}">
                                            {{ $pn->title }}
                                            @if(!empty($pn->author?->name)) — by {{ $pn->author->name }} @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="margin-top:8px;">
                                <a href="{{ route('novels.search') }}" class="text-[8.5px] uppercase tracking-[.14em] font-semibold text-[#c7a64a]/90 hover:text-[#e8d39a] inline-flex items-center gap-1.5">
                                    Can't find it? Browse the full library
                                    <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                                </a>
                            </div>
                        </div>

                        <button type="submit" class="ul-btn-solid sm:mb-0.5" id="ul-add-btn">
                            <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                            Add to List
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- ─── LIST NOVELS ────────────────────────────────────────────── --}}
        <section>
            <div class="ul-section-head">
                <div class="ul-section-title">
                    <span class="ul-section-title-text">Shelved Titles</span>
                    <div class="ul-section-rule"></div>
                </div>
                <span class="text-[8.5px] uppercase tracking-[.14em] font-semibold text-neutral-500 whitespace-nowrap">
                    {{ str_pad((string) $novelsCount, 2, '0', STR_PAD_LEFT) }} entries
                </span>
            </div>

            @if($novelsCount > 0)
                <div class="space-y-2">
                    @foreach($list->novels as $novel)
                        @php
                            $cover = $novel->cover_image_url ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
                        @endphp
                        <article class="ul-novel-card">
                            <div class="flex items-center gap-4">
                                <a href="{{ route('novels.show', $novel->slug) }}" class="ul-cover" tabindex="-1" aria-hidden="true">
                                    @if($cover)
                                        <img src="{{ $cover }}" alt="{{ e($novel->title) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null;this.src='/error.png'">
                                    @else
                                        <img src="/error.png" alt="" class="w-full h-full object-cover" loading="lazy">
                                    @endif
                                </a>

                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('novels.show', $novel->slug) }}" class="block">
                                        <h3 class="ul-novel-title line-clamp-1">{{ $novel->title }}</h3>
                                    </a>
                                    <div class="ul-novel-meta mt-1.5">
                                        By <span class="val">{{ $novel->author?->name ?? 'Unknown' }}</span>
                                    </div>
                                    @if($novel->relationLoaded('genres') && $novel->genres->isNotEmpty())
                                        <div class="mt-2.5 flex flex-wrap gap-1.5">
                                            @foreach($novel->genres->take(3) as $g)
                                                <span class="ul-genre-tag">{{ $g->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                @if($isOwner)
                                    <form action="{{ route('lists.novels.remove', ['list' => $list, 'novel' => $novel->id]) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ul-remove-btn">Remove</button>
                                    </form>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="ul-empty">
                    <div class="ul-empty-icon">
                        <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19v18H6.5a2.5 2.5 0 0 0 0 5H20"/>
                            <path d="M8 7h8"/><path d="M8 11h8"/><path d="M8 15h5"/>
                        </svg>
                    </div>
                    <h2 class="ul-empty-title">List is still empty</h2>
                    @if($isOwner)
                        <p class="ul-empty-sub">Curate your first entry from the panel above — pick a novel from your bookmarks or recent releases, then drop it here.</p>
                    @else
                        <p class="ul-empty-sub">The curator hasn't shelved any titles here yet.</p>
                    @endif
                </div>
            @endif
        </section>

    </div>
</main>

@push('scripts')
<script>
(function () {
    var form = document.getElementById('ul-add-form');
    var btn  = document.getElementById('ul-add-btn');
    if (!form || !btn) return;
    form.addEventListener('submit', function () {
        btn.disabled = true;
        btn.style.opacity = '0.75';
        var origText = btn.textContent || '';
        btn.textContent = 'Adding…';
    });
})();
</script>
@endpush
@endsection
