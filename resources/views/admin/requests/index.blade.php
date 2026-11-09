@extends('layouts.admin')

@php
    $adminTitle = 'Novel Requests';
    $adminBreadcrumbs = ['Admin', 'Novel Approval Queue'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-[1600px] space-y-5">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="admin-kicker">Moderation queue / reader suggestions</p>
            <h2 class="admin-display mt-1">Request Moderation</h2>
            <p class="mt-2 text-sm text-slate-400">Tinjau dan kelola permintaan judul baru dari pembaca.</p>
        </div>
        <p class="admin-kicker">{{ number_format($requests->total()) }} permintaan masuk</p>
    </div>

    <section class="console-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="console-table w-full text-left">
                <thead>
                    <tr>
                        <th>Requested by</th>
                        <th>Novel details</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $novelRequest)
                        @php
                            $tone = match($novelRequest->status) {
                                'pending' => 'warn',
                                'fulfilled' => '',
                                'rejected' => 'danger',
                                default => 'muted',
                            };
                            $label = match($novelRequest->status) {
                                'pending' => 'Pending review',
                                'fulfilled' => 'Approved',
                                'rejected' => 'Rejected',
                                default => 'Unknown',
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <span class="grid h-8 w-8 shrink-0 place-items-center rounded-full border border-white/10 font-serif text-sm text-zinc-300">{{ strtoupper(substr($novelRequest->user->name ?? '?', 0, 1)) }}</span>
                                    <span class="min-w-0">
                                        <span class="block truncate font-serif text-sm text-zinc-200">{{ $novelRequest->user->name ?? 'Unknown' }}</span>
                                        <span class="admin-kicker">{{ $novelRequest->created_at->diffForHumans() }}</span>
                                    </span>
                                </div>
                            </td>
                            <td class="max-w-sm">
                                <p class="font-serif text-sm text-zinc-200">{{ $novelRequest->title }}</p>
                                <p class="mt-1 text-xs italic text-zinc-500">{{ $novelRequest->description ?: 'Tidak ada konteks tambahan.' }}</p>
                            </td>
                            <td><span class="console-badge {{ $tone }}">{{ $label }}</span></td>
                            <td>
                                <div class="flex flex-wrap items-center justify-end gap-2">
                                    @if($novelRequest->status === 'pending')
                                        <form action="{{ route('admin.requests.status', $novelRequest) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="fulfilled">
                                            <button type="submit" class="console-btn sm">Approve</button>
                                        </form>
                                        <form action="{{ route('admin.requests.status', $novelRequest) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button type="submit" class="console-btn sm danger">Reject</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.requests.status', $novelRequest) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="pending">
                                            <button type="submit" class="console-btn sm neutral">Re-evaluate</button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.requests.destroy', $novelRequest) }}" method="POST" onsubmit="return confirm('Hapus permintaan ini secara permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="console-btn sm neutral" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="console-empty">
                                <span class="block font-serif text-lg text-zinc-300">Queue Clear</span>
                                Semua permintaan pembaca sudah diproses.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
            <div class="console-pager flex items-center justify-between gap-3 border-t border-white/5 px-4 py-3">
                <span class="admin-kicker">Halaman {{ $requests->currentPage() }} / {{ $requests->lastPage() }}</span>
                <div class="flex gap-2">
                    @if($requests->previousPageUrl())<a href="{{ $requests->previousPageUrl() }}">Prev</a>@endif
                    @if($requests->nextPageUrl())<a href="{{ $requests->nextPageUrl() }}">Next</a>@endif
                </div>
            </div>
        @endif
    </section>
</div>
@endsection
