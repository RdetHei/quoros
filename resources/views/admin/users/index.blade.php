@extends('layouts.admin')

@php
    $adminTitle = 'User Management';
    $adminBreadcrumbs = ['Admin', 'User Management'];
@endphp

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Filters -->
    <x-admin.admin-card class="p-6">
        <form method="GET" class="flex flex-col md:flex-row items-end gap-4">
            <div class="flex-1 w-full space-y-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Filter Role</label>
                <select name="role" class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-2.5 text-sm font-semibold focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                    <option value="all" @selected($role === 'all')>All Roles</option>
                    <option value="user" @selected($role === 'user')>Reader</option>
                    <option value="writer" @selected($role === 'writer')>Writer</option>
                    <option value="admin" @selected($role === 'admin')>Admin</option>
                </select>
            </div>
            <div class="flex-1 w-full space-y-1.5">
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">Account Status</label>
                <select name="ban" class="w-full rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-4 py-2.5 text-sm font-semibold focus:ring-2 focus:ring-indigo-500 transition-all outline-none">
                    <option value="all" @selected($banStatus === 'all')>All Status</option>
                    <option value="active" @selected($banStatus === 'active')>Active Only</option>
                    <option value="banned" @selected($banStatus === 'banned')>Banned Only</option>
                </select>
            </div>
            <button type="submit" class="w-full md:w-auto px-8 py-2.5 rounded-2xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-sm font-black uppercase tracking-widest hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-slate-200 dark:shadow-none">
                Apply Filters
            </button>
        </form>
    </x-admin.admin-card>

    <!-- Table -->
    <x-admin.data-table class="p-0 border-none shadow-xl shadow-slate-200/50 dark:shadow-none">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50/80 dark:bg-slate-800/50 text-left border-b border-slate-100 dark:border-slate-800">
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">User Identity</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Privileges</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Security Status</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Action Control</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Registration</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($users as $listedUser)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-colors group">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-900/50 flex items-center justify-center font-black text-indigo-600 dark:text-indigo-400 shrink-0">
                                    {{ strtoupper(substr($listedUser->name, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-bold text-slate-900 dark:text-white truncate">{{ $listedUser->name }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $listedUser->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <form method="POST" action="{{ route('admin.users.role.update', $listedUser) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-1.5 text-xs font-bold outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                                    <option value="user" @selected($listedUser->role === 'user')>Reader</option>
                                    <option value="writer" @selected($listedUser->role === 'writer')>Writer</option>
                                    <option value="admin" @selected($listedUser->role === 'admin')>Admin</option>
                                </select>
                                <button type="submit" class="p-1.5 rounded-xl bg-indigo-600 text-white hover:bg-indigo-500 transition-colors shadow-sm shadow-indigo-200 dark:shadow-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-5">
                            @if($listedUser->is_banned)
                                <div class="inline-flex flex-col">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 text-[10px] font-black uppercase tracking-widest border border-rose-100 dark:border-rose-900/50">
                                        Banned
                                    </span>
                                    @if($listedUser->banned_until)
                                        <span class="text-[10px] font-bold text-slate-400 mt-1 uppercase italic">{{ $listedUser->banned_until->diffForHumans() }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest border border-emerald-100 dark:border-emerald-900/50">
                                    Active
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            @if($listedUser->role !== 'admin')
                                @if($listedUser->is_banned)
                                    <form action="{{ route('admin.users.unban', $listedUser) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-[10px] font-black uppercase tracking-widest hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-500/10">
                                            Restore Account
                                        </button>
                                    </form>
                                @else
                                    <details class="group/details relative z-20 open:z-50">
                                        <summary class="cursor-pointer inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-rose-50 dark:bg-rose-950 text-rose-600 dark:text-rose-400 text-[10px] font-black uppercase tracking-widest hover:bg-rose-100 dark:hover:bg-rose-900/50 transition-all list-none">
                                            Security Ban
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M6 9l6 6 6-6"/>
                                            </svg>
                                        </summary>
                                        <div class="absolute right-0 top-full mt-2 w-64 p-4 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-2xl z-50 space-y-3">
                                            <div class="space-y-1">
                                                <label class="text-[9px] font-black uppercase tracking-widest text-slate-500">Duration</label>
                                                <input type="datetime-local" name="banned_until"
                                                       form="ban-form-{{ $listedUser->id }}"
                                                       class="w-full text-[11px] rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 outline-none focus:ring-2 focus:ring-rose-500 font-bold">
                                            </div>
                                            <div class="space-y-1">
                                                <label class="text-[9px] font-black uppercase tracking-widest text-slate-500">Reason</label>
                                                <textarea name="ban_reason" rows="2" placeholder="Policy violation detail..." form="ban-form-{{ $listedUser->id }}"
                                                          class="w-full text-[11px] rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 px-3 py-2 outline-none focus:ring-2 focus:ring-rose-500 font-bold resize-none"></textarea>
                                            </div>
                                            <form id="ban-form-{{ $listedUser->id }}" action="{{ route('admin.users.ban', $listedUser) }}" method="POST">
                                                @csrf
                                            </form>
                                            <button type="submit" form="ban-form-{{ $listedUser->id }}"
                                                    class="w-full py-2 bg-rose-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-rose-500 transition-colors">
                                                Confirm Lockdown
                                            </button>
                                        </div>
                                    </details>
                                @endif
                            @else
                                <span class="text-[10px] font-bold text-slate-400 italic uppercase">Protected Admin</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $listedUser->created_at->format('M d, Y') }}</p>
                            <p class="text-[10px] font-medium text-slate-400 uppercase tracking-tighter">{{ $listedUser->created_at->diffForHumans(null, true) }}</p>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-800/50 flex items-center justify-center mx-auto mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/>
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-slate-500">No users found matching your criteria.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.data-table>

    <div class="px-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
