@extends('layouts.admin')

@php
    $adminTitle = 'Maintenance Mode';
    $adminBreadcrumbs = ['Admin', 'System', 'Maintenance Mode'];
@endphp

@section('content')
    <div class="max-w-3xl mx-auto rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
        <h2 class="text-2xl font-black tracking-tight text-slate-900 dark:text-slate-100">Maintenance Mode</h2>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
            Phase 1 placeholder UI. In Phase 2 we can implement a toggle stored in DB/cache to block non-admin traffic.
        </p>

        <div class="mt-6 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-900/30 p-4">
            <p class="text-sm font-bold text-amber-700 dark:text-amber-300">Current status: Not implemented</p>
        </div>
    </div>
@endsection

