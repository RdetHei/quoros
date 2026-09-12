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

@push('styles')
<style>
    /* ── Ticker ──────────────────────────────── */
    .stat-ticker-track {
        display: flex;
        gap: 0;
        animation: ticker 28s linear infinite;
        width: max-content;
    }
    @keyframes ticker {
        from { transform: translateX(0); }
        to   { transform: translateX(-50%); }
    }
    .stat-ticker-track:hover { animation-play-state: paused; }

    /* ── Rak novel ───────────────────────────── */
    .novel-shelf {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.625rem;
    }
    @media (min-width: 480px)  { .novel-shelf { grid-template-columns: repeat(3, 1fr); } }
    @media (min-width: 768px)  { .novel-shelf { grid-template-columns: repeat(4, 1fr); gap: 0.75rem; } }
    @media (min-width: 1024px) { .novel-shelf { grid-template-columns: repeat(5, 1fr); } }
    @media (min-width: 1280px) { .novel-shelf { grid-template-columns: repeat(6, 1fr); } }

    .novel-cover-wrap {
        position: relative;
        aspect-ratio: 2/3;
        border-radius: 0.625rem;
        overflow: hidden;
        background: #1e293b;
        box-shadow: 0 6px 20px -8px rgba(0,0,0,0.6);
    }
    .novel-cover-wrap img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
        display: block;
    }
    .novel-cover-wrap:hover img { transform: scale(1.05); }

    .novel-cover-wrap::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.78) 0%, rgba(0,0,0,0) 50%);
        pointer-events: none;
    }

    .novel-cover-meta {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 0.55rem 0.55rem 0.5rem;
        z-index: 1;
    }

    /* ── Chapter badge hijau ─────────────────── */
    .ch-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.22rem 0.5rem;
        border-radius: 0.375rem;
        background: rgba(16,185,129,0.15);
        border: 1px solid rgba(16,185,129,0.3);
        font-size: 0.625rem;
        font-weight: 700;
        color: #34d399;
        line-height: 1;
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
    .ch-badge-dot {
        width: 5px; height: 5px;
        border-radius: 50%;
        background: #34d399;
        animation: pulse-dot 2s ease-in-out infinite;
    }
    @keyframes pulse-dot {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.4; }
    }

    /* ── Section heading ─────────────────────── */
    .section-heading {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    .section-heading-bar {
        width: 3px; height: 1.25rem;
        border-radius: 2px;
        flex-shrink: 0;
    }
    .section-heading h2 {
        font-size: 1.05rem;
        font-weight: 800;
        color: #f1f5f9;
        letter-spacing: -0.01em;
    }
    .section-heading a {
        margin-left: auto;
        font-size: 0.7rem;
        font-weight: 700;
        color: #475569;
        text-decoration: none;
        transition: color 0.15s;
        white-space: nowrap;
        display: inline-flex; align-items: center; gap: 0.35rem;
    }
    .section-heading a:hover { color: #a5b4fc; }

    /* ── HERO — baru ─────────────────────────── */
    .hero-wrap {
        position: relative;
        overflow: hidden;
        border-radius: 1.5rem;
    }
    .hero-cover-bg {
        position: absolute;
        inset: -10%;
        background-size: cover;
        background-position: center;
        filter: blur(32px) saturate(1.1);
        opacity: 0.32;
        transition: opacity 0.7s ease, transform 0.7s ease;
        transform: scale(1.05);
    }
    .hero-bg-vignette {
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 90% 70% at 20% 30%, rgba(99,102,241,0.18) 0%, transparent 55%),
            radial-gradient(ellipse 60% 60% at 85% 70%, rgba(16,185,129,0.12) 0%, transparent 55%),
            linear-gradient(135deg, rgba(8,13,20,0.6) 0%, rgba(8,13,20,0.92) 70%, rgba(8,13,20,1) 100%);
        pointer-events: none;
    }
    .hero-grid-overlay {
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
        background-size: 44px 44px;
        opacity: 0.5;
        pointer-events: none;
    }
    .eyebrow-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.28rem 0.7rem;
        border-radius: 999px;
        background: rgba(99,102,241,0.12);
        border: 1px solid rgba(99,102,241,0.25);
        color: #a5b4fc;
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 0.14em;
        text-transform: uppercase;
    }
    .eyebrow-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #6366f1;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.18);
    }
    .hero-stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.6rem;
        border-radius: 0.5rem;
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.07);
        color: #94a3b8;
        font-size: 0.6875rem;
        font-weight: 600;
    }
    .hero-btn-primary {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 0.65rem 1.4rem;
        background: linear-gradient(135deg, #6366f1 0%, #7c3aed 100%);
        color: #fff;
        font-size: 0.78rem; font-weight: 800;
        border-radius: 0.75rem;
        border: none; cursor: pointer;
        transition: transform 0.15s, box-shadow 0.2s;
        text-decoration: none;
        box-shadow: 0 10px 30px -10px rgba(99,102,241,0.55);
    }
    .hero-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 36px -12px rgba(99,102,241,0.7);
    }
    .hero-btn-secondary {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 0.65rem 1.3rem;
        background: rgba(255,255,255,0.04);
        color: #cbd5e1;
        font-size: 0.78rem; font-weight: 700;
        border-radius: 0.75rem;
        border: 1px solid rgba(255,255,255,0.09);
        transition: all 0.15s;
        text-decoration: none;
    }
    .hero-btn-secondary:hover {
        background: rgba(255,255,255,0.08);
        border-color: rgba(255,255,255,0.18);
        color: #f1f5f9;
    }

    /* Stacked featured covers */
    .stack-wrap {
        position: relative;
        height: 26rem;
        perspective: 1200px;
    }
    @media (min-width: 1024px) { .stack-wrap { height: 30rem; } }

    .stack-card {
        position: absolute;
        top: 50%; left: 50%;
        width: 11rem;
        aspect-ratio: 2/3;
        border-radius: 0.875rem;
        overflow: hidden;
        background: #1e293b;
        box-shadow:
            0 30px 60px -20px rgba(0,0,0,0.7),
            0 8px 20px -10px rgba(0,0,0,0.6),
            0 0 0 1px rgba(255,255,255,0.06);
        transition: transform 0.7s cubic-bezier(0.22,1,0.36,1), opacity 0.5s ease, box-shadow 0.4s ease;
        transform-style: preserve-3d;
    }
    .stack-card img {
        position: absolute;
        inset: 0;
        width: 100%; height: 100%;
        object-fit: cover;
    }
    .stack-card::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 45%);
        pointer-events: none;
    }
    .stack-card-label {
        position: absolute;
        left: 0; right: 0; bottom: 0;
        padding: 0.6rem 0.7rem 0.7rem;
        z-index: 2;
    }
    .stack-card-label .s-title {
        font-size: 0.72rem;
        font-weight: 800;
        color: #f8fafc;
        line-height: 1.2;
        letter-spacing: -0.01em;
        text-shadow: 0 2px 8px rgba(0,0,0,0.6);
    }
    .stack-card-label .s-sub {
        font-size: 0.6rem;
        color: rgba(226,232,240,0.7);
        font-weight: 500;
        margin-top: 0.15rem;
    }
    .stack-dots {
        display: flex;
        gap: 0.4rem;
        margin-top: 1.25rem;
        justify-content: center;
    }
    .stack-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #334155;
        transition: all 0.25s ease;
        cursor: pointer;
        border: none;
        padding: 0;
    }
    .stack-dot.active {
        background: #818cf8;
        width: 1.5rem;
        border-radius: 999px;
    }
    .stack-arrow {
        width: 2rem; height: 2rem;
        border-radius: 999px;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.08);
        color: #cbd5e1;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.15s;
        cursor: pointer;
    }
    .stack-arrow:hover {
        background: rgba(99,102,241,0.18);
        border-color: rgba(99,102,241,0.35);
        color: #c7d2fe;
    }

    /* ── Cover Showcase Gallery (Bento Mosaic) ── */
    .cover-showcase {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        grid-auto-rows: 110px;
        gap: 0.7rem;
    }
    @media (max-width: 1023px) {
        .cover-showcase {
            grid-template-columns: repeat(4, 1fr);
            grid-auto-rows: 100px;
            gap: 0.5rem;
        }
    }
    @media (max-width: 639px) {
        .cover-showcase {
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: 90px;
        }
    }
    .showcase-cell {
        position: relative;
        border-radius: 0.8rem;
        overflow: hidden;
        background: #0f172a;
        border: 1px solid rgba(255,255,255,0.05);
        transition: transform 0.35s ease, border-color 0.35s ease, box-shadow 0.35s ease;
        text-decoration: none;
        display: block;
    }
    .showcase-cell::after {
        content: '';
        position: absolute;
        inset: 0;
        background:
            linear-gradient(to top, rgba(2,6,23,0.88) 0%, rgba(2,6,23,0.2) 45%, rgba(2,6,23,0) 70%),
            linear-gradient(to right, rgba(2,6,23,0.45) 0%, transparent 35%);
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    .showcase-cell:hover {
        transform: translateY(-3px);
        border-color: rgba(99,102,241,0.35);
        box-shadow: 0 22px 44px -18px rgba(99,102,241,0.2), 0 10px 20px -10px rgba(0,0,0,0.55);
    }
    .showcase-cell:hover::after { opacity: 0.92; }
    .showcase-cell img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .showcase-cell:hover img { transform: scale(1.08); }
    .showcase-cell .cell-overlay {
        position: absolute;
        inset: 0;
        padding: 0.8rem 0.9rem;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        z-index: 2;
    }
    .showcase-cell .cell-tag {
        display: inline-flex;
        align-items: center;
        padding: 0.18rem 0.5rem;
        background: rgba(99,102,241,0.18);
        border: 1px solid rgba(99,102,241,0.3);
        color: #a5b4fc;
        font-size: 0.625rem;
        font-weight: 700;
        border-radius: 999px;
        line-height: 1.4;
        align-self: flex-start;
        margin-bottom: 0.45rem;
        backdrop-filter: blur(6px);
    }
    .showcase-cell .cell-title {
        font-size: 0.8125rem;
        font-weight: 800;
        line-height: 1.2;
        color: #f1f5f9;
        letter-spacing: -0.01em;
    }
    .showcase-cell .cell-sub {
        font-size: 0.65rem;
        color: #64748b;
        margin-top: 0.15rem;
        font-weight: 500;
    }
    .cell-hero    { grid-column: span 4; grid-row: span 3; }
    .cell-tall    { grid-column: span 2; grid-row: span 3; }
    .cell-wide    { grid-column: span 3; grid-row: span 2; }
    .cell-square  { grid-column: span 2; grid-row: span 2; }
    .cell-compact { grid-column: span 1; grid-row: span 2; }

    @media (max-width: 1023px) {
        .cell-hero    { grid-column: span 2; grid-row: span 3; }
        .cell-tall    { grid-column: span 2; grid-row: span 3; }
        .cell-wide    { grid-column: span 2; grid-row: span 2; }
        .cell-square  { grid-column: span 2; grid-row: span 2; }
        .cell-compact { grid-column: span 2; grid-row: span 2; }
    }
    @media (max-width: 639px) {
        .cell-hero    { grid-column: span 3; grid-row: span 3; }
        .cell-tall    { grid-column: span 3; grid-row: span 2; }
        .cell-wide    { grid-column: span 3; grid-row: span 2; }
        .cell-square  { grid-column: span 2; grid-row: span 2; }
        .cell-compact { grid-column: span 1; grid-row: span 2; }
        .showcase-cell .cell-title { font-size: 0.78rem; }
        .showcase-cell .cell-overlay { padding: 0.6rem 0.7rem; }
    }

    /* ── Showcase meta ───────────────────────── */
    .showcase-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 0.22em;
        text-transform: uppercase;
        color: #818cf8;
    }
    .showcase-eyebrow-dot {
        width: 0.375rem;
        height: 0.375rem;
        border-radius: 50%;
        background: #6366f1;
        box-shadow: 0 0 0 4px rgba(99,102,241,0.12);
    }

    /* Editor choice compact card */
    .ec-card {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        padding: 0.8rem;
        border-radius: 0.9rem;
        background: #0c1320;
        border: 1px solid #121c2e;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .ec-card:hover {
        border-color: rgba(99,102,241,0.3);
        background: #0e1726;
        transform: translateY(-1px);
        box-shadow: 0 14px 28px -18px rgba(99,102,241,0.25);
    }
    .ec-cover {
        width: 3rem;
        aspect-ratio: 2/3;
        border-radius: 0.45rem;
        overflow: hidden;
        flex-shrink: 0;
        background: #1e293b;
        box-shadow: 0 6px 14px -6px rgba(0,0,0,0.5);
    }
    .ec-cover img {
        width: 100%; height: 100%; object-fit: cover;
    }
    .ec-rating {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        color: #fbbf24;
        font-size: 0.65rem;
        font-weight: 800;
    }
