@extends('layouts.app')

@push('styles')
<style>
    /* ============================================
       GLOBAL READER OVERRIDES
    ============================================ */
    #navbar { display: none !important; }

    [x-cloak] { display: none !important; }

    /* ============================================
       PROGRESS BAR
    ============================================ */
    #reader-progress-bar {
        position: fixed;
        top: 0; left: 0;
        width: 100%;
        height: 3px;
        background: transparent;
        z-index: 200;
        pointer-events: none;
    }
    #scroll-progress {
        height: 100%;
        width: 0%;
        background: linear-gradient(90deg, #6366f1, #a78bfa);
        transition: width 0.1s linear;
        box-shadow: 0 0 12px rgba(99,102,241,0.6);
    }

    /* ============================================
       DESKTOP SIDEBAR (right pill)
    ============================================ */
    .reader-sidebar {
        position: fixed;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        z-index: 100;
        width: 3.75rem;
        background: rgba(15, 23, 42, 0.92);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(99,102,241,0.18);
        border-radius: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem 0.6rem;
        gap: 0.375rem;
        box-shadow: 0 24px 48px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.05);
    }

    @media (max-width: 1280px) {
        .reader-sidebar { right: 0.75rem; }
    }

    /* ============================================
       SIDEBAR BUTTONS
    ============================================ */
    .sidebar-btn {
        width: 2.75rem;
        height: 2.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.875rem;
        color: #94a3b8;
        border: 1px solid transparent;
        transition: background 0.2s, color 0.2s, transform 0.15s, box-shadow 0.2s;
        position: relative;
        flex-shrink: 0;
        cursor: pointer;
        background: transparent;
    }

    .sidebar-btn:hover {
        background: rgba(99,102,241,0.15);
        color: #e2e8f0;
        transform: scale(1.08);
        box-shadow: 0 0 0 1px rgba(99,102,241,0.3);
    }

    .sidebar-btn.active {
        background: #6366f1;
        color: #ffffff;
        border-color: rgba(255,255,255,0.12);
        box-shadow: 0 4px 12px rgba(99,102,241,0.45);
    }

    .sidebar-btn.disabled {
        opacity: 0.28;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* Tooltip */
    .sidebar-btn::after {
        content: attr(title);
        position: absolute;
        right: calc(100% + 0.75rem);
        top: 50%;
        transform: translateY(-50%);
        background: #0f172a;
        color: #e2e8f0;
        font-size: 0.7rem;
        font-weight: 600;
        white-space: nowrap;
        padding: 0.35rem 0.65rem;
        border-radius: 0.5rem;
        border: 1px solid #334155;
        pointer-events: none;
        opacity: 0;
        transition: opacity 0.15s;
        letter-spacing: 0.02em;
    }

    .sidebar-btn:hover::after { opacity: 1; }

    .sidebar-divider {
        width: 1.75rem;
        height: 1px;
        background: rgba(148,163,184,0.15);
        flex-shrink: 0;
        margin: 0.25rem 0;
    }

    /* ============================================
       SIDEBAR PANELS (chapter list / settings)
    ============================================ */
    .sidebar-panel {
        position: fixed;
        right: 5.5rem;
        top: 50%;
        transform: translateY(-50%);
        width: 20rem;
        max-height: 80vh;
        background: rgba(15, 23, 42, 0.96);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(99,102,241,0.2);
        border-radius: 1.5rem;
        padding: 1.5rem;
        box-shadow: 0 32px 64px rgba(0,0,0,0.5);
        z-index: 110;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .sidebar-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        flex-shrink: 0;
    }

    .sidebar-panel-title {
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #6366f1;
    }

    .panel-close-btn {
        width: 1.75rem;
        height: 1.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        color: #64748b;
        transition: background 0.15s, color 0.15s;
    }

    .panel-close-btn:hover { background: #1e293b; color: #e2e8f0; }

    /* Custom Scrollbar */
    .custom-scrollbar { overflow-y: auto; flex: 1; min-height: 0; }

    .custom-scrollbar::-webkit-scrollbar { width: 3px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6366f1; }

    /* Chapter list item */
    .chapter-list-item {
        display: block;
        padding: 0.6rem 0.875rem;
        border-radius: 0.75rem;
        font-size: 0.8rem;
        font-weight: 500;
        color: #64748b;
        transition: background 0.15s, color 0.15s;
        line-height: 1.4;
    }

    .chapter-list-item:hover { background: #1e293b; color: #e2e8f0; }
    .chapter-list-item.active { background: #6366f1; color: #fff; font-weight: 700; }

    /* Settings controls */
    .settings-label {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #475569;
        margin-bottom: 0.625rem;
        display: block;
    }

    .font-size-btn {
        flex: 1;
        height: 2.5rem;
        border-radius: 0.75rem;
        font-weight: 700;
        font-size: 0.85rem;
        color: #64748b;
        background: #0f172a;
        border: 1px solid #1e293b;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .font-size-btn:hover { background: #1e293b; color: #e2e8f0; }
    .font-size-btn.active { background: #6366f1; color: #fff; border-color: transparent; box-shadow: 0 4px 10px rgba(99,102,241,0.35); }

    .font-family-btn {
        width: 100%;
        height: 2.75rem;
        border-radius: 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        background: #0f172a;
        border: 1px solid #1e293b;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        padding: 0 1rem;
        gap: 0.75rem;
    }

    .font-family-btn:hover { background: #1e293b; color: #e2e8f0; }
    .font-family-btn.active { background: #6366f1; color: #fff; border-color: transparent; }

    .font-family-btn .font-preview { font-size: 1.1rem; opacity: 0.7; }

    /* ============================================
       MOBILE FAB (Floating Action Button)
     ============================================ */
    .mobile-fab { display: none; }

    @media (max-width: 640px) {
        .reader-sidebar { display: none !important; }
        .mobile-fab {
            display: flex;
            position: fixed;
            bottom: 2rem;
            right: 1.5rem;
            width: 3.5rem;
            height: 3.5rem;
            border-radius: 9999px;
            background: #6366f1;
            color: #ffffff;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
            z-index: 150;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        .mobile-fab:hover {
            background: #4f46e5;
            transform: scale(1.05);
        }
        .mobile-fab:active {
            transform: scale(0.95);
        }
    }

    /* ============================================
       MOBILE BOTTOM NAV (Prev / Next strip)
    ============================================ */
    .mobile-bottom-nav { display: none; }

    .mobile-nav-btn {
        flex: 1;
        height: 2.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        border-radius: 0.875rem;
        font-size: 0.75rem;
        font-weight: 700;
        color: #94a3b8;
        background: rgba(30,41,59,0.8);
        border: 1px solid rgba(148,163,184,0.1);
        transition: background 0.15s, color 0.15s;
        text-decoration: none;
    }

    .mobile-nav-btn:hover { background: #1e293b; color: #e2e8f0; }

    .mobile-nav-btn.disabled {
        opacity: 0.3;
        pointer-events: none;
    }

    .mobile-chapter-indicator {
        flex-shrink: 0;
        font-size: 0.65rem;
        font-weight: 800;
        color: #475569;
        text-align: center;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        padding: 0 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 7rem;
    }

    /* ============================================
       MOBILE DRAWER (Sidebar on mobile)
    ============================================ */
    .mobile-drawer-overlay {
        position: fixed;
        inset: 0;
        background: rgba(2, 6, 23, 0.75);
        backdrop-filter: blur(6px);
        -webkit-backdrop-filter: blur(6px);
        z-index: 160;
    }

    .mobile-drawer {
        position: fixed;
        top: 0; bottom: 0; right: 0;
        z-index: 170;
        background: #0f172a;
        border-left: 1px solid rgba(99,102,241,0.2);
        border-radius: 1.5rem 0 0 1.5rem;
        padding: 1.5rem 1.25rem;
        width: 18rem;
        max-width: 80vw;
        height: 100dvh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: -10px 0 30px rgba(0, 0, 0, 0.4);
    }

    .drawer-handle {
        width: 2.5rem;
        height: 4px;
        background: #334155;
        border-radius: 2px;
        margin: 0.875rem auto 1.25rem;
    }

    .drawer-section-title {
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #6366f1;
        margin-bottom: 0.75rem;
    }

    .drawer-btn {
        display: flex;
        align-items: center;
        gap: 0.875rem;
        width: 100%;
        padding: 0.875rem 1rem;
        border-radius: 1rem;
        color: #cbd5e1;
        font-size: 0.875rem;
        font-weight: 600;
        background: #1e293b;
        border: 1px solid transparent;
        margin-bottom: 0.5rem;
        transition: background 0.15s, color 0.15s;
        text-decoration: none;
    }

    .drawer-btn:hover { background: #293548; color: #fff; }
    .drawer-btn .icon-wrap { width: 2.25rem; height: 2.25rem; border-radius: 0.75rem; background: #334155; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }

    /* ============================================
       READING AREA
    ============================================ */
    .chapter-card {
        background: white;
        border-radius: 1.25rem;
        padding: 1.5rem 0.875rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.04), 0 2px 4px -1px rgba(0,0,0,0.02);
        border: 1px solid #f1f5f9;
        margin-bottom: 2.5rem;
    }

    @media (min-width: 640px) {
        .chapter-card {
            border-radius: 2rem;
            padding: 3.5rem 5rem;
        }
    }

    @media (min-width: 1024px) {
        .chapter-card { padding: 4rem 7rem; }
    }

    .dark .chapter-card {
        background: #0f172a;
        border-color: #1e293b;
        box-shadow: none;
    }

    /* Prose typography */
    .prose { line-height: 1.85 !important; }
    .prose p { margin-bottom: 1.5em !important; }

    /* Content protection */
    .reader-chapter-shell {
        user-select: none;
        -webkit-user-select: none;
    }

    /* ============================================
       CHAPTER DIVIDER
    ============================================ */
    .chapter-divider {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin: 3.5rem 0;
        color: #94a3b8;
    }

    .chapter-divider::before,
    .chapter-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: linear-gradient(to right, transparent, #334155, transparent);
    }

    .chapter-divider span {
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    /* ============================================
       AUTOLOAD TRIGGER
    ============================================ */
    .autoload-zone {
        height: 8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 5rem;
    }

    @media (max-width: 640px) {
        .autoload-zone { margin-bottom: 8rem; }
    }

    .autoload-indicator {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        color: #475569;
    }

    .autoload-dots {
        display: flex;
        gap: 0.375rem;
    }

    .autoload-dots span {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #6366f1;
        animation: bounce 1.2s infinite ease-in-out;
    }

    .autoload-dots span:nth-child(2) { animation-delay: 0.15s; }
    .autoload-dots span:nth-child(3) { animation-delay: 0.3s; }

    @keyframes bounce {
        0%, 80%, 100% { transform: scale(0.6); opacity: 0.5; }
        40% { transform: scale(1); opacity: 1; }
    }

    .autoload-text {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
    }

    /* ============================================
       COMMENTS SECTION
    ============================================ */
    .comments-section {
        background: white;
        border-radius: 1.75rem;
        padding: 1.75rem 1.5rem;
        border: 1px solid #f1f5f9;
    }

    @media (min-width: 640px) {
        .comments-section { padding: 2.5rem 3rem; }
    }

    .dark .comments-section {
        background: #0f172a;
        border-color: #1e293b;
    }


</style>
@endpush

@section('content')
<div x-data="reader(@js([

    'nextChapterSlug'    => $nextChapter    ? $nextChapter->slug    : '',
    'prevChapterSlug'    => $previousChapter ? $previousChapter->slug : '',
    'currentChapterSlug' => $chapter->slug,
    'allChapters'        => $allChapters,
    'novelSlug'          => $novel->slug,
    'novelTitle'         => $novel->title,
    'baseUrl'            => url('/'),
    'protectChapter'     => $protectContent ?? false
]))" class="max-w-5xl mx-auto px-2 sm:px-6 pb-16 pt-8">

    {{-- ── Reading progress bar ───────────────────── --}}
    <div id="reader-progress-bar">
        <div id="scroll-progress"></div>
    </div>

    {{-- ═══════════════════════════════════════════════
         DESKTOP SIDEBAR
    ════════════════════════════════════════════════ --}}
    <div class="reader-sidebar hidden sm:flex">

        {{-- Back to novel --}}
        <a href="{{ route('novels.show', $novel->slug) }}" class="sidebar-btn" title="Kembali ke Novel">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </a>

        <div class="sidebar-divider"></div>

        {{-- Prev chapter --}}
        <a id="sidebar-prev-link"
           href="{{ $previousChapter ? route('chapters.show', [$novel->slug, $previousChapter->slug]) : '#' }}"
           class="sidebar-btn" :class="!prevChapterSlug ? 'disabled' : ''" title="Chapter Sebelumnya">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>

        {{-- Chapter list toggle --}}
        <button type="button" @click="showChapters = !showChapters; showSettings = false"
                :class="showChapters ? 'active' : ''" class="sidebar-btn" title="Daftar Chapter">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
            </svg>
        </button>

        {{-- Next chapter --}}
        <a id="sidebar-next-link"
           href="{{ $nextChapter ? route('chapters.show', [$novel->slug, $nextChapter->slug]) : '#' }}"
           class="sidebar-btn" :class="!nextChapterSlug ? 'disabled' : ''" title="Chapter Berikutnya">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </a>

        <div class="sidebar-divider"></div>

        {{-- Settings toggle --}}
        <button type="button" @click="showSettings = !showSettings; showChapters = false"
                :class="showSettings ? 'active' : ''" class="sidebar-btn" title="Pengaturan Baca">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
            </svg>
        </button>

        {{-- Chapter list panel --}}
        <div x-show="showChapters" @click.away="showChapters = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-x-2"
             x-transition:enter-end="opacity-100 scale-100 translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="sidebar-panel" x-cloak>
            <div class="sidebar-panel-header">
                <span class="sidebar-panel-title">Daftar Chapter</span>
                <button @click="showChapters = false" class="panel-close-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="custom-scrollbar pr-1">
                <div class="grid gap-1">
                    <template x-for="ch in allChapters" :key="ch.slug">
                        <a :href="'{{ url('/novels/' . $novel->slug . '/read') }}/' + ch.slug"
                           class="chapter-list-item" :class="currentChapterSlug === ch.slug ? 'active' : ''">
                            <span x-text="ch.title"></span>
                        </a>
                    </template>
                </div>
            </div>
        </div>

        {{-- Settings panel --}}
        <div x-show="showSettings" @click.away="showSettings = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-x-2"
             x-transition:enter-end="opacity-100 scale-100 translate-x-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="sidebar-panel" x-cloak>
            <div class="sidebar-panel-header">
                <span class="sidebar-panel-title">Pengaturan</span>
                <button @click="showSettings = false" class="panel-close-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="space-y-5">
                {{-- Font size --}}
                <div>
                    <label class="settings-label">Ukuran Teks</label>
                    <div class="flex items-center gap-1.5">
                        <template x-for="sz in [{cls:'text-sm',lbl:'S'},{cls:'text-base',lbl:'M'},{cls:'text-lg',lbl:'L'},{cls:'text-xl',lbl:'XL'}]" :key="sz.cls">
                            <button @click="updateFontSize(sz.cls)"
                                    :class="fontSize === sz.cls ? 'active' : ''"
                                    class="font-size-btn" x-text="sz.lbl"></button>
                        </template>
                    </div>
                </div>
                {{-- Font family --}}
                <div>
                    <label class="settings-label">Jenis Font</label>
                    <div class="flex flex-col gap-1.5">
                        <template x-for="ff in [
                            {id:'font-sans',   lbl:'Sans Serif',  preview:'Aa'},
                            {id:'font-serif',  lbl:'Serif',       preview:'Aa'},
                            {id:'font-mono',   lbl:'Monospace',   preview:'Aa'}
                        ]" :key="ff.id">
                            <button @click="updateFontFamily(ff.id)"
                                    :class="[fontFamily === ff.id ? 'active' : '', ff.id]"
                                    class="font-family-btn">
                                <span class="font-preview" x-text="ff.preview"></span>
                                <span x-text="ff.lbl"></span>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         MOBILE: BOTTOM NAV
    ════════════════════════════════════════════════ --}}
    <div class="mobile-bottom-nav">
        <a id="mobile-prev-link"
           href="{{ $previousChapter ? route('chapters.show', [$novel->slug, $previousChapter->slug]) : '#' }}"
           class="mobile-nav-btn" :class="!prevChapterSlug ? 'disabled' : ''">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Sebelumnya
        </a>

        <div class="mobile-chapter-indicator" x-text="currentChapterTitle || '...'"></div>

        <a id="mobile-next-link"
           href="{{ $nextChapter ? route('chapters.show', [$novel->slug, $nextChapter->slug]) : '#' }}"
           class="mobile-nav-btn" :class="!nextChapterSlug ? 'disabled' : ''">
            Berikutnya
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

    {{-- ═══════════════════════════════════════════════
         MOBILE: FAB (open drawer)
    ════════════════════════════════════════════════ --}}
    <button class="mobile-fab" @click="drawerOpen = true" aria-label="Buka Menu Baca">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8M4 18h16"/>
        </svg>
    </button>

    {{-- ═══════════════════════════════════════════════
         MOBILE: SIDEBAR (RIGHT SIDE)
    ════════════════════════════════════════════════ --}}
    <template x-if="drawerOpen">
        <div x-cloak>
            {{-- Overlay --}}
            <div class="mobile-drawer-overlay"
                 @click="drawerOpen = false"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"></div>

            {{-- Sidebar --}}
            <div class="mobile-drawer"
                 x-transition:enter="transition ease-out duration-250"
                 x-transition:enter-start="opacity-0 translate-x-full"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-full">

                {{-- Sidebar Header --}}
                <div class="flex items-center justify-between mb-4 flex-shrink-0">
                    <div class="pr-4 overflow-hidden text-left">
                        <p class="text-[0.65rem] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest truncate mb-0.5">
                            {{ $novel->title }}
                        </p>
                        <h3 class="text-xs font-black text-slate-800 dark:text-white leading-tight truncate" x-text="currentChapterTitle || '{{ $chapter->title }}'">
                            {{ $chapter->title }}
                        </h3>
                    </div>
                    <button @click="drawerOpen = false" class="text-slate-400 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition-colors flex-shrink-0" aria-label="Tutup Menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Sidebar Divider --}}
                <div class="h-[1px] bg-slate-800 mb-4 flex-shrink-0"></div>

                {{-- Combined Scrollable Content --}}
                <div class="flex-1 overflow-y-auto custom-scrollbar pr-1 space-y-6">

                    {{-- Navigation Buttons --}}
                    <div>
                        <a id="sidebar-mobile-prev-link"
                           href="{{ $previousChapter ? route('chapters.show', [$novel->slug, $previousChapter->slug]) : '#' }}"
                           class="drawer-btn" :class="!prevChapterSlug ? 'opacity-30 pointer-events-none' : ''">
                            <span class="icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                            Sebelumnya
                        </a>
                        <a id="sidebar-mobile-next-link"
                           href="{{ $nextChapter ? route('chapters.show', [$novel->slug, $nextChapter->slug]) : '#' }}"
                           class="drawer-btn" :class="!nextChapterSlug ? 'opacity-30 pointer-events-none' : ''">
                            <span class="icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                            Berikutnya
                        </a>
                        <a href="{{ route('novels.show', $novel->slug) }}" class="drawer-btn">
                            <span class="icon-wrap">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </span>
                            Halaman Novel
                        </a>
                    </div>

                    {{-- Reader Settings --}}
                    <div class="border-t border-slate-800/80 pt-5 space-y-4">
                        <div>
                            <label class="settings-label">Ukuran Teks</label>
                            <div class="flex items-center gap-1.5">
                                <template x-for="sz in [{cls:'text-sm',lbl:'S'},{cls:'text-base',lbl:'M'},{cls:'text-lg',lbl:'L'},{cls:'text-xl',lbl:'XL'}]" :key="sz.cls">
                                    <button @click="updateFontSize(sz.cls)"
                                            :class="fontSize === sz.cls ? 'active' : ''"
                                            class="font-size-btn" x-text="sz.lbl"></button>
                                </template>
                            </div>
                        </div>
                        <div>
                            <label class="settings-label">Jenis Font</label>
                            <div class="flex flex-col gap-1.5">
                                <template x-for="ff in [
                                    {id:'font-sans',   lbl:'Sans Serif',  preview:'Aa'},
                                    {id:'font-serif',  lbl:'Serif',       preview:'Aa'},
                                    {id:'font-mono',   lbl:'Monospace',   preview:'Aa'}
                                ]" :key="ff.id">
                                    <button @click="updateFontFamily(ff.id)"
                                            :class="[fontFamily === ff.id ? 'active' : '', ff.id]"
                                            class="font-family-btn">
                                        <span class="font-preview" x-text="ff.preview"></span>
                                        <span x-text="ff.lbl"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Chapters List Section --}}
                    <div class="border-t border-slate-800/80 pt-5">
                        <label class="settings-label mb-3">Daftar Chapter</label>
                        <div class="grid gap-1 max-h-[30vh] overflow-y-auto custom-scrollbar pr-1">
                            <template x-for="ch in allChapters" :key="ch.slug">
                                <a :href="'{{ url('/novels/' . $novel->slug . '/read') }}/' + ch.slug"
                                   class="chapter-list-item" :class="currentChapterSlug === ch.slug ? 'active' : ''">
                                    <span x-text="ch.title"></span>
                                </a>
                            </template>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </template>

    {{-- ═══════════════════════════════════════════════
         CHAPTER HEADER
    ════════════════════════════════════════════════ --}}
    <div class="text-center mb-10">
        <p class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] mb-3">
            {{ $novel->title }}
        </p>
        <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white leading-tight tracking-tight">
            {{ $chapter->title }}
        </h1>
    </div>

    {{-- ═══════════════════════════════════════════════
         READING AREA
    ════════════════════════════════════════════════ --}}
    <div id="chapters-container">
        <div data-slug="{{ $chapter->slug }}"
             data-title="{{ $chapter->title }}"
             data-prev-slug="{{ $previousChapter ? $previousChapter->slug : '' }}"
             data-next-slug="{{ $nextChapter ? $nextChapter->slug : '' }}">

            <div class="chapter-card">
                <div class="{{ ($protectContent ?? false) ? 'reader-chapter-shell' : '' }}"
                     @if($protectContent ?? false) @contextmenu.prevent @endif>
                    <article :class="[fontSize, fontFamily]"
                             class="prose prose-slate dark:prose-invert max-w-none text-slate-800 dark:text-slate-200 transition-all duration-300">
                        {!! $chapterBodyHtml !!}
                    </article>
                </div>

                @if($chapter->file_path)
                    <div class="mt-12 pt-8 border-t border-slate-100 dark:border-slate-800">
                        <a href="{{ asset('storage/' . $chapter->file_path) }}"
                           target="_blank"
                           class="inline-flex items-center gap-3 w-full sm:w-auto justify-center px-8 py-4 bg-slate-950 dark:bg-white text-white dark:text-slate-950 font-bold rounded-2xl hover:opacity-90 transition-opacity shadow-xl shadow-slate-950/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Unduh File Chapter
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════
         AUTOLOAD ZONE
    ════════════════════════════════════════════════ --}}
    <div id="autoload-trigger" class="autoload-zone">
        <template x-if="isLoading">
            <div class="autoload-indicator">
                <div class="autoload-dots">
                    <span></span><span></span><span></span>
                </div>
                <p class="autoload-text text-indigo-400">Memuat chapter berikutnya…</p>
            </div>
        </template>
        <template x-if="!isLoading && nextChapterSlug">
            <div class="autoload-indicator">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                </svg>
                <p class="autoload-text">Scroll untuk lanjut baca</p>
            </div>
        </template>
        <template x-if="!isLoading && !nextChapterSlug">
            <div class="autoload-indicator">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="autoload-text">Akhir dari novel</p>
            </div>
        </template>
    </div>

    {{-- ═══════════════════════════════════════════════
         COMMENTS SECTION
    ════════════════════════════════════════════════ --}}
    <div class="comments-section">
        <h2 class="text-lg font-bold mb-6 flex items-center gap-2.5 text-slate-900 dark:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
            </svg>
            Diskusi
            <span class="ml-auto text-sm font-semibold text-slate-400">{{ $commentsCount ?? $chapter->comments->count() }}</span>
        </h2>

        <div x-data="{ replyParentId: null, replyName: '' }" @open-reply.window="replyParentId = $event.detail.parentId; replyName = $event.detail.name">

            @auth
                {{-- Reply form --}}
                <form x-show="replyParentId" x-cloak
                      action="{{ route('comments.store', $chapter->id) }}" method="POST"
                      class="mb-5 p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/30">
                    @csrf
                    <input type="hidden" name="parent_id" x-model="replyParentId">
                    <p class="text-xs font-bold text-indigo-600 dark:text-indigo-400 mb-2">
                        Membalas <span x-text="replyName" class="text-indigo-500"></span>
                    </p>
                    <textarea name="content" rows="2" required
                              class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                              placeholder="Tulis balasan…"></textarea>
                    <div class="mt-2 flex gap-2 justify-end">
                        <button type="button" @click="replyParentId = null"
                                class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-5 py-2 text-xs font-bold bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            Kirim Balasan
                        </button>
                    </div>
                </form>

                {{-- New comment form --}}
                <form x-show="!replyParentId"
                      action="{{ route('comments.store', $chapter->id) }}" method="POST"
                      class="mb-8">
                    @csrf
                    <textarea name="content" rows="3"
                              class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all placeholder-slate-400 resize-none"
                              placeholder="Bagikan pendapatmu tentang chapter ini…"></textarea>
                    <div class="mt-3 flex justify-end">
                        <button type="submit"
                                class="w-full sm:w-auto px-6 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl text-sm hover:opacity-90 transition-opacity shadow-lg shadow-slate-900/10">
                            Kirim Komentar
                        </button>
                    </div>
                </form>
            @else
                <div class="mb-8 p-6 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-center">
                    <p class="text-slate-500 dark:text-slate-400 mb-4 text-sm">Bergabung dalam diskusi?</p>
                    <a href="{{ route('login') }}"
                       class="inline-block w-full sm:w-auto px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl text-sm hover:opacity-90 transition-opacity shadow-lg shadow-slate-900/10">
                        Masuk Sekarang
                    </a>
                </div>
            @endauth

            <div class="space-y-6">
                @forelse($chapter->comments as $comment)
                    @include('partials.comment-item', ['comment' => $comment, 'chapter' => $chapter])
                @empty
                    <div class="py-10 text-center text-slate-400 text-sm">
                        Belum ada komentar. Jadilah yang pertama!
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function () {
    const el = document.getElementById('pwa-offline-banner');
    if (!el) return;
    const sync = () => el.classList.toggle('hidden', navigator.onLine);
    sync();
    window.addEventListener('online', sync);
    window.addEventListener('offline', sync);
})();

