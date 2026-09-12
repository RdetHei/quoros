@extends('layouts.app')

@section('content')
<div class="mb-12">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-2 h-10 bg-neutral-400 rounded-full"></div>
        <div>
            <h1 class="text-3xl font-extrabold text-neutral-900 dark:text-white tracking-tight">Jelajah Genre</h1>
            <p class="text-neutral-500 dark:text-neutral-400 font-medium">Temukan novel berdasarkan kategori favoritmu.</p>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        @foreach($genres as $genre)
            <a href="{{ route('novels.search', ['genre' => $genre->slug]) }}" class="px-6 py-3 bg-white dark:bg-neutral-900 rounded-2xl border border-neutral-100 dark:border-neutral-800 hover:border-neutral-400 hover:bg-neutral-50 dark:hover:bg-neutral-900/20 transition-all flex items-center gap-2">
                <span class="font-bold text-neutral-700 dark:text-neutral-300 text-sm uppercase tracking-wide">{{ $genre->name }}</span>
                <span class="px-1.5 py-0.5 bg-neutral-100 dark:bg-neutral-800 rounded-md text-[10px] text-neutral-400 font-black">{{ $genre->novels_count }}</span>
            </a>
        @endforeach
    </div>
</div>
@endsection
