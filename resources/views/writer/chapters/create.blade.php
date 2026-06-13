@extends('layouts.writer', [
    'title' => 'Add New Chapter',
    'subtitle' => 'Drafting: ' . $novel->title
])

@section('content')
<div class="space-y-8" x-data="bulkUploadHandler()">
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6" x-show="step === 'upload'">
            <div>
                <h2 class="text-xl font-black text-slate-900 dark:text-white">Creation Mode</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Choose how you want to add your content.</p>
            </div>

            <div class="flex p-1.5 bg-slate-100 dark:bg-slate-800 rounded-2xl w-fit">
                <button @click="uploadMode = 'single'" :class="uploadMode === 'single' ? 'bg-white dark:bg-slate-900 text-indigo-600 shadow-sm' : 'text-slate-500'" class="px-6 py-2.5 text-xs font-black uppercase tracking-widest rounded-xl transition-all">Manual Write</button>
                <button @click="uploadMode = 'bulk'" :class="uploadMode === 'bulk' ? 'bg-white dark:bg-slate-900 text-indigo-600 shadow-sm' : 'text-slate-500'" class="px-6 py-2.5 text-xs font-black uppercase tracking-widest rounded-xl transition-all">Bulk Import</button>
            </div>
        </div>

        <!-- Single Chapter Form -->
        <form x-show="uploadMode === 'single' && step === 'upload'" action="{{ route('writer.novels.chapters.store', $novel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-10" x-data="{ status: 'published' }">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
                <div class="md:col-span-3 space-y-4">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Chapter Artwork</label>
                    <div class="relative group aspect-square rounded-3xl overflow-hidden bg-slate-100 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 shadow-inner">
                        <div id="chapter-placeholder" class="flex flex-col items-center justify-center h-full text-slate-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            <p class="text-[10px] font-black uppercase tracking-widest">Optional</p>
                        </div>
                        <img src="" id="chapter-preview" class="hidden w-full h-full object-cover">
                        <label class="absolute inset-0 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity bg-black/40 flex items-center justify-center">
                            <input type="file" name="chapter_image" id="chapter_image" class="hidden" accept="image/*" onchange="initCropper(this, 'chapter-preview', {aspectRatio: 1, placeholderId: 'chapter-placeholder'})">
                            <span class="px-4 py-2 bg-white text-slate-900 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg">Upload</span>
                        </label>
                    </div>
                </div>

                <div class="md:col-span-9 space-y-10">
                    <div class="space-y-2">
                        <label for="title" class="text-xs font-black uppercase tracking-widest text-slate-400">Chapter Title</label>
                        <input type="text" name="title" id="title" 
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 text-base focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-bold text-slate-900 dark:text-white @error('title') border-rose-500 @enderror" 
                            value="{{ old('title') }}" placeholder="e.g. Chapter 1: The Awakening">
                        @error('title') <p class="mt-2 text-xs text-rose-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="content" class="text-xs font-black uppercase tracking-widest text-slate-400">Content</label>
                        <textarea name="content" id="content" rows="20" 
                            class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-[2rem] px-8 py-8 text-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-slate-700 dark:text-slate-200 leading-relaxed" 
                            placeholder="Once upon a time...">{{ old('content') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4">
                <div class="space-y-2">
                    <label for="status" class="text-xs font-black uppercase tracking-widest text-slate-400">Publication Status</label>
                    <select name="status" id="status" x-model="status"
                        class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="published">Publish Now</option>
                        <option value="draft">Save as Draft</option>
                        <option value="scheduled">Schedule Release</option>
                    </select>
                </div>

                <div x-show="status === 'scheduled'" x-transition class="space-y-2">
                    <label for="published_at" class="text-xs font-black uppercase tracking-widest text-slate-400">Release Date & Time</label>
                    <input type="datetime-local" name="published_at" id="published_at" 
                        class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl px-6 py-4 text-sm font-bold text-slate-900 dark:text-white focus:ring-2 focus:ring-indigo-500 transition-all"
                        value="{{ old('published_at') }}">
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 pt-10 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('dashboard', ['tab' => 'library']) }}" class="px-8 py-4 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit" class="px-12 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20">
                    Publish Chapter
                </button>
            </div>
        </form>

        <!-- Bulk EPUB Form -->
        <div x-show="uploadMode === 'bulk'">
            <!-- Phase 1: Upload & Parse -->
            <div x-show="step === 'upload'" class="space-y-8" x-transition>
                <div class="p-10 bg-indigo-50/50 dark:bg-indigo-900/10 rounded-[2.5rem] border border-indigo-100 dark:border-indigo-900/30 text-center max-w-2xl mx-auto">
                    <div class="w-20 h-20 bg-indigo-600 rounded-3xl flex items-center justify-center text-white mx-auto mb-6 shadow-lg shadow-indigo-600/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-2">Bulk Document Import</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-10 leading-relaxed">Upload your EPUB, DOCX, or PDF files. Our AI will automatically split chapters based on your document headings.</p>

                    <label class="relative group cursor-pointer block">
                        <div class="p-8 border-2 border-dashed border-indigo-200 dark:border-indigo-800 rounded-[2rem] bg-white dark:bg-slate-900 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/20 transition-all">
                            <p class="text-sm font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest" x-text="docFile ? docFile.name : 'Choose file or drag & drop'"></p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase mt-2">Maximum 50MB</p>
                        </div>
                        <input type="file" accept=".epub,.docx,.pdf" class="hidden" @change="docFile = $event.target.files[0]" />
                    </label>
                </div>

                <div class="flex items-center justify-center gap-4 pt-10 border-t border-slate-100 dark:border-slate-800">
                    <button @click="startParsing()" :disabled="!docFile || isParsing" class="px-12 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20 flex items-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed">
                        <template x-if="isParsing">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <span x-text="isParsing ? 'Analyzing Document...' : 'Start Extraction'"></span>
                    </button>
                </div>
            </div>

            <!-- Phase 2: Progress Card -->
            <div x-show="step === 'processing'" class="space-y-8" x-transition>
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-[2.5rem] p-10 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white">Processing Library...</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Please keep this window open while we work.</p>
                        </div>
                        <span class="text-4xl font-black text-indigo-600 dark:text-indigo-400" x-text="Math.round(progress) + '%'"></span>
                    </div>

                    <div class="w-full h-4 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden mb-10 shadow-inner">
                        <div class="h-full bg-indigo-600 transition-all duration-500 shadow-lg" :style="`width: ${progress}%`"></div>
                    </div>

                    <div class="flex items-center gap-6 p-6 bg-white dark:bg-slate-900 rounded-3xl border border-slate-100 dark:border-slate-800 shadow-sm mb-10">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                            <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                        <div class="flex-grow min-w-0">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Current Task</p>
                            <h4 class="text-lg font-black text-slate-900 dark:text-white truncate" x-text="chapters[currentIndex] ? chapters[currentIndex].title : 'Preparing extraction...'"></h4>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest"><span x-text="currentIndex + 1"></span> / <span x-text="chapters.length"></span></p>
                        </div>
                    </div>

                    <div class="max-h-72 overflow-y-auto pr-4 space-y-3 custom-scrollbar">
                        <template x-for="(chapter, index) in chapters" :key="index">
                            <div class="flex items-center justify-between p-4 rounded-2xl transition-all" 
                                 :class="index === currentIndex ? 'bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800 shadow-sm' : 'opacity-50'">
                                <div class="flex items-center gap-4 min-w-0">
                                    <template x-if="index < currentIndex">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    </template>
                                    <template x-if="index === currentIndex">
                                        <div class="w-5 h-5 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin shrink-0"></div>
                                    </template>
                                    <template x-if="index > currentIndex">
                                        <div class="w-5 h-5 rounded-full border-2 border-slate-200 dark:border-slate-700 shrink-0"></div>
                                    </template>
                                    <span class="text-sm font-bold truncate" :class="index === currentIndex ? 'text-indigo-900 dark:text-white' : 'text-slate-500'" x-text="chapter.title"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Phase 3: Finished -->
            <div x-show="step === 'finished'" class="text-center py-20" x-transition>
                <div class="w-28 h-28 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-10 shadow-xl shadow-emerald-200/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h2 class="text-4xl font-black text-slate-900 dark:text-white mb-4">Mission Accomplished!</h2>
                <p class="text-lg text-slate-500 dark:text-slate-400 mb-12 max-w-md mx-auto leading-relaxed"><span class="font-black text-slate-900 dark:text-white" x-text="chapters.length"></span> chapters have been extracted and added to your novel library.</p>
                <a href="{{ route('dashboard', ['tab' => 'library']) }}" class="inline-flex items-center gap-3 px-12 py-5 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20">
                    Back to Workspace
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
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
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>
@endsection
