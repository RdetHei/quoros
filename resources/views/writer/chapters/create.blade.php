@extends('layouts.writer', [
    'title' => 'Add New Chapter',
    'subtitle' => 'Drafting: ' . $novel->title
])

@section('content')
<div class="space-y-6" x-data="bulkUploadHandler()">
    <div class="bg-neutral-900 rounded-xl p-8 border border-neutral-800">
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4" x-show="step === 'upload'">
            <div>
                <h2 class="text-lg font-semibold text-white">Creation Mode</h2>
                <p class="text-sm text-neutral-400 mt-1">Choose how you want to add your content.</p>
            </div>

            <div class="flex gap-1 border-b border-neutral-800">
                <button @click="uploadMode = 'single'" :class="uploadMode === 'single' ? 'text-white border-white' : 'text-neutral-500 border-transparent hover:text-neutral-300'" class="px-4 py-2.5 text-xs font-medium uppercase tracking-wider border-b-2 -mb-px transition-colors">Manual Write</button>
                <button @click="uploadMode = 'bulk'" :class="uploadMode === 'bulk' ? 'text-white border-white' : 'text-neutral-500 border-transparent hover:text-neutral-300'" class="px-4 py-2.5 text-xs font-medium uppercase tracking-wider border-b-2 -mb-px transition-colors">Bulk Import</button>
            </div>
        </div>

        <form x-show="uploadMode === 'single' && step === 'upload'" action="{{ route('writer.novels.chapters.store', $novel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="{ status: 'published' }">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-3 space-y-3">
                    <label class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Chapter Artwork</label>
                    <div class="relative group aspect-square rounded-lg overflow-hidden bg-neutral-800 border border-dashed border-neutral-700">
                        <div id="chapter-placeholder" class="flex flex-col items-center justify-center h-full text-neutral-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <p class="text-[10px] font-medium uppercase tracking-wider">Optional</p>
                        </div>
                        <img src="" id="chapter-preview" class="hidden w-full h-full object-cover" alt="">
                        <label class="absolute inset-0 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity bg-black/60 flex items-center justify-center">
                            <input type="file" name="chapter_image" id="chapter_image" class="hidden" accept="image/*" onchange="initCropper(this, 'chapter-preview', {aspectRatio: 1, placeholderId: 'chapter-placeholder'})">
                            <span class="px-4 py-2 bg-white text-black rounded-lg text-xs font-medium uppercase tracking-wider">Upload</span>
                        </label>
                    </div>
                </div>

                <div class="md:col-span-9 space-y-6">
                    <div class="space-y-2">
                        <label for="title" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Chapter Title</label>
                        <input type="text" name="title" id="title"
                            class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-base text-white focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all @error('title') border-neutral-500 @enderror"
                            value="{{ old('title') }}" placeholder="e.g. Chapter 1: The Awakening">
                        @error('title') <p class="mt-2 text-xs text-neutral-400">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="content" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Content</label>
                        <textarea name="content" id="content" rows="20"
                            class="w-full bg-black border border-neutral-700 rounded-lg px-5 py-5 text-base text-neutral-200 leading-relaxed focus:outline-none focus:ring-1 focus:ring-white focus:border-white transition-all"
                            placeholder="Once upon a time...">{{ old('content') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="status" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Publication Status</label>
                    <select name="status" id="status" x-model="status"
                        class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white focus:ring-1 focus:ring-white focus:border-white transition-all">
                        <option value="published">Publish Now</option>
                        <option value="draft">Save as Draft</option>
                        <option value="scheduled">Schedule Release</option>
                    </select>
                </div>

                <div x-show="status === 'scheduled'" x-transition class="space-y-2">
                    <label for="published_at" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Release Date & Time</label>
                    <input type="datetime-local" name="published_at" id="published_at"
                        class="w-full bg-black border border-neutral-700 rounded-lg px-4 py-3 text-sm text-white focus:ring-1 focus:ring-white focus:border-white transition-all"
                        value="{{ old('published_at') }}">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-neutral-800">
                <a href="{{ route('writer.novels.workspace', $novel) }}" class="px-6 py-3 text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-white transition-colors">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-lg hover:bg-neutral-200 transition-all">Publish Chapter</button>
            </div>
        </form>

        <div x-show="uploadMode === 'bulk'">
            <div x-show="step === 'upload'" class="space-y-6" x-transition>
                <div class="p-8 bg-black/40 rounded-xl border border-neutral-800 text-center max-w-2xl mx-auto">
                    <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center text-black mx-auto mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Bulk Document Import</h3>
                    <p class="text-sm text-neutral-400 mb-8 leading-relaxed">Upload your EPUB, DOCX, or PDF files. Chapters will be automatically split based on document headings.</p>

                    <label class="relative group cursor-pointer block">
                        <div class="p-8 border border-dashed border-neutral-700 rounded-xl bg-neutral-900 group-hover:border-neutral-500 transition-all">
                            <p class="text-sm font-medium text-white uppercase tracking-wider" x-text="docFile ? docFile.name : 'Choose file or drag & drop'"></p>
                            <p class="text-[10px] text-neutral-500 uppercase mt-2">Maximum 50MB</p>
                        </div>
                        <input type="file" accept=".epub,.docx,.pdf" class="hidden" @change="docFile = $event.target.files[0]" />
                    </label>
                </div>

                <div class="flex items-center justify-center pt-6 border-t border-neutral-800">
                    <button @click="startParsing()" :disabled="!docFile || isParsing" class="px-8 py-3 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-lg hover:bg-neutral-200 transition-all flex items-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed">
                        <template x-if="isParsing">
                            <svg class="animate-spin h-4 w-4 text-black" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <span x-text="isParsing ? 'Analyzing Document...' : 'Start Extraction'"></span>
                    </button>
                </div>
            </div>

            <div x-show="step === 'processing'" class="space-y-6" x-transition>
                <div class="bg-black/40 rounded-xl p-8 border border-neutral-800">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-semibold text-white">Processing Library...</h3>
                            <p class="text-sm text-neutral-400 mt-1">Please keep this window open while we work.</p>
                        </div>
                        <span class="text-3xl font-semibold text-white tabular-nums" x-text="Math.round(progress) + '%'"></span>
                    </div>

                    <div class="w-full h-2 bg-neutral-800 rounded-full overflow-hidden mb-8">
                        <div class="h-full bg-white transition-all duration-500" :style="`width: ${progress}%`"></div>
                    </div>

                    <div class="flex items-center gap-5 p-5 bg-neutral-900 rounded-xl border border-neutral-800 mb-8">
                        <div class="w-12 h-12 rounded-lg bg-neutral-800 border border-neutral-700 flex items-center justify-center text-white">
                            <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                        <div class="flex-grow min-w-0">
                            <p class="text-[10px] font-medium text-neutral-500 uppercase tracking-wider mb-1">Current Task</p>
                            <h4 class="text-base font-medium text-white truncate" x-text="chapters[currentIndex] ? chapters[currentIndex].title : 'Preparing extraction...'"></h4>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wider"><span x-text="currentIndex + 1"></span> / <span x-text="chapters.length"></span></p>
                        </div>
                    </div>

                    <div class="max-h-72 overflow-y-auto pr-2 space-y-2 custom-scrollbar">
                        <template x-for="(chapter, index) in chapters" :key="index">
                            <div class="flex items-center justify-between p-3.5 rounded-lg transition-all"
                                 :class="index === currentIndex ? 'bg-neutral-800 border border-neutral-600' : 'opacity-50'">
                                <div class="flex items-center gap-3 min-w-0">
                                    <template x-if="index < currentIndex">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    </template>
                                    <template x-if="index === currentIndex">
                                        <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin shrink-0"></div>
                                    </template>
                                    <template x-if="index > currentIndex">
                                        <div class="w-5 h-5 rounded-full border-2 border-neutral-700 shrink-0"></div>
                                    </template>
                                    <span class="text-sm font-medium truncate" :class="index === currentIndex ? 'text-white' : 'text-neutral-500'" x-text="chapter.title"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div x-show="step === 'finished'" class="text-center py-16" x-transition>
                <div class="w-20 h-20 bg-white text-black rounded-full flex items-center justify-center mx-auto mb-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h2 class="text-2xl font-semibold text-white mb-3">Mission Accomplished!</h2>
                <p class="text-neutral-400 mb-10 max-w-md mx-auto leading-relaxed"><span class="font-medium text-white" x-text="chapters.length"></span> chapters have been extracted and added to your novel library.</p>
                <a href="{{ route('writer.novels.workspace', $novel) }}" class="inline-flex items-center gap-3 px-8 py-3 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-lg hover:bg-neutral-200 transition-all">
                    Back to Workspace
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                </a>
            </div>
        </div>
    </div>
</div>

@include('partials.cropping-modal')

<script>
    function bulkUploadHandler() {
        return {
            uploadMode: 'single',
            step: 'upload',
            docFile: null,
            isParsing: false,
            chapters: [],
            currentIndex: 0,
            progress: 0,

            async startParsing() {
                if (!this.docFile) return;

                this.isParsing = true;
                const formData = new FormData();
                formData.append('file', this.docFile);
                formData.append('_token', '{{ csrf_token() }}');

                try {
                    const response = await fetch('{{ route('writer.novels.chapters.bulk-parse', $novel->id) }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();
                    if (!response.ok) throw new Error(data.error || 'Failed to process document');

                    this.chapters = data.chapters;
                    this.isParsing = false;
                    this.step = 'processing';
                    this.uploadChapters();
                } catch (error) {
                    alert(error.message);
                    this.isParsing = false;
                }
            },

            async uploadChapters() {
                for (let i = 0; i < this.chapters.length; i++) {
                    this.currentIndex = i;
                    this.progress = (i / this.chapters.length) * 100;

                    try {
                        const response = await fetch('{{ route('writer.novels.chapters.store-bulk', $novel->id) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                title: this.chapters[i].title,
                                content: this.chapters[i].content
                            })
                        });
                        if (!response.ok) throw new Error('Failed to upload chapter');
                    } catch (error) {
                        console.error(error);
                    }
                }
                this.progress = 100;
                this.step = 'finished';
            }
        }
    }
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #404040; border-radius: 10px; }
</style>
@endsection
