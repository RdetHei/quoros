@php
    $useWriterShell = auth()->check() && auth()->user()->role === 'writer';
@endphp

@extends($useWriterShell ? 'layouts.writer' : 'layouts.app', [
    'title' => 'Guideline Center · Chapterly by Quoros',
    'subtitle' => 'Panduan resmi penulis Quoros: dari konsep, penulisan, sampai novel diterbitkan dan dibayar.',
])

@section('content')
<div class="{{ $useWriterShell ? 'space-y-10' : 'max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-24' }}">
    @if(!$useWriterShell)
    <nav class="mb-10 flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.22em] text-neutral-500">
        <a href="{{ route('welcome') }}" class="hover:text-[#c7a64a] transition-colors">BERANDA</a>
        <span>/</span>
        <span class="text-neutral-300">GUIDANCE CENTER</span>
    </nav>
    @endif

    <section class="relative overflow-hidden rounded-[28px] border border-[#c7a64a]/15 bg-gradient-to-br from-[#161208] via-[#0a0a0a] to-[#121212] p-8 md:p-12 lg:p-16 mb-12">
        <div class="absolute -top-24 -right-16 w-[380px] h-[380px] rounded-full bg-[#c7a64a]/10 blur-[120px]"></div>
        <div class="absolute -bottom-20 -left-10 w-[300px] h-[300px] rounded-full bg-[#5a4115]/20 blur-[100px]"></div>

        <div class="relative max-w-4xl space-y-8">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.32em] text-[#c7a64a] mb-4">CHAPTERLY · GUIDELINE CENTER</p>
                <h1 class="font-serif text-[44px] md:text-[58px] leading-[1.02] tracking-tight text-[#f2efe8]">
                    Dari penulis pemula menjadi penulis berpenghasilan.
                </h1>
                <p class="mt-6 text-[15px] md:text-lg leading-relaxed text-neutral-400 max-w-2xl">
                    Temukan jawaban, panduan, dan tips untuk memaksimalkan perjalanan kepenulisan kamu di Quoros. Setiap panduan ditulis dan diperbarui oleh tim editorial Chapterly.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('guides.category', 'how-to-write-novel') }}" class="inline-flex items-center gap-3 px-8 py-3.5 bg-[#c7a64a] hover:bg-[#d4b356] text-black text-[11.5px] font-bold uppercase tracking-[0.22em] rounded-md shadow-lg shadow-[#c7a64a]/20 active:scale-[0.98] transition-all">
                    Mulai dari panduan 9 langkah →
                </a>

                @auth
                    @if(Auth::user()->role === 'user')
                        <form method="POST" action="{{ route('dashboard.become-writer') }}" class="inline">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-3 px-8 py-3.5 border border-[#c7a64a]/40 text-[#c7a64a] hover:bg-[#c7a64a]/10 text-[11.5px] font-bold uppercase tracking-[0.22em] rounded-md active:scale-[0.98] transition-all">
                                Apply jadi Writer sekarang
                            </button>
                        </form>
                    @elseif(Auth::user()->role === 'writer')
                        <a href="{{ route('writer.dashboard') }}" class="inline-flex items-center gap-3 px-8 py-3.5 border border-neutral-700 rounded-md text-[11.5px] font-semibold uppercase tracking-[0.22em] text-neutral-300 hover:bg-white/5 hover:text-white hover:border-neutral-600 transition-all">
                            ← Kembali ke studio
                        </a>
                    @endif
                @endauth
                @guest
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-3 px-8 py-3.5 border border-neutral-700 rounded-md text-[11.5px] font-semibold uppercase tracking-[0.22em] text-neutral-300 hover:bg-white/5 hover:text-white hover:border-neutral-600 transition-all">
                        Daftar akun gratis
                    </a>
                @endguest
            </div>
        </div>
    </section>

    @if(!$useWriterShell)
    <section class="mb-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="border border-neutral-800 bg-[#121212]/60 rounded-2xl p-6 space-y-3">
                <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-[#c7a64a]">ONBOARDING · 9 STEP</p>
                <h3 class="font-serif text-[22px] text-[#f2efe8] leading-tight">Cara menulis novel pertama dari konsep sampai terbit.</h3>
                <p class="text-[12.5px] leading-relaxed text-neutral-400">Panduan 3 bagian × 9 langkah. Mulai dari persiapan, eksekusi, sampai menerbitkan bab pertama. Disertai checklist & FAQ paling sering ditanyakan.</p>
                <a href="{{ route('guides.category', 'how-to-write-novel') }}" class="inline-flex items-center gap-2 pt-2 text-[11px] font-bold uppercase tracking-[0.24em] text-[#c7a64a] hover:text-[#d4b356] transition-colors">
                    Buka panduan →
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" /></svg>
                </a>
            </div>

            <div class="border border-neutral-800 bg-[#121212]/60 rounded-2xl p-6 space-y-3">
                <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-neutral-500">PENGETAHUAN PENULIS</p>
                <h3 class="font-serif text-[22px] text-[#f2efe8] leading-tight">Panduan monetisasi & program Chapterly Rewards.</h3>
                <p class="text-[12.5px] leading-relaxed text-neutral-400">Cara kerja royalti, tip, koin chapter, program penandatanganan eksklusif, dan kriteria apa saja yang dinilai tim kurasi Chapterly.</p>
                <span class="inline-flex items-center gap-2 pt-2 text-[11px] font-bold uppercase tracking-[0.24em] text-neutral-500">
                    Akan segera tersedia
                </span>
            </div>

            <div class="border border-neutral-800 bg-[#121212]/60 rounded-2xl p-6 space-y-3">
                <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-neutral-500">PERATURAN KOMUNITAS</p>
                <h3 class="font-serif text-[22px] text-[#f2efe8] leading-tight">Panduan konten, rating, dan batas usia konten.</h3>
                <p class="text-[12.5px] leading-relaxed text-neutral-400">Pahami rating konten (13+, 17+, 21+), peraturan hak cipta, daftar kategori yang dilarang, dan cara submit tanpa ditolak otomatis.</p>
                <span class="inline-flex items-center gap-2 pt-2 text-[11px] font-bold uppercase tracking-[0.24em] text-neutral-500">
                    Akan segera tersedia
                </span>
            </div>
        </div>
    </section>
    @endif

    @auth
        @if(Auth::user()->role === 'user' && !$useWriterShell)
        <section class="relative overflow-hidden rounded-[28px] border border-[#c7a64a]/20 bg-gradient-to-br from-[#12100a] via-[#0a0a0a] to-[#0d0b06] p-8 md:p-10 lg:p-12 mb-12">
            <div class="absolute -right-16 -top-16 w-48 h-48 bg-[#c7a64a]/10 rounded-full blur-3xl"></div>

            <div class="relative grid grid-cols-1 lg:grid-cols-[1.4fr_minmax(0,1fr)] gap-10 items-center">
                <div class="space-y-6">
                    <div>
                        <h2 class="font-serif text-[30px] md:text-[38px] leading-[1.05] text-[#f2efe8] mb-3">
                            Jadilah bagian dari Chapterly, dan bagikan ceritamu.
                        </h2>
                        <p class="text-[13.5px] leading-relaxed text-neutral-400">
                            Bagikan karyamu ke ribuan pembaca setia Quoros. Tanpa biaya pendaftaran, tanpa batas upload, dan royalti 70% ke penulis — mulai dari chapter pertama kamu diterbitkan.
                        </p>
                    </div>

                    <div class="bg-[#0a0a0a]/60 border border-neutral-800 rounded-2xl p-5 md:p-6 space-y-5">
                        <p class="text-[10px] font-black uppercase tracking-[0.28em] text-[#c7a64a]">Panduan sebelum Apply Writer</p>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-emerald-500/30 bg-emerald-500/10 text-emerald-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                </span>
                                <p class="text-[12px] text-neutral-400 leading-[1.8]"><strong class="text-neutral-200">Manual Review:</strong> Semua pendaftaran melewati tahap peninjauan oleh tim editorial Chapterly (biasanya selesai dalam 1x24 jam).</p>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-red-500/30 bg-red-500/10 text-red-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                </span>
                                <p class="text-[12px] text-neutral-400 leading-[1.8]"><strong class="text-neutral-200">Strict Prohibitions:</strong> Pornografi eksplisit, eksploitasi minor, pelanggaran privasi, dan plagiasi adalah alasan penolakan permanen.</p>
                            </li>
                        </ul>
                    </div>
                </div>

                <form method="POST" action="{{ route('dashboard.become-writer') }}" class="relative z-10 space-y-5 border border-[#c7a64a]/30 bg-black/40 backdrop-blur rounded-2xl p-6 md:p-8">
                    @csrf
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#c7a64a]">STEP · AKTIFKAN HAK AKSES WRITER</p>
                        <h3 class="font-serif text-[24px] text-[#f2efe8] leading-tight">Siap memulai karier kepenulisanmu?</h3>
                    </div>
                    <p class="text-[12.5px] leading-relaxed text-neutral-400">
                        Klik tombol di bawah ini, akun kamu otomatis beralih menjadi <span class="text-[#c7a64a] font-semibold">Writer</span> dan mendapatkan akses penuh ke Chapterly Studio beserta semua panduan di balik paywall.
                    </p>
                    <button type="submit" class="w-full py-4 bg-[#c7a64a] hover:bg-[#d4b356] text-black font-bold uppercase tracking-[0.2em] rounded-xl transition-all shadow-lg shadow-[#c7a64a]/20 active:scale-[0.98]">
                        Apply as Writer
                    </button>
                    <p class="text-[10px] leading-relaxed text-neutral-500 uppercase tracking-[0.2em] text-center">
                        Dengan mendaftar kamu menyetujui peraturan komunitas &amp; syarat layanan Chapterly.
                    </p>
                </form>
            </div>
        </section>
        @endif
    @endauth

    @unless($useWriterShell)
    <section>
        <div class="flex items-end justify-between mb-7">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-neutral-500 mb-2">SEMUA PANDUAN · CHAPTERLY GUIDE CENTER</p>
                <h2 class="font-serif text-[28px] md:text-[34px] text-[#f2efe8]">Perpustakaan panduan penulis terlengkap.</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($categories as $category)
                <div class="rounded-2xl p-6 md:p-7 border border-neutral-800 bg-[#121212]/50 hover:border-[#c7a64a]/35 hover:bg-[#15130d] transition-all group space-y-6 min-h-[320px] flex flex-col justify-between">
                    <div class="space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-lg bg-[#c7a64a]/10 border border-[#c7a64a]/20 text-[#c7a64a] transition-transform group-hover:scale-105">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h3 class="text-[17px] font-semibold text-[#f2efe8] leading-snug">{{ $category->name }}</h3>
                        </div>

                        <p class="text-neutral-400 leading-[1.75] text-[13px] line-clamp-3">{{ $category->description }}</p>

                        <ul class="space-y-2.5 pt-1 border-t border-neutral-800/80">
                            @foreach($category->articles->take(4) as $article)
                                <li>
                                    <a href="{{ route('guides.show', [$category->slug, $article->slug]) }}" class="flex items-center gap-2 transition-colors group/link text-[12.5px] text-neutral-400 hover:text-[#c7a64a]">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0 text-neutral-600 group-hover/link:text-[#c7a64a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                        <span class="truncate">{{ $article->title }}</span>
                                    </a>
                                </li>
                            @endforeach
                            @if($category->articles->count() > 4)
                                <li class="pt-1 text-[11px] uppercase tracking-[0.22em] text-neutral-600 pl-[22px]">
                                    + {{ $category->articles->count() - 4 }} topik lainnya
                                </li>
                            @endif
                        </ul>
                    </div>

                    <a href="{{ route('guides.category', $category->slug) }}" class="inline-flex items-center justify-between gap-2 text-[11px] font-bold uppercase tracking-[0.24em] text-[#c7a64a] hover:text-[#d4b356] transition-colors pt-4 border-t border-neutral-800/80">
                        <span>Telusuri kategori</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" /></svg>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-20 border border-dashed border-neutral-800 rounded-2xl bg-[#121212]/30">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-4 text-neutral-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    <p class="text-[13px] text-neutral-500 uppercase tracking-[0.22em]">Belum ada panduan yang diterbitkan.</p>
                </div>
            @endforelse
        </div>
    </section>
    @endunless
</div>
@endsection
