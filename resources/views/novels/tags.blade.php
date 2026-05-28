@extends('layouts.app')

@section('content')
<div class="mb-12">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-2 h-10 bg-emerald-600 rounded-full"></div>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Tag Populer</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Cari cerita dengan elemen yang lebih spesifik.</p>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        @foreach($tags as $tag)
            <a href="{{ route('novels.search', ['tag' => $tag->slug]) }}" class="px-6 py-3 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all flex items-center gap-2">
                <span class="text-emerald-600 font-bold text-sm">#</span>
                <span class="font-bold text-slate-700 dark:text-slate-300 text-sm uppercase tracking-wide">{{ $tag->name }}</span>
                <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-md text-[10px] text-slate-400 font-black">{{ $tag->novels_count }}</span>
            </a>
        @endforeach
    </div>
</div>
@endsection
