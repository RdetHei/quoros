@extends('layouts.app')

@section('content')

{{-- ============================================================
     QUOROS TRANSLATION — Landing Page
     Tema: Dark Navy + Gold + Indigo
     ============================================================ --}}

<style>
    /* ── Base ─────────────────────────────────────────── */
    .qr-page {
        background: #080b17;
        color: #f1f5f9;
        font-family: 'Inter', sans-serif;
    }

    /* ── Utilities ────────────────────────────────────── */
    .qr-text-gradient {
        background: linear-gradient(135deg, #fff 0%, #94a3b8 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .qr-eyebrow {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .25em;
        text-transform: uppercase;
        color: #818cf8;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .qr-eyebrow::before {
        content: '';
        width: 12px;
        height: 1px;
        background: currentColor;
    }
    .qr-sec-title {
        font-size: 28px;
        font-weight: 800;
        color: #f1f5f9;
        letter-spacing: -.7px;
        line-height: 1.2;
    }
    .qr-sec-sub {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        margin-top: 4px;
    }
    .qr-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 5px 14px;
        background: rgba(99,102,241,.12);
        border: 1px solid rgba(99,102,241,.28);
        border-radius: 999px;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .18em;
        text-transform: uppercase;
        color: #a5b4fc;
        margin-bottom: 1.25rem;
    }
    .qr-badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #818cf8;
    }
    .qr-btn-primary {
        padding: 11px 24px;
        background: #4f46e5;
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: background .2s, transform .15s;
    }
    .qr-btn-primary:hover { background: #6366f1; transform: translateY(-1px); color: #fff; }
    .qr-btn-ghost {
        padding: 11px 24px;
        background: rgba(255,255,255,.06);
        color: #e2e8f0;
        font-weight: 700;
        font-size: 13px;
        border-radius: 12px;
        border: 1px solid rgba(255,255,255,.1);
        text-decoration: none;
        display: inline-block;
        transition: background .2s, transform .15s;
    }
    .qr-btn-ghost:hover { background: rgba(255,255,255,.1); transform: translateY(-1px); color: #fff; }
    .qr-btn-gold {
        padding: 11px 24px;
        background: #c9a84c;
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        border-radius: 12px;
        border: none;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        transition: background .2s, transform .15s;
    }
    .qr-btn-gold:hover { background: #d4b45a; transform: translateY(-1px); color: #fff; }

    /* ── HERO ─────────────────────────────────────────── */
    .qr-hero {
        position: relative;
        height: 700px;
        overflow: hidden;
        display: flex;
        align-items: center;
        background-color: #020617;
    }
    .qr-hero-bg {
        position: absolute;
        inset: 0;
        z-index: 0;
    }
    .qr-hero-bg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.4;
        filter: brightness(0.6) contrast(1.1) saturate(1.2);
        animation: slowZoom 30s infinite alternate;
    }
    @keyframes slowZoom {
        from { transform: scale(1); }
        to { transform: scale(1.15); }
    }
    .qr-hero-overlay {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 20% 50%, rgba(2, 6, 23, 0.8) 0%, rgba(2, 6, 23, 0.4) 50%, rgba(2, 6, 23, 0.95) 100%);
        z-index: 1;
    }
    .qr-hero-content {
        position: relative;
        z-index: 10;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 4rem;
        width: 100%;
    }
    .qr-hero-inner {
        max-width: 700px;
    }
    .qr-hero h1 {
        font-size: 84px;
        font-weight: 950;
        line-height: 0.95;
        letter-spacing: -4px;
        color: #ffffff;
        margin-bottom: 2rem;
    }
    .qr-hero h1 span.gold {
        color: #fbbf24;
        background: linear-gradient(to right, #fde68a, #fbbf24);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .qr-hero-p {
        font-size: 20px;
        line-height: 1.6;
        color: #94a3b8;
        margin-bottom: 2.5rem;
        font-weight: 500;
    }

    /* ── CAROUSEL OVERLAY ────────────────────────────── */
    .qr-hero-carousel-container {
        position: absolute;
        right: 5rem;
        top: 50%;
        transform: translateY(-50%);
        width: 440px;
        z-index: 20;
    }
    .qr-carousel-card {
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(30px) saturate(150%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 40px;
        padding: 2.5rem;
        box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.6), inset 0 0 20px rgba(255,255,255,0.02);
        transition: all 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .qr-carousel-card:hover {
        border-color: rgba(99, 102, 241, 0.3);
        background: rgba(15, 23, 42, 0.5);
    }
    .qr-carousel-img {
        width: 100%;
        aspect-ratio: 1/1.4;
        border-radius: 24px;
        object-fit: cover;
        margin-bottom: 2rem;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
    }
    .qr-carousel-title {
        font-size: 26px;
        font-weight: 950;
        color: #fff;
        margin-bottom: 0.75rem;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }
    .qr-carousel-desc {
        font-size: 15px;
        color: #94a3b8;
        line-height: 1.6;
        margin-bottom: 2rem;
        height: 4.8rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }

    /* ── BUTTONS ───────────────────────────────────────── */
    .qr-btn-primary {
        background: #4f46e5;
        color: #fff;
        font-weight: 900;
        border-radius: 18px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 14px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .qr-btn-primary:hover {
        background: #6366f1;
        transform: translateY(-3px);
        box-shadow: 0 20px 40px -10px rgba(79, 70, 229, 0.5);
    }
    .qr-btn-ghost {
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        font-weight: 900;
        border-radius: 18px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 14px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .qr-btn-ghost:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.2);
        transform: translateY(-3px);
    }

    /* ── NOVEL CARDS ───────────────────────────────────── */
    .qr-novel-card {
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .qr-novel-card:hover {
        transform: translateY(-12px);
    }
    .qr-novel-card .qr-img-wrapper {
        position: relative;
        aspect-ratio: 1/1.45;
        border-radius: 28px;
        overflow: hidden;
        background: #0f172a;
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.5);
        border: 1px solid rgba(255,255,255,0.05);
        transition: all 0.5s ease;
    }
    .qr-novel-card:hover .qr-img-wrapper {
        border-color: rgba(99, 102, 241, 0.4);
        box-shadow: 0 30px 60px -12px rgba(99, 102, 241, 0.2);
    }
    .qr-novel-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .qr-novel-card:hover img {
        transform: scale(1.1);
    }
    .qr-novel-card .qr-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(2, 6, 23, 0.9) 0%, rgba(2, 6, 23, 0.4) 40%, transparent 100%);
        opacity: 0.8;
        transition: opacity 0.3s ease;
    }
    .qr-novel-card:hover .qr-overlay { opacity: 1; }

    @media (max-width: 1024px) {
        .qr-hero { height: auto; padding: 6rem 0; }
        .qr-hero h1 { font-size: 48px; }
        .qr-stats-grid { grid-template-columns: repeat(2, 1fr); gap: 1.5rem; padding: 2rem; }
    }
    @media (max-width: 640px) {
        .qr-hero h1 { font-size: 36px; letter-spacing: -1.5px; }
        .qr-hero-p { font-size: 16px; }
        .qr-stats-grid { grid-template-columns: 1fr; margin-top: -40px; }
        .qr-stat-val { font-size: 32px; }
    }
    .qr-stats {
        position: relative;
        z-index: 10;
        margin-top: -100px;
        padding-bottom: 6rem;
    }
    .qr-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0;
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(20px);
        padding: 0;
        border-radius: 40px;
        border: 1px solid rgba(255,255,255,0.08);
        box-shadow: 0 40px 80px -20px rgba(0, 0, 0, 0.6);
        overflow: hidden;
    }
    .qr-stat-item {
        text-align: center;
        padding: 3.5rem 2rem;
        border-right: 1px solid rgba(255,255,255,0.08);
        transition: all 0.3s ease;
    }
    .qr-stat-item:last-child { border-right: none; }
    .qr-stat-item:hover { background: rgba(255,255,255,0.03); }
    .qr-stat-val {
        font-size: 48px;
        font-weight: 950;
        color: #fff;
        letter-spacing: -2px;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    .qr-stat-lbl {
        font-size: 11px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 2.5px;
    }

    /* ── SECTION HEADERS ───────────────────────────────── */
    .qr-section-header {
        margin-bottom: 4rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
    .qr-section-title {
        font-size: 36px;
        font-weight: 900;
        color: #fff;
        letter-spacing: -1.5px;
    }
    .qr-section-subtitle {
        color: #64748b;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 0.75rem;
        display: block;
    }

    /* ── APA ITU QUOROS ───────────────────────────────── */
    .qr-about {
        padding: 6rem 1rem;
        background: #06090f;
    }
    .qr-about-inner { max-width: 1000px; margin: 0 auto; }
    .qr-about-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 4rem;
        align-items: center;
        margin-top: 3rem;
    }
    .qr-about-text p {
        font-size: 14px;
        line-height: 1.85;
        color: #94a3b8;
        margin-bottom: 1rem;
    }
    .qr-about-text p:last-of-type { margin-bottom: 0; }
    .qr-about-quote {
        background: rgba(99,102,241,.08);
        border-left: 3px solid #4f46e5;
        border-radius: 0 10px 10px 0;
        padding: 1rem 1.25rem;
        margin-top: 1.25rem;
    }
    .qr-about-quote p {
        font-size: 13px;
        line-height: 1.7;
        color: #a5b4fc;
        font-style: italic;
        margin: 0 !important;
    }
    .qr-about-cards { display: flex; flex-direction: column; gap: 12px; }
    .qr-acard {
        background: #0f172a;
        border: 1px solid rgba(255,255,255,.08);
        border-radius: 18px;
        padding: 1.5rem 1.75rem;
        display: flex;
        align-items: flex-start;
        gap: 18px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px -5px rgba(0,0,0,0.3);
    }
    .qr-acard:hover {
        border-color: rgba(99,102,241,.3);
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.5);
        background: #1e293b;
    }
    .qr-acard-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        font-size: 20px;
        background: rgba(99,102,241,0.1);
        color: #818cf8;
    }
    .qr-acard h5 {
        font-size: 15px;
        font-weight: 800;
        color: #f8fafc;
        margin-bottom: 6px;
    }
    .qr-acard p {
        font-size: 13px;
        color: #94a3b8;
        line-height: 1.6;
        margin: 0;
    }

    /* ── UPDATE TERBARU ───────────────────────────────── */
    .qr-updates {
        padding: 8rem 1rem;
        background: #080b17;
    }
    .qr-updates-inner { max-width: 1200px; margin: 0 auto; }
    .qr-hdr-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 3.5rem;
    }
    .qr-see-all {
        font-size: 13px;
        font-weight: 800;
        color: #818cf8;
        text-decoration: none;
        padding: 8px 16px;
        background: rgba(129,140,248,0.1);
        border-radius: 10px;
        transition: all .2s;
    }
    .qr-see-all:hover { 
        background: rgba(129,140,248,0.2);
        transform: translateX(4px);
    }
    .qr-novel-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 2.5rem;
    }
    .qr-ncard { 
        cursor: pointer; 
        text-decoration: none;
    }
    .qr-ncover {
        aspect-ratio: 3/4;
        border-radius: 18px;
        overflow: hidden;
        position: relative;
        margin-bottom: 1rem;
        border: 1px solid rgba(255,255,255,.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 30px -5px rgba(0,0,0,0.4);
    }
    .qr-ncard:hover .qr-ncover {
        transform: translateY(-8px);
        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.6), 0 0 20px rgba(99,102,241,0.1);
        border-color: rgba(99,102,241,0.3);
    }
    .qr-ncover img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
    }
    .qr-ncard:hover .qr-ncover img { transform: scale(1.1); }
    .qr-ncover-gradient {
        position: absolute; inset: 0;
        background: linear-gradient(to top, rgba(2,6,23,0.95) 0%, transparent 60%);
    }
    .qr-nbadge {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 20px 14px 14px;
    }
    .qr-ntag {
        font-size: 8px;
        font-weight: 800;
        letter-spacing: .15em;
        text-transform: uppercase;
        color: #818cf8;
        margin-bottom: 2px;
    }
    .qr-nchap {
        font-size: 11px;
        font-weight: 700;
        color: #f8fafc;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .qr-ntitle {
        font-size: 16px;
        font-weight: 800;
        color: #f8fafc;
        margin-bottom: 6px;
        line-height: 1.4;
        transition: color 0.2s;
    }
    .qr-ncard:hover .qr-ntitle { color: #818cf8; }
    .qr-nmeta {
        font-size: 11px;
        color: #64748b;
        font-weight: 600;
        display: flex;
        gap: 6px;
        align-items: center;
    }
    .qr-ndot { width: 3px; height: 3px; border-radius: 50%; background: #334155; }

    /* ── CTA BANNER ───────────────────────────────────── */
    .qr-cta-sec {
        padding: 6rem 1rem;
        background: #06090f;
    }
    .qr-cta-box {
        max-width: 1000px;
        margin: 0 auto;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        border: 1px solid rgba(99,102,241,.3);
        border-radius: 28px;
        padding: 4rem 5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 3rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 30px 60px -15px rgba(0,0,0,0.5);
    }
    .qr-cta-box::before {
        content: '';
        position: absolute; top: -70px; right: -70px;
        width: 260px; height: 260px;
        background: radial-gradient(circle, rgba(99,102,241,.14) 0%, transparent 70%);
        pointer-events: none;
    }
    .qr-cta-box::after {
        content: '';
        position: absolute; bottom: -50px; left: -40px;
        width: 200px; height: 200px;
        background: radial-gradient(circle, rgba(180,144,60,.08) 0%, transparent 70%);
        pointer-events: none;
    }
    .qr-cta-left { position: relative; z-index: 1; }
    .qr-cta-ey {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .2em;
        text-transform: uppercase;
        color: #818cf8;
        margin-bottom: 6px;
    }
    .qr-cta-t {
        font-size: 42px;
        font-weight: 900;
        color: #ffffff;
        line-height: 1.1;
        letter-spacing: -1.5px;
        margin-bottom: 1rem;
    }
    .qr-cta-d { 
        font-size: 15px; 
        color: #cbd5e1; 
        line-height: 1.7;
        max-width: 460px;
    }
    .qr-cta-btns {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex-shrink: 0;
        position: relative;
        z-index: 1;
    }

    /* ── Responsive ───────────────────────────────────── */
    @media (max-width: 1024px) {
        .qr-hero h1 { font-size: 42px; }
        .qr-about-grid { grid-template-columns: 1fr; gap: 3rem; }
    }
    @media (max-width: 768px) {
        .qr-hero { padding: 4rem 1rem; }
        .qr-hero h1 { font-size: 36px; }
        .qr-hero-p { font-size: 14px; }
        .qr-novel-grid { grid-template-columns: repeat(2, 1fr); }
        .qr-cta-box { flex-direction: column; padding: 3rem 2rem; text-align: center; }
        .qr-cta-btns { flex-direction: row; justify-content: center; }
    }
    @media (max-width: 480px) {
        .qr-stats-row { flex-direction: column; gap: 1.5rem; }
        .qr-stat { border-bottom: 1px solid rgba(255,255,255,.05); padding-bottom: 1rem; }
        .qr-stat:last-child { border-bottom: none; }
        .qr-cta-btns { flex-direction: column; width: 100%; }
        .qr-btn-primary, .qr-btn-ghost, .qr-btn-gold { width: 100%; text-align: center; }
    }

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    
</style>

<div class="qr-page">

    {{-- ===================== HERO SECTION ===================== --}}
    @php
        $featuredCarouselData = $featuredNovels->map(fn ($n) => [
            'id' => $n->id,
            'slug' => $n->slug,
            'url' => route('novels.show', $n->slug),
            'title' => $n->title,
            'description' => $n->description,
            'cover' => $n->cover_image_url ?: ($n->cover_image ? asset('storage/' . $n->cover_image) : 'https://images.unsplash.com/photo-1578632738980-422cc36e2ec9?auto=format&fit=crop&w=2000&q=80'),
            'is_bookmarked' => (bool) ($n->is_bookmarked ?? false),
        ])->values();
    @endphp

    <section class="qr-hero" x-data="heroCarousel(@js($featuredCarouselData))">
        {{-- Fixed Banner Background from Storage --}}
        <div class="qr-hero-bg">
            <img src="{{ asset('storage/banners/landingBanner.png') }}" alt="Landing Banner" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1578632738980-422cc36e2ec9?auto=format&fit=crop&w=2000&q=80';">
        </div>
        <div class="qr-hero-overlay"></div>

        <div class="qr-hero-content">
            <div class="qr-hero-inner" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 -translate-x-10"
                 x-transition:enter-end="opacity-100 translate-x-0">
                <div class="qr-badge">
                    <div class="qr-badge-dot"></div>
                    Premium Novel Translation
                </div>
                <h1>
                    Novel Platform<br>
                    <span class="gold">Quoros Translation Team</span>
                </h1>
                <p class="qr-hero-p">
                    Experience seamless reading with high-quality translations. 
                    From epic cultivation journeys to modern system-based adventures, 
                    Quoros brings the best webnovels right to your fingertips.
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('home') }}" class="qr-btn-primary px-10 py-4 text-base flex items-center gap-2 shadow-2xl shadow-indigo-600/40">
                        Start Reading
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </a>
                    @guest
                    <a href="{{ route('register') }}" class="qr-btn-ghost px-10 py-4 text-base">
                        Join Community
                    </a>
                    @endguest
                </div>
            </div>
        </div>

        {{-- Carousel Overlay Card --}}
        <div class="qr-hero-carousel-container hidden xl:block"
             @mouseenter="paused = true"
             @mouseleave="paused = false">
            <div class="qr-carousel-card">
                <div class="relative overflow-hidden mb-6 group">
                    <template x-for="(novel, index) in novels" :key="novel.id">
                        <img x-show="activeSlide === index"
                             :src="novel.cover" 
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 scale-110"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="qr-carousel-img"
                             alt="Featured Novel">
                    </template>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>

                <div class="min-h-[140px]">
                    <h3 class="qr-carousel-title" x-text="current.title"></h3>
                    <p class="qr-carousel-desc" x-text="current.description"></p>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div class="flex gap-2">
                        <template x-for="(novel, index) in novels" :key="novel.id">
                            <button @click="activeSlide = index; resetTimer()" 
                                    class="h-1.5 rounded-full transition-all duration-300"
                                    :class="activeSlide === index ? 'w-8 bg-indigo-500' : 'w-1.5 bg-white/20 hover:bg-white/40'"></button>
                        </template>
                    </div>
                    <a :href="current.url" class="text-xs font-black text-indigo-400 hover:text-indigo-300 flex items-center gap-1 transition-colors uppercase tracking-widest">
                        Details
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== STATS SECTION ===================== --}}
    <section class="qr-stats">
        <div class="max-w-7xl mx-auto px-4">
            <div class="qr-stats-grid">
                <div class="qr-stat-item">
                    <span class="qr-stat-val">500+</span>
                    <span class="qr-stat-lbl">Premium Novels</span>
                </div>
                <div class="qr-stat-item">
                    <span class="qr-stat-val">2.5M</span>
                    <span class="qr-stat-lbl">Active Readers</span>
                </div>
                <div class="qr-stat-item">
                    <span class="qr-stat-val">99%</span>
                    <span class="qr-stat-lbl">Accuracy Rate</span>
                </div>
                <div class="qr-stat-item">
                    <span class="qr-stat-val">24/7</span>
                    <span class="qr-stat-lbl">Daily Updates</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== TRENDING SECTION ===================== --}}
    @if($featuredNovels->count() > 0)
    <section class="py-24 relative">
        <div class="max-w-7xl mx-auto px-4">
            <div class="qr-section-header items-center">
                <div>
                    <span class="qr-section-subtitle">What's Hot</span>
                    <h2 class="qr-section-title">Trending Now</h2>
                </div>
                <div class="flex gap-4">
                    <button class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-indigo-500 hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button class="w-12 h-12 rounded-full border border-slate-800 flex items-center justify-center text-slate-400 hover:border-indigo-500 hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>

            <div class="flex gap-8 overflow-x-auto pb-8 no-scrollbar">
                @foreach($featuredNovels as $novel)
                    <div class="flex-shrink-0 w-[400px] group">
                        <a href="{{ route('novels.show', $novel->slug) }}" class="flex gap-6 p-6 bg-slate-900/50 border border-slate-800 rounded-[2.5rem] group-hover:border-indigo-500/30 transition-all duration-500">
                            <div class="w-32 aspect-[3/4] rounded-2xl overflow-hidden flex-shrink-0 shadow-2xl">
                                @if($novel->cover_image_url)
                                    <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $novel->title }}">
                                @elseif($novel->cover_image)
                                    <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $novel->title }}">
                                @endif
                            </div>
                            <div class="flex flex-col justify-center overflow-hidden">
                                <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-2">{{ $novel->genres->first()?->name ?? 'Fantasy' }}</span>
                                <h3 class="text-xl font-black text-white mb-2 line-clamp-1 group-hover:text-indigo-400 transition-colors">{{ $novel->title }}</h3>
                                <p class="text-sm text-slate-500 line-clamp-2 mb-4 leading-relaxed">{{ $novel->description }}</p>
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        <span class="text-xs font-black text-slate-300">4.9</span>
                                    </div>
                                    <span class="w-1 h-1 bg-slate-700 rounded-full"></span>
                                    <span class="text-xs font-bold text-slate-500">{{ $novel->chapters->count() }} Chapters</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ===================== UPDATES SECTION ===================== --}}
    <section class="py-24">
        <div class="max-w-7xl mx-auto px-4">
            <div class="qr-section-header">
                <div>
                    <span class="qr-section-subtitle">Stay Up to Date</span>
                    <h2 class="qr-section-title">Latest Updates</h2>
                </div>
                <a href="{{ route('home') }}" class="group flex items-center gap-2 text-sm font-black text-slate-400 hover:text-white transition-colors">
                    View All Releases
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
                @foreach ($recentlyUpdated as $novel)
                    <div class="qr-novel-card group">
                        <a href="{{ route('novels.show', $novel->slug) }}" class="block mb-6">
                            <div class="qr-img-wrapper">
                                @if($novel->cover_image_url)
                                    <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}">
                                @elseif($novel->cover_image)
                                    <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-slate-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-800" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                                <div class="qr-overlay"></div>
                                
                                {{-- Chapter Badge --}}
                                <div class="absolute bottom-6 left-6 right-6 z-10">
                                    <span class="inline-flex items-center px-4 py-2 bg-indigo-600/90 backdrop-blur-md text-[10px] font-black text-white uppercase tracking-[0.2em] rounded-xl shadow-lg">
                                        {{ $novel->chapters->first()?->title ?? 'New Release' }}
                                    </span>
                                </div>
                            </div>
                        </a>
                        
                        <div class="px-2">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.25em]">
                                    {{ $novel->genres->first()?->name ?? 'Fantasy' }}
                                </span>
                                <span class="w-1.5 h-1.5 bg-slate-800 rounded-full"></span>
                                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">{{ $novel->region }}</span>
                            </div>
                            <h3 class="text-xl font-black text-white group-hover:text-indigo-400 transition-colors line-clamp-1 mb-2 tracking-tight">{{ $novel->title }}</h3>
                            <div class="flex items-center justify-between">
                                <p class="text-[11px] font-bold text-slate-500 flex items-center gap-1.5 uppercase tracking-wider">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-indigo-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
                                    {{ $novel->updated_at->diffForHumans() }}
                                </p>
                                <div class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-500" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    <span class="text-[11px] font-black text-slate-300">4.9</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== FEATURES SECTION ===================== --}}
    <section class="py-32 bg-[#020617] relative overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-indigo-600/5 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <span class="qr-eyebrow justify-center mb-4">Why Quoros?</span>
                <h2 class="text-5xl font-black text-white mb-6 tracking-tight">The Ultimate Reading Experience</h2>
                <p class="text-slate-500 font-medium text-lg leading-relaxed">We combine cutting-edge technology with passionate translation teams to bring you the stories you love, exactly how they were meant to be read.</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="group p-12 bg-slate-900/40 backdrop-blur-md border border-slate-800 hover:border-indigo-500/40 rounded-[3rem] transition-all duration-500">
                    <div class="w-20 h-20 bg-indigo-600/10 text-indigo-500 rounded-3xl flex items-center justify-center mb-10 group-hover:scale-110 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-xl shadow-indigo-600/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4 tracking-tight">Immersive Fidelity</h3>
                    <p class="text-slate-500 font-medium leading-relaxed text-base">Expert translations that preserve cultural nuances, metaphors, and the unique voice of every author.</p>
                </div>

                <div class="group p-12 bg-slate-900/40 backdrop-blur-md border border-slate-800 hover:border-emerald-500/40 rounded-[3rem] transition-all duration-500">
                    <div class="w-20 h-20 bg-emerald-600/10 text-emerald-500 rounded-3xl flex items-center justify-center mb-10 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500 shadow-xl shadow-emerald-600/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4 tracking-tight">Lightning Updates</h3>
                    <p class="text-slate-500 font-medium leading-relaxed text-base">Direct sync with original publishers ensures you get new chapters within hours of their official release.</p>
                </div>

                <div class="group p-12 bg-slate-900/40 backdrop-blur-md border border-slate-800 hover:border-amber-500/40 rounded-[3rem] transition-all duration-500">
                    <div class="w-20 h-20 bg-amber-600/10 text-amber-500 rounded-3xl flex items-center justify-center mb-10 group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-500 shadow-xl shadow-amber-600/5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-4 tracking-tight">Active Community</h3>
                    <p class="text-slate-500 font-medium leading-relaxed text-base">Connect with millions of readers, share theories, and participate in exclusive fan events and discussions.</p>
                </div>
            </div>
        </div>
    </section>


