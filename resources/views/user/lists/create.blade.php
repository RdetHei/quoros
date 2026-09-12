@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold text-neutral-900 dark:text-white mb-6">Create Novel List</h1>
    <form action="{{ route('lists.store') }}" method="POST" class="space-y-4 bg-white dark:bg-neutral-900 p-6 rounded-2xl border border-neutral-200 dark:border-neutral-800">
        @csrf
        <div>
            <label class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" required maxlength="120"
                   class="w-full rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-4 py-2.5 text-sm">
        </div>
        <div>
            <label class="block text-xs font-bold uppercase tracking-widest text-neutral-500 mb-2">Description</label>
            <textarea name="description" rows="3" maxlength="1000" class="w-full rounded-xl border border-neutral-200 dark:border-neutral-700 bg-white dark:bg-neutral-800 px-4 py-2.5 text-sm resize-none">{{ old('description') }}</textarea>
        </div>
        <label class="flex items-center gap-2 text-sm text-neutral-600 dark:text-neutral-400">
            <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }} class="rounded border-neutral-300">
            Public list (shareable)
        </label>
        <button type="submit" class="w-full py-3 bg-neutral-900 dark:bg-white text-white dark:text-neutral-900 font-bold rounded-xl text-sm">Save</button>
    </form>
</div>
@endsection
