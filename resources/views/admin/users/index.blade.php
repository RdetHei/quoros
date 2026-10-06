@extends('layouts.admin')

@php
    $adminTitle = 'User Matrix';
    $adminBreadcrumbs = ['Identity Archive', 'Reader Network'];
@endphp

@push('styles')
<style>
    .user-matrix { color:#d8d4cc; }
    .um-kicker { color:#77746e;font:500 10px 'DM Mono',monospace;letter-spacing:.1em;text-transform:uppercase; }
    .um-display,.um-serif { color:#e6e1d8;font-family:'Cormorant Garamond',serif;font-weight:500; }
    .um-display {font-size:34px;line-height:1;text-transform:uppercase;}
    .um-title {font:500 24px 'Cormorant Garamond',serif;color:#e5e0d7;}
    .um-card {border:1px solid #242426;border-radius:5px;background:#131315;}
    .um-input {height:40px;border:1px solid #29292b;border-radius:4px;background:#101012;color:#d8d4cc;padding:0 12px;font:12px 'DM Mono',monospace;outline:none;}
    .um-input:focus {border-color:#806a2d;}
    .um-chip {display:inline-flex;align-items:center;justify-content:center;min-height:34px;border:1px solid #3a3937;border-radius:4px;background:#19191b;padding:8px 12px;color:#dedad2!important;font:10px 'DM Mono',monospace;text-transform:uppercase;opacity:1;}
    .um-chip.is-active {border-color:#806a2d;background:#292316;color:#e2bd54!important;}
    .um-input option {background:#19191b;color:#e2ded6;}
    .um-table {width:100%;border-collapse:collapse;}
    .um-table th {padding:13px 12px;background:#19191b;color:#7f7b74;text-align:left;font:500 10px 'DM Mono',monospace;text-transform:uppercase;letter-spacing:.08em;white-space:nowrap;}
    .um-table td {padding:13px 12px;color:#bcb8b0;border-bottom:1px solid #242426;font:15px 'Cormorant Garamond',serif;}
    .um-table tbody tr {transition:background .15s;}
    .um-table tbody tr:hover,.um-table tbody tr.is-selected {background:#201e18;}
    .um-table tbody tr.is-selected td:first-child {box-shadow:inset 2px 0 #655426;}
    .um-avatar {height:30px;width:30px;flex:none;border:1px solid #343436;border-radius:50%;background:#19191b;}
    .um-avatar.selected {border-color:#c5a13c;background:transparent;}
    .um-badge {display:inline-block;border:1px solid #325a42;border-radius:3px;padding:4px 7px;color:#70b58a;font:9px 'DM Mono',monospace;text-transform:uppercase;}
    .um-badge.review {border-color:#725a28;color:#c6a048;}
    .um-badge.restricted {border-color:#6e3738;color:#d27676;}
    .um-badge.role {border-color:#344f66;color:#8db5d0;}
    .um-action {border:1px solid #66541f;border-radius:4px;background:#c6a541;color:#15130e;padding:11px 12px;text-align:center;font:600 10px 'DM Mono',monospace;text-transform:uppercase;}
    .um-action.secondary {background:transparent;color:#c6a541;}
    .um-action.danger {border-color:#754145;background:#2a1d20;color:#d47b7b;}
    .um-activity-track {height:3px;border-radius:3px;background:#29292b;overflow:hidden;}
    .um-activity-fill {height:100%;background:#c6a541;}
</style>
@endpush

@section('content')
@php
    $roleTabs = ['all' => 'All users', 'user' => 'Readers', 'writer' => 'Authors', 'admin' => 'Admin'];
    $activityMax = max(1, $memberActivity->max('members'), $memberActivity->max('readers'), $memberActivity->max('reviews'), $memberActivity->max('comments'));
@endphp

<div class="user-matrix mx-auto max-w-[1600px] space-y-4">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div><p class="um-kicker" style="color:#c5a13c">Identity archive / reader network</p><h2 class="um-display mt-1">User Matrix</h2><p class="mt-1 text-xs text-zinc-500">Monitor reader health, account access, and platform activity.</p></div>
        <div class="text-right"><p class="um-kicker">{{ number_format($userStats['total']) }} identities · sorted by recent activity</p><p class="mt-1 um-kicker">{{ now()->format('d M Y · H:i') }} UTC</p></div>
    </div>

    <div class="grid grid-cols-2 gap-3 xl:grid-cols-4">
        @foreach ([['Total readers', $userStats['total'], 'people', 'green', 'All accounts'], ['Active today', $userStats['activeToday'], 'activity', 'gold', 'Active reading sessions'], ['Authors', $userStats['authors'], 'books', 'blue', 'Writer accounts'], ['Restricted', $userStats['restricted'], 'lock', 'red', 'Restricted accounts']] as [$label, $value, $icon, $color, $caption])
            <section class="um-card p-5"><div class="flex items-start justify-between"><p class="um-kicker">{{ $label }}</p><span class="grid h-9 w-9 place-items-center rounded border text-sm" style="border-color:{{ ['green'=>'#31533e','gold'=>'#66531e','blue'=>'#344f66','red'=>'#693c3e'][$color] }};color:{{ ['green'=>'#70b58a','gold'=>'#c6a541','blue'=>'#8db5d0','red'=>'#d27676'][$color] }}">{{ ['people'=>'♙','activity'=>'⌁','books'=>'▧','lock'=>'♧'][$icon] }}</span></div><p class="um-display mt-3 !text-3xl">{{ number_format($value) }}</p><p class="mt-2 um-kicker" style="color:{{ ['green'=>'#70b58a','gold'=>'#c6a541','blue'=>'#8db5d0','red'=>'#d27676'][$color] }}">● {{ $caption }}</p></section>
        @endforeach
    </div>

    <div class="flex flex-wrap items-center justify-between gap-2">
        <nav class="flex flex-wrap gap-1">
            @foreach($roleTabs as $tabRole => $tabLabel)
                <a href="{{ route('admin.users.index', array_filter(['role' => $tabRole === 'all' ? null : $tabRole, 'status' => $status !== 'all' ? $status : null, 'q' => $search ?: null])) }}" class="um-chip {{ $role === $tabRole ? 'is-active' : '' }}">{{ $tabLabel }}</a>
            @endforeach
        </nav>
        <span class="um-kicker">{{ number_format($userStats['total']) }} identities · sorted by recent activity</span>
    </div>

    <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 gap-2 md:grid-cols-[minmax(180px,1fr)_90px_112px_112px_auto]">
        <input type="search" name="q" value="{{ $search }}" placeholder="⌕　Search the archive..." class="um-input w-full">
        <select name="role" class="um-input"><option value="all" @selected($role === 'all')>Role · any</option><option value="user" @selected($role === 'user')>Reader</option><option value="writer" @selected($role === 'writer')>Author</option><option value="admin" @selected($role === 'admin')>Admin</option></select>
        <select name="status" class="um-input"><option value="all">Status · any</option><option value="active" @selected($status === 'active')>Active</option><option value="review" @selected($status === 'review')>Review</option><option value="restricted" @selected($status === 'restricted')>Restricted</option></select>
        <select name="sort" class="um-input"><option value="recent" @selected($sort === 'recent')>Joined · recent</option><option value="activity" @selected($sort === 'activity')>Activity</option><option value="oldest" @selected($sort === 'oldest')>Joined · oldest</option></select>
        @if($selectedUser)<input type="hidden" name="selected" value="{{ $selectedUser->id }}">@endif
        <button class="um-chip is-active" type="submit">Filter</button>
    </form>

    <div class="grid grid-cols-1 items-stretch gap-3 xl:grid-cols-[minmax(0,1fr)_300px]">
        <section class="um-card flex min-w-0 flex-col overflow-visible">
            <div class="flex-1 overflow-x-auto rounded">
                <table class="um-table min-w-[690px]">
                    <thead><tr><th>Identity</th><th>Role</th><th>Status</th><th>Reading activity</th><th>Joined</th></tr></thead>
                    <tbody>
                    @forelse($users as $listedUser)
                        @php
                            $isSelected = $selectedUser?->id === $listedUser->id;
                            $accountStatus = $listedUser->isCurrentlyBanned() ? 'restricted' : ($listedUser->email_verified_at ? 'active' : 'review');
                            $selectUrl = route('admin.users.index', array_merge(request()->query(), ['selected' => $listedUser->id]));
                        @endphp
                        <tr class="{{ $isSelected ? 'is-selected' : '' }}">
                            <td><a href="{{ $selectUrl }}" class="flex min-w-0 items-center gap-3"><span class="um-avatar {{ $isSelected ? 'selected' : '' }}"></span><span class="min-w-0"><span class="block truncate um-serif text-base">{{ $listedUser->name }}</span><span class="block truncate text-[11px] text-zinc-500">{{ $listedUser->email }}</span></span></a></td>
                            <td>{{ ['user'=>'Reader','writer'=>'Author','admin'=>'Admin'][$listedUser->role] ?? ucfirst($listedUser->role) }}</td>
                            <td><span class="um-badge {{ $accountStatus }}">{{ $accountStatus === 'restricted' ? 'Restricted' : ($accountStatus === 'review' ? 'Review' : ($listedUser->email_verified_at ? 'Verified' : 'Active')) }}</span></td>
                            <td class="text-zinc-500">{{ number_format($listedUser->reading_histories_count) }} ch. · {{ number_format($listedUser->followers_count) }}h</td>
                            <td class="whitespace-nowrap text-zinc-500">{{ $listedUser->created_at?->format('d M y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-12 text-center text-base text-zinc-500">No identities match these filters.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-auto flex items-center justify-between gap-3 border-t border-white/5 px-3 py-2"><span class="um-kicker">Showing {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} · Page {{ $users->currentPage() }}</span><div class="flex gap-1">@if($users->previousPageUrl())<a href="{{ $users->previousPageUrl() }}" class="um-chip">Previous</a>@endif @if($users->nextPageUrl())<a href="{{ $users->nextPageUrl() }}" class="um-chip">Next</a>@endif</div></div>
        </section>

        <aside class="um-card min-h-[300px] p-4">
            @if($selectedUser)
                @php($selectedStatus = $selectedUser->isCurrentlyBanned() ? 'restricted' : ($selectedUser->email_verified_at ? 'active' : 'review'))
                <p class="um-kicker" style="color:#c5a13c">Selected identity</p><h3 class="um-title mt-1">{{ $selectedUser->name }}</h3><span class="um-badge mt-2 {{ $selectedStatus }}">{{ $selectedStatus === 'restricted' ? 'Restricted' : ($selectedStatus === 'review' ? 'Under review' : 'Verified member') }}</span>
                <dl class="mt-4 divide-y divide-white/5 border-t border-white/5">
                    <div class="flex justify-between gap-3 py-2.5"><dt class="um-kicker">Member ID</dt><dd class="um-serif text-xs">QR-{{ str_pad((string) $selectedUser->id, 5, '0', STR_PAD_LEFT) }}</dd></div>
                    <div class="flex justify-between gap-3 py-2.5"><dt class="um-kicker">Published novels</dt><dd class="um-serif text-xs">{{ number_format($selectedUser->novels_count) }} titles</dd></div>
                    <div class="flex justify-between gap-3 py-2.5"><dt class="um-kicker">Reader followers</dt><dd class="um-serif text-xs">{{ number_format($selectedUser->followers_count) }}</dd></div>
                    <div class="flex justify-between gap-3 py-2.5"><dt class="um-kicker">Last active</dt><dd class="um-serif text-xs">{{ $selectedUser->reading_sessions_max_last_active_at ? \Illuminate\Support\Carbon::parse($selectedUser->reading_sessions_max_last_active_at)->diffForHumans() : 'No recent activity' }}</dd></div>
                    <div class="flex justify-between gap-3 py-2.5"><dt class="um-kicker">Reading history</dt><dd class="um-serif text-xs">{{ number_format($selectedUser->reading_histories_count) }} novels</dd></div>
                    <div class="flex justify-between gap-3 py-2.5"><dt class="um-kicker">Trust score</dt><dd class="um-serif text-xs">Not tracked</dd></div>
                </dl>
                <p class="um-kicker mt-3">Quick actions</p>
                <div class="mt-2 grid gap-1.5">
                    <a href="{{ route('profile.show', $selectedUser->username ?: $selectedUser->id) }}" class="um-action">View profile</a>
                    <a href="mailto:{{ $selectedUser->email }}" class="um-action secondary">Message user</a>
                    @if($selectedUser->role !== 'admin')
                        @if($selectedUser->isCurrentlyBanned())
                            <form action="{{ route('admin.users.unban', $selectedUser) }}" method="POST">@csrf<button class="um-action" type="submit" style="width:100%">Restore access</button></form>
                        @else
                            <details><summary class="um-action danger cursor-pointer list-none">Restrict access</summary><form action="{{ route('admin.users.ban', $selectedUser) }}" method="POST" class="mt-2 grid gap-2">@csrf<input type="datetime-local" name="banned_until" class="um-input w-full"><textarea name="ban_reason" required rows="2" placeholder="Reason for restriction" class="um-input h-auto w-full py-2"></textarea><button type="submit" class="um-action danger">Confirm restriction</button></form></details>
                        @endif
                    @endif
                </div>
            @else
                <p class="um-kicker">Selected identity</p><p class="mt-4 text-sm text-zinc-500">Select an account from the list to inspect its details.</p>
            @endif
        </aside>
    </div>

    <section class="um-card p-4">
        <div class="flex items-center justify-between gap-3"><div><p class="um-kicker" style="color:#c5a13c">Member intelligence</p><h3 class="um-title">Reader cohort activity</h3></div><span class="um-kicker">Last 7 days</span></div>
        <div class="mt-4 grid grid-cols-1 gap-x-5 gap-y-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([['New members','members','#c6a541'],['Active readers','readers','#82a9c2'],['Review activity','reviews','#70b58a'],['Discussion','comments','#cf8d42']] as [$metric, $key, $color])
                <div><div class="mb-1 flex justify-between"><span class="um-kicker">{{ $metric }}</span><span class="um-kicker" style="color:{{ $color }}">{{ number_format($memberActivity->sum($key)) }}</span></div><div class="flex h-1 items-end gap-1">@foreach($memberActivity as $day)<span class="flex-1 rounded-sm" style="height:{{ max(18, (int) (($day[$key] / $activityMax) * 100)) }}%;background:{{ $color }}"></span>@endforeach</div></div>
            @endforeach
        </div>
    </section>
</div>
@endsection
