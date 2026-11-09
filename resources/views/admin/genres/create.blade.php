@extends('layouts.admin')

@php
    $adminTitle = 'Add Genre';
    $adminBreadcrumbs = ['Admin', 'Curation', 'Genres', 'Create'];
@endphp

@section('content')
<div class="admin-console mx-auto max-w-2xl space-y-5">
    <div>
        <p class="admin-kicker">Curation / new genre</p>
        <h2 class="admin-display mt-1">Add Genre</h2>
        <p class="mt-2 text-sm text-slate-400">Buat genre baru untuk kategori novel.</p>
    </div>

    <section class="console-card p-6">
        <form action="{{ route('admin.genres.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="console-label" for="name">Genre name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Contoh: Action, Romance" class="console-input @error('name') is-invalid @enderror">
                @error('name') <p class="mt-2 text-xs text-rose-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.genres.index') }}" class="console-btn neutral">Cancel</a>
                <button type="submit" class="console-btn">Save genre</button>
            </div>
        </form>
    </section>
</div>
@endsection
