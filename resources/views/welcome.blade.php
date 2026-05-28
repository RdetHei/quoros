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
    .qr-eyebrow {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .2em;
        text-transform: uppercase;
        color: #818cf8;
        margin-bottom: 6px;
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
        padding: 9rem 1rem 8rem;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
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
        opacity: 0.6;
        filter: brightness(0.8) saturate(1.1);
        transform: scale(1);
    }
    .qr-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(2, 6, 23, 0.3) 0%, rgba(2, 6, 23, 0.6) 50%, #080b17 100%);
        z-index: 1;
    }
    .qr-hero-grid {
        position: relative;
        z-index: 2;
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .qr-hero-content {
        max-width: 900px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .qr-hero h1 {
        font-size: 68px;
        font-weight: 900;
        line-height: 1.05;
        letter-spacing: -2.5px;
        color: #ffffff;
        margin-bottom: 1.75rem;
        text-shadow: 0 10px 40px rgba(0,0,0,0.8), 0 0 100px rgba(99,102,241,0.2);
    }
    .qr-hero h1 .gold  { 
        color: #fbbf24; 
        background: linear-gradient(to bottom, #fde68a, #fbbf24);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 0 20px rgba(251,191,36,0.3));
    }
    .qr-hero h1 .purple { 
        color: #818cf8;
        background: linear-gradient(to bottom, #c7d2fe, #818cf8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0 0 20px rgba(129,140,248,0.3));
    }
    .qr-hero-p {
        font-size: 18px;
        line-height: 1.75;
        color: #e2e8f0;
        margin-bottom: 3rem;
        max-width: 680px;
        font-weight: 500;
        text-shadow: 0 4px 12px rgba(0,0,0,0.6);
    }
    .qr-cta-row { display: flex; gap: 16px; justify-content: center; }

    /* ── STATS ────────────────────────────────────────── */
    .qr-stats {
        background: #050814;
        border-top: 1px solid rgba(255,255,255,.08);
        border-bottom: 1px solid rgba(255,255,255,.08);
        padding: 3rem 1rem;
    }
    .qr-stats-row {
        max-width: 1100px;
        margin: 0 auto;
        display: flex;
        justify-content: space-around;
        gap: 3rem;
    }
    .qr-stat {
        flex: 1;
        text-align: center;
        position: relative;
    }
    .qr-stat:not(:last-child)::after {
        content: '';
        position: absolute;
        right: -1.5rem;
        top: 20%;
        bottom: 20%;
        width: 1px;
        background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.1), transparent);
    }
    .qr-stat-n {
        display: block;
        font-size: 42px;
        font-weight: 900;
        color: #ffffff;
        letter-spacing: -1px;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    .qr-stat-l {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: #64748b;
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
</style>

<div class="qr-page">

    {{-- ===================== HERO ===================== --}}
    <section class="qr-hero">
        {{-- Background Banner --}}
        <div class="qr-hero-bg">
            <img src="{{ asset('storage/banners/landingBanner.png') }}" alt="">
        </div>
        <div class="qr-hero-overlay"></div>

        <div class="qr-hero-grid">
            {{-- Center: copy --}}
            <div class="qr-hero-content">
                <div class="qr-badge">
                    <div class="qr-badge-dot"></div>
                    Welcome to Quoros Translation
                </div>
                <h1>
                    NOVEL PLATFORM<br>
                    <span class="gold">QUOROS</span>
                    <span class="purple">TRANSLATION</span><br>
                </h1>
                <p class="qr-hero-p">
                    Platform novel modern yang menghadirkan terjemahan berkualitas tinggi.
                    Nikmati pengalaman membaca yang mulus, bersih, dan mendukung akses offline.
                </p>
                <div class="qr-cta-row">
                    <a href="{{ route('home') }}" class="qr-btn-primary">Jelajahi Katalog</a>
                    @guest
                    <a href="{{ route('register') }}" class="qr-btn-ghost">Daftar Gratis →</a>
                    @endguest
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== STATS ===================== --}}
    <div class="qr-stats">
        <div class="qr-stats-row">
            <div class="qr-stat">
                <div class="qr-stat-n">1.200<span>+</span></div>
                <div class="qr-stat-l">Novel</div>
            </div>
            <div class="qr-stat">
                <div class="qr-stat-n">48K<span>+</span></div>
                <div class="qr-stat-l">Pembaca Aktif</div>
            </div>
            <div class="qr-stat">
                <div class="qr-stat-n">320<span>+</span></div>
                <div class="qr-stat-l">Penerjemah</div>
            </div>
        </div>
    </div>

    {{-- ===================== APA ITU QUOROS ===================== --}}
    <section class="qr-about">
        <div class="qr-about-inner">
            <div class="qr-eyebrow">Tentang Kami</div>
            <div class="qr-sec-title">Apa itu Quoros?</div>

            <div class="qr-about-grid">

                {{-- Kiri: deskripsi --}}
                <div class="qr-about-text">
                    <p>
                        Quoros Translation adalah platform terjemahan novel online yang berdedikasi menghadirkan
                        karya-karya terbaik dari berbagai penulis Asia — khususnya Tiongkok, Korea, dan Jepang —
                        ke dalam Bahasa Indonesia yang natural dan mudah dipahami.
                    </p>
                    <p>
                        Kami percaya bahwa setiap cerita layak untuk dinikmati tanpa hambatan bahasa.
                        Dengan tim penerjemah berpengalaman dan sistem editorial yang ketat, setiap bab yang
                        terbit di Quoros telah melewati proses quality check untuk menjaga konsistensi istilah
                        dan alur narasi.
                    </p>
                    <div class="qr-about-quote">
                        <p>
                            "Bukan sekadar terjemahan kata per kata — kami menerjemahkan rasa,
                            suasana, dan jiwa dari setiap cerita."
                        </p>
                    </div>
                </div>

                {{-- Kanan: kartu poin --}}
                <div class="qr-about-cards">
                    <div class="qr-acard">
                        <div class="qr-acard-icon" style="background:rgba(99,102,241,.12);color:#818cf8">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                            </svg>
                        </div>
                        <div>
                            <h5>Terjemahan Berkualitas</h5>
                            <p>Setiap bab diterjemahkan oleh tim yang memahami konteks budaya dan nuansa bahasa sumber aslinya.</p>
                        </div>
                    </div>
                    <div class="qr-acard">
                        <div class="qr-acard-icon" style="background:rgba(201,168,76,.1);color:#c9a84c">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h5>Editorial Ketat</h5>
                            <p>Setiap rilis melewati proses review untuk menjaga konsistensi istilah, nama karakter, dan alur cerita.</p>
                        </div>
                    </div>
                    <div class="qr-acard">
                        <div class="qr-acard-icon" style="background:rgba(34,197,94,.08);color:#4ade80">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h5>Komunitas Aktif</h5>
                            <p>Bergabung dengan ribuan pembaca yang aktif berdiskusi dan mendukung para penerjemah kesayangan mereka.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ===================== UPDATE TERBARU ===================== --}}
    <section class="qr-updates">
        <div class="qr-updates-inner">

            <div class="qr-hdr-row">
                <div>
                    <div class="qr-eyebrow">Terkini</div>
                    <div class="qr-sec-title">Update Terbaru</div>
                    <div class="qr-sec-sub">Novel yang baru saja merilis bab baru.</div>
                </div>
                <a href="{{ route('novels.updated') }}" class="qr-see-all">Lihat Semua →</a>
            </div>

            <div class="qr-novel-grid">
                @foreach($recentlyUpdated as $novel)
                <div class="qr-ncard">
                    <a href="{{ route('novels.show', $novel->slug) }}" style="text-decoration:none">

                        <div class="qr-ncover">
                            @if($novel->cover_image_url)
                                <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}">
                            @elseif($novel->cover_image)
                                <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}">
                            @else
                                <div style="width:100%;height:100%;background:linear-gradient(160deg,#1a2240,#0f1628);display:flex;align-items:center;justify-content:center;">
                                    <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#334155" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="qr-ncover-gradient"></div>
                            <div class="qr-nbadge">
                                <div class="qr-ntag">Baru Rilis</div>
                                <div class="qr-nchap">{{ $novel->chapters->first()->title ?? 'Chapter Baru' }}</div>
                            </div>
                        </div>

                        <div class="qr-ntitle">{{ $novel->title }}</div>
                        <div class="qr-nmeta">
                            <span>{{ $novel->author->name }}</span>
                            <span class="qr-ndot"></span>
                            <span>{{ \Carbon\Carbon::parse($novel->chapters_max_created_at)->diffForHumans() }}</span>
                        </div>

                    </a>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ===================== CTA BANNER ===================== --}}
    <section class="qr-cta-sec">
        <div class="qr-cta-box">
            <div class="qr-cta-left">
                <div class="qr-cta-ey">Mulai Sekarang</div>
                <div class="qr-cta-t">Siap Memulai<br>Petualanganmu?</div>
                <div class="qr-cta-d">
                    Bergabunglah dengan komunitas pembaca Quoros dan nikmati terjemahan novel terbaik.
                </div>
            </div>
            <div class="qr-cta-btns">
                <a href="{{ route('register') }}" class="qr-btn-gold">Daftar Gratis</a>
                <a href="{{ route('home') }}" class="qr-btn-ghost" style="text-align:center">Jelajahi Dulu</a>
            </div>
        </div>
    </section>

</div>
@endsection