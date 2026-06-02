@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto my-8 space-y-6">
    @include('writer.novels._wizard-steps', ['currentStep' => 1])

    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Buat Novel - Info Dasar</h1>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Isi informasi utama novel terlebih dahulu.</p>
    </div>

    <form action="{{ route('writer.novels.store.step-1') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 space-y-6">
            <div>
                <label for="title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Judul Novel <span class="text-rose-500">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                @error('title') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="alternative_title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Alternative Title</label>
                <input type="text" name="alternative_title" id="alternative_title" value="{{ old('alternative_title') }}" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div>
                    <label for="type" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Tipe <span class="text-rose-500">*</span></label>
                    <select name="type" id="type" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                        <option value="original" {{ old('type', 'original') === 'original' ? 'selected' : '' }}>Original Story</option>
                        <option value="web_novel" {{ old('type') === 'web_novel' ? 'selected' : '' }}>Web Novel</option>
                        <option value="light_novel" {{ old('type') === 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                    </select>
                </div>

                <div>
                    <label for="language" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Bahasa</label>
                    <input type="text" name="language" id="language" value="{{ old('language') }}" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                </div>

                <div>
                    <label for="region" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Region</label>
                    <input type="text" name="region" id="region" value="{{ old('region') }}" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                </div>

                <div>
                    <label for="content_rating" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Age Rating <span class="text-rose-500">*</span></label>
                    <select name="content_rating" id="content_rating" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                        <option value="everyone" {{ old('content_rating', 'everyone') === 'everyone' ? 'selected' : '' }}>Everyone</option>
                        <option value="teen" {{ old('content_rating') === 'teen' ? 'selected' : '' }}>Teen</option>
                        <option value="mature" {{ old('content_rating') === 'mature' ? 'selected' : '' }}>Mature</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Status <span class="text-rose-500">*</span></label>
                    <select name="status" id="status" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                        <option value="ongoing" {{ old('status', 'ongoing') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="hiatus" {{ old('status') === 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                        <option value="complete" {{ old('status') === 'complete' ? 'selected' : '' }}>Complete</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition-all">
                    Lanjut ke Step 2
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
