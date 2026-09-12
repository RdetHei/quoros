@php
    $useWriterShell = auth()->check() && auth()->user()->role === 'writer';
@endphp

@extends($useWriterShell ? 'layouts.writer' : 'layouts.app', [
    'title' => $category->name,
    'subtitle' => $category->description,
])

@section('content')
<div class="{{ $useWriterShell ? 'space-y-8' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12' }}">
    <nav class="flex mb-2" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 text-sm">
            <li class="inline-flex items-center">
                <a href="{{ route('guides.index') }}" class="{{ $useWriterShell ? 'text-neutral-500 hover:text-white' : 'text-slate-400 hover:text-white' }} transition-colors">Guide</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-5 h-5 {{ $useWriterShell ? 'text-neutral-700' : 'text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-white font-medium md:ml-2">{{ $category->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row gap-8">
        <div class="flex-1">
            @unless($useWriterShell)
            <div class="mb-12">
                <h1 class="text-4xl font-bold text-white mb-4">{{ $category->name }}</h1>
                <p class="text-slate-400 text-lg">{{ $category->description }}</p>
            </div>
            @endunless

            <div class="grid grid-cols-1 gap-3">
                @forelse($articles as $article)
                    <a href="{{ route('guides.show', [$category->slug, $article->slug) }}" @class([
                        'block p-5 rounded-xl transition-all group',
                        $useWriterShell ? 'bg-neutral-900 border border-neutral-800 hover:border-neutral-600' : 'p-6 bg-slate-900/50 border border-slate-800 rounded-2xl hover:border-purple-500/50',
                    ])>
                        <div class="flex justify-between items-center gap-4">
                            <div class="min-w-0">
                                <h3 @class([
                                    'font-medium truncate mb-1 transition-colors',
                                    $useWriterShell ? 'text-white group-hover:text-neutral-200' : 'text-xl font-bold group-hover:text-purple-400 mb-2',
                                ])>{{ $article->title }}</h3>
                                <p @class([
                                    'line-clamp-1 text-sm',
                                    $useWriterShell ? 'text-neutral-500' : 'text-slate-400',
                                ])>{{ Str::limit(strip_tags($article->content), 150) }}</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" @class([
                                'h-5 w-5 shrink-0 transform group-hover:translate-x-0.5 transition-all',
                                $useWriterShell ? 'text-neutral-600 group-hover:text-white' : 'text-slate-600 group-hover:text-purple-400 group-hover:translate-x-1',
                            ]) fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @empty
                    <p class="{{ $useWriterShell ? 'text-neutral-500' : 'text-slate-500' }}">Belum ada artikel dalam kategori ini.</p>
                @endforelse
            </div>
        </div>

        <div class="w-full md:w-72 shrink-0">
            <div @class([
                'rounded-xl p-5 sticky top-24',
                $useWriterShell ? 'bg-neutral-900 border border-neutral-800' : 'bg-slate-900/50 border border-slate-800 rounded-3xl p-6',
            ])>
                <h3 class="text-sm font-medium text-white mb-4 uppercase tracking-wider">Kategori Lainnya</h3>
                <div class="space-y-1">
                    @foreach(App\Models\GuideCategory::where('id', '!=', $category->id)->get() as $otherCategory)
                        <a href="{{ route('guides.category', $otherCategory->slug) }}" @class([
                            'block p-2.5 rounded-lg transition-all text-sm',
                            $useWriterShell ? 'text-neutral-400 hover:text-white hover:bg-neutral-800' : 'p-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800',
                        ])>
                            {{ $otherCategory->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
