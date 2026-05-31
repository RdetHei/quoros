@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        @include('partials.writer-nav', ['active' => 'novels'])

        <div class="lg:col-span-9 space-y-6">
            {{-- Page header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-1.5 h-10 bg-indigo-600 rounded-full shrink-0"></div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Novel Saya</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Kelola dan pantau semua karya yang telah dipublikasikan.</p>
                    </div>
                </div>
                <a href="{{ route('writer.novels.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Buat Novel
                </a>
            </div>

            @if(session('success'))
                <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-sm font-medium text-emerald-700 dark:text-emerald-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Summary stats --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Total Novel</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($summary['novel_count']) }}</p>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Total Chapter</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($summary['chapter_count']) }}</p>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Total Views</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($summary['total_views']) }}</p>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4">
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-1">Total Bookmark</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($summary['total_bookmarks']) }}</p>
                </div>
            </div>

            {{-- Novel list --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                @forelse($novels as $novel)
                    <div @class(['border-b border-slate-100 dark:border-slate-800 last:border-b-0'])>
                        {{-- Desktop row --}}
                        <div class="hidden md:flex items-center gap-5 p-5 hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors">
                            <a href="{{ route('novels.show', $novel->slug) }}" class="shrink-0 w-14 h-[4.5rem] rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 shadow-sm">
                                @if($novel->cover_image_url)
                                    <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                                @elseif($novel->cover_image)
                                    <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300 dark:text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                    </div>
                                @endif
                            </a>

                            <div class="flex-grow min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <a href="{{ route('novels.show', $novel->slug) }}" class="text-base font-semibold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate">{{ $novel->title }}</a>
                                    @php
                                        $statusLabels = ['ongoing' => 'Ongoing', 'complete' => 'Selesai', 'hiatus' => 'Hiatus'];
                                        $statusColors = [
                                            'ongoing' => 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border-emerald-100 dark:border-emerald-800',
                                            'complete' => 'bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 border-indigo-100 dark:border-indigo-800',
                                            'hiatus' => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700',
                                        ];
                                    @endphp
                                    <span class="shrink-0 px-2 py-0.5 text-[10px] font-semibold rounded-md border {{ $statusColors[$novel->status] ?? $statusColors['hiatus'] }}">
                                        {{ $statusLabels[$novel->status] ?? $novel->status }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-1 mb-2">{{ $novel->description ?: 'Belum ada deskripsi.' }}</p>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
                                    <span>{{ $novel->chapters_count }} chapter</span>
                                    <span>{{ number_format($novel->view_count) }} views</span>
                                    <span>{{ $novel->bookmarks_count }} bookmark</span>
                                    <span class="flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-amber-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        {{ number_format($novel->rating_avg, 1) }}
                                    </span>
                                    @foreach($novel->genres->take(2) as $genre)
                                        <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded text-[10px] font-medium">{{ $genre->name }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="shrink-0 flex items-center gap-2">
                                <a href="{{ route('novels.show', $novel->slug) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Lihat">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                @can('update', $novel)
                                <a href="{{ route('writer.chapters.create', $novel->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors" title="Tambah Chapter">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                </a>
                                <a href="{{ route('writer.novels.edit', $novel->id) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-900/20 rounded-lg transition-colors" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                @endcan
                                @can('delete', $novel)
                                <form action="{{ route('writer.novels.destroy', $novel->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors" title="Hapus" onclick="return confirm('Hapus novel ini selamanya?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </div>

                        {{-- Mobile card --}}
                        <div class="md:hidden p-4 space-y-3">
                            <div class="flex gap-3">
                                <a href="{{ route('novels.show', $novel->slug) }}" class="shrink-0 w-16 h-[5.5rem] rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800">
                                @if($novel->cover_image_url)
                                    <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                                @elseif($novel->cover_image)
                                    <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                                @endif
                            </a>
                                <div class="flex-grow min-w-0">
                                    <div class="flex items-start justify-between gap-2">
                                        <a href="{{ route('novels.show', $novel->slug) }}" class="text-sm font-semibold text-slate-900 dark:text-white line-clamp-2">{{ $novel->title }}</a>
                                        <span class="shrink-0 px-2 py-0.5 text-[10px] font-semibold rounded-md border {{ $statusColors[$novel->status] ?? $statusColors['hiatus'] }}">
                                            {{ $statusLabels[$novel->status] ?? $novel->status }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-2 mt-2 text-[11px] text-slate-500 dark:text-slate-400">
                                        <span>{{ $novel->chapters_count }} ch</span>
                                        <span>{{ number_format($novel->view_count) }} views</span>
                                        <span>★ {{ number_format($novel->rating_avg, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                @can('update', $novel)
                                <a href="{{ route('writer.chapters.create', $novel->id) }}" class="flex-1 text-center py-2 text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">+ Chapter</a>
                                <a href="{{ route('writer.novels.edit', $novel->id) }}" class="flex-1 text-center py-2 text-xs font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 rounded-lg">Edit</a>
                                @endcan
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-16 px-6 text-center">
                        <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">Belum ada novel</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 max-w-xs mx-auto">Mulai perjalanan menulismu dengan membuat novel pertama.</p>
                        <a href="{{ route('writer.novels.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                            Buat Novel Pertama
                        </a>
                    </div>
                @endforelse
            </div>

            @if($novels->hasPages())
                <div class="flex justify-center">
                    {{ $novels->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
