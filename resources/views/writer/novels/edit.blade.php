@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto my-8 space-y-6">
    <!-- Page Header -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">Edit Novel</h1>
        <p class="text-slate-50 dark:text-slate-400">Update your novel information: <span class="font-bold text-indigo-600 dark:text-indigo-400">{{ $novel->title }}</span></p>
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
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Basic Information</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Title, description, and basic novel details</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Novel Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" id="title"
                           class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('title') border-rose-500 @enderror"
                           value="{{ old('title', $novel->title) }}" required>
                    @error('title')
                        <p class="mt-2 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alternative Title -->
                <div>
                    <label for="alternative_title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Alternative Title (Optional)</label>
                    <input type="text" name="alternative_title" id="alternative_title"
                           class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all"
                           value="{{ old('alternative_title', $novel->alternative_title) }}">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Description / Synopsis <span class="text-rose-500">*</span></label>
                    <textarea name="description" id="description" rows="5"
                              class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">{{ old('description', $novel->description) }}</textarea>
                </div>

                <!-- Grid for Region, Language, Type, Rating, Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    <!-- Region -->
                    <div>
                        <label for="region" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Region / Origin</label>
                        <input type="text" name="region" id="region"
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all"
                               value="{{ old('region', $novel->region) }}" placeholder="Example: Japan">
                    </div>

                    <!-- Language -->
                    <div>
                        <label for="language" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Language</label>
                        <input type="text" name="language" id="language"
                               class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all"
                               value="{{ old('language', $novel->language) }}" placeholder="Example: Indonesian">
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Novel Type</label>
                        <select name="type" id="type" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                            <option value="original" {{ old('type', $novel->type) == 'original' ? 'selected' : '' }}>Original Story</option>
                            <option value="web_novel" {{ old('type', $novel->type) == 'web_novel' ? 'selected' : '' }}>Web Novel</option>
                            <option value="light_novel" {{ old('type', $novel->type) == 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                        </select>
                    </div>

                    <!-- Content Rating -->
                    <div>
                        <label for="content_rating" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Age Rating</label>
                        <select name="content_rating" id="content_rating" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                            <option value="everyone" {{ old('content_rating', $novel->content_rating) == 'everyone' ? 'selected' : '' }}>Everyone</option>
                            <option value="teen" {{ old('content_rating', $novel->content_rating) == 'teen' ? 'selected' : '' }}>Teen</option>
                            <option value="mature" {{ old('content_rating', $novel->content_rating) == 'mature' ? 'selected' : '' }}>Mature</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Novel Status</label>
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
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Novel Cover</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Upload a new cover for your novel</p>
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
                                <p class="text-[10px] text-slate-500 mt-1">3:4 Ratio</p>
                            </div>
                        @endif
                        <button type="button" onclick="document.getElementById('cover_image').click()" class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="text-white font-bold text-[10px] uppercase">Change Cover</span>
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
                            <p class="mb-2 text-sm text-slate-500 dark:text-slate-400 font-bold">Upload New Cover</p>
                            <p class="text-xs text-slate-400">3:4 Ratio Recommended (Example: 600x800)</p>
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
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Genres & Tags</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Select genres and tags that suit your novel</p>
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

        <div class="bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-2xl p-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                    <h2 class="text-sm font-bold text-indigo-800 dark:text-indigo-300">Character Management Dipisah</h2>
                    <p class="text-sm text-indigo-700 dark:text-indigo-400 mt-1">
                        Kelola karakter novel melalui halaman khusus agar lebih rapi.
                    </p>
                </div>
                <a href="{{ route('writer.novels.characters.index', $novel) }}" class="inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                    Buka Halaman Karakter
                </a>
            </div>
        </div>

        <!-- Section 5: Submit -->
        <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('writer.novels.index') }}" class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 text-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-all order-2 sm:order-1">
                    Cancel
                </a>
                <button type="submit" class="flex-grow py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none order-1 sm:order-2">
                    Save Changes
                </button>
            </div>
        </div>
    </form>
</div>

@endsection
