@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto my-12">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 md:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">Edit Novel</h1>
            <p class="text-slate-500 dark:text-slate-400">Sesuaikan informasi untuk <span class="font-bold text-indigo-600">{{ $novel->title }}</span>.</p>
        </div>

        <form action="{{ route('writer.novels.update', $novel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')
            <div>
                <label for="title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Judul Novel</label>
                <input type="text" name="title" id="title" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('title') border-red-500 @enderror" 
                    value="{{ old('title', $novel->title) }}" required>
                @error('title')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="alternative_title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Judul Alternatif (Opsional)</label>
                <input type="text" name="alternative_title" id="alternative_title" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                    value="{{ old('alternative_title', $novel->alternative_title) }}">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="region" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Region / Asal</label>
                    <input type="text" name="region" id="region" 
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                        value="{{ old('region', $novel->region) }}" placeholder="Contoh: Jepang, China, Indonesia">
                </div>
                <div>
                    <label for="language" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Bahasa</label>
                    <input type="text" name="language" id="language" 
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                        value="{{ old('language', $novel->language) }}" placeholder="Contoh: Indonesia, English, Japanese">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="type" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Jenis Novel</label>
                    <select name="type" id="type" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                        <option value="original" {{ old('type', $novel->type) == 'original' ? 'selected' : '' }}>Original Story</option>
                        <option value="web_novel" {{ old('type', $novel->type) == 'web_novel' ? 'selected' : '' }}>Web Novel</option>
                        <option value="light_novel" {{ old('type', $novel->type) == 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                    </select>
                </div>
                <div>
                    <label for="content_rating" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Rating Usia</label>
                    <select name="content_rating" id="content_rating" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                        <option value="everyone" {{ old('content_rating', $novel->content_rating) == 'everyone' ? 'selected' : '' }}>Everyone (Semua Umur)</option>
                        <option value="teen" {{ old('content_rating', $novel->content_rating) == 'teen' ? 'selected' : '' }}>Teen (Remaja)</option>
                        <option value="mature" {{ old('content_rating', $novel->content_rating) == 'mature' ? 'selected' : '' }}>Mature (Dewasa)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Deskripsi / Sinopsis</label>
                <textarea name="description" id="description" rows="6" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all" 
                    >{{ old('description', $novel->description) }}</textarea>
            </div>

            <div>
                <label for="status" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Status Novel</label>
                <select name="status" id="status" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                    <option value="ongoing" {{ old('status', $novel->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="hiatus" {{ old('status', $novel->status) == 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                    <option value="complete" {{ old('status', $novel->status) == 'complete' ? 'selected' : '' }}>Complete</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Cover Novel</label>
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    <div class="w-32 h-44 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 flex-shrink-0 shadow-lg">
                        @if($novel->cover_image)
                            <img src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center p-2 text-[10px] font-bold text-slate-400 text-center uppercase tracking-tighter leading-none">NO COVER</div>
                        @endif
                    </div>
                    <div class="flex-grow w-full">
                        <label for="cover_image" class="flex flex-col items-center justify-center w-full h-44 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-3xl cursor-pointer bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                                <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0l-4-4m4 4V4"></path></svg>
                                <p class="mb-1 text-sm text-slate-500 dark:text-slate-400 font-bold">Ganti Cover</p>
                                <p class="text-xs text-slate-400 px-4">Biarkan kosong jika tidak ingin mengubah</p>
                            </div>
                            <input id="cover_image" name="cover_image" type="file" class="hidden" />
                        </label>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Genre</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($genres as $genre)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-indigo-200 dark:hover:border-indigo-800 transition-all group">
                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}" {{ $novel->genres->contains($genre->id) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-indigo-600 transition-colors">{{ $genre->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Tag</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($tags as $tag)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-indigo-200 dark:hover:border-indigo-800 transition-all group">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ $novel->tags->contains($tag->id) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-indigo-600 transition-colors">#{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Karakter Novel</label>
                        <p class="text-xs text-slate-500 mt-1">Kelola card karakter yang ditampilkan di informasi novel.</p>
                    </div>
                    <button type="button" id="add-character-btn" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition-all">+ Tambah Karakter</button>
                </div>

                <div id="characters-list" class="space-y-4">
                    @foreach($novel->characters as $character)
                        <div class="character-card p-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40 space-y-4">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200">Karakter</h4>
                                <button type="button" class="remove-character text-xs font-bold text-rose-500 hover:text-rose-600">Hapus</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Karakter</label>
                                    <input type="text" name="character_name[]" value="{{ $character->name }}" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Peran</label>
                                    <input type="text" name="character_role[]" value="{{ $character->role }}" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi Singkat</label>
                                <textarea name="character_description[]" rows="3" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm">{{ $character->description }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Foto Karakter (Opsional)</label>
                                @if($character->image)
                                    <div class="mb-3 w-16 h-16 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                                        <img src="{{ asset('storage/' . $character->image) }}" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <input type="hidden" name="existing_character_image[]" value="{{ $character->image }}">
                                <input type="file" name="character_image[]" accept="image/jpeg,image/png,image/jpg,image/gif" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="flex-grow py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none">Simpan Perubahan</button>
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

    function attachRemoveHandlers() {
        list.querySelectorAll('.remove-character').forEach(function (button) {
            button.onclick = function () {
                button.closest('.character-card').remove();
            };
        });
    }

    function createCharacterCard() {
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
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Foto Karakter (Opsional)</label>
                <input type="hidden" name="existing_character_image[]" value="">
                <input type="file" name="character_image[]" accept="image/jpeg,image/png,image/jpg,image/gif" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>
        `;

        list.appendChild(card);
        attachRemoveHandlers();
    }

    addButton.addEventListener('click', createCharacterCard);
    attachRemoveHandlers();
});
</script>
@endpush
