@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto my-8 space-y-6">
    @include('writer.novels._wizard-steps', ['currentStep' => 3])

    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <h1 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Buat Novel - Genre & Tags</h1>
        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Pilih genre dan tag untuk membantu novel lebih mudah ditemukan pembaca.</p>
    </div>

    <form action="{{ route('writer.novels.update.step-3', $novel) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Genre <span class="text-rose-500">*</span></label>
                    <div class="grid grid-cols-2 gap-3 max-h-[320px] overflow-y-auto pr-2">
                        @foreach($genres as $genre)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}" class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500 dark:focus:ring-offset-slate-900" {{ in_array($genre->id, old('genres', $novel->genres->pluck('id')->all()), true) ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">{{ $genre->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('genres') <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Tag</label>
                    <div class="grid grid-cols-2 gap-3 max-h-[320px] overflow-y-auto pr-2">
                        @foreach($tags as $tag)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500 dark:focus:ring-offset-slate-900" {{ in_array($tag->id, old('tags', $novel->tags->pluck('id')->all()), true) ? 'checked' : '' }}>
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400">#{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 rounded-3xl p-6 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
            <div class="flex justify-between">
                <a href="{{ route('writer.novels.create.step-2', $novel) }}" class="px-6 py-3 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Kembali</a>
                <button type="submit" class="px-8 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition-all">
                    Selesaikan Novel
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
