@extends('layouts.admin')

@php
    $adminTitle = 'Create Announcement';
    $adminBreadcrumbs = ['Admin', 'Platform Settings', 'Announcements', 'Create'];
@endphp

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 shadow-sm">
            <h2 class="text-2xl font-black tracking-tight text-slate-900 dark:text-slate-100">Create Announcement</h2>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Write an announcement visible to users. Keep it concise.</p>

            <form method="POST" action="{{ route('admin.announcements.store') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2" for="title">Title</label>
                    <input id="title" name="title" type="text" value="{{ old('title') }}" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('title') <p class="mt-2 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2" for="type">Type (optional)</label>
                    <input id="type" name="type" type="text" value="{{ old('type') }}" placeholder="warning/success/info"
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2" for="content">Content</label>
                    <textarea id="content" name="content" rows="6" required
                        class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"
                    >{{ old('content') }}</textarea>
                    @error('content') <p class="mt-2 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 cursor-pointer rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40 px-4 py-3">
                        <input type="checkbox" name="is_active" value="1" class="h-4 w-4 text-indigo-600">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-200">Active</span>
                    </label>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2" for="link">Link (optional)</label>
                        <input id="link" name="link" type="url" value="{{ old('link') }}"
                            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('admin.announcements.index') }}" class="px-5 py-2.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-700">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-bold hover:bg-indigo-500">
                        Save Announcement
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

