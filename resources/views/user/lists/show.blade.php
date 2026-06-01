@extends('layouts.app')

@section('content')
@php
    $isOwner = $isOwner ?? (Auth::check() && Auth::id() === $list->user_id);
    $shareUrl = $list->is_public
        ? route('lists.public', [$list->user->username ?? $list->user_id, $list->slug])
        : null;
@endphp
<div class="max-w-4xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-1">List by {{ $list->user->name }}</p>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">{{ $list->title }}</h1>
            @if($list->description)
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">{{ $list->description }}</p>
            @endif
            <p class="text-xs text-slate-400 mt-2">{{ $list->novels->count() }} novels · {{ $list->is_public ? 'Public' : 'Private' }}</p>
            @if($shareUrl)
                <p class="text-xs text-indigo-600 dark:text-indigo-400 mt-2 break-all">Share: {{ $shareUrl }}</p>
            @endif
        </div>
        @if($isOwner)
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('lists.edit', $list) }}" class="px-4 py-2 text-xs font-bold rounded-xl border border-slate-200 dark:border-slate-700">Edit</a>
                <form action="{{ route('lists.destroy', $list) }}" method="POST" onsubmit="return confirm('Delete this list?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 text-xs font-bold rounded-xl text-rose-600 border border-rose-200 dark:border-rose-800">Delete</button>
                </form>
            </div>
        @endif
    </div>

    <div class="space-y-3">
        @forelse($list->novels as $novel)
            <div class="flex items-center gap-4 p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
                <a href="{{ route('novels.show', $novel->slug) }}" class="flex items-center gap-4 flex-grow min-w-0">
                    <div class="w-12 h-16 shrink-0 rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800">
                        @if($novel->cover_image_url)
                            <img src="{{ $novel->cover_image_url }}" alt="" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-900 dark:text-white line-clamp-1">{{ $novel->title }}</p>
                        <p class="text-xs text-slate-500">{{ $novel->author->name }}</p>
                    </div>
                </a>
                @if($isOwner)
                    <form action="{{ route('lists.novels.remove', [$list, $novel]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs font-bold text-slate-400 hover:text-rose-500">Delete</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-center py-12 text-slate-500">List is still empty.</p>
        @endforelse
    </div>
</div>
@endsection
