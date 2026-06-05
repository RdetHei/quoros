@extends('layouts.writer', [
    'title' => 'Novel Settings',
    'subtitle' => 'Update your story details, visuals, and categorization.'
])

@section('content')
<div class="space-y-8">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 border border-slate-200 dark:border-slate-800 shadow-sm">
        <form action="{{ route('writer.novels.update', $novel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Visuals Sidebar -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="space-y-4">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400">Cover Artwork</label>
                        <div class="relative group aspect-[3/4] rounded-3xl overflow-hidden bg-slate-100 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 shadow-inner">
                            @if($novel->cover_image_url)
                                <div id="cover-placeholder" class="hidden"></div>
                                <img src="{{ $novel->cover_image_url }}" id="cover-preview" class="w-full h-full object-cover">
                            @else
                                <div id="cover-placeholder" class="flex flex-col items-center justify-center h-full text-slate-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p class="text-[10px] font-black uppercase tracking-widest">No Cover</p>
                                </div>
                                <img src="" id="cover-preview" class="hidden w-full h-full object-cover">
                            @endif
                            <label class="absolute inset-0 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity bg-black/40 flex items-center justify-center">
                                <input type="file" name="cover_image" class="hidden" onchange="initCropper(this, 'cover-preview', {aspectRatio: 3/4, placeholderId: 'cover-placeholder'})">
                                <span class="px-4 py-2 bg-white text-slate-900 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg">Change Artwork</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400">Content Type</label>
                        <select name="type" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white">
                            <option value="original" {{ $novel->type === 'original' ? 'selected' : '' }}>Original Story</option>
                            <option value="web_novel" {{ $novel->type === 'web_novel' ? 'selected' : '' }}>Web Novel</option>
                            <option value="light_novel" {{ $novel->type === 'light_novel' ? 'selected' : '' }}>Light Novel</option>
                        </select>
                    </div>

                    <div class="space-y-4">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400">Content Rating</label>
                        <select name="content_rating" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white">
                            <option value="everyone" {{ $novel->content_rating === 'everyone' ? 'selected' : '' }}>Everyone</option>
                            <option value="teen" {{ $novel->content_rating === 'teen' ? 'selected' : '' }}>Teen (13+)</option>
                            <option value="mature" {{ $novel->content_rating === 'mature' ? 'selected' : '' }}>Mature (18+)</option>
                        </select>
                    </div>

                    <div class="space-y-4">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400">Release Status</label>
                        <select name="status" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white">
                            <option value="ongoing" {{ $novel->status === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="complete" {{ $novel->status === 'complete' ? 'selected' : '' }}>Completed</option>
                            <option value="hiatus" {{ $novel->status === 'hiatus' ? 'selected' : '' }}>On Hiatus</option>
                        </select>
                    </div>

                    <div class="space-y-4">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400">Language</label>
                        <select name="language" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white">
                            <option value="id" {{ $novel->language === 'id' ? 'selected' : '' }}>Indonesian</option>
                            <option value="en" {{ $novel->language === 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>

                    <div class="space-y-4">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-400">Region</label>
                        <select name="region" class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white">
                            <option value="lokal" {{ $novel->region === 'lokal' ? 'selected' : '' }}>Lokal</option>
                            <option value="global" {{ $novel->region === 'global' ? 'selected' : '' }}>Global</option>
                        </select>
                    </div>
                </div>

                <!-- Main Info Area -->
                <div class="lg:col-span-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label for="title" class="text-xs font-black uppercase tracking-widest text-slate-400">Novel Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $novel->title) }}" 
                                class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white" required>
                        </div>
                        <div class="space-y-2">
                            <label for="alternative_title" class="text-xs font-black uppercase tracking-widest text-slate-400">Alternative Title</label>
                            <input type="text" name="alternative_title" id="alternative_title" value="{{ old('alternative_title', $novel->alternative_title) }}" 
                                class="w-full px-5 py-4 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="text-xs font-black uppercase tracking-widest text-slate-400">Synopsis</label>
                        <textarea name="description" id="description" rows="10" 
                            class="w-full px-6 py-5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-3xl focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-slate-700 dark:text-slate-200 leading-relaxed" required>{{ old('description', $novel->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 pt-4">
                        <div class="space-y-4">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400">Genres</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($genres as $genre)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="genres[]" value="{{ $genre->id }}" class="peer hidden" 
                                            {{ in_array($genre->id, $novel->genres->pluck('id')->toArray()) ? 'checked' : '' }}>
                                        <div class="px-3 py-2 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-[10px] font-black uppercase tracking-widest text-slate-400 peer-checked:bg-indigo-600 peer-checked:text-white peer-checked:border-indigo-600 transition-all text-center">
                                            {{ $genre->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-400">Tags</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="peer hidden"
                                            {{ in_array($tag->id, $novel->tags->pluck('id')->toArray()) ? 'checked' : '' }}>
                                        <div class="px-2 py-1.5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-lg text-[9px] font-black uppercase tracking-widest text-slate-400 peer-checked:bg-slate-900 dark:peer-checked:bg-white peer-checked:text-white dark:peer-checked:text-slate-900 transition-all">
                                            #{{ $tag->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-10 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('writer.novels.index') }}" class="px-8 py-4 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit" class="px-12 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials.cropping-modal')
@endsection
