@extends('layouts.dashboard', [
    'title' => 'Author Studio',
    'subtitle' => 'Manage your manuscripts, track performance, and engage with your readers.'
])

@section('dashboard-content')
<div class="space-y-8 pb-10" x-data="{
    activeTab: (new URLSearchParams(window.location.search)).get('tab') || 'overview',
    switchTab(tab) {
        this.activeTab = tab;
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.history.pushState({}, '', url);
    }
}">

    {{-- Quick Stats --}}
    <section class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-5 hover:border-neutral-700 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 rounded-md bg-neutral-800 text-neutral-300 border border-neutral-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <span class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider">Today</span>
            </div>
            <p class="text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-1">Total Impressions</p>
            <p class="text-2xl font-semibold text-white tabular-nums">{{ number_format($viewsToday) }}</p>
        </div>

        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-5 hover:border-neutral-700 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 rounded-md bg-neutral-800 text-neutral-300 border border-neutral-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <span class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider">New</span>
            </div>
            <p class="text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-1">Added Bookmarks</p>
            <p class="text-2xl font-semibold text-white tabular-nums">{{ number_format($newBookmarksToday) }}</p>
        </div>

        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-5 hover:border-neutral-700 transition-colors">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2 rounded-md bg-neutral-800 text-neutral-300 border border-neutral-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.54 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.196-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <span class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider">Avg</span>
            </div>
            <p class="text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-1">Story Rating</p>
            <p class="text-2xl font-semibold text-white tabular-nums">{{ number_format($averageRating, 1) }} <span class="text-sm text-neutral-500 font-normal">/ 5.0</span></p>
        </div>

        <a href="{{ route('writer.novels.create') }}" class="group bg-white rounded-xl p-5 hover:bg-neutral-200 transition-colors flex flex-col justify-between border border-neutral-200">
            <div>
                <p class="text-[10px] font-medium uppercase tracking-wider text-neutral-500 mb-1">New Project</p>
                <h3 class="text-lg font-semibold text-black leading-tight">Create Novel</h3>
            </div>
            <div class="mt-5 flex items-center gap-2 text-xs font-medium text-black uppercase tracking-wider">
                Get Started
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </div>
        </a>
    </section>

    {{-- Tab Navigation --}}
    <div class="flex items-center gap-1 border-b border-neutral-800">
        @foreach(['overview' => 'Overview', 'library' => 'Library', 'analytics' => 'Analytics', 'community' => 'Community'] as $tab => $label)
            <button @click="switchTab('{{ $tab }}')"
                    :class="activeTab === '{{ $tab }}' ? 'text-white border-white' : 'text-neutral-500 border-transparent hover:text-neutral-300'"
                    class="px-4 py-3 text-xs font-medium uppercase tracking-wider border-b-2 -mb-px transition-colors">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Tab: Overview --}}
    <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 xl:grid-cols-12 gap-6">
        <div class="xl:col-span-8 space-y-6">
            <section class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-sm font-medium text-white uppercase tracking-wider">Top Performing</h2>
                </div>
                <div class="space-y-3">
                    @forelse($myNovels->take(3) as $novel)
                        <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-4 hover:border-neutral-600 transition-colors group">
                            <div class="flex items-center gap-5">
                                <div class="shrink-0 w-12 h-[4.5rem] rounded-md overflow-hidden border border-neutral-700 bg-neutral-800">
                                    @if($novel->cover_image_url)
                                        <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover" alt="">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-neutral-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow min-w-0">
                                    <h3 class="text-sm font-medium text-white truncate group-hover:text-neutral-200 transition-colors">{{ $novel->title }}</h3>
                                    <div class="flex items-center gap-4 mt-1.5 text-neutral-500">
                                        <span class="text-[11px]"><span class="text-neutral-300">{{ number_format($novel->view_count) }}</span> views</span>
                                        <span class="text-[11px]"><span class="text-neutral-300">{{ number_format($novel->bookmarks_count) }}</span> fans</span>
                                    </div>
                                </div>
                                <a href="{{ route('writer.novels.workspace', $novel) }}" class="p-2.5 bg-white hover:bg-neutral-200 text-black rounded-lg transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center border border-dashed border-neutral-800 rounded-xl text-neutral-500 text-xs uppercase tracking-wider">No manuscripts yet</div>
                    @endforelse
                </div>
            </section>

            <section class="bg-neutral-900 border border-neutral-800 rounded-xl p-5">
                <h2 class="text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-4">Work in Progress</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @forelse($draftChapters as $chapter)
                        <div class="flex items-center justify-between p-3.5 rounded-lg bg-black/40 border border-neutral-800 hover:border-neutral-700 transition-colors">
                            <div class="min-w-0 pr-3">
                                <p class="text-xs font-medium text-white truncate">{{ $chapter->title }}</p>
                                <p class="text-[10px] text-neutral-500 truncate mt-0.5">{{ $chapter->novel?->title }}</p>
                            </div>
                            <a href="{{ route('writer.novels.chapters.edit', [$chapter->novel_id, $chapter->id]) }}" class="shrink-0 p-2 rounded-md border border-neutral-700 text-neutral-400 hover:bg-white hover:text-black hover:border-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </a>
                        </div>
                    @empty
                        <div class="md:col-span-2 py-6 text-center text-neutral-600 text-[11px] uppercase tracking-wider">No drafts currently</div>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="xl:col-span-4">
            <section class="bg-neutral-900 border border-neutral-800 rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-[11px] font-medium text-neutral-500 uppercase tracking-wider">Latest Interactions</h2>
                    <button @click="switchTab('community')" class="text-[10px] font-medium text-neutral-400 uppercase tracking-wider hover:text-white transition-colors">View All</button>
                </div>
                <div class="space-y-3">
                    @foreach($latestComments->take(4) as $comment)
                        <div class="p-3.5 bg-black/40 rounded-lg border border-neutral-800 hover:border-neutral-700 transition-colors">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[9px] font-medium text-neutral-400 bg-neutral-800 px-1.5 py-0.5 rounded border border-neutral-700 uppercase tracking-wider">Comment</span>
                                <p class="text-[9px] text-neutral-600 truncate">{{ $comment->chapter?->novel?->title }}</p>
                            </div>
                            <p class="text-xs text-neutral-300 leading-relaxed line-clamp-2 mb-2">"{{ $comment->content }}"</p>
                            <div class="flex items-center justify-between text-[10px] text-neutral-500">
                                <span>{{ $comment->user?->name }}</span>
                                <span>{{ $comment->created_at->diffForHumans(null, true) }}</span>
                            </div>
                        </div>
                    @endforeach
                    @foreach($latestReviews->take(4) as $review)
                        <div class="p-3.5 bg-black/40 rounded-lg border border-neutral-800 hover:border-neutral-700 transition-colors">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-[9px] font-medium text-neutral-400 bg-neutral-800 px-1.5 py-0.5 rounded border border-neutral-700 uppercase tracking-wider">Review</span>
                                <div class="flex items-center gap-0.5 text-neutral-400 scale-75 origin-left">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $i < $review->rating ? 'fill-current text-white' : 'text-neutral-700' }}" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-xs text-neutral-300 leading-relaxed line-clamp-2 mb-2">"{{ $review->content ?: 'No written feedback.' }}"</p>
                            <div class="flex items-center justify-between text-[10px] text-neutral-500">
                                <span>{{ $review->user?->name }}</span>
                                <span>{{ $review->created_at->diffForHumans(null, true) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    {{-- Tab: Library --}}
    <div x-show="activeTab === 'library'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($myNovels as $novel)
                <div class="bg-neutral-900 border border-neutral-800 rounded-xl p-5 hover:border-neutral-600 transition-colors group flex flex-col justify-between">
                    <div class="flex gap-4 mb-4">
                        <div class="shrink-0 w-14 h-20 rounded-md overflow-hidden border border-neutral-700 bg-neutral-800">
                            @if($novel->cover_image_url)
                                <img src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover" alt="">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-neutral-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-medium text-white truncate group-hover:text-neutral-200 transition-colors">{{ $novel->title }}</h3>
                            <p class="text-[10px] font-medium uppercase tracking-wider text-neutral-500 mt-1">{{ $novel->status }}</p>
                            <div class="flex items-center gap-4 mt-3">
                                <div>
                                    <p class="text-[9px] text-neutral-600 uppercase tracking-wider">Chapters</p>
                                    <p class="text-xs font-medium text-white tabular-nums">{{ $novel->chapters_count }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] text-neutral-600 uppercase tracking-wider">Fans</p>
                                    <p class="text-xs font-medium text-white tabular-nums">{{ number_format($novel->bookmarks_count) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 pt-4 border-t border-neutral-800">
                        <a href="{{ route('writer.novels.workspace', $novel) }}" class="flex-grow py-2 bg-white hover:bg-neutral-200 text-black text-[10px] font-medium uppercase tracking-wider rounded-lg text-center transition-colors">Workspace</a>
                        <a href="{{ route('writer.novels.edit', $novel) }}" class="p-2 border border-neutral-700 text-neutral-400 hover:bg-neutral-800 hover:text-white rounded-lg transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center border border-dashed border-neutral-800 rounded-xl">
                    <p class="text-neutral-500 text-xs uppercase tracking-wider">No manuscripts in your catalog</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Tab: Analytics --}}
    <div x-show="activeTab === 'analytics'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-neutral-900 p-5 rounded-xl border border-neutral-800">
            <div>
                <h3 class="text-sm font-medium text-white uppercase tracking-wider">Performance Metrics</h3>
                <p class="text-[11px] text-neutral-500 mt-1">Last 30 days aggregation</p>
            </div>
            <form action="{{ route('dashboard') }}" method="GET" id="analyticsFilterForm" class="w-full md:w-72">
                <input type="hidden" name="tab" value="analytics">
                <select name="novel_id" onchange="document.getElementById('analyticsFilterForm').submit()" class="w-full px-4 py-2.5 bg-black border border-neutral-700 rounded-lg text-[11px] font-medium uppercase tracking-wider text-neutral-300 focus:ring-1 focus:ring-white focus:border-white transition-all cursor-pointer">
                    <option value="">All Managed Works</option>
                    @foreach($allNovels as $novel)
                        <option value="{{ $novel->id }}" {{ $selectedNovelId == $novel->id ? 'selected' : '' }}>{{ $novel->title }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach([
                ['label' => 'Lifetime Views', 'value' => number_format($totalViews)],
                ['label' => 'Total Bookmarks', 'value' => number_format($totalBookmarks)],
                ['label' => 'Total Reviews', 'value' => number_format($totalReviews)],
            ] as $metric)
                <div class="bg-neutral-900 rounded-xl p-5 border border-neutral-800">
                    <p class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider mb-1">{{ $metric['label'] }}</p>
                    <p class="text-2xl font-semibold text-white tabular-nums">{{ $metric['value'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
            <div class="lg:col-span-8 bg-neutral-900 rounded-xl p-6 border border-neutral-800">
                <h3 class="text-sm font-medium text-white uppercase tracking-wider mb-5">Engagement Trend (Last 30 Days)</h3>
                <div class="h-[300px]">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
            <div class="lg:col-span-4 bg-neutral-900 rounded-xl p-6 border border-neutral-800">
                <h3 class="text-sm font-medium text-white uppercase tracking-wider mb-5">Interaction Ratio</h3>
                <div class="h-[250px] relative">
                    <canvas id="interactionChart"></canvas>
                </div>
                <div class="mt-6 space-y-2">
                    <div class="flex items-center justify-between p-3.5 rounded-lg bg-black/40 border border-neutral-800">
                        <span class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider">Bookmarks</span>
                        <span class="text-xs font-medium text-white tabular-nums">{{ number_format($totalBookmarks) }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3.5 rounded-lg bg-black/40 border border-neutral-800">
                        <span class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider">Reviews</span>
                        <span class="text-xs font-medium text-white tabular-nums">{{ number_format($totalReviews) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab: Community --}}
    <div x-show="activeTab === 'community'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-neutral-800">
                <h2 class="text-[11px] font-medium text-neutral-500 uppercase tracking-wider">Latest Reviews</h2>
            </div>
            <div class="divide-y divide-neutral-800">
                @forelse($latestReviews as $review)
                    <div class="p-5 hover:bg-black/30 transition-colors">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-md bg-neutral-800 flex items-center justify-center text-[10px] font-medium text-neutral-400 border border-neutral-700">
                                    {{ substr($review->user?->name ?? 'R', 0, 1) }}
                                </div>
                                <span class="text-xs font-medium text-white">{{ $review->user?->name }}</span>
                            </div>
                            <div class="flex items-center gap-0.5 text-neutral-400 scale-90">
                                @for($i = 0; $i < 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 {{ $i < $review->rating ? 'fill-current text-white' : 'text-neutral-700' }}" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                        </div>
                        <p class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider mb-2">{{ $review->novel?->title }}</p>
                        <p class="text-xs text-neutral-400 leading-relaxed">"{{ $review->content ?: 'No written feedback.' }}"</p>
                    </div>
                @empty
                    <div class="py-10 text-center text-neutral-600 text-[11px] uppercase tracking-wider">No reviews found</div>
                @endforelse
            </div>
        </div>

        <div class="bg-neutral-900 border border-neutral-800 rounded-xl overflow-hidden flex flex-col">
            <div class="px-5 py-4 border-b border-neutral-800">
                <h2 class="text-[11px] font-medium text-neutral-500 uppercase tracking-wider">Latest Comments</h2>
            </div>
            <div class="divide-y divide-neutral-800">
                @forelse($latestComments as $comment)
                    <div class="p-5 hover:bg-black/30 transition-colors">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-md bg-neutral-800 flex items-center justify-center text-[10px] font-medium text-neutral-400 border border-neutral-700">
                                    {{ substr($comment->user?->name ?? 'R', 0, 1) }}
                                </div>
                                <span class="text-xs font-medium text-white">{{ $comment->user?->name }}</span>
                            </div>
                            <span class="text-[10px] text-neutral-500">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider mb-2">{{ $comment->chapter?->novel?->title }} / {{ $comment->chapter?->title }}</p>
                        <p class="text-xs text-neutral-400 leading-relaxed">"{{ $comment->content }}"</p>
                    </div>
                @empty
                    <div class="py-10 text-center text-neutral-600 text-[11px] uppercase tracking-wider">No comments found</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($labels);
    const bookmarkData = @json($bookmarkData);
    const reviewData = @json($reviewData);
    const readerData = @json($readerData);
    const gridColor = 'rgba(255, 255, 255, 0.06)';
    const tickColor = '#737373';

    const tooltipStyles = {
        backgroundColor: 'rgba(23, 23, 23, 0.95)',
        titleFont: { size: 11, weight: '500' },
        bodyFont: { size: 11 },
        padding: 10,
        cornerRadius: 8,
        borderColor: 'rgba(255,255,255,0.1)',
        borderWidth: 1,
        displayColors: true,
        usePointStyle: true,
    };

    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Bookmarks',
                    data: bookmarkData,
                    borderColor: '#ffffff',
                    borderWidth: 1.5,
                    pointRadius: 0,
                    pointHoverRadius: 3,
                    fill: true,
                    backgroundColor: 'rgba(255, 255, 255, 0.04)',
                    tension: 0.35,
                },
                {
                    label: 'Reviews',
                    data: reviewData,
                    borderColor: '#737373',
                    borderWidth: 1.5,
                    pointRadius: 0,
                    pointHoverRadius: 3,
                    fill: true,
                    backgroundColor: 'rgba(115, 115, 115, 0.04)',
                    tension: 0.35,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: { legend: { display: false }, tooltip: tooltipStyles },
            scales: {
                y: { beginAtZero: true, grid: { color: gridColor, drawBorder: false }, ticks: { font: { size: 10 }, color: tickColor } },
                x: { grid: { display: false }, ticks: { font: { size: 10 }, color: tickColor, maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } },
            },
        },
    });

    new Chart(document.getElementById('interactionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Bookmarks', 'Reviews'],
            datasets: [{
                data: [{{ $totalBookmarks }}, {{ $totalReviews }}],
                backgroundColor: ['#ffffff', '#525252'],
                borderWidth: 3,
                borderColor: '#171717',
                hoverOffset: 4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '78%',
            plugins: { legend: { display: false }, tooltip: tooltipStyles },
        },
    });
</script>
@endpush
@endsection
