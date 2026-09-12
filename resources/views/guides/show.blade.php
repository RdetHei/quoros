@php
    $useWriterShell = auth()->check() && auth()->user()->role === 'writer';
@endphp

@extends($useWriterShell ? 'layouts.writer' : 'layouts.app', [
    'title' => $article->title,
    'subtitle' => $category->name,
])

@section('content')
<div class="{{ $useWriterShell ? 'space-y-8' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12' }}">
    <nav class="flex mb-2" aria-label="Breadcrumb">
        <ol class="inline-flex items-center flex-wrap gap-y-1 space-x-1 md:space-x-2 text-sm">
            <li class="inline-flex items-center">
                <a href="{{ route('guides.index') }}" class="{{ $useWriterShell ? 'text-neutral-500 hover:text-white' : 'text-slate-400 hover:text-white' }} transition-colors">Guide</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-5 h-5 {{ $useWriterShell ? 'text-neutral-700' : 'text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('guides.category', $category->slug) }}" class="ml-1 {{ $useWriterShell ? 'text-neutral-500 hover:text-white' : 'text-slate-400 hover:text-white' }} transition-colors md:ml-2">{{ $category->name }}</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-5 h-5 {{ $useWriterShell ? 'text-neutral-700' : 'text-slate-600' }}" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-white font-medium md:ml-2 line-clamp-1">{{ $article->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row gap-8">
        <article class="flex-1 min-w-0">
            @unless($useWriterShell)
            <header class="mb-12">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">{{ $article->title }}</h1>
                <div class="flex items-center gap-4 text-slate-400">
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Last updated {{ $article->updated_at->diffForHumans() }}
                    </span>
                </div>
            </header>
            @else
            <p class="text-xs text-neutral-500 mb-6">Last updated {{ $article->updated_at->diffForHumans() }}</p>
            @endunless

            @if($article->video_url)
                <div @class([
                    'mb-8 aspect-video rounded-xl overflow-hidden border',
                    $useWriterShell ? 'bg-black border-neutral-800' : 'mb-12 rounded-3xl bg-slate-900 border-slate-800',
                ])>
                    @php
                        $videoId = '';
                        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $article->video_url, $match)) {
                            $videoId = $match[1];
                        }
                    @endphp
                    @if($videoId)
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    @else
                        <video src="{{ $article->video_url }}" controls class="w-full h-full"></video>
                    @endif
                </div>
            @endif

            <div @class([
                'prose max-w-none',
                $useWriterShell
                    ? 'prose-invert prose-neutral prose-headings:text-white prose-p:text-neutral-300 prose-li:text-neutral-300 prose-a:text-white prose-strong:text-white prose-img:rounded-xl prose-img:border prose-img:border-neutral-800'
                    : 'prose-invert prose-purple prose-headings:text-white prose-p:text-slate-300 prose-li:text-slate-300 prose-img:rounded-3xl prose-img:border prose-img:border-slate-800',
            ])>
                {!! $article->content !!}
            </div>

            <footer @class([
                'mt-12 pt-8 border-t',
                $useWriterShell ? 'border-neutral-800' : 'border-slate-800',
            ])>
                <div @class([
                    'rounded-xl p-6 text-center',
                    $useWriterShell ? 'bg-neutral-900 border border-neutral-800' : 'bg-slate-900/50 rounded-3xl p-8',
                ])>
                    <h3 class="text-lg font-medium text-white mb-2">Was this guide helpful?</h3>
                    <p class="{{ $useWriterShell ? 'text-neutral-500' : 'text-slate-400' }} mb-5 text-sm">Your feedback helps us improve our guides.</p>
                    <div class="flex justify-center gap-3">
                        <button @class([
                            'px-5 py-2 text-sm rounded-lg transition-colors',
                            $useWriterShell ? 'bg-white text-black hover:bg-neutral-200' : 'bg-slate-800 hover:bg-slate-700 text-white rounded-xl',
                        ])>Yes, it was</button>
                        <button @class([
                            'px-5 py-2 text-sm rounded-lg transition-colors border',
                            $useWriterShell ? 'border-neutral-700 text-neutral-400 hover:text-white hover:border-neutral-500' : 'bg-slate-800 hover:bg-slate-700 text-white rounded-xl border-transparent',
                        ])>No, it wasn't</button>
                    </div>
                </div>
            </footer>
        </article>

        <aside class="w-full lg:w-72 shrink-0">
            <div class="sticky top-24 space-y-4">
                <div @class([
                    'rounded-xl p-5',
                    $useWriterShell ? 'bg-neutral-900 border border-neutral-800' : 'bg-slate-900/50 border border-slate-800 rounded-3xl p-6',
                ])>
                    <h3 class="text-sm font-medium text-white mb-4 uppercase tracking-wider">In This Category</h3>
                    <div class="space-y-1">
                        @foreach($category->articles as $sibling)
                            <a href="{{ route('guides.show', [$category->slug, $sibling->slug]) }}"
                               @class([
                                   'block p-2.5 rounded-lg transition-all text-sm',
                                   $useWriterShell && $sibling->id === $article->id => 'bg-white text-black font-medium',
                                   $useWriterShell && $sibling->id !== $article->id => 'text-neutral-400 hover:text-white hover:bg-neutral-800',
                                   !$useWriterShell && $sibling->id === $article->id => 'p-3 rounded-xl bg-purple-500/10 text-purple-400',
                                   !$useWriterShell && $sibling->id !== $article->id => 'p-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800',
                               ])>
                                {{ $sibling->title }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div @class([
                    'rounded-xl p-6 text-white',
                    $useWriterShell ? 'bg-neutral-900 border border-neutral-800' : 'bg-gradient-to-br from-purple-600 to-blue-600 rounded-3xl p-8',
                ])>
                    <h3 class="text-base font-medium mb-3">Need more help?</h3>
                    <p @class([
                        'mb-5 text-sm',
                        $useWriterShell ? 'text-neutral-500' : 'text-purple-100 mb-6',
                    ])>Our team is ready to help you anytime.</p>
                    <a href="mailto:support@quoros.id" @class([
                        'inline-block w-full py-2.5 font-medium rounded-lg text-center transition-colors text-sm',
                        $useWriterShell ? 'bg-white text-black hover:bg-neutral-200' : 'py-3 bg-white text-purple-600 font-bold rounded-xl hover:bg-purple-50',
                    ])>Contact Support</a>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
