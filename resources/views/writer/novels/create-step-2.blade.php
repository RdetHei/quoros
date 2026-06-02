@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto my-8 space-y-6">
    @include('writer.novels._wizard-steps', ['currentStep' => 2])

    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Buat Novel - Sinopsis & Cover</h1>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Lengkapi deskripsi cerita dan unggah cover novel.</p>
    </div>

    <form action="{{ route('writer.novels.update.step-2', $novel) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800 space-y-6">
            <div>
                <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Sinopsis</label>
                <textarea name="description" id="description" rows="6" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40">{{ old('description', $novel->description) }}</textarea>
            </div>

            <div>
                <label for="cover_image" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Cover Image</label>
                <input id="cover_image" name="cover_image" type="file" accept="image/*" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm">
                @error('cover_image') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                @if ($novel->cover_image_url)
                    <img src="{{ $novel->cover_image_url }}" alt="Cover preview" class="mt-4 w-32 h-44 object-cover rounded-xl border border-slate-200 dark:border-slate-700">
                @endif
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between">
                <a href="{{ route('writer.novels.create.step-1') }}" class="px-6 py-3 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Kembali</a>
                <button type="submit" class="px-8 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition-all">
                    Lanjut ke Step 3
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
