@extends('layouts.admin')

@php
    $adminTitle = 'Create Announcement';
    $adminBreadcrumbs = ['Admin', 'Platform Settings', 'Announcements', 'Create'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-3xl space-y-5">
    <div>
        <p class="admin-kicker">Communications / new broadcast</p>
        <h2 class="admin-display mt-1">Create Announcement</h2>
        <p class="mt-2 text-sm text-slate-400">Tulis pengumuman yang terlihat oleh pengguna. Usahakan ringkas dan jelas.</p>
    </div>

    <section class="console-card p-6">
        <form method="POST" action="{{ route('admin.announcements.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="console-label" for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required class="console-input @error('title') is-invalid @enderror">
                @error('title') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="console-label" for="type">Type (opsional)</label>
                    <input id="type" name="type" type="text" value="{{ old('type') }}" placeholder="info / warning / success" class="console-input @error('type') is-invalid @enderror">
                    @error('type') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="console-label" for="link">Link (opsional)</label>
                    <input id="link" name="link" type="url" value="{{ old('link') }}" placeholder="https://..." class="console-input @error('link') is-invalid @enderror">
                    @error('link') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="console-label" for="content">Content</label>
                <textarea id="content" name="content" rows="6" required class="console-textarea @error('content') is-invalid @enderror">{{ old('content') }}</textarea>
                @error('content') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>

            <label class="flex cursor-pointer items-center gap-3 rounded border border-white/10 bg-white/[.03] px-4 py-3">
                <input type="checkbox" name="is_active" value="1" class="h-4 w-4" @checked(old('is_active'))>
                <span class="text-sm text-zinc-300">Aktifkan sekarang &amp; kirim notifikasi ke semua pengguna</span>
            </label>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.announcements.index') }}" class="console-btn neutral">Cancel</a>
                <button type="submit" class="console-btn">Save announcement</button>
            </div>
        </form>
    </section>
</div>
@endsection
