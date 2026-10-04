@extends('layouts.app', ['title' => $notification->title() . ' — Notification · Quoros'])

@push('styles')
<style>
    .nv-serif { font-family: 'Cormorant Garamond', Georgia, serif; }
    .nv-page {
        --nv-bg: #0a0a0a;
        --nv-surface: #121212;
        --nv-card: #131313;
        --nv-border: rgba(255, 255, 255, 0.10);
        --nv-gold: #c7a64a;
        --nv-gold-soft: #e8d39a;
        --nv-gold-pale: #ead79f;
        --nv-ink: #eeeae1;
        --nv-title: #f2efe8;
        --nv-muted: #a3a3a3;
        --nv-subtle: #737373;
        background: var(--nv-bg);
    }
    body:has(.nv-page) { background-color: var(--nv-bg) !important; }

    .nv-kicker {
        font-size: 9px;
        letter-spacing: 0.22em;
        font-weight: 650;
        text-transform: uppercase;
        color: var(--nv-gold);
    }
    .nv-kicker .sep { color: var(--nv-subtle); margin: 0 8px; }

    .nv-divider-grad { height: 1px; background: linear-gradient(to right, var(--nv-gold), transparent); }

    .nv-hero-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 38px;
        line-height: 1.08;
        letter-spacing: 0.01em;
        color: var(--nv-title);
    }
    @media (max-width: 639px) { .nv-hero-title { font-size: 28px; } }

    .nv-back-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 650;
        text-transform: uppercase;
        color: var(--nv-muted);
        transition: color .15s ease;
    }
    .nv-back-link:hover { color: var(--nv-gold-soft); }
    .nv-back-link:hover svg { color: var(--nv-gold); }

    .nv-type-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 3px 10px;
        font-size: 7.5px;
        letter-spacing: 0.2em;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 2px;
        background: rgba(199, 166, 74, 0.14);
        color: var(--nv-gold);
        border: 1px solid rgba(199, 166, 74, 0.4);
    }
    .nv-type-pill .dot { width: 5px; height: 5px; border-radius: 999px; background: currentColor; }

    .nv-date-meta {
        font-size: 9px;
        letter-spacing: 0.14em;
        font-weight: 550;
        text-transform: uppercase;
        color: var(--nv-subtle);
    }
    .nv-date-meta .val { color: var(--nv-muted); }

    /* Card */
    .nv-card {
        background: var(--nv-card);
        border: 1px solid var(--nv-border);
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 24px 60px -28px rgba(0, 0, 0, 0.9);
    }

    .nv-card-header {
        padding: 20px 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        background: #111111;
    }
    @media (max-width: 639px) { .nv-card-header { padding: 16px 18px; } }

    .nv-card-body {
        padding: 28px 26px 30px;
    }
    @media (max-width: 639px) { .nv-card-body { padding: 20px 18px 22px; } }

    .nv-notif-body {
        padding: 18px 18px;
        border-radius: 4px;
        border: 1px solid rgba(255, 255, 255, 0.06);
        background: #0f0f0f;
        color: #e2dcd1;
        font-size: 13.5px;
        line-height: 1.82;
        letter-spacing: 0.002em;
        white-space: pre-line;
    }
    @media (max-width: 639px) { .nv-notif-body { padding: 14px 14px; font-size: 12.5px; } }

    /* Link box */
    .nv-link-box {
        margin-top: 18px;
        padding: 16px 18px;
        border-radius: 4px;
        background: rgba(199, 166, 74, 0.06);
        border: 1px solid rgba(199, 166, 74, 0.28);
    }
    .nv-link-label {
        font-size: 8px;
        letter-spacing: 0.2em;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--nv-gold);
    }
    .nv-link-url {
        display: block;
        margin-top: 8px;
        font-size: 11.5px;
        line-height: 1.6;
        color: var(--nv-gold-soft);
        word-break: break-all;
        transition: color .15s ease;
    }
    .nv-link-url:hover { color: var(--nv-gold-pale); }

    .nv-divider-simple {
        height: 1px;
        background: rgba(255, 255, 255, 0.06);
        margin: 26px 0;
    }

    .nv-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .nv-btn-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 16px;
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 650;
        text-transform: uppercase;
        border-radius: 2px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: var(--nv-muted);
        background: transparent;
        transition: border-color .15s ease, color .15s ease, background-color .15s ease;
    }
    .nv-btn-outline:hover {
        border-color: rgba(199, 166, 74, 0.55);
        color: var(--nv-gold-soft);
        background: rgba(199, 166, 74, 0.06);
    }

    .nv-btn-solid {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 16px;
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 2px;
        background: var(--nv-gold);
        color: #101010;
        transition: filter .15s ease, transform .12s ease;
        white-space: nowrap;
    }
    .nv-btn-solid:hover { filter: brightness(1.08); }
    .nv-btn-solid:active { transform: translateY(1px); }

    /* Footer */
    .nv-footer { border-top: 1px solid rgba(255, 255, 255, 0.06); background: #080808; margin-top: 72px; }
    .nv-foot-brand { font-size: 15px; letter-spacing: 0.28em; font-weight: 600; color: var(--nv-title); }
    .nv-foot-tagline { font-size: 10.5px; color: var(--nv-subtle); line-height: 1.6; margin-top: 8px; max-width: 320px; }
    .nv-foot-nav { display: flex; flex-wrap: wrap; gap: 22px 28px; justify-content: flex-end; }
    .nv-foot-nav a {
        font-size: 9px; letter-spacing: 0.16em; font-weight: 600;
        text-transform: uppercase; color: var(--nv-muted); transition: color .15s ease;
    }
    .nv-foot-nav a:hover { color: var(--nv-gold); }
    .nv-foot-bottom { border-top: 1px solid rgba(255, 255, 255, 0.06); font-size: 8.5px; letter-spacing: 0.08em; color: var(--nv-subtle); }
    .nv-foot-bottom .links a { color: var(--nv-muted); transition: color .15s ease; }
    .nv-foot-bottom .links a:hover { color: var(--nv-gold); }
    .nv-foot-bottom .links .sep { margin: 0 7px; color: #2e2e2e; }
</style>
@endpush

@section('content')
<main class="nv-page min-h-screen w-full overflow-x-hidden pt-24 sm:pt-28 pb-12">
    <div class="mx-auto w-full max-w-[860px] px-5 sm:px-7 lg:px-10">

        {{-- Breadcrumb --}}
        <a href="{{ route('notifications.index') }}" class="nv-back-link mb-6 inline-flex">
            <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
            Back to Notifications
        </a>

        {{-- Kicker line --}}
        <p class="mb-3 nv-kicker">
            Your Chronicle <span class="sep">/</span> Dispatch
        </p>

        {{-- Main card --}}
        <section class="nv-card">
            <header class="nv-card-header">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <span class="nv-type-pill shrink-0 self-start">
                        <span class="dot"></span>
                        {{ $notification->type->value ?? 'Dispatch' }}
                    </span>
                    <div class="nv-date-meta sm:text-right">
                        <span class="val">{{ $notification->created_at->translatedFormat('l, j F Y') }}</span>
                        <span style="color:#2f2f2f;margin:0 7px;">·</span>
                        {{ $notification->created_at->format('H:i') }}
                        <span style="color:#2f2f2f;margin:0 7px;">·</span>
                        UTC+7
                    </div>
                </div>
            </header>

            <div class="nv-card-body">
                <h1 class="nv-hero-title leading-tight">{{ $notification->title() }}</h1>

                <div class="nv-divider-simple"></div>

                <div class="nv-notif-body">{{ $notification->body() }}</div>

                @if($notification->url())
                    <div class="nv-link-box">
                        <div class="nv-link-label">Related Link</div>
                        <a href="{{ $notification->url() }}" target="_blank" rel="noopener noreferrer" class="nv-link-url">
                            {{ $notification->url() }}
                        </a>
                    </div>
                @endif

                <div class="nv-actions @if($notification->url()) mt-7 @else mt-7 @endif">
                    <a href="{{ route('notifications.index') }}" class="nv-btn-outline">
                        <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/></svg>
                        All Notifications
                    </a>
                    @if($notification->url())
                        <a href="{{ $notification->url() }}" target="_blank" rel="noopener noreferrer" class="nv-btn-solid">
                            Open Source
                            <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
                        </a>
                    @endif
                </div>
            </div>
        </section>

    </div>
</main>
@endsection
