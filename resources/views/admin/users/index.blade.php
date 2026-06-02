@extends('layouts.admin')

@php
    $adminTitle = 'User Management';
    $adminBreadcrumbs = ['Admin', 'User Management'];
@endphp

@section('content')
<section class="max-w-5xl mx-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-5">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <select name="role" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
            <option value="all" @selected($role === 'all')>All Roles</option>
            <option value="user" @selected($role === 'user')>Reader</option>
            <option value="writer" @selected($role === 'writer')>Writer</option>
            <option value="admin" @selected($role === 'admin')>Admin</option>
        </select>
        <select name="ban" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-3 py-2 text-sm">
            <option value="all" @selected($banStatus === 'all')>All Status</option>
            <option value="active" @selected($banStatus === 'active')>Active</option>
            <option value="banned" @selected($banStatus === 'banned')>Banned</option>
        </select>
        <button type="submit" class="rounded-xl bg-indigo-600 text-white text-sm font-semibold px-4 py-2">Apply Filter</button>
    </form>

    <x-admin.data-table class="mt-5">
        <table class="w-full text-sm">
            <thead class="text-left text-slate-500 dark:text-slate-400">
                <tr>
                    <th class="py-2">Name</th>
                    <th class="py-2">Role</th>
                    <th class="py-2">Ban Status</th>
                    <th class="py-2">Actions</th>
                    <th class="py-2">Joined</th>
                </tr>
            </thead>
            <tbody class="text-slate-700 dark:text-slate-200">
                @forelse($users as $listedUser)
                    <tr class="border-t border-slate-100 dark:border-slate-800">
                        <td class="py-2">{{ $listedUser->name }}</td>
                        <td class="py-2">
                            <form method="POST" action="{{ route('admin.users.role.update', $listedUser) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-2.5 py-1 text-sm">
                                    <option value="user" @selected($listedUser->role === 'user')>Reader</option>
                                    <option value="writer" @selected($listedUser->role === 'writer')>Writer</option>
                                    <option value="admin" @selected($listedUser->role === 'admin')>Admin</option>
                                </select>
                                <button type="submit" class="px-3 py-1 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-500">
                                    Save
                                </button>
                            </form>
                        </td>
                        <td class="py-2">
                            <span class="{{ $listedUser->is_banned ? 'text-rose-700 dark:text-rose-300' : 'text-emerald-700 dark:text-emerald-300' }} font-semibold">
                                {{ $listedUser->is_banned ? 'Banned' : 'Active' }}
                            </span>
                            @if($listedUser->is_banned && $listedUser->banned_until)
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                    Until {{ $listedUser->banned_until->format('d M Y H:i') }}
                                </p>
                            @endif
                        </td>
                        <td class="py-2">
                            @if($listedUser->role !== 'admin')
                                @if($listedUser->is_banned)
                                    <form action="{{ route('admin.users.unban', $listedUser) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-500">
                                            Unban
                                        </button>
                                    </form>
                                @else
                                    <details class="group">
                                        <summary class="cursor-pointer px-3 py-1 rounded-xl bg-rose-600 text-white text-xs font-bold hover:bg-rose-500 list-none">
                                            Ban
                                        </summary>
                                        <div class="mt-2 p-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 space-y-2">
                                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-widest">Ban until (empty = permanent)</label>
                                            <input type="datetime-local" name="banned_until"
                                                   form="ban-form-{{ $listedUser->id }}"
                                                   class="w-full text-xs rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-2 py-1.5">
                                            <textarea name="ban_reason" rows="2" placeholder="Ban reason..." form="ban-form-{{ $listedUser->id }}"
                                                      class="w-full text-xs rounded-lg border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 px-2 py-1.5 resize-none"></textarea>
                                            <form id="ban-form-{{ $listedUser->id }}" action="{{ route('admin.users.ban', $listedUser) }}" method="POST">
                                                @csrf
                                            </form>
                                            <div class="flex justify-end">
                                                <button type="submit" form="ban-form-{{ $listedUser->id }}"
                                                        class="px-3 py-1.5 bg-rose-700 text-white text-xs font-bold rounded-lg hover:bg-rose-600">
                                                    Confirm
                                                </button>
                                            </div>
                                            <p class="text-[11px] text-slate-500 dark:text-slate-400">
                                                Fill inputs then confirm. (Permanent if empty)
                                            </p>
                                        </div>
                                    </details>
                                @endif
                            @else
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">Admin cannot be banned.</span>
                            @endif
                        </td>
                        <td class="py-2">{{ $listedUser->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-4 text-slate-500">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </x-admin.data-table>

    <div class="mt-4">{{ $users->links() }}</div>
</section>
@endsection
