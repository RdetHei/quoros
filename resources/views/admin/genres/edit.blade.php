@extends('layouts.admin')

@php
    $adminTitle = 'Edit Genre';
    $adminBreadcrumbs = ['Admin', 'Curation', 'Genres', 'Edit'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-2xl space-y-5">
    <div>
        <p class="admin-kicker">Curation / edit genre</p>
        <h2 class="admin-display mt-1">Edit Genre</h2>
        <p class="mt-2 text-sm text-slate-400">Perbarui informasi genre <span class="font-serif text-zinc-200">{{ $genre->name }}</span>.</p>
    </div>

    <section class="console-card p-6">
        <form action="{{ route('admin.genres.update', $genre) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label class="console-label" for="name">Genre name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $genre->name) }}" required class="console-input @error('name') is-invalid @enderror">
                @error('name') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.genres.index') }}" class="console-btn neutral">Cancel</a>
                <button type="submit" class="console-btn">Save changes</button>
            </div>
        </form>
    </section>
</div>
@endsection
