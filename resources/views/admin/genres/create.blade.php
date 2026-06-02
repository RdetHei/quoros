@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto my-12">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 md:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">Add Genre</h1>
            <p class="text-slate-500 dark:text-slate-400">Create a new genre for novel categories.</p>
        </div>

        <form action="{{ route('admin.genres.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="name" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Genre Name</label>
                <input type="text" name="name" id="name" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('name') border-red-500 @enderror" 
                    value="{{ old('name') }}" required placeholder="Example: Action, Romance, etc">
                @error('name')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-4">
                <button type="submit" class="flex-grow py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none">Save Genre</button>
                <a href="{{ route('admin.genres.index') }}" class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 text-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
