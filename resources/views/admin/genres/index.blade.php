@extends('layouts.admin')

@php
    $adminTitle = 'Manage Genres';
    $adminBreadcrumbs = ['Admin', 'Curation', 'Genres'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-[1400px] space-y-5">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="admin-kicker">Curation / classification</p>
            <h2 class="admin-display mt-1">Manage Genres</h2>
            <p class="mt-2 text-sm text-slate-400">Tambahkan atau ubah genre untuk klasifikasi novel.</p>
        </div>
        <a href="{{ route('admin.genres.create') }}" class="console-btn">＋ Add genre</a>
    </div>

    <form method="GET" action="{{ route('admin.genres.index') }}" class="flex flex-wrap items-center gap-2">
        <input type="search" name="q" value="{{ $search }}" placeholder="⌕　Cari genre..." class="console-input inline">
        <button type="submit" class="console-btn sm">Search</button>
    </form>

    <section class="console-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="console-table w-full text-left">
                <thead>
                    <tr>
                        <th>Genre name</th>
                        <th>Novel count</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($genres as $genre)
                        <tr>
                            <td>
                                <span class="block font-serif text-sm text-zinc-200">{{ $genre->name }}</span>
                                <span class="admin-kicker">{{ $genre->slug }}</span>
                            </td>
                            <td><span class="console-badge info">{{ number_format($genre->novels_count) }} novel</span></td>
                            <td>
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.genres.edit', $genre) }}" class="console-btn sm neutral">Edit</a>
                                    <form action="{{ route('admin.genres.destroy', $genre) }}" method="POST" onsubmit="return confirm('Hapus genre ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="console-btn sm danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="console-empty">Belum ada genre yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($genres->hasPages())
            <div class="console-pager flex items-center justify-between gap-3 border-t border-white/5 px-4 py-3">
                <span class="admin-kicker">Halaman {{ $genres->currentPage() }} / {{ $genres->lastPage() }}</span>
                <div class="flex gap-2">
                    @if($genres->previousPageUrl())<a href="{{ $genres->previousPageUrl() }}">Prev</a>@endif
                    @if($genres->nextPageUrl())<a href="{{ $genres->nextPageUrl() }}">Next</a>@endif
                </div>
            </div>
        @endif
    </section>
</div>
@endsection
