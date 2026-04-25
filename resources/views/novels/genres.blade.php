@extends('layouts.app')

@section('content')
<div class="mb-12">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-2 h-10 bg-indigo-600 rounded-full"></div>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Jelajah Genre</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Temukan novel berdasarkan kategori favoritmu.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
        @foreach($genres as $genre)
            <a href="{{ route('home', ['genre' => $genre->slug]) }}" class="group p-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 hover:border-indigo-600 hover:shadow-xl hover:shadow-indigo-500/5 transition-all text-center">
                <h3 class="font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-600 transition-colors mb-1 uppercase tracking-wider text-sm">{{ $genre->name }}</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $genre->novels_count }} Novel</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