</style>
@endpush

<div class="min-h-screen" style="background:#060a10; color:#e2e8f0;">

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
                                <a href="{{ route('home') }}" class="hero-btn-primary">
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

    {{-- ═══════════════════════════════════════════
         STATISTIK — ticker horizontal
    ═══════════════════════════════════════════ --}}
    <div style="border-top:1px solid #0f172a; border-bottom:1px solid #0f172a; background: linear-gradient(to right, #060a10, #0a1220, #060a10); overflow:hidden; padding:0.7rem 0;">
        <div class="stat-ticker-track">
            @php
                $tickerItems = [
                    ['val' => number_format($stats['novels']),       'lbl' => 'novel tersedia'],
                    ['val' => number_format($stats['chapters']),     'lbl' => 'chapter diterbitkan'],
                    ['val' => number_format($stats['genres']),       'lbl' => 'genre beragam'],
                    ['val' => number_format($stats['updates_week']), 'lbl' => 'update minggu ini'],
                ];
                $tickerItems = array_merge($tickerItems, $tickerItems, $tickerItems, $tickerItems);
            @endphp
            @foreach($tickerItems as $t)
            <div class="flex items-center shrink-0" style="padding:0 2.25rem;">
                <span class="text-[15px] font-black tabular-nums" style="color:#e2e8f0;">{{ $t['val'] }}</span>
                <span class="text-xs font-medium ml-1.5" style="color:#475569;">{{ $t['lbl'] }}</span>
                <span style="display:inline-block;width:5px;height:5px;border-radius:50%;background:#1e293b;margin-left:2.25rem;"></span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════════
         EDITOR'S CHOICE
    ═══════════════════════════════════════════ --}}
    @if($featuredNovels->count() > 0)
    <section style="padding:3rem 0 2.5rem;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="section-heading">
                <div class="section-heading-bar" style="background: linear-gradient(to bottom,#818cf8,#6366f1);"></div>
                <h2>Pilihan Editor</h2>
                <a href="{{ route('home') }}">
                    Lihat semua
                    <svg style="width:11px;height:11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                @foreach($featuredNovels->take(6) as $novel)
                @php
                    $coverUrl = $novel->cover_image_url ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
                    $rating = $novel->rating_avg ?? 4.8;
                @endphp
                <a href="{{ route('novels.show', $novel->slug) }}" class="ec-card">

                    <div class="ec-cover">
                        @if($coverUrl)
                        <img src="{{ $coverUrl }}" alt="{{ $novel->title }}"
                             width="48" height="72" loading="lazy"
                             onerror="this.src='/error.png'">
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            @if($novel->genres->first())
                            <span class="text-[9px] font-bold px-2 py-0.5 rounded-full"
                                  style="color:#818cf8; background:rgba(99,102,241,0.12); border:1px solid rgba(99,102,241,0.2);">
                                {{ $novel->genres->first()->name }}
                            </span>
                            @endif
                            <span class="ec-rating">
                                <svg style="width:10px;height:10px;" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ number_format($rating, 1) }}
                            </span>
                        </div>
                        <h3 class="text-[13px] font-bold line-clamp-1 leading-snug" style="color:#e2e8f0;">
                            {{ $novel->title }}
                        </h3>
                        <p class="text-[10px] mt-0.5 truncate" style="color:#475569;">
                            {{ $novel->author->name }} &middot; {{ $novel->chapters_count }} bab
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════
         LATEST UPDATES
    ═══════════════════════════════════════════ --}}
    @if($recentlyUpdated->count() > 0)
    <section style="padding:2.5rem 0 3rem; border-top:1px solid #0a1220;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="section-heading">
                <div class="section-heading-bar" style="background: linear-gradient(to bottom,#34d399,#10b981);"></div>
                <h2>Baru Diperbarui</h2>
                <a href="{{ route('novels.updated') }}">
                    Lihat semua
                    <svg style="width:11px;height:11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="novel-shelf">
                @foreach($recentlyUpdated as $novel)
                @php
                    $coverUrl  = $novel->cover_image_url ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
                    $latestCh  = $novel->chapters->first();
                    $latestAgo = $latestCh
                        ? \Illuminate\Support\Carbon::parse($latestCh->published_at ?? $latestCh->created_at)->diffForHumans(null, true)
                        : null;
                @endphp
                <div class="group">
                    <a href="{{ $latestCh ? route('chapters.show', [$novel->slug, $latestCh->slug]) : route('novels.show', $novel->slug) }}"
                       class="novel-cover-wrap block">
                        @if($coverUrl)
                        <img src="{{ $coverUrl }}" alt="{{ $novel->title }}"
                             width="240" height="360" loading="lazy"
                             onerror="this.src='/error.png'">
                        @else
                        <div class="w-full h-full" style="background:#1e293b;"></div>
                        @endif

                        @if($latestCh)
                        <div class="novel-cover-meta">
                            <div class="ch-badge">
                                <span class="ch-badge-dot"></span>
                                <span class="truncate" style="max-width:8rem;">{{ $latestCh->title }}</span>
                            </div>
                        </div>
                        @endif
                    </a>

                    <a href="{{ route('novels.show', $novel->slug) }}" class="block mt-2">
                        <h3 class="text-[11px] font-semibold line-clamp-1 leading-snug" style="color:#94a3b8;">
                            {{ $novel->title }}
                        </h3>
                        @if($latestAgo)
                        <p class="text-[9px] mt-0.5" style="color:#334155;">{{ $latestAgo }}</p>
                        @endif
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- ═══════════════════════════════════════════
         COVER SHOWCASE — Bento Mosaic Gallery
    ═══════════════════════════════════════════ --}}
    @php
        $showcaseNovels = collect()
            ->merge($featuredNovels ?? collect())
            ->merge($recentlyUpdated ?? collect())
            ->unique('id')
            ->take(5)
            ->values();

        $cellLayouts = ['cell-hero', 'cell-tall', 'cell-wide', 'cell-square', 'cell-compact'];
    @endphp
    @if($showcaseNovels->count() >= 4)
    <section style="padding:3rem 0 5rem;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between gap-4 mb-5 sm:mb-7">
                <div class="min-w-0">
                    <div class="showcase-eyebrow mb-2.5">
                        <span class="showcase-eyebrow-dot"></span>
                        Cover Showcase
                    </div>
                    <h2 class="text-xl sm:text-2xl font-black tracking-tight leading-tight" style="color:#f1f5f9;">
                        Karya pilihan terbaik<br>
                        <span style="background: linear-gradient(135deg,#a5b4fc 0%,#818cf8 60%,#6366f1 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">dengan ilustrasi cover memukau.</span>
                    </h2>
                    <p class="mt-2 text-xs max-w-lg" style="color:#64748b;">
                        Setiap cover dipilih untuk memberikan gambaran visual dari alur cerita yang akan kamu nikmati.
                    </p>
                </div>
                <a href="{{ route('home') }}"
                   class="shrink-0 hidden sm:inline-flex items-center gap-1.5 px-3.5 h-8 rounded-lg text-[11px] font-bold transition-all"
                   style="background:rgba(99,102,241,0.1); color:#a5b4fc; border:1px solid rgba(99,102,241,0.22);"
                   onmouseenter="this.style.background='rgba(99,102,241,0.2)';"
                   onmouseleave="this.style.background='rgba(99,102,241,0.1)';">
                    Jelajahi semua
                    <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            <div class="cover-showcase">
                @foreach($showcaseNovels as $i => $novel)
                    @php
                        $coverUrl = $novel->cover_image_url ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
                        $firstGenre = $novel->genres->first()?->name ?? 'Featured';
                        $cellClass = $cellLayouts[$i] ?? 'cell-square';
                        $chCount = $novel->chapters_count ?? 0;
                    @endphp
                    <a href="{{ route('novels.show', $novel->slug) }}"
                       class="showcase-cell {{ $cellClass }}">
                        @if($coverUrl)
                            <img src="{{ $coverUrl }}"
                                 alt="{{ $novel->title }}"
                                 loading="lazy"
                                 onerror="this.style.display='none'">
                        @endif
                        <div class="cell-overlay">
                            <span class="cell-tag">{{ $firstGenre }}</span>
                            <h3 class="cell-title {{ $cellClass === 'cell-compact' ? 'line-clamp-3' : 'line-clamp-2' }}">
                                {{ $novel->title }}
                            </h3>
                            @if($cellClass !== 'cell-compact')
                            <p class="cell-sub">
                                {{ $novel->author->name }}
                                @if($chCount > 0)
                                    &middot; {{ $chCount }} bab
                                @endif
                            </p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

</div>
@endsection