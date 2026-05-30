@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto my-8 space-y-6">
    <!-- Page Header -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">Edit Novel</h1>
        <p class="text-slate-500 dark:text-slate-400">Perbarui informasi novel kamu: <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $novel->title }}</span></p>
    </div>

    <form action="{{ route('writer.novels.update', $novel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Section 1: Basic Information -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Informasi Dasar</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Judul, deskripsi, dan detail dasar novel</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Judul Novel <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" id="title"
                           class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('title') border-rose-500 @enderror"
                           value="{{ old('title', $novel->title) }}" required>
                    @error('title')
                        <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alternative Title -->
                <div>
                    <label for="alternative_title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Judul Alternatif (Opsional)</label>
                    <input type="text" name="alternative_title" id="alternative_title"
                           class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all"
                           value="{{ old('alternative_title', $novel->alternative_title) }}">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi / Sinopsis <span class="text-rose-500">*</span></label>
                    <textarea name="description" id="description" rows="5"
                              class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">{{ old('description', $novel->description) }}</textarea>
                </div>

                <!-- Grid for Region, Language, Type, Rating, Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <!-- Region -->
                    <div>
                        <label for="region" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Region / Asal</label>
                        <input type="text" name="region" id="region"
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all"
                               value="{{ old('region', $novel->region) }}" placeholder="Contoh: Jepang">
                    </div>

                    <!-- Language -->
                    <div>
                        <label for="language" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Bahasa</label>
                        <input type="text" name="language" id="language"
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all"
                               value="{{ old('language', $novel->language) }}" placeholder="Contoh: Indonesia">
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Jenis Novel</label>
                        <select name="type" id="type" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                            <option value="original" {{ old('type', $novel->type) == 'original' ? 'selected' : '' }}>Original Story</option>
                            <option value="web_novel" {{ old('type', $novel->type) == 'web_novel' ? 'selected' : '' }}>Web Novel</option>
                            <option value="light_novel" {{ old('type', $novel->type) == 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                        </select>
                    </div>

                    <!-- Content Rating -->
                    <div>
                        <label for="content_rating" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Rating Usia</label>
                        <select name="content_rating" id="content_rating" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                            <option value="everyone" {{ old('content_rating', $novel->content_rating) == 'everyone' ? 'selected' : '' }}>Everyone (Semua Umur)</option>
                            <option value="teen" {{ old('content_rating', $novel->content_rating) == 'teen' ? 'selected' : '' }}>Teen (Remaja)</option>
                            <option value="mature" {{ old('content_rating', $novel->content_rating) == 'mature' ? 'selected' : '' }}>Mature (Dewasa)</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Status Novel</label>
                        <select name="status" id="status" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                            <option value="ongoing" {{ old('status', $novel->status) == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="hiatus" {{ old('status', $novel->status) == 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                            <option value="complete" {{ old('status', $novel->status) == 'complete' ? 'selected' : '' }}>Complete</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Cover & Media -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Cover Novel</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Upload cover baru untuk novel kamu</p>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8 items-start">
                <!-- Cover Preview -->
                <div class="flex-shrink-0">
                    <div id="cover-preview-container" class="w-40 h-[240px] rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 shadow-lg relative group">
                        @if($novel->cover_image_url)
                            <img id="cover-preview" src="{{ $novel->cover_image_url }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png';">
                        @elseif($novel->cover_image)
                            <img id="cover-preview" src="{{ asset('storage/' . $novel->cover_image) }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png';">
                        @else
                            <img id="cover-preview" src="" class="w-full h-full object-cover hidden" onerror="this.onerror=null; this.src='/error.png';">
                            <div id="cover-placeholder" class="w-full h-full flex flex-col items-center justify-center p-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">No Cover</p>
                                <p class="text-[10px] text-slate-500 mt-1">Rasio 3:4</p>
                            </div>
                        @endif
                        <button type="button" onclick="document.getElementById('cover_image').click()" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-white font-bold text-[10px] uppercase">Ganti Cover</span>
                        </button>
                    </div>
                </div>

                <!-- Upload Area -->
                <div class="flex-grow w-full">
                    <label for="cover_image" class="flex flex-col items-center justify-center w-full h-44 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-3xl cursor-pointer bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                            <svg class="w-10 h-10 mb-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="mb-2 text-sm text-slate-500 dark:text-slate-400 font-bold">Upload Cover Baru</p>
                            <p class="text-xs text-slate-400">Rasio 3:4 Disarankan (Contoh: 600x800)</p>
                        </div>
                        <input id="cover_image" name="cover_image" type="file" class="hidden" accept="image/*"
                               onchange="initCropper(this, 'cover-preview', { aspectRatio: 3/4, width: 600, height: 800 }); if(document.getElementById('cover-placeholder')) document.getElementById('cover-placeholder').remove();">
                    </label>
                </div>
            </div>
        </div>

        <!-- Section 3: Genres & Tags -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Genre & Tag</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Pilih genre dan tag yang sesuai dengan novel kamu</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Genres -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Genre</label>
                    <div class="grid grid-cols-2 gap-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($genres as $genre)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-indigo-200 dark:hover:border-indigo-800 transition-all group">
                                <input type="checkbox" name="genres[]" value="{{ $genre->id }}" {{ $novel->genres->contains($genre->id) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-indigo-600 transition-colors">{{ $genre->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Tags -->
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Tag</label>
                    <div class="grid grid-cols-2 gap-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($tags as $tag)
                            <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30 cursor-pointer hover:border-indigo-200 dark:hover:border-indigo-800 transition-all group">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" {{ $novel->tags->contains($tag->id) ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                                <span class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-indigo-600 transition-colors">#{{ $tag->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 4: Characters -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Karakter Novel</h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Kelola karakter yang ditampilkan di halaman novel (Maks 10)</p>
                    </div>
                </div>
                <button type="button" id="add-character-btn" class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-xl hover:bg-indigo-700 transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Karakter
                </button>
            </div>

            <div id="characters-list" class="space-y-5">
                @foreach($novel->characters as $index => $character)
                    @php $id = 'char-' . $character->id; @endphp
                    <div class="character-card p-6 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40 space-y-5 shadow-sm">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-700">
                            <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Karakter {{ $index + 1 }}
                            </h4>
                            <button type="button" class="remove-character text-xs font-bold text-rose-500 hover:text-rose-600 flex items-center gap-1 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </div>
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                            <!-- Character Image -->
                            <div class="lg:col-span-1">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-24 h-24 rounded-2xl overflow-hidden bg-slate-200 dark:bg-slate-700 flex-shrink-0 relative group shadow-inner">
                                        @if($character->image_url)
                                            <img id="{{ $id }}-preview" src="{{ $character->image_url }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png';">
                                        @elseif($character->image)
                                            <img id="{{ $id }}-preview" src="{{ asset('storage/' . $character->image) }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png';">
                                        @else
                                            <img id="{{ $id }}-preview" class="w-full h-full object-cover hidden" onerror="this.onerror=null; this.src='/error.png';">
                                            <div id="{{ $id }}-no-img" class="w-full h-full flex items-center justify-center text-[9px] font-bold text-slate-400 uppercase">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <button type="button" onclick="document.getElementById('{{ $id }}-input').click()" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <span class="text-white font-bold text-[9px] uppercase">Ganti</span>
                                        </button>
                                    </div>
                                    <input type="hidden" name="existing_character_image[]" value="{{ $character->image_url ?? $character->image }}">
                                    <input type="hidden" name="existing_character_public_id[]" value="{{ $character->image_public_id }}">
                                    <input type="file" id="{{ $id }}-input" name="character_image[]" accept="image/*" class="hidden"
                                           onchange="initCropper(this, '{{ $id }}-preview', { aspectRatio: 1, width: 600, height: 600 }); if(document.getElementById('{{ $id }}-no-img')) document.getElementById('{{ $id }}-no-img').classList.add('hidden'); document.getElementById('{{ $id }}-preview').classList.remove('hidden');">
                                    <button type="button" onclick="document.getElementById('{{ $id }}-input').click()" class="w-full py-2 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-[11px] font-bold text-slate-500 hover:bg-slate-50 transition-all text-center">
                                        Ganti Foto
                                    </button>
                                </div>
                            </div>

                            <!-- Character Details -->
                            <div class="lg:col-span-2 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Karakter</label>
                                        <input type="text" name="character_name[]" value="{{ $character->name }}" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Peran</label>
                                        <input type="text" name="character_role[]" value="{{ $character->role }}" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition-all">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi Singkat</label>
                                    <textarea name="character_description[]" rows="3" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition-all">{{ $character->description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <p id="max-characters-warning" class="text-xs text-amber-500 mt-3 hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Kamu sudah mencapai batas maksimal 10 karakter!
            </p>
        </div>

        <!-- Section 5: Submit -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('writer.novels.index') }}" class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 text-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-all order-2 sm:order-1">
                    Batal
                </a>
                <button type="submit" class="flex-grow py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none order-1 sm:order-2">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-track {
        background: #1e293b;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #475569;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('characters-list');
    const addButton = document.getElementById('add-character-btn');
    const maxCharactersWarning = document.getElementById('max-characters-warning');
    const MAX_CHARACTERS = 10;

    function updateAddButtonState() {
        const characterCount = list.querySelectorAll('.character-card').length;
        if (characterCount >= MAX_CHARACTERS) {
            addButton.disabled = true;
            addButton.classList.add('opacity-50', 'cursor-not-allowed');
            maxCharactersWarning.classList.remove('hidden');
        } else {
            addButton.disabled = false;
            addButton.classList.remove('opacity-50', 'cursor-not-allowed');
            maxCharactersWarning.classList.add('hidden');
        }
    }

    function attachRemoveHandlers() {
        list.querySelectorAll('.remove-character').forEach(function (button) {
            button.onclick = function () {
                button.closest('.character-card').remove();
                updateAddButtonState();
                // Re-number characters
                const cards = list.querySelectorAll('.character-card');
                cards.forEach((card, index) => {
                    const h4 = card.querySelector('h4');
                    if (h4) {
                        h4.innerHTML = `
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Karakter ${index + 1}
                        `;
                    }
                });
            };
        });
    }

    function createCharacterCard() {
        const characterCount = list.querySelectorAll('.character-card').length;
        if (characterCount >= MAX_CHARACTERS) return;

        const id = 'char-' + Date.now();
        const card = document.createElement('div');
        card.className = 'character-card p-6 rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40 space-y-5 shadow-sm';
        card.innerHTML = `
            <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-700">
                <h4 class="text-sm font-bold text-slate-700 dark:text-slate-200 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Karakter ${characterCount + 1}
                </h4>
                <button type="button" class="remove-character text-xs font-bold text-rose-500 hover:text-rose-600 flex items-center gap-1 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Hapus
                </button>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <div class="lg:col-span-1">
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-24 h-24 rounded-2xl overflow-hidden bg-slate-200 dark:bg-slate-700 flex-shrink-0 relative group shadow-inner">
                            <img id="${id}-preview" class="w-full h-full object-cover hidden" onerror="this.onerror=null; this.src='/error.png'">
                            <div id="${id}-no-img" class="w-full h-full flex items-center justify-center text-[9px] font-bold text-slate-400 uppercase">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <button type="button" onclick="document.getElementById('${id}-input').click()" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <span class="text-white font-bold text-[9px] uppercase">Ganti</span>
                            </button>
                        </div>
                        <input type="hidden" name="existing_character_image[]" value="">
                        <input type="hidden" name="existing_character_public_id[]" value="">
                        <input type="file" id="${id}-input" name="character_image[]" accept="image/*" class="hidden"
                               onchange="initCropper(this, '${id}-preview', { aspectRatio: 1, width: 600, height: 600 }); document.getElementById('${id}-no-img').classList.add('hidden'); document.getElementById('${id}-preview').classList.remove('hidden');">
                        <button type="button" onclick="document.getElementById('${id}-input').click()" class="w-full py-2 px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-[11px] font-bold text-slate-500 hover:bg-slate-50 transition-all text-center">
                            Pilih Foto
                        </button>
                    </div>
                </div>
                <div class="lg:col-span-2 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Nama Karakter</label>
                            <input type="text" name="character_name[]" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition-all" placeholder="Contoh: Akiro">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Peran</label>
                            <input type="text" name="character_role[]" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition-all" placeholder="Contoh: Protagonis">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi Singkat</label>
                        <textarea name="character_description[]" rows="3" class="w-full bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/40 transition-all" placeholder="Deskripsikan karakter ini..."></textarea>
                    </div>
                </div>
            </div>
        `;

        list.appendChild(card);
        attachRemoveHandlers();
        updateAddButtonState();
    }

    addButton.addEventListener('click', createCharacterCard);
    attachRemoveHandlers();
    updateAddButtonState();
});
</script>
@endpush
