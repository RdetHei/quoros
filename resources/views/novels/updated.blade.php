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
                <div class="w-24 h-32 flex-shrink-0 rounded-2xl overflow-hidden shadow-md">
                    @if($novel->cover_image)
                        <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center p-2 text-center">
                            <span class="text-[10px] text-slate-400 font-bold">{{ $novel->title }}</span>
                        </div>
                    @endif
                </div>
                <div class="flex flex-col justify-between flex-grow py-1">
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-slate-100 group-hover:text-emerald-600 transition-colors line-clamp-1 mb-1">{{ $novel->title }}</h3>
                        <p class="text-xs text-slate-500 font-medium">Oleh {{ $novel->author->name }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex flex-wrap gap-1">
                            @foreach($novel->genres->take(2) as $genre)
                                <span class="px-2 py-0.5 bg-slate-50 dark:bg-slate-800 text-slate-400 dark:text-slate-500 text-[10px] font-bold rounded-full border border-slate-100 dark:border-slate-700 uppercase tracking-tighter">{{ $genre->name }}</span>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400">Chapter {{ $novel->chapters->count() }}</span>
                            <span class="text-[10px] text-slate-400 italic">{{ $novel->chapters->max('created_at')->diffForHumans() }}</span>
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

    <div class="mt-12">
        {{ $novels->links() }}
    </div>
</div>
@endsection
