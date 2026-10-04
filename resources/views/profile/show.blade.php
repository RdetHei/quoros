@extends('layouts.app')

@section('title', $user->name . ' — Profile · Quoros')

@push('styles')
<style>
    .pf-serif { font-family: 'Cormorant Garamond', Georgia, serif; }
    .pf-page {
        --pf-bg: #0a0a0a;
        --pf-surface: #121212;
        --pf-card: #131313;
        --pf-border: rgba(255, 255, 255, 0.10);
        --pf-border-gold-soft: rgba(199, 166, 74, 0.35);
        --pf-gold: #c7a64a;
        --pf-gold-soft: #e8d39a;
        --pf-gold-pale: #ead79f;
        --pf-ink: #eeeae1;
        --pf-title: #f2efe8;
        --pf-muted: #a3a3a3;
        --pf-subtle: #737373;
        background: var(--pf-bg);
    }
    body:has(.pf-page) { background-color: var(--pf-bg) !important; }

    .pf-kicker {
        font-size: 9px;
        letter-spacing: 0.22em;
        font-weight: 650;
        text-transform: uppercase;
        color: var(--pf-gold);
    }
    .pf-kicker .sep { color: var(--pf-subtle); margin: 0 8px; }

    .pf-divider-grad {
        height: 1px;
        background: linear-gradient(to right, var(--pf-gold), transparent);
    }

    .pf-hero-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 56px;
        line-height: 1;
        letter-spacing: 0.015em;
        text-transform: uppercase;
        color: var(--pf-title);
    }
    @media (max-width: 639px) {
        .pf-hero-title { font-size: 40px; }
    }

    .pf-hero-sub {
        font-size: 12px;
        line-height: 1.7;
        color: var(--pf-muted);
        max-width: 480px;
    }

    .pf-tab-link {
        padding: 14px 0;
        font-size: 10px;
        letter-spacing: 0.16em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--pf-muted);
        border-bottom: 2px solid transparent;
        transition: color .18s ease, border-color .18s ease;
    }
    .pf-tab-link:hover { color: #d4d4d4; }
    .pf-tab-link.is-active {
        color: var(--pf-gold);
        border-bottom-color: var(--pf-gold);
    }

    .pf-identity-card {
        background: linear-gradient(180deg, #141414 0%, #101010 100%);
        border: 1px solid var(--pf-border);
        border-radius: 8px;
        padding: 28px 32px;
    }
    @media (max-width: 639px) {
        .pf-identity-card { padding: 20px; }
    }

    .pf-avatar-wrap {
        width: 88px;
        height: 88px;
        border-radius: 999px;
        overflow: hidden;
        border: 1px solid rgba(199, 166, 74, 0.4);
        background: #1a1a1a;
        flex-shrink: 0;
    }
    @media (max-width: 639px) { .pf-avatar-wrap { width: 72px; height: 72px; } }

    .pf-role-label {
        font-size: 8px;
        letter-spacing: 0.2em;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--pf-gold);
    }

    .pf-display-name {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 34px;
        line-height: 1;
        letter-spacing: 0.005em;
        color: var(--pf-title);
    }
    @media (max-width: 639px) { .pf-display-name { font-size: 26px; } }

    .pf-username {
        font-size: 10.5px;
        color: var(--pf-muted);
        letter-spacing: 0.01em;
    }

    .pf-bio {
        font-size: 11.5px;
        line-height: 1.7;
        color: var(--pf-muted);
        max-width: 560px;
    }

    .pf-meta-tiny {
        font-size: 8.5px;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        color: var(--pf-subtle);
        font-weight: 650;
    }
    .pf-meta-tiny .val { color: var(--pf-muted); }

    .pf-edit-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 8px 16px;
        border: 1px solid rgba(199, 166, 74, 0.45);
        color: var(--pf-gold);
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 650;
        text-transform: uppercase;
        border-radius: 2px;
        transition: background-color .15s ease, border-color .15s ease, color .15s ease;
    }
    .pf-edit-btn:hover {
        background: rgba(199, 166, 74, 0.08);
        border-color: var(--pf-gold);
        color: var(--pf-gold-soft);
    }

    .pf-profile-url {
        font-size: 8.5px;
        color: var(--pf-subtle);
        letter-spacing: 0.04em;
    }

    .pf-stat-num {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 40px;
        line-height: 1;
        color: var(--pf-gold);
        letter-spacing: 0.01em;
    }
    @media (max-width: 639px) { .pf-stat-num { font-size: 30px; } }

    .pf-stat-label {
        font-size: 8.5px;
        letter-spacing: 0.16em;
        font-weight: 700;
        text-transform: uppercase;
        color: #e6e1d6;
        margin-top: 6px;
    }

    .pf-stat-sub {
        font-size: 7.5px;
        color: var(--pf-subtle);
        letter-spacing: 0.05em;
        margin-top: 3px;
    }

    .pf-section-head {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .pf-section-title {
        display: flex;
        align-items: center;
        gap: 18px;
        flex: 1;
        min-width: 0;
    }

    .pf-section-title-text {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 22px;
        line-height: 1;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        color: var(--pf-title);
        white-space: nowrap;
    }
    @media (max-width: 639px) { .pf-section-title-text { font-size: 18px; } }

    .pf-section-rule {
        flex: 1;
        height: 1px;
        background: rgba(255, 255, 255, 0.08);
        min-width: 24px;
    }

    .pf-view-all-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 8.5px;
        letter-spacing: 0.16em;
        font-weight: 650;
        text-transform: uppercase;
        color: var(--pf-gold);
        white-space: nowrap;
        transition: color .15s ease;
    }
    .pf-view-all-link:hover { color: var(--pf-gold-soft); }
    .pf-view-all-link svg { flex-shrink: 0; }

    /* Continue Journey card */
    .pf-cj-card {
        background: var(--pf-card);
        border: 1px solid var(--pf-border);
        border-radius: 6px;
        padding: 16px;
        transition: border-color .2s ease, background-color .2s ease, transform .2s ease;
    }
    .pf-cj-card:hover {
        background: #171717;
        border-color: rgba(199, 166, 74, 0.35);
        transform: translateY(-1px);
    }

    .pf-cj-status {
        font-size: 8px;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        padding: 2px 7px;
        border-radius: 2px;
        font-weight: 650;
        background: rgba(199, 166, 74, 0.14);
        color: var(--pf-gold);
        border: 1px solid rgba(199, 166, 74, 0.30);
        width: fit-content;
    }

    .pf-cj-cover {
        width: 68px;
        height: 90px;
        border-radius: 2px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: #1f1f1f;
        flex-shrink: 0;
    }
    @media (max-width: 639px) { .pf-cj-cover { width: 56px; height: 76px; } }

    .pf-cj-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 17px;
        line-height: 1.15;
        letter-spacing: 0.01em;
        text-transform: uppercase;
        color: var(--pf-title);
        transition: color .15s ease;
    }
    .pf-cj-card:hover .pf-cj-title { color: #e2c56f; }
    @media (max-width: 639px) { .pf-cj-title { font-size: 14px; } }

    .pf-cj-meta {
        font-size: 8px;
        letter-spacing: 0.12em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--pf-subtle);
    }
    .pf-cj-meta .val { color: var(--pf-muted); }
    .pf-cj-meta .dot { color: #3f3f3f; margin: 0 7px; }

    .pf-cj-chapter {
        font-size: 10.5px;
        color: #cfcbc2;
    }
    .pf-cj-chapter .num {
        font-weight: 650;
        color: #ded9ce;
    }

    .pf-progress-bar {
        background: rgba(255, 255, 255, 0.08);
        height: 3px;
        width: 100%;
        border-radius: 999px;
        overflow: hidden;
    }
    .pf-progress-fill {
        background: var(--pf-gold);
        height: 100%;
        border-radius: 999px;
        transition: width .45s cubic-bezier(0.22, 1, 0.36, 1);
        box-shadow: 0 0 6px 0 rgba(199, 166, 74, 0.35);
    }

    .pf-cj-progress-meta {
        font-size: 8px;
        letter-spacing: 0.04em;
        color: var(--pf-subtle);
        font-weight: 550;
    }
    .pf-cj-progress-meta .val { color: #d4d4d4; font-weight: 600; }
    .pf-cj-progress-meta .pct { color: var(--pf-gold); font-weight: 650; }

    .pf-btn-solid {
        background: var(--pf-gold);
        color: #101010;
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
        transition: filter .15s ease, transform .12s ease;
        white-space: nowrap;
    }
    .pf-btn-solid:hover { filter: brightness(1.08); }
    .pf-btn-solid:active { transform: translateY(1px); }

    .pf-btn-outline {
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
        border: 1px solid rgba(199, 166, 74, 0.5);
        color: var(--pf-gold);
        transition: background-color .15s ease, border-color .15s ease, color .15s ease;
        white-space: nowrap;
    }
    .pf-btn-outline:hover {
        background: rgba(199, 166, 74, 0.08);
        border-color: var(--pf-gold);
        color: var(--pf-gold-soft);
    }

    .pf-read-time {
        font-size: 8px;
        letter-spacing: 0.1em;
        color: var(--pf-subtle);
        text-transform: uppercase;
        font-weight: 600;
    }
    .pf-read-time .val { color: var(--pf-muted); font-weight: 650; }

    /* Shelf cards */
    .pf-shelf-card {
        background: var(--pf-card);
        border: 1px solid var(--pf-border);
        border-radius: 6px;
        padding: 10px 12px;
        display: flex;
        align-items: center;
        gap: 12px;
        transition: border-color .2s ease, background-color .2s ease, transform .2s ease;
    }
    .pf-shelf-card:hover {
        background: #171717;
        border-color: rgba(199, 166, 74, 0.3);
        transform: translateY(-1px);
    }

    .pf-shelf-cover {
        width: 40px;
        height: 54px;
        border-radius: 2px;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: #1f1f1f;
        flex-shrink: 0;
    }

    .pf-shelf-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 14px;
        line-height: 1.15;
        letter-spacing: 0.01em;
        text-transform: uppercase;
        color: var(--pf-ink);
        transition: color .15s ease;
    }
    .pf-shelf-card:hover .pf-shelf-title { color: #e2c56f; }

    .pf-shelf-meta {
        font-size: 7.5px;
        letter-spacing: 0.1em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--pf-subtle);
        margin-top: 3px;
    }
    .pf-shelf-meta .dot { color: #3f3f3f; margin: 0 6px; }

    /* Reading lists */
    .pf-list-card {
        background: var(--pf-card);
        border: 1px solid var(--pf-border);
        border-radius: 6px;
        padding: 16px 18px;
        transition: border-color .2s ease, background-color .2s ease, transform .18s ease;
    }
    .pf-list-card:hover {
        background: #171717;
        border-color: rgba(199, 166, 74, 0.35);
        transform: translateY(-1px);
    }

    .pf-list-thumbs {
        display: flex;
        align-items: stretch;
        gap: 0;
        width: 104px;
    }
    .pf-list-thumb {
        flex: 1;
        height: 132px;
        background: #1a1a1a;
        border: 1px solid rgba(255,255,255,0.08);
        overflow: hidden;
        border-radius: 2px;
    }
    .pf-list-thumb + .pf-list-thumb { margin-left: -1px; }
    .pf-list-thumb:nth-child(1) { z-index: 3; transform: rotate(-2deg) translateY(2px); }
    .pf-list-thumb:nth-child(2) { z-index: 2; }
    .pf-list-thumb:nth-child(3) { z-index: 1; transform: rotate(2deg) translateY(2px); }
    .pf-list-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }

    .pf-list-badge {
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
    .pf-list-badge--public {
        background: rgba(199, 166, 74, 0.12);
        color: var(--pf-gold);
        border: 1px solid rgba(199, 166, 74, 0.4);
    }
    .pf-list-badge--private {
        background: rgba(255, 255, 255, 0.04);
        color: var(--pf-muted);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .pf-list-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 18px;
        line-height: 1.15;
        letter-spacing: 0.015em;
        text-transform: uppercase;
        color: var(--pf-title);
        transition: color .15s ease;
    }
    .pf-list-card:hover .pf-list-title { color: #e2c56f; }

    .pf-list-count {
        font-size: 8.5px;
        letter-spacing: 0.14em;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--pf-gold);
    }

    .pf-list-desc {
        font-size: 11px;
        line-height: 1.65;
        color: var(--pf-muted);
    }

    .pf-list-visibility {
        font-size: 7.5px;
        letter-spacing: 0.14em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--pf-subtle);
    }

    /* Privacy banner */
    .pf-privacy-banner {
        background: #111111;
        border: 1px solid var(--pf-border);
        border-radius: 6px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }
    @media (max-width: 639px) {
        .pf-privacy-banner { flex-direction: column; align-items: flex-start; }
    }

    .pf-privacy-text {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 10.5px;
        color: var(--pf-muted);
        line-height: 1.5;
    }
    .pf-privacy-text svg { flex-shrink: 0; color: var(--pf-gold); }

    .pf-privacy-link {
        font-size: 8.5px;
        letter-spacing: 0.16em;
        font-weight: 650;
        text-transform: uppercase;
        color: var(--pf-gold);
        white-space: nowrap;
        transition: color .15s ease;
    }
    .pf-privacy-link:hover { color: var(--pf-gold-soft); }

    /* Footer */
    .pf-footer {
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        background: #080808;
    }

    .pf-foot-brand {
        font-size: 15px;
        letter-spacing: 0.28em;
        font-weight: 600;
        color: var(--pf-title);
    }
    .pf-foot-tagline {
        font-size: 10.5px;
        color: var(--pf-subtle);
        line-height: 1.6;
        margin-top: 8px;
        max-width: 320px;
    }
    .pf-foot-nav {
        display: flex;
        flex-wrap: wrap;
        gap: 22px 28px;
        justify-content: flex-end;
    }
    .pf-foot-nav a {
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--pf-muted);
        transition: color .15s ease;
    }
    .pf-foot-nav a:hover { color: var(--pf-gold); }
    .pf-foot-nav a.is-active { color: var(--pf-gold); }

    .pf-foot-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        font-size: 8.5px;
        letter-spacing: 0.08em;
        color: var(--pf-subtle);
    }
    .pf-foot-bottom .links a {
        color: var(--pf-muted);
        transition: color .15s ease;
    }
    .pf-foot-bottom .links a:hover { color: var(--pf-gold); }
    .pf-foot-bottom .links .sep { margin: 0 7px; color: #2e2e2e; }

    @media (prefers-reduced-motion: reduce) {
        .pf-cj-card, .pf-shelf-card, .pf-list-card, .pf-tab-link, .pf-view-all-link, .pf-edit-btn, .pf-btn-solid, .pf-btn-outline {
            transition: none !important;
        }
    }
</style>
@endpush

@section('content')
<main class="pf-page min-h-screen w-full overflow-x-hidden pt-24 sm:pt-28 pb-12">
    <div class="mx-auto w-full max-w-[1200px] px-5 sm:px-7 lg:px-10">

        {{-- ─── HERO HEADER ─────────────────────────────────────────────── --}}
        <header class="mb-8 border-b border-white/[.08] pb-9 sm:mb-10 sm:pb-10">
            <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end sm:gap-8">
                <div class="min-w-0 max-w-2xl">
                    <p class="mb-3 pf-kicker">
                        Your Identity <span class="sep">/</span> Reader's Archive
                    </p>
                    <h1 class="pf-hero-title">Profile</h1>
                    <p class="mt-4 pf-hero-sub">
                        A home for your stories, your shelves, and the worlds you return to.
                    </p>
                    <div class="mt-6 pf-divider-grad w-56"></div>
                </div>
            </div>

            {{-- TABS --}}
            <nav class="mt-9 flex flex-wrap items-center gap-x-8 sm:mt-10 sm:gap-x-10" role="tablist" aria-label="Profile sections">
                @php
                    $profileTabs = [
                        ['id' => 'profile', 'label' => 'Profile', 'route' => route('profile.show', $user->username ?? $user->id), 'active' => true],
                        ['id' => 'bookmark', 'label' => 'Bookmark', 'route' => route('bookmarks.index'), 'active' => false],
                        ['id' => 'lists', 'label' => 'Reading Lists', 'route' => route('lists.index'), 'active' => false],
                        ['id' => 'history', 'label' => 'History', 'route' => route('history.index'), 'active' => false],
                    ];
                @endphp
                @foreach($profileTabs as $t)
                    <a href="{{ $t['route'] }}"
                       role="tab"
                       aria-selected="{{ $t['active'] ? 'true' : 'false' }}"
                       class="pf-tab-link @if($t['active']) is-active @endif">
                        {{ $t['label'] }}
                    </a>
                @endforeach
            </nav>
        </header>

        {{-- ─── IDENTITY CARD ───────────────────────────────────────────── --}}
        <section class="mb-10">
            <div class="pf-identity-card">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:gap-7">
                    @php
                        $profileImg = null;
                        if (!empty($user->profile_photo_url)) {
                            $profileImg = $user->profile_photo_url;
                        } elseif (!empty($user->profile_photo)) {
                            $profileImg = asset('storage/' . $user->profile_photo);
                        }
                    @endphp
                    {{-- Avatar --}}
                    <div class="pf-avatar-wrap">
                        @if($profileImg)
                            <img src="{{ $profileImg }}" alt="{{ e($user->name) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null;this.src='/error.png'">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="pf-serif text-4xl font-semibold text-[#c7a64a]/60 uppercase tracking-wide">
                                    {{ substr($user->name, 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Identity info --}}
                    <div class="flex-1 min-w-0">
                        <div class="pf-role-label mb-1.5">Keeper of the Archive</div>
                        <div class="flex flex-wrap items-baseline gap-3">
                            <h2 class="pf-display-name">{{ $user->name }}</h2>
                            <span class="pf-username">@<span>{{ $user->username ?? $user->id }}</span></span>
                        </div>
                        @if($user->bio)
                            <p class="pf-bio mt-3">{{ $user->bio }}</p>
                        @else
                            <p class="pf-bio mt-3 italic text-[#737373]">Drawn to forgotten kingdoms, second chances, and stories that linger long after the last page.</p>
                        @endif
                        <div class="mt-4 pf-meta-tiny">
                            Member since <span class="val">{{ $user->created_at->format('M Y') }}</span>
                            <span class="mx-3" style="color:#3a3a3a;">·</span>
                            <span class="val">{{ $publicListsCount ?? 0 }} Public</span> Lists
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex flex-col items-end gap-2 shrink-0 sm:self-start">
                        @if($isOwner)
                            <a href="{{ route('settings') }}" class="pf-edit-btn">
                                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                Edit Profile
                            </a>
                            <div class="pf-profile-url">quoros.com/{{ $user->username ?? $user->id }}</div>
                        @elseif($canFollow)
                            <form action="{{ route('authors.follow', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="pf-btn-solid">
                                    {{ ($isFollowing ?? false) ? 'Following' : 'Follow Author' }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        {{-- ─── STATS ROW ───────────────────────────────────────────────── --}}
        <section class="mb-11">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-6 sm:gap-8 md:gap-10">
                @php
                    $statSaved = str_pad((string) max(0, (int) ($statsCounts['saved'] ?? 0)), 2, '0', STR_PAD_LEFT);
                    $statReading = str_pad((string) max(0, (int) ($statsCounts['reading'] ?? 0)), 2, '0', STR_PAD_LEFT);
                    $statCompleted = str_pad((string) max(0, (int) ($statsCounts['completed'] ?? 0)), 2, '0', STR_PAD_LEFT);
                    $statLists = str_pad((string) max(0, (int) ($totalListsCount ?? 0)), 2, '0', STR_PAD_LEFT);
                    $statHours = $readingHours ?? 1234;
                @endphp
                <div>
                    <div class="pf-stat-num">{{ $statSaved }}</div>
                    <div class="pf-stat-label">Saved Novels</div>
                    <div class="pf-stat-sub">Your personal library</div>
                </div>
                <div>
                    <div class="pf-stat-num">{{ $statReading }}</div>
                    <div class="pf-stat-label">Currently Reading</div>
                    <div class="pf-stat-sub">Across four realms</div>
                </div>
                <div>
                    <div class="pf-stat-num">{{ $statCompleted }}</div>
                    <div class="pf-stat-label">Completed Novels</div>
                    <div class="pf-stat-sub">Journeys remembered</div>
                </div>
                <div>
                    <div class="pf-stat-num">{{ $statLists }}</div>
                    <div class="pf-stat-label">Reading Lists</div>
                    <div class="pf-stat-sub">{{ $publicListsCount ?? 0 }} public · {{ $privateListsCount ?? 0 }} private</div>
                </div>
                <div>
                    <div class="pf-stat-num">{{ $statHours }}<span style="font-size:22px;">h</span></div>
                    <div class="pf-stat-label">Hour of Reading</div>
                    <div class="pf-stat-sub">{{ $publicListsCount ?? 0 }} public · {{ $privateListsCount ?? 0 }} private</div>
                </div>
            </div>
        </section>

        {{-- ─── CONTINUE YOUR JOURNEY ──────────────────────────────────── --}}
        <section class="mb-11">
            <div class="pf-section-head">
                <div class="pf-section-title">
                    <span class="pf-section-title-text">Continue Your Journey</span>
                    <div class="pf-section-rule"></div>
                </div>
                <a href="{{ route('history.index') }}" class="pf-view-all-link">
                    View Reading History
                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                </a>
            </div>

            @if($continueJourney->isNotEmpty())
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">
                    @foreach($continueJourney as $bm)
                        @php
                            $novel = $bm->novel;
                            $coverUrl = $novel->cover_image_url ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
                            $lastChapter = $bm->last_read_chapter;
                            $lastReadAt = $bm->last_read_at;
                            $readCount = (int) ($bm->read_chapters_count ?? 0);
                            $totalChapters = (int) ($bm->total_chapters ?? 0);
                            $progress = (float) ($bm->progress_percentage ?? 0);
                            $genre = $novel->genres?->first()?->name ?? 'General Fiction';
                            $author = $novel->author?->name ?? 'Unknown';
                        @endphp
                        <article class="pf-cj-card">
                            <div class="flex items-start gap-4">
                                @php
                                    $cjHref = $lastChapter
                                        ? route('chapters.show', [$novel->slug, $lastChapter->route_identifier])
                                        : route('novels.show', $novel->slug);
                                @endphp
                                <a href="{{ route('novels.show', $novel->slug) }}" class="pf-cj-cover" tabindex="-1" aria-hidden="true">
                                    @if($coverUrl)
                                        <img src="{{ $coverUrl }}" alt="{{ e($novel->title) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null;this.src='/error.png'">
                                    @else
                                        <img src="/error.png" alt="" class="w-full h-full object-cover" loading="lazy">
                                    @endif
                                </a>

                                <div class="flex-1 min-w-0">
                                    <div class="pf-cj-status mb-2">Reading</div>

                                    <a href="{{ route('novels.show', $novel->slug) }}" class="block">
                                        <h3 class="pf-cj-title line-clamp-1">{{ $novel->title }}</h3>
                                    </a>

                                    <div class="pf-cj-meta mt-1.5">
                                        By <span class="val">{{ $author }}</span>
                                        <span class="dot">·</span>
                                        <span class="val">{{ $genre }}</span>
                                    </div>

                                    <div style="height:1px;background:rgba(255,255,255,.08); margin: 11px 0;"></div>

                                    <div class="pf-cj-chapter">
                                        @if($lastChapter)
                                            <span class="num">Ch. {{ $lastChapter->chapter_number ?? ($lastChapter->order ?? '—') }}</span>
                                            <span style="color:#6b6b6b; margin:0 7px;">—</span>
                                            <span class="line-clamp-1">{{ \Illuminate\Support\Str::limit($lastChapter->title ?? '', 44) }}</span>
                                        @else
                                            <span class="num">Ch. 1</span>
                                            <span style="color:#6b6b6b; margin:0 7px;">—</span>
                                            <span>Ready when you are.</span>
                                        @endif
                                    </div>

                                    {{-- Progress --}}
                                    <div class="mt-4">
                                        <div class="flex items-center justify-between mb-1.5">
                                            <span class="pf-cj-progress-meta">
                                                <span class="val">{{ $readCount }}</span>
                                                <span style="color:#4a4a4a;"> / </span>
                                                <span>{{ $totalChapters }}</span> chapters read
                                            </span>
                                            <span class="pf-cj-progress-meta pct tabular-nums">{{ number_format($progress, 0) }}%</span>
                                        </div>
                                        <div class="pf-progress-bar">
                                            <div class="pf-progress-fill" style="width: {{ $progress }}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Bottom row --}}
                            <div class="mt-4 flex items-end justify-between gap-3">
                                <div class="pf-read-time">
                                    Read Today
                                    <span style="margin:0 6px;color:#3a3a3a;">·</span>
                                    <span class="val">{{ $lastReadAt ? $lastReadAt->format('H:i') : '—:—' }}</span>
                                </div>
                                <a href="{{ $cjHref }}" class="pf-btn-solid">
                                    Continue Reading
                                    <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-lg border border-white/10 bg-[#121212] px-5 py-16 text-center">
                    <p class="pf-serif text-xl uppercase text-[#eeeae1]">No journeys in motion yet.</p>
                    <p class="mt-2 text-xs text-neutral-500 max-w-sm mx-auto">Pick a novel from your shelf to begin.</p>
                </div>
            @endif
        </section>

        {{-- ─── ON YOUR SHELF ───────────────────────────────────────────── --}}
        <section class="mb-11">
            <div class="pf-section-head">
                <div class="pf-section-title">
                    <span class="pf-section-title-text">On Your Shelf</span>
                    <div class="pf-section-rule"></div>
                </div>
                <a href="{{ route('bookmarks.index') }}" class="pf-view-all-link">
                    View All Bookmarks
                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                </a>
            </div>

            @if($onShelf->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    @foreach($onShelf as $bm)
                        @php
                            $sNovel = $bm->novel;
                            $sCover = $sNovel->cover_image_url ?: ($sNovel->cover_image ? asset('storage/' . $sNovel->cover_image) : null);
                            $sStatus = $bm->reading_status ?? 'plan';
                            $sLast = $bm->last_read_chapter;
                            $sChapterNum = $sLast ? ($sLast->chapter_number ?? ($sLast->order ?? '—')) : '—';
                            $sStatusLabel = match($sStatus) {
                                'reading' => 'Reading',
                                'completed' => 'Completed',
                                default => 'Plan to Read',
                            };
                        @endphp
                        <a href="{{ route('novels.show', $sNovel->slug) }}" class="pf-shelf-card block">
                            <div class="pf-shelf-cover">
                                @if($sCover)
                                    <img src="{{ $sCover }}" alt="{{ e($sNovel->title) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null;this.src='/error.png'">
                                @else
                                    <img src="/error.png" alt="" class="w-full h-full object-cover" loading="lazy">
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="pf-shelf-title line-clamp-2">{{ $sNovel->title }}</div>
                                <div class="pf-shelf-meta mt-2">
                                    <span class="val">{{ $sStatusLabel }}</span>
                                    @if($sLast)
                                        <span class="dot">·</span>
                                        Chapter {{ $sChapterNum }}
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="rounded-lg border border-white/10 bg-[#121212] px-5 py-14 text-center">
                    <p class="pf-serif text-xl uppercase text-[#eeeae1]">Shelf is quiet here.</p>
                    <p class="mt-2 text-xs text-neutral-500 max-w-sm mx-auto">Your saved novels will appear here once bookmarked.</p>
                    <a href="{{ route('welcome') }}" class="mt-5 inline-flex items-center gap-1.5 rounded border border-[#c7a64a]/45 px-4 py-2.5 text-[9px] font-semibold uppercase tracking-[.14em] text-[#c7a64a] transition hover:bg-[#c7a64a]/10">
                        Explore Novels
                    </a>
                </div>
            @endif
        </section>

        {{-- ─── YOUR READING LISTS ──────────────────────────────────────── --}}
        <section class="mb-10">
            <div class="pf-section-head">
                <div class="pf-section-title">
                    <span class="pf-section-title-text">{{ $isOwner ? 'Your Reading Lists' : 'Public Reading Lists' }}</span>
                    <div class="pf-section-rule"></div>
                </div>
                @if($isOwner)
                    <div class="flex flex-wrap items-center justify-end gap-4">
                        <a href="{{ route('lists.create') }}" class="pf-btn-solid">
                            <svg viewBox="0 0 24 24" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                            Create List
                        </a>
                        <a href="{{ route('lists.index') }}" class="pf-view-all-link">
                            Manage All Lists
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                        </a>
                    </div>
                @endif
            </div>

            @if($userLists->isNotEmpty())
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-5">
                    @foreach($userLists as $list)
                        @php
                            $listNovels = $list->novels ?? collect();
                            $listThumbs = $listNovels->take(3);
                            $listIsPublic = (bool) ($list->is_public ?? false);
                            $listHref = $listIsPublic
                                ? route('lists.public', [$user->username ?? $user->id, $list])
                                : route('lists.show', $list);
                        @endphp
                        <article class="pf-list-card">
                            <div class="flex flex-col sm:flex-row gap-5">
                                {{-- Thumbnails --}}
                                <div class="pf-list-thumbs shrink-0">
                                    @for($i = 0; $i < 3; $i++)
                                        @php
                                            $tn = $listThumbs[$i] ?? null;
                                            $thumbUrl = $tn ? ($tn->cover_image_url ?: ($tn->cover_image ? asset('storage/' . $tn->cover_image) : null)) : null;
                                        @endphp
                                        <div class="pf-list-thumb">
                                            @if($thumbUrl)
                                                <img src="{{ $thumbUrl }}" alt="" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null;this.src='/error.png'">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <span class="text-[7px] text-[#5a5a5a] font-semibold tracking-wider uppercase">LIST</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endfor
                                </div>

                                <div class="min-w-0 flex-1 flex flex-col">
                                    <div class="mb-2.5">
                                        <span class="pf-list-badge pf-list-badge--{{ $listIsPublic ? 'public' : 'private' }}">
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
                                        <h3 class="pf-list-title">{{ $list->title }}</h3>
                                    </a>
                                    <div class="pf-list-count mt-2">{{ str_pad((string) max(0, (int) ($list->novels_count ?? 0)), 2, '0', STR_PAD_LEFT) }} Novels</div>

                                    @if(!empty($list->description))
                                        <p class="pf-list-desc mt-3 line-clamp-2">{{ $list->description }}</p>
                                    @else
                                        <p class="pf-list-desc mt-3 italic text-[#6f6f6f] line-clamp-2">
                                            {{ $listIsPublic
                                                ? 'Forgotten gods, impossible bargains, and kingdoms on the brink.'
                                                : 'The stories I want to begin when the current chapter ends.' }}
                                        </p>
                                    @endif

                                    <div class="mt-auto pt-4 flex items-center justify-between gap-3">
                                        <span class="pf-list-visibility">
                                            {{ $listIsPublic ? 'Anyone can view' : 'Only you can view' }}
                                        </span>
                                        <a href="{{ $listHref }}" class="pf-btn-outline">
                                            Open List
                                            <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="rounded-lg border border-white/10 bg-[#121212] px-5 py-14 text-center">
                    <p class="pf-serif text-xl uppercase text-[#eeeae1]">No reading lists yet.</p>
                    <p class="mt-2 text-xs text-neutral-500 max-w-sm mx-auto">Curate themed collections and organize your shelf.</p>
                    @if($isOwner)
                        <a href="{{ route('lists.create') }}" class="mt-5 pf-btn-solid">
                            Create Your First List
                        </a>
                    @endif
                </div>
            @endif
        </section>

        {{-- ─── PRIVACY BANNER ──────────────────────────────────────────── --}}
        <section class="mb-12">
            <div class="pf-privacy-banner">
                <div class="pf-privacy-text">
                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Your bookmarks and reading history stay private. Only lists marked <em style="font-style:normal;color:#d4d4d4;">public</em> can be viewed or shared with others.
                </div>
                @if($isOwner)
                    <a href="{{ route('settings') }}" class="pf-privacy-link">
                        Privacy Settings
                    </a>
                @endif
            </div>
        </section>

    </div>
</main>
@endsection
