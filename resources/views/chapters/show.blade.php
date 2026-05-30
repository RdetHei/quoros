@extends('layouts.app')

@push('styles')
<style>
    /* Hide main navbar for immersive reading */
    #navbar {
        display: none !important;
    }
    
    /* Reader Sidebar Styles */
    .reader-sidebar {
        position: fixed;
        right: 2rem;
        top: 50%;
        transform: translateY(-50%);
        z-index: 100;
        width: 4.5rem;
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 1.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1.25rem 0.75rem;
        gap: 1.25rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @media (max-width: 1024px) {
        .reader-sidebar {
            right: 1rem;
            width: 4rem;
        }
    }

    @media (max-width: 640px) {
        .reader-sidebar {
            top: 0;
            right: 0;
            bottom: 0;
            left: auto;
            transform: none;
            width: 80vw;
            max-width: 300px;
            height: 100dvh;
            flex-direction: column;
            padding: 2rem 1.5rem;
            border-radius: 2rem 0 0 2rem;
            gap: 1.5rem;
            border-right: none;
            overflow-y: auto;
        }

        .reader-sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(4px);
            z-index: 90;
        }
    }

    .sidebar-btn {
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 1rem;
        background: #334155;
        color: #f1f5f9;
        border: 1px solid transparent;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        flex-shrink: 0;
    }

    @media (max-width: 640px) {
        .sidebar-btn {
            width: 100%;
            height: 3.5rem;
            justify-content: flex-start;
            padding: 0 1.25rem;
            gap: 1rem;
            border-radius: 1.25rem;
        }

        .sidebar-btn span {
            display: inline !important;
            font-size: 0.875rem;
            font-weight: 600;
        }
    }

    .sidebar-btn span {
        display: none;
    }

    .sidebar-btn:hover {
        background: #475569;
        color: #ffffff;
        transform: translateY(-2px);
    }

    @media (max-width: 640px) {
        .sidebar-btn:hover {
            transform: translateX(-4px);
        }
    }

    .sidebar-btn.active {
        background: #ffffff;
        color: #0f172a;
    }

    .sidebar-btn.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }

    .sidebar-divider {
        width: 100%;
        height: 1px;
        background: #334155;
        flex-shrink: 0;
    }

    .sidebar-panel {
        position: absolute;
        right: 100%;
        bottom: 0;
        margin-right: 1.5rem;
        width: 18rem;
        background: #1e293b;
        border: 1px solid #334155;
        border-radius: 1.5rem;
        padding: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        z-index: 110;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #0f172a;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #334155;
        border-radius: 10px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #475569;
    }

    @media (max-width: 640px) {
        .sidebar-panel {
            position: fixed;
            inset: auto 1rem 1rem 1rem;
            width: auto;
            margin: 0;
            bottom: 1rem;
            right: 1rem;
            left: 1rem;
            z-index: 120;
        }
    }

    /* Tighten line spacing in prose */
    .prose {
        line-height: 1.625 !important;
    }

    .reader-chapter-shell {
        user-select: none;
        -webkit-user-select: none;
    }

    [x-cloak] {
        display: none !important;
    }
</style>
@endpush

