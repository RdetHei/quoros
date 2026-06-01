@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('guides.index') }}" class="text-slate-400 hover:text-white transition-colors">Guide</a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <a href="{{ route('guides.category', $category->slug) }}" class="ml-1 text-slate-400 hover:text-white transition-colors md:ml-2">{{ $category->name }}</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-slate-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-slate-100 font-medium md:ml-2">{{ $article->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col lg:flex-row gap-12">
        <article class="flex-1">
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

            @if($article->video_url)
                <div class="mb-12 aspect-video rounded-3xl overflow-hidden bg-slate-900 border border-slate-800">
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

            <div class="prose prose-invert prose-purple max-w-none prose-headings:text-white prose-p:text-slate-300 prose-li:text-slate-300 prose-img:rounded-3xl prose-img:border prose-img:border-slate-800">
                {!! $article->content !!}
            </div>

            <footer class="mt-16 pt-8 border-t border-slate-800">
                <div class="bg-slate-900/50 rounded-3xl p-8 text-center">
                    <h3 class="text-xl font-bold text-white mb-2">Was this guide helpful?</h3>
                    <p class="text-slate-400 mb-6">Your feedback helps us improve our guides.</p>
                    <div class="flex justify-center gap-4">
                        <button class="px-6 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl transition-colors">Yes, it was</button>
                        <button class="px-6 py-2 bg-slate-800 hover:bg-slate-700 text-white rounded-xl transition-colors">No, it wasn't</button>
                    </div>
                </div>
            </footer>
        </article>

        <aside class="w-full lg:w-80 shrink-0">
            <div class="sticky top-24 space-y-8">
                <div class="bg-slate-900/50 border border-slate-800 rounded-3xl p-6">
                    <h3 class="text-lg font-bold text-white mb-6">In This Category</h3>
                    <div class="space-y-2">
                        @foreach($category->articles as $sibling)
                            <a href="{{ route('guides.show', [$category->slug, $sibling->slug]) }}" 
                               class="block p-3 rounded-xl transition-all {{ $sibling->id === $article->id ? 'bg-purple-500/10 text-purple-400' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                                {{ $sibling->title }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-600 to-blue-600 rounded-3xl p-8 text-white">
                    <h3 class="text-xl font-bold mb-4">Need more help?</h3>
                    <p class="text-purple-100 mb-6 text-sm">Our team is ready to help you anytime.</p>
                    <a href="mailto:support@quoros.id" class="inline-block w-full py-3 bg-white text-purple-600 font-bold rounded-xl text-center hover:bg-purple-50 transition-colors">Contact Support</a>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
