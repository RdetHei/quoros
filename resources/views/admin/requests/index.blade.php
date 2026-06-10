@extends('layouts.admin')

@php
    $adminTitle = 'Novel Requests';
    $adminBreadcrumbs = ['Admin', 'Novel Requests'];
@endphp

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-0.5 rounded-md bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold uppercase tracking-widest">
                    Moderation Queue
                </span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Novel Requests</p>
            </div>
            <h2 class="text-3xl md:text-4xl font-black tracking-tight text-slate-900 dark:text-white">
                Request <span class="text-indigo-600 dark:text-indigo-400">Moderation</span>
            </h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 max-w-2xl leading-relaxed">
                Review and manage reader suggestions for new titles to be added to the platform.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-100 dark:border-emerald-800 rounded-2xl text-emerald-600 dark:text-emerald-400 font-bold text-sm flex items-center gap-3 animate-in fade-in slide-in-from-top-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <x-admin.data-table class="p-0 border-none shadow-xl shadow-slate-200/50 dark:shadow-none">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/80 dark:bg-slate-800/50 text-left border-b border-slate-100 dark:border-slate-800">
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Requested By</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Novel Details</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Current Status</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Moderation Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($requests as $request)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors group">
                        <td class="px-6 py-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center shrink-0 font-bold text-slate-500 group-hover:border-indigo-200 dark:group-hover:border-indigo-900/50 transition-colors">
                                    {{ strtoupper(substr($request->user->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-800 dark:text-slate-100 truncate">{{ $request->user->name }}</p>
                                    <p class="text-[10px] font-medium text-slate-500 uppercase tracking-tighter">{{ $request->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 max-w-xs">
                            <p class="text-sm font-black text-slate-900 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $request->title }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 line-clamp-2 leading-relaxed italic">
                                {{ $request->description ?: 'No additional context provided.' }}
                            </p>
                        </td>
                        <td class="px-6 py-6 text-center">
                            @php
                                $variant = match($request->status) {
                                    'pending' => 'warning',
                                    'fulfilled' => 'success',
                                    'rejected' => 'danger',
                                    default => 'neutral',
                                };
                                $label = match($request->status) {
                                    'pending' => 'Pending Review',
                                    'fulfilled' => 'Approved',
                                    'rejected' => 'Rejected',
                                    default => 'Unknown',
                                };
                            @endphp
                            <x-admin.status-badge :label="$label" :variant="$variant" />
                        </td>
                        <td class="px-6 py-6">
                            <div class="flex items-center justify-end gap-2">
                                @if($request->status === 'pending')
                                    <form action="{{ route('admin.requests.status', $request->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="fulfilled">
                                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-500/10">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.requests.status', $request->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest hover:bg-rose-500 transition-all shadow-lg shadow-rose-500/10">
                                            Reject
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.requests.status', $request->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="pending">
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                                            Re-evaluate
                                        </button>
                                    </form>
                                @endif
                                
                                <div class="w-px h-4 bg-slate-200 dark:bg-slate-800 mx-1"></div>

                                <form action="{{ route('admin.requests.destroy', $request->id) }}" method="POST" onsubmit="return confirm('Securely delete this request permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors group-hover:bg-rose-50 dark:group-hover:bg-rose-900/20 rounded-lg">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-20 text-center">
                            <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-slate-800/50 flex items-center justify-center mx-auto mb-6">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300 dark:text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white">Queue Clear</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">All reader requests have been processed.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.data-table>

    @if($requests->hasPages())
        <div class="px-4">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection
