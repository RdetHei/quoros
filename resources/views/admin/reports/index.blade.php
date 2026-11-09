@extends('layouts.admin')

@php
    use App\Enums\ReportStatus;

    $adminTitle = 'Moderation & Reports';
    $adminBreadcrumbs = ['Admin', 'Security Reports'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-[1600px] space-y-5">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="admin-kicker">Moderation / user reports</p>
            <h2 class="admin-display mt-1">Security Reports</h2>
            <p class="mt-2 text-sm text-slate-400">Tinjau laporan pengguna dan kelola pembatasan akun.</p>
        </div>
        <p class="admin-kicker">{{ number_format($counts['pending'] ?? 0) }} laporan menunggu tindakan</p>
    </div>

    <nav class="flex flex-wrap gap-2">
        @foreach(['pending' => 'Pending', 'reviewed' => 'Reviewed', 'dismissed' => 'Dismissed', 'all' => 'All'] as $key => $label)
            <a href="{{ route('admin.reports.index', ['status' => $key]) }}"
               class="console-chip {{ $status === $key ? 'is-active' : '' }}">
                {{ $label }}
                @if($key !== 'all' && isset($counts[$key]))
                    <span class="opacity-70">({{ $counts[$key] }})</span>
                @endif
            </a>
        @endforeach
    </nav>

    <section class="console-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="console-table w-full text-left">
                <thead>
                    <tr>
                        <th>Reporter</th>
                        <th>Target</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        @php
                            $subject = $report->subjectUser();
                            $statusTone = match($report->status) {
                                ReportStatus::Pending => 'console-badge warn',
                                ReportStatus::Reviewed => 'console-badge',
                                default => 'console-badge muted',
                            };
                        @endphp
                        <tr class="align-top">
                            <td>
                                <p class="font-serif text-sm text-zinc-200">{{ $report->reporter?->name ?? 'Unknown' }}</p>
                                <p class="admin-kicker mt-1">{{ $report->created_at->diffForHumans() }}</p>
                            </td>
                            <td>
                                <p class="font-serif text-sm text-zinc-200">{{ $report->reportableLabel() }}</p>
                                @if($report->details)
                                    <p class="mt-2 text-xs italic text-zinc-500">&ldquo;{{ $report->details }}&rdquo;</p>
                                @endif
                                @if($report->admin_notes)
                                    <p class="mt-2 rounded border border-white/10 bg-white/[.03] px-2 py-1 text-[11px] text-zinc-400">Catatan: {{ $report->admin_notes }}</p>
                                @endif
                                @if($subject)
                                    <p class="admin-kicker mt-2">Subject: {{ $subject->name }}</p>
                                @endif
                            </td>
                            <td><span class="font-serif text-sm text-zinc-300">{{ $report->reason->label() }}</span></td>
                            <td><span class="{{ $statusTone }}">{{ $report->status->label() }}</span></td>
                            <td>
                                <div class="flex min-w-[210px] flex-col gap-2">
                                    @if($report->status === ReportStatus::Pending)
                                        <details>
                                            <summary class="console-btn sm cursor-pointer list-none">Review report</summary>
                                            <form action="{{ route('admin.reports.update', $report) }}" method="POST" class="mt-2 grid gap-2 rounded border border-white/10 bg-white/[.03] p-3">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" class="console-select">
                                                    <option value="reviewed">Tandai reviewed</option>
                                                    <option value="dismissed">Dismiss</option>
                                                </select>
                                                <textarea name="admin_notes" rows="2" placeholder="Catatan admin (opsional)" class="console-textarea !min-h-0 !py-2"></textarea>
                                                <button type="submit" class="console-btn sm">Simpan</button>
                                            </form>
                                        </details>
                                        <form action="{{ route('admin.reports.update', $report) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="dismissed">
                                            <button type="submit" class="console-btn sm neutral w-full">Dismiss</button>
                                        </form>
                                    @endif

                                    @if($subject && $subject->role !== 'admin')
                                        @if($subject->isCurrentlyBanned())
                                            <form action="{{ route('admin.users.unban', $subject) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="console-btn sm ghost w-full">Unban user</button>
                                            </form>
                                        @else
                                            <details>
                                                <summary class="console-btn sm danger cursor-pointer list-none">Ban user</summary>
                                                <form action="{{ route('admin.users.ban', $subject) }}" method="POST" class="mt-2 grid gap-2 rounded border border-white/10 bg-white/[.03] p-3">
                                                    @csrf
                                                    <label class="admin-kicker">Ban until (kosong = permanen)</label>
                                                    <input type="datetime-local" name="banned_until" class="console-input">
                                                    <textarea name="ban_reason" rows="2" placeholder="Alasan pembatasan..." class="console-textarea !min-h-0 !py-2"></textarea>
                                                    <button type="submit" class="console-btn sm danger">Konfirmasi ban</button>
                                                </form>
                                            </details>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="console-empty">Tidak ada laporan pada filter ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
            <div class="console-pager flex items-center justify-between gap-3 border-t border-white/5 px-4 py-3">
                <span class="admin-kicker">Halaman {{ $reports->currentPage() }} / {{ $reports->lastPage() }}</span>
                <div class="flex gap-2">
                    @if($reports->previousPageUrl())<a href="{{ $reports->previousPageUrl() }}">Prev</a>@endif
                    @if($reports->nextPageUrl())<a href="{{ $reports->nextPageUrl() }}">Next</a>@endif
                </div>
            </div>
        @endif
    </section>
</div>
@endsection
