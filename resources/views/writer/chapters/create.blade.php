@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto my-12" x-data="{ uploadMode: 'single' }">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 md:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">Tambah Chapter</h1>
                <p class="text-slate-500 dark:text-slate-400">Menambahkan bab baru untuk novel <span class="font-bold text-indigo-600">{{ $novel->title }}</span>.</p>
            </div>

            <div class="flex p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl w-fit">
                <button @click="uploadMode = 'single'" :class="uploadMode === 'single' ? 'bg-white dark:bg-slate-900 text-indigo-600 shadow-sm' : 'text-slate-500'" class="px-6 py-2 text-xs font-bold rounded-xl transition-all">Single</button>
                <button @click="uploadMode = 'bulk'" :class="uploadMode === 'bulk' ? 'bg-white dark:bg-slate-900 text-indigo-600 shadow-sm' : 'text-slate-500'" class="px-6 py-2 text-xs font-bold rounded-xl transition-all">Bulk EPUB</button>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/30 border border-rose-100 dark:border-rose-800 rounded-2xl text-rose-600 dark:text-rose-400 font-bold text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Single Chapter Form -->
        <form x-show="uploadMode === 'single'" action="{{ route('writer.chapters.store', $novel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <div>
                <label for="title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Judul Chapter</label>
                <input type="text" name="title" id="title" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('title') border-red-500 @enderror" 
                    value="{{ old('title') }}" placeholder="Contoh: Bab 1: Awal Mula">
                @error('title')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Isi Chapter (Teks)</label>
                <textarea name="content" id="content" rows="15" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all font-serif" 
                    placeholder="Tulis ceritamu di sini...">{{ old('content') }}</textarea>
            </div>

            <div>
                <label for="file" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">ATAU Upload File (PDF, EPUB, DOCX)</label>
                <div class="flex items-center justify-center w-full">
                    <label for="file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-3xl cursor-pointer bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="text-sm text-slate-500 dark:text-slate-400 font-bold text-center">Klik untuk upload dokumen chapter</p>
                        </div>
                        <input id="file" name="file" type="file" class="hidden" />
                    </label>
                </div>
                @error('file')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <button type="submit" class="flex-grow py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none">Simpan Chapter</button>
                <a href="{{ route('novels.show', $novel->slug) }}" class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 text-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Batal</a>
            </div>
        </form>

        <!-- Bulk EPUB Form -->
        <form x-show="uploadMode === 'bulk'" action="{{ route('writer.chapters.bulk', $novel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-transition>
            @csrf
            <div class="p-8 bg-indigo-50 dark:bg-indigo-900/20 rounded-[2rem] border border-indigo-100 dark:border-indigo-800/50">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Bulk Upload EPUB</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Sistem akan otomatis membedah file EPUB Anda dan menjadikannya chapter-chapter terpisah berdasarkan struktur buku.</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <label for="epub_file" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Pilih File EPUB</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="epub_file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-indigo-200 dark:border-indigo-800 border-dashed rounded-3xl cursor-pointer bg-white dark:bg-slate-900 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-12 h-12 mb-4 text-indigo-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-sm text-slate-500 dark:text-slate-400 font-bold">Seret atau klik untuk pilih file .epub</p>
                                <p class="text-[10px] text-slate-400 uppercase mt-2">Maksimal 50MB</p>
                            </div>
                            <input id="epub_file" name="epub_file" type="file" accept=".epub" class="hidden" required />
                        </label>
                    </div>
                    @error('epub_file')
                        <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <button type="submit" class="flex-grow py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                    Proses & Ekstrak Chapter
                </button>
                <a href="{{ route('novels.show', $novel->slug) }}" class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 text-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
