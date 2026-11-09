@extends('layouts.admin')

@php
    $adminTitle = 'Edit Announcement';
    $adminBreadcrumbs = ['Admin', 'Platform Settings', 'Announcements', 'Edit'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-3xl space-y-5">
    <div>
        <p class="admin-kicker">Communications / edit broadcast</p>
        <h2 class="admin-display mt-1">Edit Announcement</h2>
        <p class="mt-2 text-sm text-slate-400">Perbarui isi pengumuman <span class="font-serif text-zinc-200">{{ $announcement->title }}</span>.</p>
    </div>

    <section class="console-card p-6">
        <form method="POST" action="{{ route('admin.announcements.update', $announcement) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="console-label" for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $announcement->title) }}" required class="console-input @error('title') is-invalid @enderror">
                @error('title') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="console-label" for="type">Type (opsional)</label>
                    <input id="type" name="type" type="text" value="{{ old('type', $announcement->type) }}" placeholder="info / warning / success" class="console-input @error('type') is-invalid @enderror">
                    @error('type') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="console-label" for="link">Link (opsional)</label>
                    <input id="link" name="link" type="url" value="{{ old('link', $announcement->link) }}" placeholder="https://..." class="console-input @error('link') is-invalid @enderror">
                    @error('link') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="console-label" for="content">Content</label>
                <textarea id="content" name="content" rows="6" required class="console-textarea @error('content') is-invalid @enderror">{{ old('content', $announcement->content) }}</textarea>
                @error('content') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>

            <label class="flex cursor-pointer items-center gap-3 rounded border border-white/10 bg-white/[.03] px-4 py-3">
                <input type="checkbox" name="is_active" value="1" class="h-4 w-4" @checked(old('is_active', $announcement->is_active))>
                <span class="text-sm text-zinc-300">Aktif (notifikasi dikirim saat pertama kali diaktifkan)</span>
            </label>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('admin.announcements.index') }}" class="console-btn neutral">Cancel</a>
                <button type="submit" class="console-btn">Save changes</button>
            </div>
        </form>

        <form action="{{ route('admin.announcements.destroy', $announcement) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini secara permanen?')" class="mt-5 border-t border-white/5 pt-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="console-btn danger">Delete announcement</button>
        </form>
    </section>
</div>
@endsection
