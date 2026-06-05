@extends('layouts.writer', [
    'title' => 'Edit Chapter',
    'subtitle' => $novel->title . ' - ' . $chapter->title
])

@section('content')
<div class="space-y-8">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 border border-slate-200 dark:border-slate-800 shadow-sm">
        <form action="{{ route('writer.chapters.update', [$novel->id, $chapter->id]) }}" method="POST" enctype="multipart/form-data" class="space-y-10" x-data="{ status: '{{ old('status', $chapter->status) }}' }">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                <div class="md:col-span-3 space-y-4">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Chapter Artwork</label>
                    <div class="relative group aspect-square rounded-3xl overflow-hidden bg-slate-100 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 shadow-inner">
                        @if($chapter->chapter_image)
                            <div id="chapter-placeholder" class="hidden"></div>
                            <img src="{{ asset('storage/' . $chapter->chapter_image) }}" id="chapter-preview" class="w-full h-full object-cover">
                        @else
                            <div id="chapter-placeholder" class="flex flex-col items-center justify-center h-full text-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <p class="text-[10px] font-black uppercase tracking-widest">Optional</p>
                            </div>
                            <img src="" id="chapter-preview" class="hidden w-full h-full object-cover">
                        @endif
                        <label class="absolute inset-0 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity bg-black/40 flex items-center justify-center">
                            <input type="file" name="chapter_image" id="chapter_image" class="hidden" accept="image/*" onchange="initCropper(this, 'chapter-preview', {aspectRatio: 1, placeholderId: 'chapter-placeholder'})">
                            <span class="px-4 py-2 bg-white text-slate-900 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg">Change Artwork</span>
                        </label>
                    </div>
                </div>

                <div class="md:col-span-9 space-y-10">
                    <div class="space-y-2">
                        <label for="title" class="text-xs font-black uppercase tracking-widest text-slate-400">Chapter Title</label>
                        <input type="text" name="title" id="title" 
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 text-base focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white @error('title') border-rose-500 @enderror" 
                            value="{{ old('title', $chapter->title) }}" placeholder="e.g. Chapter 1: The Awakening">
                        @error('title') <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="content" class="text-xs font-black uppercase tracking-widest text-slate-400">Content</label>
                        <textarea name="content" id="content" rows="20" 
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-[2rem] px-8 py-8 text-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-slate-700 dark:text-slate-200 leading-relaxed" 
                            placeholder="Once upon a time...">{{ old('content', $chapter->content) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                <div class="space-y-2">
                    <label for="status" class="text-xs font-black uppercase tracking-widest text-slate-400">Publication Status</label>
                    <select name="status" id="status" x-model="status"
                        class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="scheduled">Scheduled</option>
                    </select>
                </div>

                <div x-show="status === 'scheduled'" x-transition class="space-y-2">
                    <label for="published_at" class="text-xs font-black uppercase tracking-widest text-slate-400">Release Date & Time</label>
                    <input type="datetime-local" name="published_at" id="published_at" 
                        class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all"
                        value="{{ old('published_at', $chapter->published_at ? $chapter->published_at->format('Y-m-d\TH:i') : '') }}">
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-10 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('writer.novels.index') }}" class="px-8 py-4 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit" class="px-12 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20">
                    Update Chapter
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="bg-rose-50 dark:bg-rose-900/10 rounded-[2rem] p-8 border border-rose-100 dark:border-rose-900/30 flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <h3 class="text-lg font-black text-rose-900 dark:text-rose-400 mb-1">Delete Chapter</h3>
            <p class="text-sm text-rose-600/70 dark:text-rose-400/60 font-medium">This action is permanent and cannot be undone.</p>
        </div>
        <form action="{{ route('writer.chapters.destroy', [$novel->id, $chapter->id]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-10 py-4 bg-rose-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-rose-700 transition-all shadow-lg shadow-rose-600/20" onclick="return confirm('Permanently delete this chapter?')">
                Confirm Delete
            </button>
        </form>
    </div>
</div>

@include('partials.cropping-modal')
@endsection
