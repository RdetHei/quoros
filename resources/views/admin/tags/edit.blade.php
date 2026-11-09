@extends('layouts.admin')

@php
    $adminTitle = 'Edit Tag';
    $adminBreadcrumbs = ['Admin', 'Curation', 'Tags', 'Edit'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-2xl space-y-5">
    <div>
        <p class="admin-kicker">Curation / edit tag</p>
        <h2 class="admin-display mt-1">Edit Tag</h2>
        <p class="mt-2 text-sm text-slate-400">Perbarui informasi tag <span class="font-serif text-zinc-200">#{{ $tag->name }}</span>.</p>
    </div>

    <section class="console-card p-6">
        <form action="{{ route('admin.tags.update', $tag) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="console-label" for="name">Tag name</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 font-mono text-xs text-zinc-500">#</span>
                    <input type="text" name="name" id="name" value="{{ old('name', $tag->name) }}" required class="console-input pl-7 @error('name') is-invalid @enderror">
                </div>
                @error('name') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.tags.index') }}" class="console-btn neutral">Cancel</a>
                <button type="submit" class="console-btn">Save changes</button>
            </div>
        </form>
    </section>
</div>
@endsection
