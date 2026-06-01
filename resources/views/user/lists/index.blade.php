@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-1 h-10 bg-violet-600 rounded-full shrink-0"></div>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 dark:text-white">My Lists</h1>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">Custom novel collections that can be shared.</p>
            </div>
        </div>
        <a href="{{ route('lists.create') }}" class="px-5 py-2.5 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-sm font-bold rounded-xl hover:opacity-90 transition-opacity text-center">
            Create New List
        </a>
    </div>

    <div class="grid gap-4">
        @forelse($lists as $list)
            <a href="{{ route('lists.show', $list) }}" class="block p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 hover:border-violet-400 dark:hover:border-violet-600 transition-colors">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">{{ $list->title }}</h2>
                        @if($list->description)
                            <p class="text-sm text-slate-500 mt-1 line-clamp-2">{{ $list->description }}</p>
                        @endif
                        <p class="text-xs text-slate-400 mt-2">{{ $list->items_count }} novels · {{ $list->is_public ? 'Public' : 'Private' }}</p>
                    </div>
                    <span class="text-slate-400">→</span>
                </div>
            </a>
        @empty
            <div class="py-16 text-center bg-white dark:bg-slate-900 rounded-2xl border border-dashed border-slate-200 dark:border-slate-700">
                <p class="text-slate-500">No lists yet. Create your first list to group your favorite novels.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
