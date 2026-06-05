@extends('layouts.dashboard', [
    'title' => 'Feedback Hub',
    'subtitle' => 'All comments and reviews across your novels in one place.'
])

@section('dashboard-content')
<div class="space-y-8">
    <section class="grid grid-cols-1 xl:grid-cols-2 gap-8">
        <!-- Reviews Column -->
        <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-4">
                    <span class="w-2 h-8 bg-amber-500 rounded-full"></span>
                    Latest Reviews
                </h2>
                <span class="px-3 py-1 bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-amber-100 dark:border-amber-900/50">
                    {{ $reviews->total() }} Total
                </span>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($reviews as $review)
                    <div class="p-8 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-sm font-black text-slate-500 border border-slate-200 dark:border-slate-700 group-hover:bg-white dark:group-hover:bg-slate-700 transition-colors">
                                    {{ substr($review->user?->name ?? 'R', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900 dark:text-white">{{ $review->user?->name ?? 'Reader' }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1 text-amber-500">
                                @for($i = 0; $i < 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 {{ $i < $review->rating ? 'fill-current' : 'text-slate-200 dark:text-slate-700' }}" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-2">{{ $review->novel?->title }}</p>
                        @if($review->content)
                            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $review->content }}</p>
                        @endif
                    </div>
                @empty
                    <div class="p-20 text-center">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No reviews found</p>
                    </div>
                @endforelse
            </div>
            
            @if($reviews->hasPages())
                <div class="p-8 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
                    {{ $reviews->links() }}
                </div>
            @endif
        </article>

        <!-- Comments Column -->
        <article class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h2 class="text-xl font-black text-slate-900 dark:text-white flex items-center gap-4">
                    <span class="w-2 h-8 bg-indigo-600 rounded-full"></span>
                    Latest Comments
                </h2>
                <span class="px-3 py-1 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-xl border border-indigo-100 dark:border-indigo-900/50">
                    {{ $comments->total() }} Total
                </span>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($comments as $comment)
                    <div class="p-8 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-all group">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-sm font-black text-slate-500 border border-slate-200 dark:border-slate-700 group-hover:bg-white dark:group-hover:bg-slate-700 transition-colors">
                                    {{ substr($comment->user?->name ?? 'R', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900 dark:text-white">{{ $comment->user?->name ?? 'Reader' }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $comment->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('chapters.show', [$comment->chapter?->novel?->slug, $comment->chapter?->slug]) }}#comment-{{ $comment->id }}" target="_blank" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                            </a>
                        </div>
                        <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1">
                            {{ $comment->chapter?->novel?->title ?? 'Unknown' }} 
                            <span class="text-slate-400 mx-1">/</span> 
                            {{ $comment->chapter?->title ?? 'Chapter' }}
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed italic">"{{ $comment->content }}"</p>
                    </div>
                @empty
                    <div class="p-20 text-center">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">No comments found</p>
                    </div>
                @endforelse
            </div>

            @if($comments->hasPages())
                <div class="p-8 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
                    {{ $comments->links() }}
                </div>
            @endif
        </article>
    </section>
</div>
@endsection
