@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto my-12">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 md:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">Buat Novel Baru</h1>
            <p class="text-slate-500 dark:text-slate-400">Mulailah petualangan menulismu hari ini.</p>
        </div>

        <form action="{{ route('writer.novels.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            <div>
                <label for="title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Judul Novel</label>
                <input type="text" name="title" id="title" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('title') border-red-500 @enderror" 
                    value="{{ old('title') }}" required placeholder="Contoh: Petualangan Sang Penyihir">
                @error('title')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="alternative_title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Judul Alternatif (Opsional)</label>
                <input type="text" name="alternative_title" id="alternative_title" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                    value="{{ old('alternative_title') }}" placeholder="Contoh: The Sorcerer's Adventure">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="type" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Jenis Novel</label>
                    <select name="type" id="type" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                        <option value="original" {{ old('type') == 'original' ? 'selected' : '' }}>Original Story</option>
                        <option value="web_novel" {{ old('type') == 'web_novel' ? 'selected' : '' }}>Web Novel</option>
                        <option value="light_novel" {{ old('type') == 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                    </select>
                </div>
                <div>
                    <label for="content_rating" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Rating Usia</label>
                    <select name="content_rating" id="content_rating" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                        <option value="everyone" {{ old('content_rating') == 'everyone' ? 'selected' : '' }}>Everyone (Semua Umur)</option>
                        <option value="teen" {{ old('content_rating') == 'teen' ? 'selected' : '' }}>Teen (Remaja)</option>
                        <option value="mature" {{ old('content_rating') == 'mature' ? 'selected' : '' }}>Mature (Dewasa)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Deskripsi / Sinopsis</label>
                <textarea name="description" id="description" rows="6" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                    placeholder="Ceritakan sedikit tentang alur ceritamu...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Status Novel</label>
                <select name="status" id="status" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                    <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="hiatus" {{ old('status') == 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                    <option value="complete" {{ old('status') == 'complete' ? 'selected' : '' }}>Complete</option>
                </select>
            </div>

            <div>
                <label for="cover_image" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Cover Novel</label>
                <div class="flex items-center justify-center w-full">
                    <label for="cover_image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-3xl cursor-pointer bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-10 h-10 mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-2 text-sm text-slate-500 dark:text-slate-400 font-bold">Klik untuk upload cover</p>
                            <p class="text-xs text-slate-400">PNG, JPG atau WEBP (Max. 2MB)</p>
                        </div>
                        <input id="cover_image" name="cover_image" type="file" class="hidden" />
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Genre</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($genres as $genre)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-indigo-200 dark:hover:border-indigo-800 transition-all group">
                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}" class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500 dark:focus:ring-offset-slate-900">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-indigo-600 transition-colors">{{ $genre->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Tag Populer</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($tags as $tag)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-indigo-200 dark:hover:border-indigo-800 transition-all group">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500 dark:focus:ring-offset-slate-900">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-indigo-600 transition-colors">#{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <button type="submit" class="flex-grow py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none">Publikasikan Novel</button>
                <a href="{{ route('writer.novels.index') }}" class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 text-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
