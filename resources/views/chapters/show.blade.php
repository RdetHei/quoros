@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Chapter Navigation (Top) -->
    <div class="flex items-center justify-between mb-10 p-4 bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <a href="{{ $previousChapter ? route('chapters.show', [$novel->slug, $previousChapter->slug]) : '#' }}" 
           class="flex items-center gap-2 px-4 py-2 text-sm font-bold rounded-xl transition-all {{ $previousChapter ? 'text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' : 'text-slate-300 dark:text-slate-700 cursor-not-allowed' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            <span class="hidden sm:block">Sebelumnya</span>
        </a>

        <div class="text-center">
            <h2 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $novel->title }}</h2>
            <h1 class="text-lg font-extrabold text-slate-900 dark:text-white line-clamp-1">{{ $chapter->title }}</h1>
        </div>

        <a href="{{ $nextChapter ? route('chapters.show', [$novel->slug, $nextChapter->slug]) : '#' }}" 
           class="flex items-center gap-2 px-4 py-2 text-sm font-bold rounded-xl transition-all {{ $nextChapter ? 'text-indigo-600 dark:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' : 'text-slate-300 dark:text-slate-700 cursor-not-allowed' }}">
            <span class="hidden sm:block">Selanjutnya</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" transform="rotate(180 10 10)" /></svg>
        </a>
    </div>

    <!-- Reading Area -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 md:p-12 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 mb-10">
        <article class="prose prose-slate dark:prose-invert prose-lg md:prose-xl max-w-none leading-relaxed text-slate-800 dark:text-slate-200">
            {!! nl2br(e($chapter->content)) !!}
        </article>

        @if($chapter->file_path)
            <div class="mt-12 p-8 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-2xl text-center">
                <p class="text-indigo-700 dark:text-indigo-400 font-medium mb-4 text-lg">Tersedia dalam format file untuk dibaca offline:</p>
                <a href="{{ asset('storage/' . $chapter->file_path) }}" class="inline-flex items-center gap-3 px-8 py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none" target="_blank">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                    Download Chapter File
                </a>
            </div>
        @endif
    </div>

    <!-- Chapter Navigation (Bottom) -->
    <div class="flex items-center justify-between mb-16">
        <a href="{{ $previousChapter ? route('chapters.show', [$novel->slug, $previousChapter->slug]) : '#' }}" 
           class="flex items-center gap-3 px-6 py-4 bg-white dark:bg-slate-900 text-slate-700 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-indigo-600 transition-all shadow-sm {{ !$previousChapter ? 'opacity-50 cursor-not-allowed' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            Sebelumnya
        </a>

        <a href="{{ route('novels.show', $novel->slug) }}" class="p-4 bg-white dark:bg-slate-900 text-slate-400 hover:text-indigo-600 rounded-2xl border border-slate-200 dark:border-slate-800 transition-all shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
        </a>

        <a href="{{ $nextChapter ? route('chapters.show', [$novel->slug, $nextChapter->slug]) : '#' }}" 
           class="flex items-center gap-3 px-6 py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none {{ !$nextChapter ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
            Selanjutnya
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" transform="rotate(180 10 10)" /></svg>
        </a>
    </div>

    <!-- Comments Section -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 md:p-10 border border-slate-100 dark:border-slate-800 shadow-sm mb-20">
        <h2 class="text-2xl font-bold mb-8 flex items-center gap-3 text-slate-900 dark:text-white">
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
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl text-sm hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 dark:shadow-none">Kirim Komentar</button>
                    </div>
                </div>
            </form>
        @else
            <div class="mb-10 p-6 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-center">
                <p class="text-slate-600 dark:text-slate-400 mb-4 font-medium">Ingin ikut berdiskusi?</p>
                <a href="{{ route('login') }}" class="inline-block px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl text-sm transition-all shadow-lg shadow-indigo-200 dark:shadow-none">Login Sekarang</a>
            </div>
        @endauth

        <div class="space-y-8">
            @forelse($chapter->comments as $comment)
                <div class="flex gap-4 group">
                    <div class="w-10 h-10 flex-shrink-0 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-sm">
                        {{ substr($comment->user->name, 0, 1) }}
                    </div>
                    <div class="flex-grow">
                        <div class="flex items-center justify-between mb-1">
                            <a href="{{ route('profile.show', $comment->user->username ?? $comment->user->id) }}" class="font-bold text-sm text-slate-900 dark:text-white hover:text-indigo-600 transition-colors">{{ $comment->user->name }}</a>
                            <span class="text-[10px] font-medium text-slate-400 uppercase tracking-widest">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $comment->content }}</p>
                        
                        <div class="mt-3 flex items-center gap-4">
                            <!-- Reaction Buttons -->
                            <div class="flex items-center bg-slate-50 dark:bg-slate-800 rounded-lg p-1 border border-slate-100 dark:border-slate-700">
                                <form action="{{ route('reactions.toggle', ['type' => 'comment', 'id' => $comment->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="reaction_type" value="like">
                                    <button type="submit" class="flex items-center gap-1.5 px-2 py-1 rounded-md transition-all {{ $comment->likes->where('user_id', Auth::id())->first() ? 'text-indigo-600 bg-indigo-50 dark:bg-indigo-900/30' : 'text-slate-500 hover:text-indigo-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="{{ $comment->likes->where('user_id', Auth::id())->first() ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.757c1.246 0 2.256 1.01 2.256 2.256 0 .42-.116.83-.335 1.189l-2.723 4.856c-.466.83-1.34 1.343-2.285 1.343H10m4-9.644V7a3 3 0 00-3-3H9m1.5 14H7a3 3 0 01-3-3V10a3 3 0 013-3h2.5" />
                                        </svg>
                                        <span class="text-xs font-bold">{{ $comment->likes->count() }}</span>
                                    </button>
                                </form>
                                <div class="w-px h-4 bg-slate-200 dark:bg-slate-700 mx-1"></div>
                                <form action="{{ route('reactions.toggle', ['type' => 'comment', 'id' => $comment->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="reaction_type" value="dislike">
                                    <button type="submit" class="flex items-center gap-1.5 px-2 py-1 rounded-md transition-all {{ $comment->dislikes->where('user_id', Auth::id())->first() ? 'text-rose-600 bg-rose-50 dark:bg-rose-900/30' : 'text-slate-500 hover:text-rose-600' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="{{ $comment->dislikes->where('user_id', Auth::id())->first() ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.243c-1.246 0-2.256-1.01-2.256-2.256 0-.42.116-.83.335-1.189l2.723-4.856c.466-.83 1.34-1.343 2.285-1.343H14m-4 9.644V17a3 3 0 003 3h2m-1.5-14H17a3 3 0 013 3v7a3 3 0 01-3 3h-2.5" />
                                        </svg>
                                        <span class="text-xs font-bold">{{ $comment->dislikes->count() }}</span>
                                    </button>
                                </form>
                            </div>

                            @if(Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->id == $comment->user_id))
                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-slate-400 hover:text-red-500 text-[10px] font-bold uppercase tracking-widest transition-colors" onclick="return confirm('Hapus komentar ini?')">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-slate-500 italic">
                    Belum ada komentar. Jadilah yang pertama berkomentar!
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
