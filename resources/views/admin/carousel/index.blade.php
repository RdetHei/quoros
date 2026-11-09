@extends('layouts.admin')

@php
    $adminTitle = 'Spotlight Slider';
    $adminBreadcrumbs = ['Admin', 'Curation', 'Spotlight Slider'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-[1600px] space-y-5">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="admin-kicker">Curation / homepage spotlight</p>
            <h2 class="admin-display mt-1">Spotlight Slider</h2>
            <p class="mt-2 text-sm text-slate-400">Pilih novel yang tampil di banner utama halaman depan.</p>
        </div>
        <p class="admin-kicker">{{ number_format($featuredNovels->count()) }} novel aktif di slider</p>
    </div>

    <section class="console-card p-5">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="admin-kicker" style="color:#c5a13c">Currently featured</p>
                <h3 class="admin-section-title">Active slides</h3>
            </div>
        </div>

        @if($featuredNovels->isEmpty())
            <div class="mt-4 rounded border border-dashed border-white/15 p-10 text-center text-sm text-zinc-500">
                Belum ada novel terpilih. Sistem akan memakai novel populer sebagai fallback.
            </div>
        @else
            <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                @foreach($featuredNovels as $novel)
                    <div class="group relative overflow-hidden rounded border border-white/10">
                        <div class="aspect-[16/9] overflow-hidden bg-black/40">
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='/error.png'">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 p-4">
                            <h4 class="truncate font-serif text-sm text-white">{{ $novel->title }}</h4>
                            <p class="mt-1 text-[11px] text-zinc-400">{{ $novel->author?->name ?? 'Unknown' }}</p>
                            <form action="{{ route('admin.carousel.toggle', $novel) }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" class="console-btn sm danger w-full">Remove from spotlight</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <section class="console-card p-5">
        <div class="flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="admin-kicker" style="color:#c5a13c">Add novel</p>
                <h3 class="admin-section-title">Catalogue library</h3>
            </div>
            <form action="{{ route('admin.carousel.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="⌕　Cari judul novel..." class="console-input inline">
                <button type="submit" class="console-btn sm">Search</button>
            </form>
        </div>

        <div class="mt-4 divide-y divide-white/5">
            @forelse($availableNovels as $novel)
                <div class="flex items-center justify-between gap-4 py-3">
                    <div class="flex min-w-0 items-center gap-3">
                        <span class="h-14 w-10 shrink-0 overflow-hidden rounded border border-white/10">
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" alt="{{ $novel->title }}" class="h-full w-full object-cover" loading="lazy" decoding="async" onerror="this.onerror=null; this.src='/error.png'">
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate font-serif text-sm text-zinc-200">{{ $novel->title }}</span>
                            <span class="admin-kicker">{{ $novel->author?->name ?? 'Unknown' }}</span>
                        </span>
                    </div>
                    <form action="{{ route('admin.carousel.toggle', $novel) }}" method="POST">
                        @csrf
                        <button type="submit" class="console-btn sm">Add</button>
                    </form>
                </div>
            @empty
                <p class="console-empty">Tidak ada novel lain yang cocok.</p>
            @endforelse
        </div>

        @if($availableNovels->hasPages())
            <div class="console-pager mt-4 flex items-center justify-between gap-3 border-t border-white/5 pt-3">
                <span class="admin-kicker">Halaman {{ $availableNovels->currentPage() }} / {{ $availableNovels->lastPage() }}</span>
                <div class="flex gap-2">
                    @if($availableNovels->previousPageUrl())<a href="{{ $availableNovels->previousPageUrl() }}">Prev</a>@endif
                    @if($availableNovels->nextPageUrl())<a href="{{ $availableNovels->nextPageUrl() }}">Next</a>@endif
                </div>
            </div>
        @endif
    </section>
</div>
@endsection
