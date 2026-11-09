@extends('layouts.admin')

@php
    $adminTitle = 'Announcements';
    $adminBreadcrumbs = ['Admin', 'Platform Settings', 'Announcements'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-[1600px] space-y-5">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="admin-kicker">Communications / broadcasts</p>
            <h2 class="admin-display mt-1">Announcements</h2>
            <p class="mt-2 text-sm text-slate-400">Kelola pengumuman yang tampil di seluruh platform.</p>
        </div>
        <a href="{{ route('admin.announcements.create') }}" class="console-btn">＋ New announcement</a>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @forelse($announcements as $announcement)
            <section class="console-card p-5">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="font-serif text-lg text-zinc-100">{{ $announcement->title }}</h3>
                            <span class="console-badge {{ $announcement->is_active ? '' : 'muted' }}">{{ $announcement->is_active ? 'Active' : 'Inactive' }}</span>
                            @if($announcement->type)
                                <span class="console-badge info">{{ $announcement->type }}</span>
                            @endif
                        </div>
                        <p class="admin-kicker mt-2">{{ $announcement->created_at->format('d M Y H:i') }}</p>
                        <p class="mt-3 whitespace-pre-line text-sm text-zinc-400">{{ $announcement->content }}</p>
                        @if($announcement->link)
                            <a href="{{ $announcement->link }}" target="_blank" rel="noopener" class="admin-link mt-3 inline-block">Buka tautan →</a>
                        @endif
                    </div>

                    <div class="flex shrink-0 flex-col gap-2 sm:w-44">
                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="console-btn sm neutral w-full">Edit</a>
                        <form action="{{ route('admin.announcements.toggle', $announcement) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="console-btn sm ghost w-full">{{ $announcement->is_active ? 'Deactivate' : 'Activate' }}</button>
                        </form>
                        <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini secara permanen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="console-btn sm danger w-full">Delete</button>
                        </form>
                    </div>
                </div>
            </section>
        @empty
            <section class="console-card">
                <p class="console-empty">Belum ada pengumuman. Buat yang pertama untuk pembaca kamu.</p>
            </section>
        @endforelse
    </div>

    @if($announcements->hasPages())
        <div class="console-pager flex items-center justify-between gap-3">
            <span class="admin-kicker">Halaman {{ $announcements->currentPage() }} / {{ $announcements->lastPage() }}</span>
            <div class="flex gap-2">
                @if($announcements->previousPageUrl())<a href="{{ $announcements->previousPageUrl() }}">Prev</a>@endif
                @if($announcements->nextPageUrl())<a href="{{ $announcements->nextPageUrl() }}">Next</a>@endif
            </div>
        </div>
    @endif
</div>
@endsection
