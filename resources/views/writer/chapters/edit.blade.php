@extends('layouts.writer', [
    'title' => 'Edit Chapter',
    'subtitle' => $novel->title . ' - ' . $chapter->title
])

@section('content')
<div class="space-y-6">
    <div class="bg-neutral-900 rounded-xl p-8 border border-neutral-800">
        <form action="{{ route('writer.novels.chapters.update', [$novel, $chapter]) }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="{ status: '{{ old('status', $chapter->status) }}' }">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-3 space-y-3">
                    <label class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Chapter Artwork</label>
                    <div class="relative group aspect-square rounded-lg overflow-hidden bg-neutral-800 border border-dashed border-neutral-700">
                        @if($chapter->chapter_image)
                            <div id="chapter-placeholder" class="hidden"></div>
                            <img src="{{ asset('storage/' . $chapter->chapter_image) }}" id="chapter-preview" class="w-full h-full object-cover" alt="">
                        @else
                            <div id="chapter-placeholder" class="flex flex-col items-center justify-center h-full text-neutral-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                <p class="text-[10px] font-medium uppercase tracking-wider">Optional</p>
                            </div>
                            <img src="" id="chapter-preview" class="hidden w-full h-full object-cover" alt="">
                        @endif
                        <label class="absolute inset-0 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity bg-black/60 flex items-center justify-center">
                            <input type="file" name="chapter_image" id="chapter_image" class="hidden" accept="image/*" onchange="initCropper(this, 'chapter-preview', {aspectRatio: 1, placeholderId: 'chapter-placeholder'})">
                            <span class="px-4 py-2 bg-white text-black rounded-lg text-xs font-medium uppercase tracking-wider">Change Artwork</span>
                        </label>
                    </div>
                </div>

                <div class="md:col-span-9 space-y-6">
                    <div class="space-y-2">
                        <label for="title" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Chapter Title</label>
                        <input type="text" name="title" id="title"
                            class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-base text-white focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all @error('title') border-neutral-500 @enderror"
                            value="{{ old('title', $chapter->title) }}" placeholder="e.g. Chapter 1: The Awakening">
                        @error('title') <p class="mt-2 text-xs text-neutral-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="content" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Content</label>
                        <textarea name="content" id="content" rows="20"
                            class="w-full bg-black border border-neutral-700 rounded-lg px-5 py-5 text-base text-neutral-200 leading-relaxed focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all"
                            placeholder="Once upon a time...">{{ old('content', $chapter->content) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="status" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Publication Status</label>
                    <select name="status" id="status" x-model="status"
                        class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white focus:ring-1 focus:ring-white focus:border-white transition-all">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="scheduled">Scheduled</option>
                    </select>
                </div>

                <div x-show="status === 'scheduled'" x-transition class="space-y-2">
                    <label for="published_at" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Release Date & Time</label>
                    <input type="datetime-local" name="published_at" id="published_at"
                        class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white focus:ring-1 focus:ring-white focus:border-white transition-all"
                        value="{{ old('published_at', $chapter->published_at ? $chapter->published_at->format('Y-m-d\TH:i') : '') }}">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-neutral-800">
                <a href="{{ route('writer.novels.workspace', $novel) }}" class="px-6 py-3 text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-white transition-colors">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-lg hover:bg-neutral-200 transition-all">Update Chapter</button>
            </div>
        </form>
    </div>

    <div class="bg-neutral-900 rounded-xl p-6 border border-neutral-700 flex flex-col md:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-base font-medium text-white mb-1">Delete Chapter</h3>
            <p class="text-sm text-neutral-500">This action is permanent and cannot be undone.</p>
        </div>
        <form action="{{ route('writer.novels.chapters.destroy', [$novel, $chapter]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-3 border border-neutral-600 text-neutral-300 text-xs font-medium uppercase tracking-wider rounded-lg hover:text-white hover:border-white transition-all" onclick="return confirm('Permanently delete this chapter?')">
                Confirm Delete
            </button>
        </form>
    </div>
</div>

@include('partials.cropping-modal')
@endsection
