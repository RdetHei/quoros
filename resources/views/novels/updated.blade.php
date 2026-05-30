@extends('layouts.app')

@section('content')
<div class="mb-12">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-2 h-10 bg-emerald-500 rounded-full"></div>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Baru Diupdate</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Daftar novel dengan pembaruan chapter terbaru.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($novels as $novel)
            <a href="{{ route('novels.show', $novel->slug) }}" class="flex gap-4 p-5 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-emerald-500 hover:shadow-xl hover:shadow-emerald-500/5 transition-all group">
                <div class="relative w-24 h-32 flex-shrink-0 rounded-2xl overflow-hidden shadow-md">
                    @if($novel->cover_image_url)
                        <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" onerror="this.onerror=null; this.src='/error.png';">
                    @elseif($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" loading="lazy" onerror="this.onerror=null; this.src='/error.png';">
                    @else
                        <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2 text-center">
                            <span class="text-[10px] text-slate-400 font-bold">{{ $novel->title }}</span>
                        </div>
                    @endif
                    
                    <div class="absolute bottom-1 left-1 right-1 flex flex-col gap-0.5">
                        @php
                            $typeBadge = match($novel->type) {
                                'web_novel' => 'bg-amber-600',
                                'light_novel' => 'bg-slate-700',
                                'original' => 'bg-emerald-700',
                                default => 'bg-slate-500'
                            };
                            $ratingBadge = match($novel->content_rating) {
                                'everyone' => 'bg-emerald-600',
                                'teen' => 'bg-amber-500',
                                'mature' => 'bg-rose-700',
                                default => 'bg-slate-500'
                            };
                        @endphp
                        <span class="px-1 py-0.5 text-[6px] font-black {{ $typeBadge }} text-white rounded uppercase tracking-tighter shadow-lg w-max">{{ str_replace('_', ' ', $novel->type) }}</span>
                        <span class="px-1 py-0.5 text-[6px] font-black {{ $ratingBadge }} text-white rounded uppercase tracking-tighter shadow-lg w-max">{{ $novel->content_rating }}</span>
                    </div>
                </div>
                <div class="flex flex-col justify-between flex-grow py-1">
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-slate-100 group-hover:text-emerald-600 transition-colors line-clamp-1 mb-1">{{ $novel->title }}</h3>
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-slate-500 font-medium">Oleh {{ $novel->author->name }}</p>
                            <div class="flex items-center gap-2">
                                <span class="flex items-center gap-0.5 text-[10px] font-bold text-amber-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                    {{ number_format($novel->rating_avg, 1) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex flex-wrap gap-1">
                            @foreach($novel->genres->take(2) as $genre)
                                <span class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-[10px] font-bold rounded-full border border-slate-100 dark:border-slate-700 uppercase tracking-tighter">{{ $genre->name }}</span>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Chapter {{ $novel->chapters_count }}</span>
                                <span class="text-[9px] text-slate-400 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    {{ number_format($novel->view_count) }}
                                </span>
                            </div>
                            <span class="text-[10px] text-slate-400 italic">{{ $novel->chapters_max_created_at ? \Illuminate\Support\Carbon::parse($novel->chapters_max_created_at)->diffForHumans() : '—' }}</span>
                        </div>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full py-20 text-center">
                <p class="text-slate-500 italic">Belum ada novel yang diupdate.</p>
            </div>
        @endforelse
    </div>

    @if($novels->hasPages())
        <div class="mt-12 flex justify-center">
            {{ $novels->links() }}
        </div>
    @endif
</div>
@endsection
