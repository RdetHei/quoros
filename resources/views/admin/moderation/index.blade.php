@extends('layouts.admin')

@php
    $adminTitle = 'Content Moderation';
    $adminBreadcrumbs = ['Admin', 'Content Moderation'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-[1600px] space-y-5">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="admin-kicker">Catalogue / content moderation</p>
            <h2 class="admin-display mt-1">All Novels</h2>
            <p class="mt-2 text-sm text-slate-400">Pantau seluruh novel yang terdaftar di platform.</p>
        </div>
        <div class="text-right">
            <p class="admin-kicker">{{ number_format($novels->total()) }} novel terdaftar</p>
            <p class="admin-kicker mt-1" style="color:#c6a048">{{ number_format($pendingReports) }} laporan menunggu</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.moderation.index') }}" class="flex flex-wrap items-center gap-2">
        <input type="search" name="q" value="{{ $search }}" placeholder="⌕　Cari judul atau penulis..." class="console-input inline">
        <select name="status" class="console-select inline" onchange="this.form.submit()">
            <option value="all" @selected($status === 'all')>Status · semua</option>
            <option value="ongoing" @selected($status === 'ongoing')>Ongoing</option>
            <option value="hiatus" @selected($status === 'hiatus')>Hiatus</option>
            <option value="complete" @selected($status === 'complete')>Complete</option>
        </select>
        <button type="submit" class="console-btn sm">Filter</button>
    </form>

    <section class="console-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="console-table w-full text-left">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($novels as $novel)
                        <tr>
                            <td><span class="font-serif text-sm text-zinc-200">{{ $novel->title }}</span></td>
                            <td>{{ $novel->author?->name ?? 'Unknown' }}</td>
                            <td><span class="console-badge muted">{{ ucfirst($novel->status ?? 'ongoing') }}</span></td>
                            <td>{{ number_format($novel->view_count) }}</td>
                            <td>{{ number_format($novel->rating_avg, 1) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="console-empty">Tidak ada novel yang cocok dengan filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($novels->hasPages())
            <div class="console-pager flex items-center justify-between gap-3 border-t border-white/5 px-4 py-3">
                <span class="admin-kicker">Halaman {{ $novels->currentPage() }} / {{ $novels->lastPage() }}</span>
                <div class="flex gap-2">
                    @if($novels->previousPageUrl())<a href="{{ $novels->previousPageUrl() }}">Prev</a>@endif
                    @if($novels->nextPageUrl())<a href="{{ $novels->nextPageUrl() }}">Next</a>@endif
                </div>
            </div>
        @endif
    </section>
</div>
@endsection
