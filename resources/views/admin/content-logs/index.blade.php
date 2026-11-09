@extends('layouts.admin')

@php
    $adminTitle = 'Content Logs';
    $adminBreadcrumbs = ['Admin', 'Global Logs'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-[1600px] space-y-5">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="admin-kicker">Audit stream / chapter uploads</p>
            <h2 class="admin-display mt-1">Content Logs</h2>
            <p class="mt-2 text-sm text-slate-400">Riwayat unggahan chapter terbaru di seluruh platform.</p>
        </div>
        <p class="admin-kicker">{{ number_format($recentChapters->total()) }} chapter tercatat</p>
    </div>

    <form method="GET" action="{{ route('admin.content-logs.index') }}" class="flex flex-wrap items-center gap-2">
        <input type="search" name="q" value="{{ $search }}" placeholder="⌕　Cari chapter atau novel..." class="console-input inline">
        <button type="submit" class="console-btn sm">Filter</button>
    </form>

    <section class="console-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="console-table w-full text-left">
                <thead>
                    <tr>
                        <th>Chapter</th>
                        <th>Novel</th>
                        <th>Author</th>
                        <th>Uploaded at</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentChapters as $chapter)
                        <tr>
                            <td><span class="font-serif text-sm text-zinc-200">{{ $chapter->title }}</span></td>
                            <td>{{ $chapter->novel?->title ?? 'Unknown' }}</td>
                            <td>{{ $chapter->novel?->author?->name ?? 'Unknown' }}</td>
                            <td class="whitespace-nowrap text-zinc-500">{{ $chapter->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="console-empty">Belum ada log chapter yang cocok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($recentChapters->hasPages())
            <div class="console-pager flex items-center justify-between gap-3 border-t border-white/5 px-4 py-3">
                <span class="admin-kicker">Halaman {{ $recentChapters->currentPage() }} / {{ $recentChapters->lastPage() }}</span>
                <div class="flex gap-2">
                    @if($recentChapters->previousPageUrl())<a href="{{ $recentChapters->previousPageUrl() }}">Prev</a>@endif
                    @if($recentChapters->nextPageUrl())<a href="{{ $recentChapters->nextPageUrl() }}">Next</a>@endif
                </div>
            </div>
        @endif
    </section>
</div>
@endsection