window.reader = function (config = {}) {
    let initialFontSize   = 'text-base';
    let initialFontFamily = 'font-sans';
    try {
        initialFontSize   = localStorage.getItem('reader-font-size')   || 'text-base';
        initialFontFamily = localStorage.getItem('reader-font-family') || 'font-sans';
    } catch (e) {}

    return {
        /* ── State ──────────────────────────────── */
        fontSize:            initialFontSize,
        fontFamily:          initialFontFamily,
        showSettings:        false,
        showChapters:        false,
        sidebarOpen:         false,
        drawerOpen:          false,
        drawerTab:           'nav',
        nextChapterSlug:     config.nextChapterSlug     || '',
        prevChapterSlug:     config.prevChapterSlug     || '',
        currentChapterSlug:  config.currentChapterSlug  || '',
        currentChapterTitle: '',
        allChapters:         config.allChapters         || [],
        isLoading:           false,
        isMobile:            window.innerWidth <= 640,
        novelSlug:           config.novelSlug           || '',
        novelTitle:          config.novelTitle          || '',
        baseUrl:             config.baseUrl             || '',
        protectChapter:      config.protectChapter      || false,
        scrollObserver:      null,

        /* ── Init ───────────────────────────────── */
        init() {
            this.baseUrl = (this.baseUrl || '').replace(/\/$/, '');

            // Set initial chapter title for mobile indicator
            const firstDiv = document.querySelector('#chapters-container > div');
            if (firstDiv) this.currentChapterTitle = firstDiv.dataset.title || '';

            this.$watch('fontSize',    () => this.updateAllChapters());
            this.$watch('fontFamily',  () => this.updateAllChapters());

            window.addEventListener('resize', () => {
                this.isMobile = window.innerWidth <= 640;
            });

            this.updateAllChapters();
            this.setupScrollProgress();
            this.setupScrollObserver();
            this.setupAutoloadObserver();
            this.setupTouchGestures();

            if (this.protectChapter) {
                const zone = document.getElementById('chapters-container');
                if (zone) {
                    ['copy', 'cut', 'contextmenu'].forEach(ev =>
                        zone.addEventListener(ev, e => { if (this.protectChapter) e.preventDefault(); }, true)
                    );
                }
            }
        },

        setupScrollProgress() {
            window.addEventListener('scroll', () => {
                const winScroll = window.pageYOffset || document.documentElement.scrollTop;
                const height    = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                if (height > 0) {
                    const pct = (winScroll / height) * 100;
                    const bar = document.getElementById('scroll-progress');
                    if (bar) bar.style.width = pct + '%';
                }
            }, { passive: true });
        },

        setupAutoloadObserver() {
            const trigger = document.getElementById('autoload-trigger');
            if (!trigger) return;
            new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting && !this.isLoading && this.nextChapterSlug) {
                    this.loadNextChapter();
                }
            }, { rootMargin: '400px' }).observe(trigger);
        },

        setupTouchGestures() {
            if (window.innerWidth > 640) return;
            let touchStartX = 0;
            window.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
            window.addEventListener('touchend', e => {
                const dist = touchStartX - e.changedTouches[0].screenX;
                // Swipe left (from right to left) to open right sidebar
                if (dist > 60 && !this.drawerOpen) { this.drawerOpen = true; }
                // Swipe right (from left to right) to close right sidebar
                if (dist < -60 && this.drawerOpen) { this.drawerOpen = false; }
            }, { passive: true });
        },

        updateFontSize(size) {
            this.fontSize = size;
            try { localStorage.setItem('reader-font-size', size); } catch (e) {}
        },

        updateFontFamily(family) {
            this.fontFamily = family;
            try { localStorage.setItem('reader-font-family', family); } catch (e) {}
        },

        updateAllChapters() {
            this.$nextTick(() => {
                document.querySelectorAll('#chapters-container article').forEach(el => {
                    el.classList.remove('text-sm', 'text-base', 'text-lg', 'text-xl', 'font-sans', 'font-serif', 'font-mono');
                    el.classList.add(this.fontSize, this.fontFamily);
                });
            });
        },

        async loadNextChapter() {
            if (!this.nextChapterSlug || this.isLoading) return;
            this.isLoading = true;
            try {
                const url      = `${this.baseUrl}/novels/${this.novelSlug}/read/${this.nextChapterSlug}`;
                const response = await fetch(url, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });
                if (!response.ok) {
                    if (response.status === 404) this.nextChapterSlug = null;
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                if (!data.chapter?.content) { this.nextChapterSlug = null; return; }

                const div = document.createElement('div');
                div.className = 'chapter-section';
                div.dataset.slug     = data.chapter.slug;
                div.dataset.title    = data.chapter.title;
                div.dataset.prevSlug = data.chapter.prev_chapter_slug || '';
                div.dataset.nextSlug = data.chapter.next_chapter_slug || '';

                const shellOpen  = this.protectChapter ? "<div class='reader-chapter-shell'>" : '<div>';
                const shellClose = '</div>';

                div.innerHTML = `
                    <div class="chapter-divider"><span>${data.novel.title}</span></div>
                    <div class="text-center mb-10">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mb-3">${data.novel.title}</p>
                        <h1 class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white leading-tight tracking-tight">${data.chapter.title}</h1>
                    </div>
                    <div class="chapter-card">
                        ${shellOpen}
                        <article class="${this.fontSize} ${this.fontFamily} prose prose-slate dark:prose-invert max-w-none text-slate-800 dark:text-slate-200 transition-all duration-300 chapter-content-article"></article>
                        ${shellClose}
                        <div class="mt-10 pt-8 border-t border-slate-100 dark:border-slate-800">
                            <a href="${url}" class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
                                Diskusi (${data.chapter.comments_count})
                            </a>
                        </div>
                    </div>
                `;

                div.querySelector('.chapter-content-article').innerHTML = data.chapter.content;
                document.getElementById('chapters-container').appendChild(div);

                this.nextChapterSlug = data.chapter.next_chapter_slug;
                if (data.all_chapters) this.allChapters = data.all_chapters;
                if (this.scrollObserver) this.scrollObserver.observe(div);

            } catch (error) {
                console.error('Failed to load next chapter:', error);
            } finally {
                this.isLoading = false;
            }
        },

        setupScrollObserver() {
            this.scrollObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && entry.intersectionRatio > 0.1) {
                        const { slug, title, prevSlug, nextSlug } = entry.target.dataset;
                        if (!slug) return;

                        const newUrl = `${this.baseUrl}/novels/${this.novelSlug}/read/${slug}`;
                        if (!window.location.pathname.includes(slug)) {
                            window.history.pushState({ slug }, '', newUrl);
                        }

                        document.title = `${this.novelTitle} - ${title} | {{ config('app.name') }}`;
                        this.currentChapterSlug  = slug;
                        this.currentChapterTitle = title;
                        this.prevChapterSlug     = prevSlug || '';
                        this.nextChapterSlug     = nextSlug || '';

                        const prevLink             = document.getElementById('sidebar-prev-link');
                        const nextLink             = document.getElementById('sidebar-next-link');
                        const mobilePrevLink       = document.getElementById('mobile-prev-link');
                        const mobileNextLink       = document.getElementById('mobile-next-link');
                        const sidebarMobilePrevLink = document.getElementById('sidebar-mobile-prev-link');
                        const sidebarMobileNextLink = document.getElementById('sidebar-mobile-next-link');

                        if (prevLink && prevSlug) prevLink.href = `${this.baseUrl}/novels/${this.novelSlug}/read/${prevSlug}`;
                        if (nextLink && nextSlug) nextLink.href = `${this.baseUrl}/novels/${this.novelSlug}/read/${nextSlug}`;
                        if (mobilePrevLink && prevSlug) mobilePrevLink.href = `${this.baseUrl}/novels/${this.novelSlug}/read/${prevSlug}`;
                        if (mobileNextLink && nextSlug) mobileNextLink.href = `${this.baseUrl}/novels/${this.novelSlug}/read/${nextSlug}`;
                        if (sidebarMobilePrevLink && prevSlug) sidebarMobilePrevLink.href = `${this.baseUrl}/novels/${this.novelSlug}/read/${prevSlug}`;
                        if (sidebarMobileNextLink && nextSlug) sidebarMobileNextLink.href = `${this.baseUrl}/novels/${this.novelSlug}/read/${nextSlug}`;
                    }
                });
            }, { threshold: [0.1, 0.5] });

            document.querySelectorAll('#chapters-container > div, .chapter-section').forEach(div => {
                this.scrollObserver.observe(div);
            });
        }
    };
};
</script>
@endpush