@extends('layouts.app')

@section('content')
<div>
    <div class="flex items-center gap-4 mb-8">
        <div class="w-1 h-10 bg-emerald-600 rounded-full shrink-0"></div>
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Baru Diupdate</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Novel dengan pembaruan chapter terbaru, diurutkan berdasarkan aktivitas.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        @forelse($novels as $novel)
            <div @class(['border-b border-slate-100 dark:border-slate-800 last:border-b-0'])>
                <div class="hidden md:flex items-center gap-5 p-5 hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors">
                    <a href="{{ route('novels.show', $novel->slug) }}" class="shrink-0 w-14 h-[4.5rem] rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200 dark:ring-slate-700">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="w-full h-full object-cover">
                        @endif
                    </a>

                    <div class="flex-grow min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <a href="{{ route('novels.show', $novel->slug) }}" class="text-base font-semibold text-slate-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate">
                                {{ $novel->title }}
                            </a>
                            @if($novel->rating_avg > 0)
                                <span class="shrink-0 flex items-center gap-0.5 text-xs font-medium text-amber-600 dark:text-amber-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    {{ number_format($novel->rating_avg, 1) }}
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">{{ $novel->author->name }}</p>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 dark:text-slate-400">
                            <span>{{ $novel->chapters_count }} chapter</span>
                            <span>{{ number_format($novel->view_count) }} views</span>
                            @foreach($novel->genres->take(2) as $genre)
                                <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded text-[10px] font-medium">{{ $genre->name }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="shrink-0 text-right">
                        <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wide mb-0.5">Terakhir update</p>
                        <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">
                            {{ $novel->chapters_max_created_at ? \Illuminate\Support\Carbon::parse($novel->chapters_max_created_at)->diffForHumans() : '—' }}
                        </p>
                    </div>
                </div>

                {{-- Mobile --}}
                <a href="{{ route('novels.show', $novel->slug) }}" class="md:hidden flex gap-3 p-4 hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition-colors">
                    <div class="shrink-0 w-16 h-[5.5rem] rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" alt="" class="w-full h-full object-cover">
                        @elseif($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-grow min-w-0">
                        <h3 class="text-sm font-semibold text-slate-900 dark:text-white line-clamp-2 mb-1">{{ $novel->title }}</h3>
                        <p class="text-xs text-slate-500 mb-2">{{ $novel->author->name }} · {{ $novel->chapters_count }} ch</p>
                        <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                            {{ $novel->chapters_max_created_at ? \Illuminate\Support\Carbon::parse($novel->chapters_max_created_at)->diffForHumans() : '—' }}
                        </p>
                    </div>
                </a>
            </div>
        @empty
            <div class="py-16 px-6 text-center">
                <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">Belum ada pembaruan</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Novel yang baru diupdate akan muncul di sini.</p>
            </div>
        @endforelse
    </div>

    @if($novels->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $novels->links() }}
        </div>
    @endif
</div>
@endsection