@section('content')
<div x-data="reader(@js([
    'nextChapterSlug' => $nextChapter ? $nextChapter->slug : '',
    'prevChapterSlug' => $previousChapter ? $previousChapter->slug : '',
    'currentChapterSlug' => $chapter->slug,
    'allChapters' => $allChapters,
    'novelSlug' => $novel->slug,
    'novelTitle' => $novel->title,
    'baseUrl' => url('/'),
    'protectChapter' => $protectContent ?? false
]))" class="max-w-4xl mx-auto px-4 sm:px-0 pb-20 pt-10">
    <!-- Reader Progress -->
    <div class="fixed top-0 left-0 w-full h-1.5 bg-slate-200 dark:bg-slate-800 z-[60]">
        <div class="h-full bg-indigo-500 transition-all duration-300 shadow-[0_0_10px_rgba(99,102,241,0.5)]" id="scroll-progress"></div>
    </div>

    <!-- Reader Sidebar Overlay (Mobile) -->
    <div class="reader-sidebar-overlay md:hidden" 
         x-show="sidebarOpen" 
         @click="sidebarOpen = false; showSettings = false; showChapters = false"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak></div>

    <!-- Reader Sidebar (Rectangular Design) -->
    <div class="reader-sidebar" 
         x-show="!isLoading && (!isMobile || sidebarOpen)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10 sm:translate-y-0 sm:translate-x-10"
         x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:translate-x-0"
         x-transition:leave-end="opacity-0 translate-y-10 sm:translate-y-0 sm:translate-x-10"
         x-cloak>
        
        <!-- Mobile Header (Visible only on mobile) -->
        <div class="flex items-center gap-3 mb-4 md:hidden">
            <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-8 w-auto" onerror="this.onerror=null; this.src='/error.png'">
            <div class="leading-tight">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Navigasi</p>
                <p class="text-sm font-bold text-white">Menu Baca</p>
            </div>
        </div>

        <!-- Back to Novel -->
        <a href="{{ route('novels.show', $novel->slug) }}" class="sidebar-btn" title="Kembali ke Novel">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Kembali ke Novel</span>
        </a>

        <div class="sidebar-divider"></div>

        <!-- Previous Chapter -->
        <a id="sidebar-prev-link" 
           href="{{ $previousChapter ? route('chapters.show', [$novel->slug, $previousChapter->slug]) : '#' }}" 
           class="sidebar-btn" 
           :class="!prevChapterSlug ? 'disabled' : ''"
           title="Bab Sebelumnya">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Bab Sebelumnya</span>
        </a>

        <!-- Chapter Selector Toggle -->
        <button type="button" 
                @click="showChapters = !showChapters; showSettings = false"
                :class="showChapters ? 'active' : ''"
                class="sidebar-btn" title="Pilih Chapter">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span>Pilih Chapter</span>
        </button>

        <!-- Next Chapter -->
        <a id="sidebar-next-link" 
           href="{{ $nextChapter ? route('chapters.show', [$novel->slug, $nextChapter->slug]) : '#' }}" 
           class="sidebar-btn"
           :class="!nextChapterSlug ? 'disabled' : ''"
           title="Bab Berikutnya">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            <span>Bab Berikutnya</span>
        </a>

        <div class="sidebar-divider"></div>

        <!-- Settings Toggle -->
        <button type="button"
                @click="showSettings = !showSettings; showChapters = false" 
                :class="showSettings ? 'active' : ''"
                class="sidebar-btn" title="Pengaturan Baca">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Pengaturan Baca</span>
        </button>

        <!-- Mobile Close (Visible only on mobile) -->
        <button type="button" @click="sidebarOpen = false" class="mt-auto sidebar-btn bg-slate-800 md:hidden">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            <span>Tutup Menu</span>
        </button>

        <!-- Chapter Selector Panel -->
        <div x-show="showChapters" 
             @click.away="showChapters = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             class="sidebar-panel" x-cloak>
            <h3 class="text-white font-bold mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z" />
                    </svg>
                    Pilih Chapter
                </div>
                <button @click="showChapters = false" class="md:hidden p-1 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </h3>
            <div class="max-h-[50vh] md:max-h-[60vh] overflow-y-auto pr-2 custom-scrollbar">
                <div class="grid gap-2">
                    <template x-for="ch in allChapters" :key="ch.slug">
                        <a :href="'{{ url('/novels/' . $novel->slug) }}/' + ch.slug" 
                           class="block p-3 rounded-xl text-sm transition-all"
                           :class="currentChapterSlug === ch.slug ? 'bg-indigo-600 text-white' : 'bg-slate-800 text-slate-400 hover:bg-slate-700 hover:text-white'">
                            <span x-text="ch.title"></span>
                        </a>
                    </template>
                </div>
            </div>
        </div>

        <!-- Settings Panel -->
        <div x-show="showSettings" 
             @click.away="showSettings = false"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             class="sidebar-panel" x-cloak>
            <div class="flex items-center justify-between mb-4 md:hidden">
                <h3 class="text-white font-bold">Pengaturan Baca</h3>
                <button @click="showSettings = false" class="p-1 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Ukuran Font</label>
                    <div class="flex items-center gap-1.5">
                        <template x-for="size in ['text-base', 'text-lg', 'text-xl', 'text-2xl']">
                            <button @click="updateFontSize(size)" 
                                    :class="fontSize === size ? 'bg-white text-slate-950' : 'bg-slate-800 text-slate-400 hover:bg-slate-700'"
                                    class="flex-1 h-10 rounded-xl text-sm font-bold transition-all flex items-center justify-center"
                                    x-text="size === 'text-base' ? 'A' : (size === 'text-lg' ? 'A+' : (size === 'text-xl' ? 'A++' : 'A+++'))"></button>
                        </template>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3">Jenis Font</label>
                    <div class="flex flex-col gap-1.5">
                        <template x-for="family in [{id: 'font-sans', label: 'Sans Serif'}, {id: 'font-serif', label: 'Serif'}, {id: 'font-mono', label: 'Monospace'}]">
                            <button @click="updateFontFamily(family.id)" 
                                    :class="[fontFamily === family.id ? 'bg-white text-slate-950' : 'bg-slate-800 text-slate-400 hover:bg-slate-700', family.id]"
                                    class="w-full h-10 rounded-xl text-xs font-bold transition-all px-4 text-left flex items-center justify-between"
                                    x-text="family.label"></button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Settings Panel (Removed redundant one, consolidated above) -->

    <!-- Title Header -->
    <div class="text-center mb-12">
        <h2 class="text-xs font-bold text-slate-500 uppercase tracking-[0.2em] mb-3">{{ $novel->title }}</h2>
        <h1 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white leading-tight tracking-tight">{{ $chapter->title }}</h1>
    </div>

    <!-- Reading Area -->
    <div id="chapters-container" class="-mx-4 sm:mx-0">
        <div data-slug="{{ $chapter->slug }}" data-title="{{ $chapter->title }}" data-prev-slug="{{ $previousChapter ? $previousChapter->slug : '' }}" data-next-slug="{{ $nextChapter ? $nextChapter->slug : '' }}">
            <div class="bg-white dark:bg-slate-900 sm:rounded-[2.5rem] p-6 md:p-16 shadow-2xl shadow-slate-200/50 dark:shadow-none border-y sm:border border-slate-100 dark:border-slate-800 mb-10">
                <div class="{{ ($protectContent ?? false) ? 'reader-chapter-shell' : '' }}" @if($protectContent ?? false) @contextmenu.prevent @endif>
                    <article :class="[fontSize, fontFamily]" class="prose prose-slate dark:prose-invert max-w-none text-slate-800 dark:text-slate-200 transition-all duration-300">
                        {!! $chapterBodyHtml !!}
                    </article>
                </div>

                @if($chapter->file_path)
                    <div class="mt-16 pt-10 border-t border-slate-100 dark:border-slate-800 text-center">
                        <a href="{{ asset('storage/' . $chapter->file_path) }}" class="inline-flex items-center gap-3 px-8 py-4 bg-slate-950 dark:bg-white text-white dark:text-slate-950 font-bold rounded-2xl hover:opacity-90 transition-all shadow-xl shadow-slate-950/10 w-full sm:w-auto justify-center" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            Download Chapter File
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Autoload Trigger -->
    <div id="autoload-trigger" class="h-40 flex items-center justify-center mb-20 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-[2.5rem]">
        <template x-if="isLoading">
            <div class="flex items-center gap-3 text-slate-800 dark:text-white">
                <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="font-bold text-sm uppercase tracking-widest">Memuat Bab Selanjutnya...</span>
            </div>
        </template>
        <template x-if="!isLoading && nextChapterSlug">
            <div class="text-slate-400 text-xs font-medium uppercase tracking-widest">Scroll untuk memuat bab berikutnya</div>
        </template>
        <template x-if="!nextChapterSlug">
            <div class="text-slate-400 text-xs font-medium uppercase tracking-widest italic">Akhir dari Novel</div>
        </template>
    </div>

    <!-- Comments Section -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-10 border border-slate-100 dark:border-slate-800 shadow-sm">
        <h2 class="text-xl md:text-2xl font-bold mb-8 flex items-center gap-3 text-slate-900 dark:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
            Diskusi ({{ $chapter->comments->count() }})
        </h2>

        @auth
            <form action="{{ route('comments.store', $chapter->id) }}" method="POST" class="mb-10">
                @csrf
                <div class="relative">
                    <textarea name="content" rows="3" 
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-slate-500 focus:bg-white dark:focus:bg-slate-900 transition-all placeholder-slate-400" 
                        placeholder="Bagikan pemikiranmu tentang chapter ini..."></textarea>
                    <div class="mt-3 flex justify-end">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl text-sm hover:bg-slate-800 dark:hover:bg-slate-100 transition-all shadow-lg shadow-slate-900/10">Kirim Komentar</button>
                    </div>
                </div>
            </form>
        @else
            <div class="mb-10 p-6 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-center">
                <p class="text-slate-600 dark:text-slate-400 mb-4 font-medium text-sm">Ingin ikut berdiskusi?</p>
                <a href="{{ route('login') }}" class="inline-block w-full sm:w-auto px-8 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl text-sm transition-all shadow-lg shadow-slate-900/10">Login Sekarang</a>
            </div>
        @endauth

        <div class="space-y-8">
            @forelse($chapter->comments as $comment)
                <div class="flex gap-3 md:gap-4 group">
                    <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}"
                       class="w-8 h-8 md:w-10 md:h-10 flex-shrink-0 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold text-xs md:text-sm hover:ring-2 hover:ring-slate-500/40 transition-all"
                       title="Lihat profil">
                        @if($comment->user->profile_photo)
                            <img src="{{ asset('storage/' . $comment->user->profile_photo) }}" alt="" class="w-full h-full object-cover rounded-full" onerror="this.onerror=null; this.src='/error.png'">
                        @else
                            {{ substr($comment->user->name, 0, 1) }}
                        @endif
                    </a>
                    <div class="flex-grow">
                        <div class="flex items-center justify-between mb-1">
                            <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}" class="font-bold text-xs md:text-sm text-slate-900 dark:text-white hover:text-slate-900 dark:hover:text-white transition-colors">{{ $comment->user->name }}</a>
                            <span class="text-[9px] md:text-[10px] font-medium text-slate-400 uppercase tracking-widest">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $comment->content }}</p>
                        
                        <div class="mt-3 flex items-center gap-4">
                            <div class="flex items-center bg-slate-50 dark:bg-slate-800 rounded-lg p-1 border border-slate-100 dark:border-slate-700">
                                <form action="{{ route('reactions.toggle', ['type' => 'comment', 'id' => $comment->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="reaction_type" value="like">
                                    <button type="submit" class="flex items-center gap-1.5 px-2 py-1 rounded-md transition-all {{ $comment->likes->where('user_id', Auth::id())->first() ? 'text-slate-900 dark:text-white bg-slate-100 dark:bg-slate-700 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="{{ $comment->likes->where('user_id', Auth::id())->first() ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.757c1.246 0 2.256 1.01 2.256 2.256 0 .42-.116.83-.335 1.189l-2.723 4.856c-.466.83-1.34 1.343-2.285 1.343H10m4-9.644V7a3 3 0 00-3-3H9m1.5 14H7a3 3 0 01-3-3V10a3 3 0 013-3h2.5" />
                                        </svg>
                                        <span class="text-[10px] font-bold">{{ $comment->likes->count() }}</span>
                                    </button>
                                </form>
                                <div class="w-px h-3 bg-slate-200 dark:bg-slate-700 mx-1"></div>
                                <form action="{{ route('reactions.toggle', ['type' => 'comment', 'id' => $comment->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="reaction_type" value="dislike">
                                    <button type="submit" class="flex items-center gap-1.5 px-2 py-1 rounded-md transition-all {{ $comment->dislikes->where('user_id', Auth::id())->first() ? 'text-rose-600 bg-rose-50 dark:bg-rose-900/30' : 'text-slate-500 hover:text-rose-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="{{ $comment->dislikes->where('user_id', Auth::id())->first() ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.243c-1.246 0-2.256-1.01-2.256-2.256 0-.42.116-.83.335-1.189l2.723-4.856c.466-.83 1.34-1.343 2.285-1.343H14m-4 9.644V17a3 3 0 003 3h2m-1.5-14H17a3 3 0 013 3v7a3 3 0 01-3 3h-2.5" />
                                        </svg>
                                        <span class="text-[10px] font-bold">{{ $comment->dislikes->count() }}</span>
                                    </button>
                                </form>
                            </div>

                            @can('delete', $comment)
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-500 text-[9px] md:text-[10px] font-bold uppercase tracking-widest transition-colors" onclick="return confirm('Hapus komentar ini?')">Hapus</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-slate-500 italic text-sm">
                    Belum ada komentar. Jadilah yang pertama berkomentar!
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const el = document.getElementById('pwa-offline-banner');
        if (!el) return;
        function sync() {
            el.classList.toggle('hidden', navigator.onLine);
        }
        sync();
        window.addEventListener('online', sync);
        window.addEventListener('offline', sync);
    })();

    // Reader Component Function (Global scope for Alpine.js)
    window.reader = function(config = {}) {
        // Safe localStorage access
        let initialFontSize = 'text-lg';
        let initialFontFamily = 'font-sans';
        
        try {
            initialFontSize = localStorage.getItem('reader-font-size') || 'text-lg';
            initialFontFamily = localStorage.getItem('reader-font-family') || 'font-sans';
        } catch (e) {
            console.warn('LocalStorage access failed:', e);
        }

        return {
            fontSize: initialFontSize,
            fontFamily: initialFontFamily,
            showSettings: false,
            showChapters: false,
            sidebarOpen: false,
            nextChapterSlug: config.nextChapterSlug || '',
            prevChapterSlug: config.prevChapterSlug || '',
            currentChapterSlug: config.currentChapterSlug || '',
            allChapters: config.allChapters || [],
            isLoading: false,
            isMobile: window.innerWidth <= 640,
            novelSlug: config.novelSlug || '',
            novelTitle: config.novelTitle || '',
            baseUrl: config.baseUrl || '',
            protectChapter: config.protectChapter || false,
            scrollObserver: null,

            init() {
                // Clean baseUrl to avoid double slashes
                this.baseUrl = (this.baseUrl || '').replace(/\/$/, '');

                // Sincronize view with current state
                this.$watch('fontSize', () => this.updateAllChapters());
                this.$watch('fontFamily', () => this.updateAllChapters());
                
                // Reactive screen width
                window.addEventListener('resize', () => {
                    this.isMobile = window.innerWidth <= 640;
                });
                
                this.updateAllChapters();
                this.setupScrollObserver();
                this.setupAutoloadObserver();
                this.setupTouchGestures();
                
                if (this.protectChapter) {
                    const zone = document.getElementById('chapters-container');
                    if (zone) {
                        ['copy', 'cut', 'contextmenu'].forEach((ev) => {
                            zone.addEventListener(ev, (e) => {
                                if (this.protectChapter) e.preventDefault();
                            }, true);
                        });
                    }
                }
                
                window.addEventListener('scroll', () => {
                    const winScroll = window.pageYOffset || document.documentElement.scrollTop;
                    const scrollHeight = document.documentElement.scrollHeight;
                    const clientHeight = document.documentElement.clientHeight;
                    const height = scrollHeight - clientHeight;
                    
                    if (height > 0) {
                        const scrolled = (winScroll / height) * 100;
                        const progressBar = document.getElementById('scroll-progress');
                        if (progressBar) {
                            progressBar.style.width = scrolled + '%';
                        }
                    }
                }, { passive: true });
            },

            setupAutoloadObserver() {
                const trigger = document.getElementById('autoload-trigger');
                if (!trigger) return;

                const observer = new IntersectionObserver((entries) => {
                    if (entries[0].isIntersecting && !this.isLoading && this.nextChapterSlug) {
                        this.loadNextChapter();
                    }
                }, { rootMargin: '400px' }); // Trigger when 400px from viewport

                observer.observe(trigger);
            },

            setupTouchGestures() {
                // Hanya aktifkan di layar mobile
                if (window.innerWidth > 640) return;

                let touchStartX = 0;
                let touchEndX = 0;

                window.addEventListener('touchstart', (e) => {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });

                window.addEventListener('touchend', (e) => {
                    touchEndX = e.changedTouches[0].screenX;
                    const swipeDistance = touchStartX - touchEndX;
                    const minSwipeDistance = 50;

                    // Swipe dari kanan ke kiri (Buka sidebar)
                    if (swipeDistance > minSwipeDistance) {
                        if (!this.sidebarOpen) {
                            this.sidebarOpen = true;
                        }
                    } 
                    // Swipe dari kiri ke kanan (Tutup sidebar)
                    else if (swipeDistance < -minSwipeDistance) {
                        if (this.sidebarOpen) {
                            this.sidebarOpen = false;
                            this.showSettings = false;
                            this.showChapters = false;
                        }
                    }
                }, { passive: true });
            },

            updateFontSize(size) {
                this.fontSize = size;
                try {
                    localStorage.setItem('reader-font-size', size);
                } catch (e) {}
            },

            updateFontFamily(family) {
                this.fontFamily = family;
                try {
                    localStorage.setItem('reader-font-family', family);
                } catch (e) {}
            },

            updateAllChapters() {
                // Wait for Alpine to update the DOM
                this.$nextTick(() => {
                    document.querySelectorAll('#chapters-container article').forEach(el => {
                        el.classList.remove('text-base', 'text-lg', 'text-xl', 'text-2xl', 'font-sans', 'font-serif', 'font-mono');
                        el.classList.add(this.fontSize, this.fontFamily);
                    });
                });
            },

            async loadNextChapter() {
                if (!this.nextChapterSlug || this.isLoading) return;
                
                this.isLoading = true;
                try {
                    const url = `${this.baseUrl}/novels/${this.novelSlug}/read/${this.nextChapterSlug}`;
                    
                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        if (response.status === 404) {
                            this.nextChapterSlug = null; // No more chapters
                        }
                        throw new Error('Network response was not ok');
                    }
                    
                    const data = await response.json();
                    
                    if (!data.chapter || !data.chapter.content) {
                        this.nextChapterSlug = null;
                        return;
                    }
                    
                    const chapterDiv = document.createElement('div');
                    chapterDiv.className = 'mt-20 pt-20 border-t border-slate-100 dark:border-slate-800 chapter-section';
                    chapterDiv.dataset.slug = data.chapter.slug;
                    chapterDiv.dataset.title = data.chapter.title;
                    chapterDiv.dataset.prevSlug = data.chapter.prev_chapter_slug || '';
                    chapterDiv.dataset.nextSlug = data.chapter.next_chapter_slug || '';
                    
                    const shellOpen = this.protectChapter ? "<div class='reader-chapter-shell'>" : '<div>';
                    const shellClose = '</div>';

                    chapterDiv.innerHTML = `
                        <div class='mb-12 text-center'>
                            <h2 class='text-xs font-bold text-slate-500 uppercase tracking-[0.2em] mb-3'>${data.novel.title}</h2>
                            <h1 class='text-3xl md:text-5xl font-black text-slate-900 dark:text-white leading-tight tracking-tight'>${data.chapter.title}</h1>
                        </div>
                        <div class='bg-white dark:bg-slate-900 sm:rounded-[2.5rem] p-6 md:p-16 shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800'>
                            ${shellOpen}
                            <article class='${this.fontSize} ${this.fontFamily} prose prose-slate dark:prose-invert max-w-none text-slate-800 dark:text-slate-200 transition-all duration-300 chapter-content-article'>
                            </article>
                            ${shellClose}
                            <div class='mt-10 pt-10 border-t border-slate-100 dark:border-slate-800'>
                                <a href='${url}' class='text-sm font-bold text-indigo-600 hover:text-indigo-700 flex items-center gap-2'>
                                    <svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z' /></svg>
                                    Diskusi (${data.chapter.comments_count})
                                </a>
                            </div>
                        </div>
                    `;
                    
                    chapterDiv.querySelector('.chapter-content-article').innerHTML = data.chapter.content;
                    document.getElementById('chapters-container').appendChild(chapterDiv);
                    
                    // Update states from response
                    this.nextChapterSlug = data.chapter.next_chapter_slug;
                    if (data.all_chapters) {
                        this.allChapters = data.all_chapters;
                    }
                    
                    // Re-observe new content
                    if (this.scrollObserver) {
                        this.scrollObserver.observe(chapterDiv);
                    }

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
                            const slug = entry.target.dataset.slug;
                            const title = entry.target.dataset.title;
                            const prevSlug = entry.target.dataset.prevSlug;
                            const nextSlug = entry.target.dataset.nextSlug;
                            
                            if (slug && window.location.pathname.indexOf(slug) === -1) {
                                const newUrl = `${this.baseUrl}/novels/${this.novelSlug}/read/${slug}`;
                                window.history.pushState({ slug }, '', newUrl);
                                document.title = `${this.novelTitle} - ${title} | {{ config('app.name') }}`;

                                // Update Alpine state for sidebar reactivity
                                this.currentChapterSlug = slug;
                                this.prevChapterSlug = prevSlug;
                                this.nextChapterSlug = nextSlug;

                                // Update Sidebar Hrefs manually for standard anchor tags if needed, 
                                // but Alpine :href should handle it if we use it correctly.
                                const prevLink = document.getElementById('sidebar-prev-link');
                                const nextLink = document.getElementById('sidebar-next-link');

                                if (prevLink && prevSlug) {
                                    prevLink.href = `${this.baseUrl}/novels/${this.novelSlug}/read/${prevSlug}`;
                                }
                                if (nextLink && nextSlug) {
                                    nextLink.href = `${this.baseUrl}/novels/${this.novelSlug}/read/${nextSlug}`;
                                }
                            }
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
