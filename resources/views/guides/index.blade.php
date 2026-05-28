@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-16">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Quoros Guide Center</h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">Temukan jawaban, panduan, dan tips untuk memaksimalkan pengalaman Anda di Quoros.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($categories as $category)
            <div class="bg-slate-900/50 border border-slate-800 rounded-3xl p-8 hover:border-purple-500/50 transition-all group">
                <div class="flex items-center gap-4 mb-6">
                    <div class="p-3 bg-purple-500/10 rounded-2xl text-purple-500 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-white">{{ $category->name }}</h2>
                </div>
                
                <p class="text-slate-400 mb-8 line-clamp-2">{{ $category->description }}</p>

                <ul class="space-y-4 mb-8">
                    @foreach($category->articles->take(5) as $article)
                        <li>
                            <a href="{{ route('guides.show', [$category->slug, $article->slug]) }}" class="flex items-center gap-3 text-slate-300 hover:text-purple-400 transition-colors group/link">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 group-hover/link:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                                <span>{{ $article->title }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ route('guides.category', $category->slug) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-purple-400 hover:text-purple-300 transition-colors">
                    Lihat Semua
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-slate-500">Belum ada panduan tersedia.</p>
            </div>
        @endforelse

        @auth
            @if(Auth::user()->role === 'user')
                <div class="bg-gradient-to-br from-emerald-600 to-teal-600 rounded-3xl p-8 text-white flex flex-col justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-4">Ingin Menjadi Penulis?</h2>
                        <p class="text-emerald-50 mb-8 leading-relaxed">Bagikan ceritamu dengan ribuan pembaca di Quoros. Mulai perjalanan menulismu sekarang juga!</p>
                    </div>
                    <form action="{{ route('dashboard.become-writer') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-4 bg-white text-emerald-600 font-bold rounded-2xl hover:bg-emerald-50 transition-all shadow-lg">
                            Mulai Menulis Sekarang
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
</div>
@endsection
