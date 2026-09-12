@extends('layouts.dashboard', [
    'title' => 'Novel Workspace',
    'subtitle' => 'Draft, publish, and manage chapters for your book.'
])

@section('dashboard-content')
<div class="space-y-6 pb-10">
    <div class="bg-neutral-900 rounded-xl border border-neutral-800 p-6 sm:p-8 flex flex-col md:flex-row gap-6 items-center md:items-start">
        <div class="shrink-0">
            <div class="w-24 h-36 rounded-lg overflow-hidden border border-neutral-700 bg-neutral-800">
                @if($novel->cover_image_url)
                    <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-neutral-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        <div class="flex-grow min-w-0 text-center md:text-left space-y-4">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-center md:justify-start mb-2">
                    <h2 class="text-xl font-semibold text-white truncate">{{ $novel->title }}</h2>
                    <span class="w-fit mx-auto sm:mx-0 px-2.5 py-0.5 rounded text-[9px] font-medium uppercase tracking-wider border border-neutral-600 text-neutral-300 bg-neutral-800">{{ $novel->status }}</span>
                </div>
                <p class="text-xs text-neutral-400 leading-relaxed max-w-3xl line-clamp-2">{{ $novel->description ?: 'No briefing provided.' }}</p>
            </div>

            <div class="flex flex-wrap items-center justify-center md:justify-start gap-6 pt-4 border-t border-neutral-800 text-neutral-400">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider">Chapters</span>
                    <span class="text-xs font-medium text-white tabular-nums">{{ $chapters->count() }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider">Views</span>
                    <span class="text-xs font-medium text-white tabular-nums">{{ number_format($novel->view_count) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider">Bookmarks</span>
                    <span class="text-xs font-medium text-white tabular-nums">{{ number_format($novel->bookmarks_count) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider">Rating</span>
                    <span class="text-xs font-medium text-white tabular-nums">{{ number_format($novel->reviews_avg_rating ?? 0, 1) }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-medium text-white uppercase tracking-wider">Chapter List</h3>
            <div class="flex items-center gap-2">
                <a href="{{ route('novels.show', $novel->slug) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-neutral-900 border border-neutral-700 text-neutral-400 hover:text-white hover:border-neutral-500 text-[10px] font-medium uppercase tracking-wider rounded-lg transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                    Public View
                </a>
                <a href="{{ route('writer.novels.chapters.create', $novel) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-neutral-200 text-black text-[10px] font-medium uppercase tracking-wider rounded-lg transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    New Chapter
                </a>
            </div>
        </div>

        <div class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden">
            <div class="divide-y divide-neutral-800">
                @forelse($chapters as $chapter)
                    <div class="flex items-center gap-4 p-4 hover:bg-black/30 transition-colors group">
                        <div class="shrink-0 w-11 h-11 rounded-lg bg-black flex items-center justify-center border border-neutral-800">
                            <span class="text-sm font-medium text-neutral-400 tabular-nums">#{{ $chapter->order ?? $loop->index + 1 }}</span>
                        </div>

                        <div class="flex-grow min-w-0">
                            <div class="flex items-center gap-3 mb-1 flex-wrap">
                                <h4 class="text-sm font-medium text-white truncate max-w-[80%] group-hover:text-neutral-200 transition-colors">{{ $chapter->title }}</h4>
                                @if($chapter->is_published)
                                    <span class="px-2 py-0.5 text-[9px] font-medium uppercase tracking-wider text-white bg-neutral-800 border border-neutral-600 rounded">Published</span>
                                @else
                                    <span class="px-2 py-0.5 text-[9px] font-medium uppercase tracking-wider text-neutral-500 bg-neutral-900 border border-neutral-700 rounded">Draft</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-neutral-500 uppercase tracking-wider">Saved {{ $chapter->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="shrink-0 flex items-center gap-2">
                            <a href="{{ route('writer.novels.chapters.edit', [$novel, $chapter]) }}" class="p-2 rounded-lg border border-neutral-700 text-neutral-400 hover:bg-white hover:text-black hover:border-white transition-all" title="Edit Chapter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </a>
                            <form action="{{ route('writer.novels.chapters.destroy', [$novel, $chapter]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Chapter ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg border border-neutral-700 text-neutral-500 hover:text-white hover:border-neutral-500 transition-all" title="Delete Chapter">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="py-16 text-center">
                        <div class="w-14 h-14 bg-neutral-800 border border-neutral-700 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <h4 class="text-base font-medium text-white mb-1">No Chapters Logged</h4>
                        <p class="text-xs text-neutral-500 mb-6">Your story holds no chapters yet. Begin drafting your first block of content.</p>
                        <a href="{{ route('writer.novels.chapters.create', $novel) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-white text-black hover:bg-neutral-200 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                            Create First Chapter
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
