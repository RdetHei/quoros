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
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white dark:focus:bg-slate-900 transition-all @error('title') border-red-500 @enderror" 
                    value="{{ old('title') }}" required placeholder="Contoh: Petualangan Sang Penyihir">
                @error('title')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="alternative_title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Judul Alternatif (Opsional)</label>
                <input type="text" name="alternative_title" id="alternative_title" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                    value="{{ old('alternative_title') }}" placeholder="Contoh: The Sorcerer's Adventure">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="region" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Region / Asal</label>
                    <input type="text" name="region" id="region" 
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                        value="{{ old('region') }}" placeholder="Contoh: Jepang, China, Indonesia">
                </div>
                <div>
                    <label for="language" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Bahasa</label>
                    <input type="text" name="language" id="language" 
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                        value="{{ old('language') }}" placeholder="Contoh: Indonesia, English, Japanese">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="type" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Jenis Novel</label>
                    <select name="type" id="type" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white dark:focus:bg-slate-900 transition-all">
                        <option value="original" {{ old('type') == 'original' ? 'selected' : '' }}>Original Story</option>
                        <option value="web_novel" {{ old('type') == 'web_novel' ? 'selected' : '' }}>Web Novel</option>
                        <option value="light_novel" {{ old('type') == 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                    </select>
                </div>
                <div>
                    <label for="content_rating" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Rating Usia</label>
                    <select name="content_rating" id="content_rating" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white dark:focus:bg-slate-900 transition-all">
                        <option value="everyone" {{ old('content_rating') == 'everyone' ? 'selected' : '' }}>Everyone (Semua Umur)</option>
                        <option value="teen" {{ old('content_rating') == 'teen' ? 'selected' : '' }}>Teen (Remaja)</option>
                        <option value="mature" {{ old('content_rating') == 'mature' ? 'selected' : '' }}>Mature (Dewasa)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Deskripsi / Sinopsis</label>
                <textarea name="description" id="description" rows="6" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                    placeholder="Ceritakan sedikit tentang alur ceritamu...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Status Novel</label>
                <select name="status" id="status" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:bg-white dark:focus:bg-slate-900 transition-all">
                    <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="hiatus" {{ old('status') == 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                    <option value="complete" {{ old('status') == 'complete' ? 'selected' : '' }}>Complete</option>
                </select>
            </div>

            <div>
                <label for="cover_image" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Cover Novel</label>
                <div class="flex flex-col gap-4">
                    <div id="cover-preview-container" class="relative w-full h-64 rounded-3xl overflow-hidden bg-slate-100 dark:bg-slate-800 hidden">
                        <img id="cover-preview" class="w-full h-full object-cover">
                        <button type="button" onclick="document.getElementById('cover_image').click()" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                            <span class="text-white font-bold text-sm">Ganti Gambar</span>
                        </button>
                    </div>
                    
                    <div id="cover-upload-placeholder" class="flex items-center justify-center w-full">
                        <label for="cover_image" class="flex flex-col items-center justify-center w-full h-64 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-3xl cursor-pointer bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-10 h-10 mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                                <p class="mb-2 text-sm text-slate-500 dark:text-slate-400 font-bold">Klik untuk upload cover</p>
                                <p class="text-xs text-slate-400">Rasio 3:4 Disarankan (Contoh: 600x800)</p>
                            </div>
                            <input id="cover_image" name="cover_image" type="file" class="hidden" accept="image/*" 
                                onchange="initCropper(this, 'cover-preview', { aspectRatio: 3/4, width: 600, height: 800 }); document.getElementById('cover-upload-placeholder').classList.add('hidden'); document.getElementById('cover-preview-container').classList.remove('hidden')" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Genre</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($genres as $genre)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-emerald-200 dark:hover:border-emerald-800 transition-all group">
                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}" class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500 dark:focus:ring-offset-slate-900">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-emerald-600 transition-colors">{{ $genre->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Tag Populer</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($tags as $tag)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-emerald-200 dark:hover:border-emerald-800 transition-all group">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500 dark:focus:ring-offset-slate-900">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-emerald-600 transition-colors">#{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Karakter Novel</label>
                        <p class="text-xs text-slate-500 mt-1">Tambahkan card karakter yang akan tampil di halaman novel.</p>
                    </div>
                    <button type="button" id="add-character-btn" class="px-4 py-2 bg-slate-900 text-white text-xs font-bold rounded-xl hover:bg-slate-800 transition-all">+ Tambah Karakter</button>
                </div>

                <div id="characters-list" class="space-y-4"></div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <button type="submit" class="flex-grow py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all shadow-xl shadow-slate-200/50 dark:shadow-none">Publikasikan Novel</button>
                <a href="{{ route('writer.novels.index') }}" class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 text-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('characters-list');
    const addButton = document.getElementById('add-character-btn');

    function createCharacterCard() {
        const id = 'char-' + Date.now();
        const card = document.createElement('div');
        card.className = 'character-card p-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40 space-y-4';
        card.innerHTML = `
            <div class="flex items-center justify-between">
                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200">Karakter</h4>
                <button type="button" class="remove-character text-xs font-bold text-rose-500 hover:text-rose-600">Hapus</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Karakter</label>
                    <input type="text" name="character_name[]" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm" placeholder="Contoh: Akiro">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Peran</label>
                    <input type="text" name="character_role[]" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm" placeholder="Contoh: Protagonis">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi Singkat</label>
                <textarea name="character_description[]" rows="3" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm" placeholder="Deskripsi karakter..."></textarea>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 items-start">
                <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-200 dark:bg-slate-700 flex-shrink-0 relative group">
                    <img id="${id}-preview" class="w-full h-full object-cover hidden">
                    <div id="${id}-no-img" class="w-full h-full flex items-center justify-center text-[8px] font-bold text-slate-400 uppercase">FOTO</div>
                    <button type="button" onclick="document.getElementById('${id}-input').click()" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <span class="text-white font-bold text-[8px] uppercase">Ganti</span>
                    </button>
                </div>
                <div class="flex-grow w-full">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Foto Karakter (Opsional)</label>
                    <input type="hidden" name="existing_character_image[]" value="">
                    <input type="hidden" name="existing_character_public_id[]" value="">
                    <input type="file" id="${id}-input" name="character_image[]" accept="image/*" class="hidden" 
                        onchange="initCropper(this, '${id}-preview', { aspectRatio: 1, width: 600, height: 600 }); document.getElementById('${id}-no-img').classList.add('hidden'); document.getElementById('${id}-preview').classList.remove('hidden')">
                    <button type="button" onclick="document.getElementById('${id}-input').click()" class="w-full py-2 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-500 hover:bg-slate-50 transition-all text-left">Pilih & Potong Foto</button>
                </div>
            </div>
        `;

        card.querySelector('.remove-character').addEventListener('click', function () {
            card.remove();
        });

        list.appendChild(card);
    }

    addButton.addEventListener('click', createCharacterCard);
});
</script>
@endpush
