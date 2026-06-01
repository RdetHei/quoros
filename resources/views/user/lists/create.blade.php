@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Buat List Novel</h1>
    <form action="{{ route('lists.store') }}" method="POST" class="space-y-4 bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800">
        @csrf
        <div>
            <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Judul</label>
            <input type="text" name="title" value="{{ old('title') }}" required maxlength="120"
                   class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 mb-2">Deskripsi</label>
            <textarea name="description" rows="3" maxlength="1000" class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 py-2.5 text-sm resize-none">{{ old('description') }}</textarea>
        </div>
        <label class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
            <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} class="rounded border-slate-300">
            List publik (dapat dibagikan)
        </label>
        <button type="submit" class="w-full py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold rounded-xl text-sm">Simpan</button>
    </form>
</div>
@endsection
