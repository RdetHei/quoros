@extends('layouts.admin')

@php
    $adminTitle = 'Add Tag';
    $adminBreadcrumbs = ['Admin', 'Curation', 'Tags', 'Create'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-2xl space-y-5">
    <div>
        <p class="admin-kicker">Curation / new tag</p>
        <h2 class="admin-display mt-1">Add Tag</h2>
        <p class="mt-2 text-sm text-slate-400">Buat tag baru untuk detail elemen novel.</p>
    </div>

    <section class="console-card p-6">
        <form action="{{ route('admin.tags.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="console-label" for="name">Tag name</label>
                <div class="relative">
                    <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 font-mono text-xs text-zinc-500">#</span>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Isekai, Overpowered" class="console-input pl-7 @error('name') is-invalid @enderror">
                </div>
                @error('name') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.tags.index') }}" class="console-btn neutral">Cancel</a>
                <button type="submit" class="console-btn">Save tag</button>
            </div>
        </form>
    </section>
</div>
@endsection
