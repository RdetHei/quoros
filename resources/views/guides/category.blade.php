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
                    <span class="ml-1 text-slate-100 font-medium md:ml-2">{{ $category->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row gap-12">
        <div class="flex-1">
            <div class="mb-12">
                <h1 class="text-4xl font-bold text-white mb-4">{{ $category->name }}</h1>
                <p class="text-slate-400 text-lg">{{ $category->description }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4">
                @forelse($articles as $article)
                    <a href="{{ route('guides.show', [$category->slug, $article->slug]) }}" class="block p-6 bg-slate-900/50 border border-slate-800 rounded-2xl hover:border-purple-500/50 transition-all group">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-bold text-white group-hover:text-purple-400 transition-colors mb-2">{{ $article->title }}</h3>
                                <p class="text-slate-400 line-clamp-1">{{ Str::limit(strip_tags($article->content), 150) }}</p>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600 group-hover:text-purple-400 transform group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @empty
                    <p class="text-slate-500">Belum ada artikel dalam kategori ini.</p>
                @endforelse
            </div>
        </div>

        <div class="w-full md:w-80 shrink-0">
            <div class="bg-slate-900/50 border border-slate-800 rounded-3xl p-6 sticky top-24">
                <h3 class="text-lg font-bold text-white mb-6">Kategori Lainnya</h3>
                <div class="space-y-2">
                    @foreach(App\Models\GuideCategory::where('id', '!=', $category->id)->get() as $otherCategory)
                        <a href="{{ route('guides.category', $otherCategory->slug) }}" class="block p-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all">
                            {{ $otherCategory->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
