@php
    $useWriterShell = auth()->check() && auth()->user()->role === 'writer';
@endphp

@extends($useWriterShell ? 'layouts.writer' : 'layouts.app', [
    'title' => $article->title,
    'subtitle' => $category->name,
])

@section('content')
<div class="{{ $useWriterShell ? 'space-y-10' : 'max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-24' }}">
    @if(!$useWriterShell)
    <nav class="mb-10 flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.22em] text-neutral-500">
        <a href="{{ route('guides.index') }}" class="hover:text-[#c7a64a] transition-colors">GUIDANCE</a>
        <span>/</span>
        <a href="{{ route('guides.category', $category->slug) }}" class="hover:text-[#c7a64a] transition-colors">{{ str($category->name)->upper() }}</a>
        <span>/</span>
        <span class="text-neutral-300">{{ str($article->title)->words(6, '…')->upper() }}</span>
    </nav>
    @endif

    <div class="flex flex-col lg:flex-row gap-8 lg:gap-10">
        <article class="flex-1 min-w-0 space-y-10">
            @unless($useWriterShell)
            <header class="space-y-7 pb-10 border-b border-neutral-800">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 border border-[#c7a64a]/30 bg-[#c7a64a]/[0.06] rounded-md text-[10px] font-bold uppercase tracking-[0.28em] text-[#c7a64a]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.837 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    {{ $category->name }}
                </div>
                <h1 class="font-serif text-[40px] md:text-[52px] leading-[1.06] tracking-tight text-[#f2efe8]">{{ $article->title }}</h1>
                <div class="flex flex-wrap items-center gap-x-8 gap-y-3 text-[11.5px] uppercase tracking-[0.2em] text-neutral-500">
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Terakhir diperbarui · {{ $article->updated_at->diffForHumans() }}
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                        </svg>
                        Editorial Chapterly
                    </span>
                    <span class="inline-flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                        </svg>
                        {{ str($article->content)->stripTags()->wordCount() }} kata · ±{{ max(1, (int)round(str($article->content)->stripTags()->wordCount() / 220)) }} menit baca
                    </span>
                </div>
            </header>
            @else
            <header class="space-y-3 pb-8 border-b border-neutral-800">
                <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-[#c7a64a]">{{ $category->name }} · Last updated {{ $article->updated_at->diffForHumans() }}</p>
                <h1 class="font-serif text-[30px] leading-[1.08] text-[#f2efe8]">{{ $article->title }}</h1>
            </header>
            @endunless

            @if($article->video_url)
                <div @class([
                    'aspect-video rounded-[20px] overflow-hidden border',
                    $useWriterShell ? 'bg-black border-neutral-800' : 'bg-black border-neutral-800/90 shadow-2xl shadow-black/60',
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
                    ? 'prose-invert prose-neutral prose-headings:text-[#f2efe8] prose-p:text-neutral-300 prose-li:text-neutral-300 prose-a:text-[#c7a64a] prose-strong:text-[#f2efe8] prose-headings:font-serif prose-h2:text-3xl prose-h3:text-2xl prose-img:rounded-xl prose-img:border prose-img:border-neutral-800 prose-blockquote:border-[#c7a64a]/40 prose-blockquote:bg-[#c7a64a]/[0.04] prose-blockquote:text-neutral-300'
                    : 'prose-invert prose-lg prose-neutral max-w-[78ch] mx-auto prose-headings:text-[#f2efe8] prose-p:text-neutral-300 prose-li:text-neutral-300 prose-a:text-[#c7a64a] prose-a:no-underline hover:prose-a:underline prose-strong:text-[#f2efe8] prose-headings:font-serif prose-h1:text-4xl prose-h2:text-[34px] prose-h3:text-[26px] prose-h4:text-xl prose-p:leading-[1.9] prose-img:rounded-2xl prose-img:border prose-img:border-neutral-800 prose-blockquote:border-l-[#c7a64a]/50 prose-blockquote:border-l-4 prose-blockquote:bg-[#c7a64a]/[0.04] prose-blockquote:text-neutral-300 prose-blockquote:rounded-r-lg prose-blockquote:not-italic prose-hr:border-neutral-800',
            ])>
                {!! $article->content !!}
            </div>

            <footer class="pt-10 border-t border-neutral-800 space-y-6">
                <div class="rounded-[22px] p-6 md:p-8 border border-neutral-800 bg-[#0e0e0e] text-center space-y-4">
                    <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-[#c7a64a] mb-1">Umpan balik editorial</p>
                    <h3 class="font-serif text-[22px] md:text-[26px] text-[#f2efe8]">Apakah panduan ini membantu kamu?</h3>
                    <p class="text-[13px] leading-relaxed text-neutral-400 max-w-xl mx-auto">Setiap umpan balik yang kamu kirim akan dibaca tim editorial Chapterly, dan akan kami gunakan untuk memperbarui panduan ini.</p>
                    <div class="flex flex-col sm:flex-row justify-center gap-3 pt-2">
                        <button class="px-7 py-3 bg-[#c7a64a] hover:bg-[#d4b356] text-black text-[11px] font-bold uppercase tracking-[0.22em] rounded-md transition-all active:scale-[0.98] shadow-lg shadow-[#c7a64a]/15">
                            Ya · Membantu
                        </button>
                        <button class="px-7 py-3 border border-neutral-700 hover:border-neutral-600 hover:bg-white/5 text-neutral-300 hover:text-white text-[11px] font-semibold uppercase tracking-[0.22em] rounded-md transition-all">
                            Tidak · Perlu perbaikan
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-between gap-4 text-[11px] uppercase tracking-[0.22em] text-neutral-500 pt-2">
                    <a href="{{ route('guides.category', $category->slug) }}" class="inline-flex items-center gap-2 hover:text-[#c7a64a] transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z" clip-rule="evenodd" /></svg>
                        Kembali ke {{ $category->name }}
                    </a>
                    <a href="{{ route('guides.index') }}" class="inline-flex items-center gap-2 hover:text-[#c7a64a] transition-colors">
                        Semua panduan chapterly
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" /></svg>
                    </a>
                </div>
            </footer>
        </article>

        <aside class="w-full lg:w-[300px] shrink-0">
            <div class="sticky lg:top-[6.5rem] space-y-5">
                <div class="rounded-[20px] p-6 border border-neutral-800 bg-[#121212]/70 space-y-4">
                    <h3 class="text-[10px] font-bold uppercase tracking-[0.28em] text-neutral-500">Di kategori yang sama</h3>
                    <div class="space-y-1.5">
                        @foreach($category->articles as $sibling)
                            <a href="{{ route('guides.show', [$category->slug, $sibling->slug]) }}"
                               @class([
                                   'block px-3 py-2.5 rounded-lg transition-all text-[13px] leading-snug border',
                                   $useWriterShell && $sibling->id === $article->id => 'bg-[#c7a64a]/10 border-[#c7a64a]/30 text-[#f2efe8] font-semibold',
                                   $useWriterShell && $sibling->id !== $article->id => 'text-neutral-400 hover:text-[#f2efe8] hover:bg-white/5 border-transparent',
                                   !$useWriterShell && $sibling->id === $article->id => 'bg-[#c7a64a]/10 border-[#c7a64a]/30 text-[#f2efe8] font-semibold',
                                   !$useWriterShell && $sibling->id !== $article->id => 'text-neutral-400 hover:text-[#f2efe8] hover:bg-white/[0.03] border-transparent',
                               ])>
                                {{ $sibling->title }}
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('guides.category', $category->slug) }}" class="inline-flex items-center gap-2 pt-1 text-[11px] font-bold uppercase tracking-[0.22em] text-[#c7a64a] hover:text-[#d4b356] transition-colors">
                        Lihat semua topik →
                    </a>
                </div>

                <div class="relative overflow-hidden rounded-[20px] border border-[#c7a64a]/25 bg-gradient-to-br from-[#161208] via-[#0e0b06] to-[#0a0a0a] p-6 space-y-4">
                    <div class="absolute -top-6 -right-6 w-32 h-32 bg-[#c7a64a]/10 rounded-full blur-3xl"></div>
                    <div class="relative space-y-2">
                        <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-[#c7a64a]">Masih butuh bantuan?</p>
                        <h3 class="font-serif text-[22px] leading-[1.1] text-[#f2efe8]">Konsultasi langsung dengan tim Chapterly.</h3>
                        <p class="text-[12.5px] leading-relaxed text-neutral-400">
                            Tim editorial dan kurasi kami siap membantu. Mulai dari baca draft, rekomendasi plot, sampai tips lewat tahap review.
                        </p>
                    </div>
                    <a href="mailto:writer-support@chapterly.quoros.id" class="relative block w-full py-3 bg-[#c7a64a] hover:bg-[#d4b356] text-black text-center text-[11px] font-bold uppercase tracking-[0.22em] rounded-md transition-all shadow-lg shadow-[#c7a64a]/15 active:scale-[0.98]">
                        Hubungi support →
                    </a>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