</div>
@endsection

@push('scripts')
<script>
    function heroCarousel(initialNovels) {
        return {
            activeSlide: 0,
            slideCount: initialNovels.length,
            novels: initialNovels,
            paused: false,
            timer: null,
            bookmarkLoading: false,
            
            get current() { 
                return this.novels[this.activeSlide] || {}; 
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
                if (this.slideCount > 1) this.startTimer(); 
            },
            
            startTimer() { 
                this.timer = setInterval(() => { 
                    if (!this.paused) this.next(); 
                }, 6000); 
            },
            
            resetTimer() { 
                clearInterval(this.timer); 
                this.startTimer(); 
            },
            
            toggleBookmark() {
                @auth
                    if (this.bookmarkLoading) return;
                    this.bookmarkLoading = true;
                    const id = this.current.id;
                    
                    fetch(`/novels/${id}/bookmark`, {
                        method: 'POST',
                        headers: { 
                            'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                            'Accept': 'application/json', 
                            'X-Requested-With': 'XMLHttpRequest' 
                        }
                    })
                    .then(r => r.json())
                    .then(d => {
                        const i = this.activeSlide;
                        if (this.novels[i]) {
                            // Reaktif update untuk Alpine
                            this.novels[i].is_bookmarked = d.status === 'added';
                        }
                        this.bookmarkLoading = false;
                    })
                    .catch(() => { 
                        this.bookmarkLoading = false; 
                    });
                @else
                    window.location.href = '{{ route('login') }}';
                @endauth
            }
        }
    }
</script>
@endpush