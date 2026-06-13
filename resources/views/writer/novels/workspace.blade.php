@extends('layouts.dashboard', [
    'title' => 'Novel Workspace',
    'subtitle' => 'Draft, publish, and manage chapters for your book.'
])

@section('dashboard-content')
<div class="space-y-8 pb-10">
    <!-- Novel Quick Showcase Widget -->
    <div class="bg-gradient-to-br from-slate-900/60 to-slate-950/80 rounded-[2.5rem] border border-slate-800/60 p-6 sm:p-8 shadow-xl flex flex-col md:flex-row gap-6 items-center md:items-start relative overflow-hidden group">
        <!-- Cover Art -->
        <div class="shrink-0 relative">
            <div class="w-24 h-36 rounded-2xl overflow-hidden shadow-lg border border-slate-800/80 group-hover:scale-[1.02] transition-transform duration-300">
                @if($novel->cover_image_url)
                    <img src="{{ $novel->cover_image_url }}" alt="{{ $novel->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-slate-800 flex items-center justify-center text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Block -->
        <div class="flex-grow min-w-0 text-center md:text-left space-y-4">
            <div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 justify-center md:justify-start mb-2">
                    <h2 class="text-xl font-black text-white truncate">{{ $novel->title }}</h2>
                    <span @class([
                        'w-fit mx-auto sm:mx-0 px-2.5 py-0.5 rounded text-[8px] font-black uppercase tracking-widest border',
                        'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' => $novel->status === 'ongoing',
                        'bg-indigo-500/10 text-indigo-400 border-indigo-500/20' => $novel->status === 'complete',
                        'bg-slate-500/10 text-slate-400 border-slate-500/20' => $novel->status === 'hiatus',
                    ])>{{ $novel->status }}</span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed max-w-3xl line-clamp-2">{{ $novel->description ?: 'No briefing provided.' }}</p>
            </div>

            <!-- Stats Bar -->
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-6 pt-4 border-t border-slate-800/40 text-slate-400">
                <div class="flex items-center gap-2">
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Chapters</span>
                    <span class="text-xs font-black text-white">{{ $chapters->count() }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Views</span>
                    <span class="text-xs font-black text-white">{{ number_format($novel->view_count) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Bookmarks</span>
                    <span class="text-xs font-black text-white">{{ number_format($novel->bookmarks_count) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Rating</span>
                    <span class="text-xs font-black text-white">{{ number_format($novel->reviews_avg_rating ?? 0, 1) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Chapter Management Table -->
    <div class="space-y-6">
        <div class="flex items-center justify-between px-1">
            <div class="flex items-center gap-3">
                <div class="w-1.5 h-6 bg-indigo-500 rounded-full shadow-lg shadow-indigo-500/50"></div>
                <h3 class="text-base font-black text-slate-800 dark:text-white uppercase tracking-wider">Chapter List</h3>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('novels.show', $novel->slug) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 border border-slate-800 text-slate-400 hover:text-white text-[10px] font-black uppercase tracking-wider rounded-2xl transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Public View
                </a>
                <a href="{{ route('writer.novels.chapters.create', $novel) }}" class="inline-flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-wider rounded-2xl transition-all shadow-lg shadow-indigo-600/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    New Chapter
                </a>
            </div>
        </div>

        <div class="bg-gradient-to-br from-slate-900/60 to-slate-950/80 border border-slate-800/60 rounded-[2rem] shadow-xl overflow-hidden">
            <div class="divide-y divide-slate-800/60">
                @forelse($chapters as $chapter)
                    <div class="flex items-center gap-4 p-5 hover:bg-slate-950/30 transition-colors group">
                        <!-- Index indicator -->
                        <div class="shrink-0 w-12 h-12 rounded-2xl bg-slate-950/60 flex items-center justify-center border border-slate-800/40">
                            <span class="text-sm font-black text-slate-400">#{{ $chapter->order ?? $loop->index + 1 }}</span>
                        </div>

                        <!-- Title & publication metadata -->
                        <div class="flex-grow min-w-0">
                            <div class="flex items-center gap-3 mb-1 flex-wrap">
                                <h4 class="text-sm font-bold text-white truncate max-w-[80%] group-hover:text-indigo-400 transition-colors">{{ $chapter->title }}</h4>
                                @if($chapter->is_published)
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase tracking-widest text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 rounded-md">Published</span>
                                @else
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase tracking-widest text-slate-400 bg-slate-500/10 border border-slate-500/20 rounded-md">Draft</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">
                                Saved {{ $chapter->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <!-- Actions row -->
                        <div class="shrink-0 flex items-center gap-2">
                            <a href="{{ route('writer.novels.chapters.edit', [$novel, $chapter]) }}" class="p-2.5 rounded-xl bg-slate-800/50 hover:bg-slate-800 text-slate-400 hover:text-white border border-slate-800 transition-all" title="Edit Chapter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </a>
                            <form action="{{ route('writer.novels.chapters.destroy', [$novel, $chapter]) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Chapter ini?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 rounded-xl bg-slate-800/50 hover:bg-rose-500/10 text-slate-400 hover:text-rose-400 border border-slate-800 hover:border-rose-500/20 transition-all" title="Delete Chapter">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <!-- Empty list view placeholder -->
                    <div class="py-16 text-center">
                        <div class="w-16 h-16 bg-slate-900 border border-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h4 class="text-base font-black text-white mb-1">No Chapters Logged</h4>
                        <p class="text-xs text-slate-500 mb-6 leading-relaxed">Your story holds no chapters yet. Begin drafting your first block of content!</p>
                        <a href="{{ route('writer.novels.chapters.create', $novel) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 transition-all shadow-md">
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
