@extends('layouts.admin')

@php
    $adminTitle = 'Announcements';
    $adminBreadcrumbs = ['Admin', 'Platform Settings', 'Announcements'];
@endphp

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-900 dark:text-slate-100">Announcements</h2>
                <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Manage announcements shown across the platform.</p>
            </div>
            <a href="{{ route('admin.announcements.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4">
            @foreach($announcements as $a)
                <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <p class="font-bold text-slate-900 dark:text-slate-100 truncate">{{ $a->title }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-300 mt-1">
                                {{ $a->created_at->format('d M Y H:i') }} · {{ $a->type ?? 'General' }}
                            </p>
                            <p class="mt-3 text-sm text-slate-600 dark:text-slate-300 line-clamp-3">{{ $a->content }}</p>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black tracking-widest border
                            {{ $a->is_active ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300 border-emerald-200/60' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-200 border-slate-200/60' }}">
                            {{ $a->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-2">
            {{ $announcements->links() }}
        </div>
    </div>
@endsection

