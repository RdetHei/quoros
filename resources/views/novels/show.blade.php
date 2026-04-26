@extends('layouts.app')

@section('content')
<div class="mb-12">
    <!-- Breadcrumbs -->
    <nav class="flex mb-8 text-sm font-medium text-slate-500 dark:text-slate-400" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li><a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Katalog</a></li>
            <li>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mx-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 md:ml-2 text-slate-900 dark:text-slate-100">{{ $novel->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Novel Header Card -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 md:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <div class="flex flex-col md:flex-row gap-10">
            <!-- Left: Cover -->
            <div class="w-full md:w-64 lg:w-72 flex-shrink-0">
                <div class="aspect-[3/4] rounded-2xl overflow-hidden shadow-2xl shadow-indigo-500/10 border border-slate-100 dark:border-slate-800">
                    @if($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover" alt="{{ $novel->title }}">
                    @else
                        <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-6 text-center">
                            <span class="text-slate-400 dark:text-slate-600 font-bold text-lg leading-tight">{{ $novel->title }}</span>
                        </div>
                    @endif
                </div>
                
                @if(Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->id == $novel->author_id))
                    <div class="mt-6 flex flex-col gap-2">
                        <a href="{{ route('writer.novels.edit', $novel->id) }}" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-center transition-colors shadow-lg shadow-amber-500/20">Edit Novel</a>
                        <a href="{{ route('writer.chapters.create', $novel->id) }}" class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl text-center transition-colors shadow-lg shadow-emerald-500/20">Tambah Chapter</a>
                    </div>
                @endif
            </div>

            <!-- Right: Info -->
            <div class="flex-grow">
                <div class="flex flex-wrap gap-2 mb-4">
                    @foreach($novel->genres as $genre)
                        <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-xs font-bold rounded-full border border-indigo-100 dark:border-indigo-800 uppercase tracking-wider">{{ $genre->name }}</span>
                    @endforeach
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-2 tracking-tight leading-tight">{{ $novel->title }}</h1>
                @if($novel->alternative_title)
                    <p class="text-lg font-medium text-slate-500 dark:text-slate-400 mb-6 italic">{{ $novel->alternative_title }}</p>
                @endif
                
                <div class="flex flex-wrap items-center gap-6 mb-8 text-sm font-medium">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold">
                            {{ substr($novel->author->name, 0, 1) }}
                        </div>
                        <span class="text-slate-700 dark:text-slate-300">{{ $novel->author->name }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-amber-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        <span class="font-bold">{{ number_format($novel->rating_avg, 1) }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-slate-500 dark:text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <span class="font-bold">{{ number_format($novel->view_count) }} Views</span>
                    </div>
                    <div class="text-slate-500 dark:text-slate-400">
                        {{ $novel->chapters->count() }} Chapter
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        @php
                            $statusColor = match($novel->status) {
                                'ongoing' => 'bg-emerald-500',
                                'hiatus' => 'bg-amber-500',
                                'complete' => 'bg-indigo-500',
                                default => 'bg-slate-500'
                            };
                            $typeColor = match($novel->type) {
                                'web_novel' => 'bg-amber-500',
                                'light_novel' => 'bg-blue-500',
                                'original' => 'bg-purple-500',
                                default => 'bg-slate-500'
                            };
                            $ratingColor = match($novel->content_rating) {
                                'everyone' => 'bg-emerald-500',
                                'teen' => 'bg-orange-500',
                                'mature' => 'bg-rose-500',
                                default => 'bg-slate-500'
                            };
                        @endphp
                        <div class="flex items-center gap-1.5 px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-full border border-slate-200 dark:border-slate-700">
                            <span class="w-2 h-2 rounded-full {{ $statusColor }}"></span>
                            <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ $novel->status }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-full border border-slate-200 dark:border-slate-700">
                            <span class="w-2 h-2 rounded-full {{ $typeColor }}"></span>
                            <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ str_replace('_', ' ', $novel->type) }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 bg-slate-100 dark:bg-slate-800 rounded-full border border-slate-200 dark:border-slate-700">
                            <span class="w-2 h-2 rounded-full {{ $ratingColor }}"></span>
                            <span class="text-[10px] font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">{{ $novel->content_rating }}</span>
                        </div>
                    </div>
                </div>

                <div class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-400 mb-8 leading-relaxed">
                    <p>{{ $novel->description ?: 'Belum ada deskripsi untuk novel ini.' }}</p>
                </div>

                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach($novel->tags as $tag)
                        <span class="text-xs font-medium text-slate-500 dark:text-slate-400 px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded-md">#{{ $tag->name }}</span>
                    @endforeach
                </div>

                <div class="flex gap-4">
                    @if($novel->chapters->isNotEmpty())
                        <a href="{{ route('chapters.show', [$novel->slug, $novel->chapters->first()->slug]) }}" class="px-8 py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none">Mulai Membaca</a>
                    @else
                        <button disabled class="px-8 py-4 bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-600 font-bold rounded-2xl cursor-not-allowed">Belum Ada Chapter</button>
                    @endif
                    
                    @auth
                        @php
                            $isBookmarked = Auth::user()->bookmarks()->where('novel_id', $novel->id)->exists();
                        @endphp
                        <form action="{{ route('bookmarks.toggle', $novel->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="p-4 {{ $isBookmarked ? 'bg-rose-500 text-white' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300' }} border border-slate-200 dark:border-slate-700 rounded-2xl hover:border-rose-500 transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ $isBookmarked ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="p-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl text-slate-600 dark:text-slate-300 hover:border-indigo-600 transition-colors shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Detail Information Section -->
        <div class="mt-10 pt-10 border-t border-slate-100 dark:border-slate-800">
            <h2 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">Informasi Detail</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Judul Alternatif</p>
                    <p class="text-sm font-medium text-slate-700 dark:text-slate-300 italic">{{ $novel->alternative_title ?: '-' }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Jenis Novel</p>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">{{ str_replace('_', ' ', $novel->type) }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Rating Konten</p>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wide">{{ $novel->content_rating }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">Total Tayangan</p>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ number_format($novel->view_count) }} Views</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs for Chapters & Reviews -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
    <!-- Chapters List -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-100 dark:border-slate-800 shadow-sm">
            <h2 class="text-2xl font-bold mb-8 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" /></svg>
                Daftar Chapter
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @forelse($novel->chapters as $chapter)
                    <div class="group flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 border border-transparent hover:border-indigo-200 dark:hover:border-indigo-800 rounded-2xl transition-all">
                        <a href="{{ route('chapters.show', [$novel->slug, $chapter->slug]) }}" class="flex-grow flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="font-medium text-slate-700 dark:text-slate-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $chapter->title }}</span>
                                @if(Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->id == $novel->author_id))
                                    <div class="flex items-center gap-2 mt-1">
                                        @if($chapter->status === 'draft')
                                            <span class="px-1.5 py-0.5 bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 text-[8px] font-bold rounded uppercase tracking-widest">Draft</span>
                                        @elseif($chapter->status === 'scheduled')
                                            <span class="px-1.5 py-0.5 bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[8px] font-bold rounded uppercase tracking-widest">Scheduled: {{ $chapter->published_at->format('d/m/y H:i') }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </a>
                        
                        @if(Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->id == $novel->author_id))
                            <div class="flex items-center gap-2 ml-4">
                                <a href="{{ route('writer.chapters.edit', [$novel->id, $chapter->id]) }}" class="p-2 text-amber-500 hover:bg-amber-100 dark:hover:bg-amber-900/30 rounded-lg transition-colors" title="Edit Chapter">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" /></svg>
                                </a>
                                <form action="{{ route('writer.chapters.destroy', [$novel->id, $chapter->id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-rose-500 hover:bg-rose-100 dark:hover:bg-rose-900/30 rounded-lg transition-colors" onclick="return confirm('Hapus chapter ini?')" title="Hapus Chapter">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </button>
                                </form>
                            </div>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 group-hover:text-indigo-600 transition-colors transform group-hover:translate-x-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        @endif
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-500">
                        Belum ada chapter tersedia.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar: Reviews -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-100 dark:border-slate-800 shadow-sm sticky top-24">
            <h2 class="text-2xl font-bold mb-8 flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" /></svg>
                Ulasan
            </h2>

            @auth
                <form action="{{ route('reviews.store', $novel->id) }}" method="POST" class="mb-8 p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Rating</label>
                        <select name="rating" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="5">⭐⭐⭐⭐⭐ (Sangat Bagus)</option>
                            <option value="4">⭐⭐⭐⭐ (Bagus)</option>
                            <option value="3">⭐⭐⭐ (Lumayan)</option>
                            <option value="2">⭐⭐ (Kurang)</option>
                            <option value="1">⭐ (Buruk)</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <textarea name="content" rows="3" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-slate-400" placeholder="Apa pendapatmu tentang novel ini?"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-bold rounded-xl text-sm hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-500/10">Kirim Ulasan</button>
                </form>
            @else
                <div class="mb-8 p-6 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 rounded-2xl text-center">
                    <p class="text-sm text-indigo-700 dark:text-indigo-400 mb-4 font-medium">Masuk untuk memberikan ulasan.</p>
                    <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-indigo-600 text-white font-bold rounded-xl text-xs uppercase tracking-wider">Login</a>
                </div>
            @endauth

            <div class="space-y-6">
                @forelse($novel->reviews as $review)
                    <div class="border-b border-slate-100 dark:border-slate-800 pb-6 last:border-0 last:pb-0">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-bold text-sm">{{ $review->user->name }}</span>
                            <div class="flex text-amber-500 text-[10px]">
                                @for($i = 0; $i < 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="{{ $i < $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 italic">"{{ $review->content }}"</p>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-[10px] text-slate-400">{{ $review->created_at->diffForHumans() }}</span>
                            @if(Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->id == $review->user_id))
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600 text-[10px] font-bold uppercase tracking-widest" onclick="return confirm('Hapus ulasan ini?')">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500 italic text-center py-4">Belum ada ulasan.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Recommendations: Novel Serupa -->
@if($similarNovels->count() > 0)
    <div class="mt-16 mb-20">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Novel Serupa</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Berdasarkan kesamaan genre dan tag novel ini.</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($similarNovels as $similar)
                <a href="{{ route('novels.show', $similar->slug) }}" class="group block">
                    <div class="relative aspect-[3/4] rounded-2xl overflow-hidden mb-3 shadow-lg shadow-slate-200/50 dark:shadow-none transition-all duration-300 group-hover:-translate-y-2 group-hover:shadow-xl group-hover:shadow-indigo-200/40 dark:group-hover:shadow-indigo-900/20">
                        @if($similar->cover_image)
                            <img src="{{ asset('storage/' . $similar->cover_image) }}" alt="{{ $similar->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        @else
                            <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                            <span class="text-[10px] font-bold text-white uppercase tracking-widest bg-indigo-600 w-fit px-2 py-1 rounded-md mb-2">Lihat Detail</span>
                        </div>
                    </div>
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm line-clamp-2 group-hover:text-indigo-600 transition-colors">{{ $similar->title }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        {{ $similar->author->name }}
                    </p>
                </a>
            @endforeach
        </div>
    </div>
@endif
@endsection
