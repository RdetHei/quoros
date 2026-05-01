@extends('layouts.app')

@section('content')
<div x-data="reader({
    initialFontSize: localStorage.getItem('reader-font-size') || 'text-lg',
    initialFontFamily: localStorage.getItem('reader-font-family') || 'font-sans',
    nextChapterSlug: '{{ $nextChapter ? $nextChapter->slug : '' }}',
    novelSlug: '{{ $novel->slug }}',
    novelTitle: @js($novel->title),
    baseUrl: '{{ url('/') }}',
    protectChapter: @js($protectContent ?? false)
})" class="max-w-4xl mx-auto px-4 sm:px-0 pb-20">
    <!-- Reader Progress & Sticky Header (Mobile) -->
    <div class="fixed top-0 left-0 w-full h-1.5 bg-slate-200 dark:bg-slate-800 z-[60] md:hidden">
        <div class="h-full bg-indigo-600 transition-all duration-300 shadow-[0_0_10px_rgba(79,70,229,0.5)]" id="scroll-progress"></div>
    </div>

    <div id="pwa-offline-banner" class="hidden fixed top-14 md:top-20 left-1/2 -translate-x-1/2 z-[70] max-w-md w-[calc(100%-2rem)] px-4 py-2.5 rounded-xl bg-amber-950/95 border border-amber-700/50 text-amber-100 text-xs font-semibold text-center shadow-lg" role="status">
        Mode offline — bab yang sudah pernah dibuka dari perangkat ini tetap bisa dibaca. Muat bab baru perlu koneksi internet.
    </div>

    <!-- Chapter Navigation (Top) -->
    <div class="flex items-center justify-between mb-6 p-3 md:p-4 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <a href="{{ $previousChapter ? route('chapters.show', [$novel->slug, $previousChapter->slug]) : '#' }}" 
           class="flex items-center gap-1 md:gap-2 px-2 md:px-3 py-2 text-sm font-bold rounded-xl transition-all {{ $previousChapter ? 'text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' : 'text-slate-300 dark:text-slate-700 cursor-not-allowed' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            <span class="hidden sm:block">Sebelumnya</span>
        </a>

        <div class="text-center flex-1 mx-2 md:mx-4 min-w-0">
            <h2 class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5 truncate">{{ $novel->title }}</h2>
            <h1 class="text-xs md:text-lg font-extrabold text-slate-900 dark:text-white truncate">{{ $chapter->title }}</h1>
        </div>

        <div class="flex items-center gap-0.5 md:gap-1">
            <!-- Reader Settings Toggle -->
            <button @click="showSettings = !showSettings" 
                class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors relative"
                title="Pengaturan Baca">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </button>

            <a href="{{ $nextChapter ? route('chapters.show', [$novel->slug, $nextChapter->slug]) : '#' }}" 
               class="flex items-center gap-1 md:gap-2 px-2 md:px-3 py-2 text-sm font-bold rounded-xl transition-all {{ $nextChapter ? 'text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' : 'text-slate-300 dark:text-slate-700 cursor-not-allowed' }}">
                <span class="hidden sm:block">Selanjutnya</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" transform="rotate(180 10 10)" /></svg>
            </a>
        </div>
    </div>

    <!-- Reader Settings Panel -->
    <div x-show="showSettings" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="mb-6 p-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-xl"
         style="display: none;">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Font Size -->
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Ukuran Font</label>
                <div class="flex items-center gap-2">
                    <button @click="updateFontSize('text-base')" :class="fontSize === 'text-base' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="flex-1 py-2 rounded-xl text-sm font-bold transition-all">A</button>
                    <button @click="updateFontSize('text-lg')" :class="fontSize === 'text-lg' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="flex-1 py-2 rounded-xl text-base font-bold transition-all">A</button>
                    <button @click="updateFontSize('text-xl')" :class="fontSize === 'text-xl' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="flex-1 py-2 rounded-xl text-lg font-bold transition-all">A</button>
                    <button @click="updateFontSize('text-2xl')" :class="fontSize === 'text-2xl' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="flex-1 py-2 rounded-xl text-xl font-bold transition-all">A</button>
                </div>
            </div>
            <!-- Font Family -->
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Jenis Font</label>
                <div class="flex items-center gap-2">
                    <button @click="updateFontFamily('font-sans')" :class="fontFamily === 'font-sans' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="flex-1 py-2 rounded-xl text-sm font-sans transition-all">Sans</button>
                    <button @click="updateFontFamily('font-serif')" :class="fontFamily === 'font-serif' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="flex-1 py-2 rounded-xl text-sm font-serif transition-all">Serif</button>
                    <button @click="updateFontFamily('font-mono')" :class="fontFamily === 'font-mono' ? 'bg-indigo-600 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="flex-1 py-2 rounded-xl text-sm font-mono transition-all">Mono</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reading Area Container (anti-copy / watermark hanya di area ini, bukan komentar) -->
    <div id="chapters-container" class="@if($protectContent ?? false) select-none @endif">
        <!-- Current Chapter -->
        <div data-slug="{{ $chapter->slug }}" data-title="{{ $chapter->title }}">
            <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-12 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 mb-10">
                @if($protectContent ?? false)
                <div class="reader-chapter-shell" @contextmenu.prevent>
                @endif
                <article :class="[fontSize, fontFamily]" class="prose prose-slate dark:prose-invert max-w-none leading-relaxed md:leading-loose text-slate-800 dark:text-slate-200 transition-all duration-300">
                    {!! $chapterBodyHtml !!}
                </article>
                @if($protectContent ?? false)
                </div>
                @endif

                @if($chapter->file_path)
                    <div class="mt-12 p-6 md:p-8 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-2xl text-center">
                        <p class="text-indigo-700 dark:text-indigo-400 font-medium mb-4 text-base md:text-lg">Tersedia dalam format file untuk dibaca offline:</p>
                        <a href="{{ asset('storage/' . $chapter->file_path) }}" class="inline-flex items-center gap-3 px-6 md:px-8 py-3 md:py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none w-full sm:w-auto justify-center" target="_blank">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            Download Chapter File
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Autoload Trigger -->
    <div id="autoload-trigger" class="h-40 flex items-center justify-center mb-10 border-2 border-dashed border-slate-200 dark:border-slate-800 rounded-3xl">
        <template x-if="isLoading">
            <div class="flex items-center gap-3 text-indigo-600">
                <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="font-bold text-sm uppercase tracking-widest">Memuat Chapter Selanjutnya...</span>
            </div>
        </template>
        <template x-if="!isLoading && nextChapterSlug">
            <div class="text-slate-400 text-xs font-medium uppercase tracking-widest">Scroll untuk memuat chapter berikutnya</div>
        </template>
        <template x-if="!nextChapterSlug">
            <div class="text-slate-400 text-xs font-medium uppercase tracking-widest italic">Anda telah mencapai akhir novel</div>
        </template>
    </div>

    <!-- Chapter Navigation (Bottom) -->
    <div class="flex items-center justify-between mb-16 gap-2 md:gap-4">
        <a href="{{ $previousChapter ? route('chapters.show', [$novel->slug, $previousChapter->slug]) : '#' }}" 
           class="flex-1 flex items-center justify-center gap-2 px-3 md:px-4 py-3 md:py-4 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-indigo-600 transition-all shadow-sm {{ !$previousChapter ? 'opacity-50 cursor-not-allowed' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            <span class="hidden sm:inline">Sebelumnya</span>
        </a>

        <a href="{{ route('novels.show', $novel->slug) }}" class="p-3 md:p-4 bg-white dark:bg-slate-900 text-slate-400 hover:text-indigo-600 rounded-2xl border border-slate-200 dark:border-slate-800 transition-all shadow-sm shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 md:h-6 md:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
        </a>

        <a id="next-chapter-link" href="{{ $nextChapter ? route('chapters.show', [$novel->slug, $nextChapter->slug]) : '#' }}" 
           class="flex-1 flex items-center justify-center gap-2 px-3 md:px-4 py-3 md:py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none {{ !$nextChapter ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
            <span class="hidden sm:inline">Selanjutnya</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" transform="rotate(180 10 10)" /></svg>
        </a>
    </div>

    <!-- Comments Section -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-10 border border-slate-100 dark:border-slate-800 shadow-sm">
        <h2 class="text-xl md:text-2xl font-bold mb-8 flex items-center gap-3 text-slate-900 dark:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" /></svg>
            Diskusi ({{ $chapter->comments->count() }})
        </h2>

        @auth
            <form action="{{ route('comments.store', $chapter->id) }}" method="POST" class="mb-10">
                @csrf
                <div class="relative">
                    <textarea name="content" rows="3" 
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all placeholder-slate-400" 
                        placeholder="Bagikan pemikiranmu tentang chapter ini..."></textarea>
                    <div class="mt-3 flex justify-end">
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-none">Kirim Komentar</button>
                    </div>
                </div>
            </form>
        @else
            <div class="mb-10 p-6 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-center">
                <p class="text-slate-600 dark:text-slate-400 mb-4 font-medium text-sm">Ingin ikut berdiskusi?</p>
                <a href="{{ route('login') }}" class="inline-block w-full sm:w-auto px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-indigo-200 dark:shadow-none">Login Sekarang</a>
            </div>
        @endauth

        <div class="space-y-8">
            @forelse($chapter->comments as $comment)
                <div class="flex gap-3 md:gap-4 group">
                    <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}"
                       class="w-8 h-8 md:w-10 md:h-10 flex-shrink-0 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-xs md:text-sm hover:ring-2 hover:ring-indigo-500/40 transition-all"
                       title="Lihat profil">
                        @if($comment->user->profile_photo)
                            <img src="{{ asset('storage/' . $comment->user->profile_photo) }}" alt="" class="w-full h-full object-cover rounded-full">
                        @else
                            {{ substr($comment->user->name, 0, 1) }}
                        @endif
                    </a>
                    <div class="flex-grow">
                        <div class="flex items-center justify-between mb-1">
                            <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}" class="font-bold text-xs md:text-sm text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{{ $comment->user->name }}</a>
                            <span class="text-[9px] md:text-[10px] font-medium text-slate-400 uppercase tracking-widest">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-xs md:text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $comment->content }}</p>
                        
                        <div class="mt-3 flex items-center gap-4">
                            <!-- Reaction Buttons -->
                            <div class="flex items-center bg-slate-50 dark:bg-slate-800 rounded-lg p-1 border border-slate-100 dark:border-slate-700">
                                <form action="{{ route('reactions.toggle', ['type' => 'comment', 'id' => $comment->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="reaction_type" value="like">
                                    <button type="submit" class="flex items-center gap-1.5 px-2 py-1 rounded-md transition-all {{ $comment->likes->where('user_id', Auth::id())->first() ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30' : 'text-slate-500 hover:text-indigo-600' }}">
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

    @push('styles')
    <style>
        .reader-chapter-shell article,
        .reader-chapter-shell article * {
            -webkit-user-select: none;
            user-select: none;
        }
    </style>
    @endpush

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

        document.addEventListener('alpine:init', () => {
            Alpine.data('reader', (config) => ({
                fontSize: config.initialFontSize,
                fontFamily: config.initialFontFamily,
                showSettings: false,
                nextChapterSlug: config.nextChapterSlug,
                isLoading: false,
                novelSlug: config.novelSlug,
                novelTitle: config.novelTitle,
                baseUrl: config.baseUrl,
                protectChapter: config.protectChapter,

                init() {
                    this.setupScrollObserver();
                    if (this.protectChapter) {
                        const zone = document.getElementById('chapters-container');
                        if (zone) {
                            ['copy', 'cut'].forEach((ev) => {
                                zone.addEventListener(ev, (e) => e.preventDefault(), true);
                            });
                        }
                    }
                    
                    window.addEventListener('scroll', () => {
                        const winScroll = window.pageYOffset || document.documentElement.scrollTop;
                        const scrollHeight = document.documentElement.scrollHeight;
                        const clientHeight = document.documentElement.clientHeight;
                        const height = scrollHeight - clientHeight;
                        const scrolled = (winScroll / height) * 100;
                        
                        const progressBar = document.getElementById('scroll-progress');
                        if (progressBar) {
                            progressBar.style.width = scrolled + '%';
                        }

                        const distanceFromBottom = scrollHeight - (winScroll + clientHeight);
                        if (distanceFromBottom < 1000 && !this.isLoading && this.nextChapterSlug) {
                            this.loadNextChapter();
                        }
                    });
                },

                updateFontSize(size) {
                    this.fontSize = size;
                    localStorage.setItem('reader-font-size', size);
                    this.updateAllChapters();
                },

                updateFontFamily(family) {
                    this.fontFamily = family;
                    localStorage.setItem('reader-font-family', family);
                    this.updateAllChapters();
                },

                updateAllChapters() {
                    document.querySelectorAll('#chapters-container article').forEach(el => {
                        el.classList.remove('text-base', 'text-lg', 'text-xl', 'text-2xl', 'font-sans', 'font-serif', 'font-mono');
                        el.classList.add(this.fontSize, this.fontFamily);
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
                        
                        if (!response.ok) throw new Error('Network response was not ok');
                        
                        const data = await response.json();
                        
                        const chapterDiv = document.createElement('div');
                        chapterDiv.className = 'mt-20 pt-20 border-t border-slate-100 dark:border-slate-800 chapter-section';
                        chapterDiv.dataset.slug = data.chapter.slug;
                        chapterDiv.dataset.title = data.chapter.title;
                        
                        const shellOpen = this.protectChapter ? "<div class='reader-chapter-shell select-none' oncontextmenu='return false'>" : '';
                        const shellClose = this.protectChapter ? '</div>' : '';

                        chapterDiv.innerHTML = `
                            <div class='mb-10 text-center'>
                                <h2 class='text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1'>${data.novel.title}</h2>
                                <h1 class='text-xl md:text-2xl font-extrabold text-slate-900 dark:text-white'>${data.chapter.title}</h1>
                            </div>
                            <div class='bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-12 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800'>
                                ${shellOpen}
                                <article class='${this.fontSize} ${this.fontFamily} prose prose-slate dark:prose-invert max-w-none leading-relaxed md:leading-loose text-slate-800 dark:text-slate-200 transition-all duration-300 chapter-content-article'>
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
                        
                        // Set content safely to avoid breaking template literals
                        chapterDiv.querySelector('.chapter-content-article').innerHTML = data.chapter.content;
                        
                        document.getElementById('chapters-container').appendChild(chapterDiv);
                        this.nextChapterSlug = data.chapter.next_chapter_slug;
                        
                        this.scrollObserver.observe(chapterDiv);

                        const nextLink = document.getElementById('next-chapter-link');
                        if (nextLink) {
                            if (this.nextChapterSlug) {
                                nextLink.href = `${this.baseUrl}/novels/${this.novelSlug}/read/${this.nextChapterSlug}`;
                                nextLink.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                            } else {
                                nextLink.href = '#';
                                nextLink.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                            }
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
                                
                                if (slug && window.location.pathname.indexOf(slug) === -1) {
                                    const newUrl = `${this.baseUrl}/novels/${this.novelSlug}/read/${slug}`;
                                    window.history.pushState({ slug }, '', newUrl);
                                    document.title = `${this.novelTitle} - ${title} | {{ config('app.name') }}`;
                                }
                            }
                        });
                    }, { threshold: [0.1, 0.5] });

                    document.querySelectorAll('#chapters-container > div').forEach(div => {
                        this.scrollObserver.observe(div);
                    });
                }
            }));
        });
    </script>
    @endpush
@endsection
