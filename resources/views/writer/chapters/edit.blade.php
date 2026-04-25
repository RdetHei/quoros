@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto my-12">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 md:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">Edit Chapter</h1>
            <p class="text-slate-500 dark:text-slate-400">Mengubah bab <span class="font-bold text-indigo-600">{{ $chapter->title }}</span> pada novel <span class="font-bold text-indigo-600">{{ $novel->title }}</span>.</p>
        </div>

        <form action="{{ route('writer.chapters.update', [$novel->id, $chapter->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')
            <div>
                <label for="title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Judul Chapter</label>
                <input type="text" name="title" id="title" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('title') border-red-500 @enderror" 
                    value="{{ old('title', $chapter->title) }}" required>
                @error('title')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Isi Chapter (Teks)</label>
                <textarea name="content" id="content" rows="15" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all font-serif" 
                    placeholder="Tulis ceritamu di sini...">{{ old('content', $chapter->content) }}</textarea>
                @error('content')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="file" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Ganti File Dokumen (Opsional)</label>
                <div class="flex flex-col gap-4">
                    @if($chapter->file_path)
                        <div class="flex items-center gap-3 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-800 rounded-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <div class="flex-grow">
                                <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400">File Saat Ini</p>
                                <a href="{{ asset('storage/' . $chapter->file_path) }}" target="_blank" class="text-xs text-emerald-600 hover:underline">Lihat File</a>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center justify-center w-full">
                        <label for="file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-3xl cursor-pointer bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <p class="text-sm text-slate-500 dark:text-slate-400 font-bold text-center">Klik untuk upload file baru</p>
                                <p class="text-xs text-slate-400">PDF, EPUB, DOCX (Max. 10MB)</p>
                            </div>
                            <input id="file" name="file" type="file" class="hidden" />
                        </label>
                    </div>
                </div>
                @error('file')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="flex-grow py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none">Update Chapter</button>
                <a href="{{ route('novels.show', $novel->slug) }}" class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 text-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Batal</a>
            </div>
        </form>

        <div class="mt-12 pt-8 border-t border-rose-100 dark:border-rose-900/30">
            <div class="bg-rose-50 dark:bg-rose-900/10 p-6 rounded-3xl border border-rose-100 dark:border-rose-900/30 flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-lg font-bold text-rose-800 dark:text-rose-400 mb-1">Zona Bahaya</h3>
                    <p class="text-sm text-rose-600/70 dark:text-rose-400/60">Menghapus chapter bersifat permanen dan tidak dapat dibatalkan.</p>
                </div>
                <form action="{{ route('writer.chapters.destroy', [$novel->id, $chapter->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-8 py-3 bg-rose-600 text-white font-bold rounded-xl text-sm hover:bg-rose-700 transition-all shadow-lg shadow-rose-200 dark:shadow-none" onclick="return confirm('Apakah kamu yakin ingin menghapus chapter ini?')">Hapus Chapter</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
