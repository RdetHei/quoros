@extends('layouts.app')

@php
    use App\Enums\ReportReason;
    use App\Enums\ReportStatus;
@endphp

@section('content')
<div class="max-w-6xl mx-auto mb-12">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-2 h-10 bg-rose-600 rounded-full"></div>
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Moderation & Reports</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Review user reports and manage account bans.</p>
        </div>
    </div>

    <div class="flex flex-wrap gap-2 mb-6">
        @foreach(['pending' => 'Pending', 'reviewed' => 'Reviewed', 'dismissed' => 'Dismissed', 'all' => 'All'] as $key => $label)
            <a href="{{ route('admin.reports.index', ['status' => $key]) }}"
               class="px-4 py-2 text-xs font-bold uppercase tracking-widest rounded-xl border transition-all
                      {{ $status === $key
                          ? 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 border-transparent'
                          : 'bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-700 hover:border-slate-300' }}">
                {{ $label }}
                @if($key !== 'all' && isset($counts[$key]))
                    <span class="ml-1 opacity-70">({{ $counts[$key] }})</span>
                @endif
            </a>
        @endforeach
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50">
                        <th class="px-4 md:px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Reporter</th>
                        <th class="px-4 md:px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Target</th>
                        <th class="px-4 md:px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Reason</th>
                        <th class="px-4 md:px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="px-4 md:px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                    @forelse($reports as $report)
                        @php
                            $subject = $report->subjectUser();
                            $statusClasses = [
                                'pending' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-900/20 dark:text-amber-400',
                                'reviewed' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400',
                                'dismissed' => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400',
                            ];
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 align-top">
                            <td class="px-4 md:px-6 py-6">
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $report->reporter->name }}</p>
                                <p class="text-xs text-slate-400 mt-1">{{ $report->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="px-4 md:px-6 py-6">
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $report->reportableLabel() }}</p>
                                @if($report->details)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 italic line-clamp-3">&ldquo;{{ $report->details }}&rdquo;</p>
                                @endif
                                @if($subject)
                                    <p class="text-[10px] text-slate-400 mt-2 uppercase tracking-widest font-bold">Subject: {{ $subject->name }}</p>
                                @endif
                            </td>
                            <td class="px-4 md:px-6 py-6">
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ $report->reason->label() }}</span>
                            </td>
                            <td class="px-4 md:px-6 py-6">
                                <span class="px-3 py-1 text-[10px] font-black uppercase tracking-widest rounded-full border {{ $statusClasses[$report->status->value] ?? '' }}">
                                    {{ $report->status->label() }}
                                </span>
                            </td>
                            <td class="px-4 md:px-6 py-6">
                                <div class="flex flex-col gap-2 min-w-[200px]">
                                    @if($report->status === ReportStatus::Pending)
                                        <form action="{{ route('admin.reports.update', $report) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="reviewed">
                                            <button type="submit" class="w-full px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg">Mark as reviewed</button>
                                        </form>
                                        <form action="{{ route('admin.reports.update', $report) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="dismissed">
                                            <button type="submit" class="w-full px-3 py-1.5 bg-slate-500 hover:bg-slate-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg">Dismiss report</button>
                                        </form>
                                    @endif

                                    @if($subject && $subject->role !== 'admin')
                                        @if($subject->isCurrentlyBanned())
                                            <form action="{{ route('admin.users.unban', $subject) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full px-3 py-1.5 bg-indigo-500 hover:bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg">Unban user</button>
                                            </form>
                                        @else
                                            <details class="group">
                                                <summary class="cursor-pointer px-3 py-1.5 bg-rose-500 hover:bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-lg text-center list-none">Ban user</summary>
                                                <form action="{{ route('admin.users.ban', $subject) }}" method="POST" class="mt-2 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700 space-y-2">
                                                    @csrf
                                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Ban until (empty = permanent)</label>
                                                    <input type="datetime-local" name="banned_until" class="w-full text-xs rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-2 py-1.5">
                                                    <textarea name="ban_reason" rows="2" placeholder="Ban reason..." class="w-full text-xs rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-2 py-1.5 resize-none"></textarea>
                                                    <button type="submit" class="w-full px-3 py-1.5 bg-rose-600 text-white text-[10px] font-black uppercase rounded-lg">Confirm ban</button>
                                                </form>
                                            </details>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">No reports found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reports->hasPages())
            <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
