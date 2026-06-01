@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto my-12" x-data="bulkUploadHandler()">
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 md:p-10 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-slate-800">
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6" x-show="step === 'upload'">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mb-2">Add Chapter</h1>
                <p class="text-slate-500 dark:text-slate-400">Adding a new chapter for novel <span class="font-bold text-indigo-600">{{ $novel->title }}</span>.</p>
            </div>

            <div class="flex p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl w-fit">
                <button @click="uploadMode = 'single'" :class="uploadMode === 'single' ? 'bg-white dark:bg-slate-900 text-indigo-600 shadow-sm' : 'text-slate-500'" class="px-6 py-2 text-xs font-bold rounded-xl transition-all">Single</button>
                <button @click="uploadMode = 'bulk'" :class="uploadMode === 'bulk' ? 'bg-white dark:bg-slate-900 text-indigo-600 shadow-sm' : 'text-slate-500'" class="px-6 py-2 text-xs font-bold rounded-xl transition-all">Bulk Upload</button>
            </div>
        </div>

        <!-- Single Chapter Form -->
        <form x-show="uploadMode === 'single' && step === 'upload'" action="{{ route('writer.chapters.store', $novel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="{ status: 'published' }">
            @csrf
            <div>
                <label for="title" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Chapter Title</label>
                <input type="text" name="title" id="title" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all @error('title') border-red-500 @enderror" 
                    value="{{ old('title') }}" placeholder="Example: Chapter 1: The Beginning">
                @error('title')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="content" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Chapter Content (Text)</label>
                <textarea name="content" id="content" rows="15" 
                    class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all font-serif" 
                    placeholder="Write your story here...">{{ old('content') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Publication Status</label>
                    <select name="status" id="status" x-model="status"
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all">
                        <option value="published">Publish Now</option>
                        <option value="draft">Save as Draft</option>
                        <option value="scheduled">Schedule Release</option>
                    </select>
                </div>

                <div x-show="status === 'scheduled'" x-transition>
                    <label for="published_at" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Release Date & Time</label>
                    <input type="datetime-local" name="published_at" id="published_at" 
                        class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl px-5 py-4 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white dark:focus:bg-slate-900 transition-all"
                        value="{{ old('published_at') }}">
                    @error('published_at')
                        <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="file" class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">OR Upload File (PDF, EPUB, DOCX)</label>
                <div class="flex items-center justify-center w-full">
                    <label for="file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-200 dark:border-slate-700 border-dashed rounded-3xl cursor-pointer bg-slate-50 dark:bg-slate-800/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="text-sm text-slate-500 dark:text-slate-400 font-bold text-center">Click to upload chapter document</p>
                        </div>
                        <input id="file" name="file" type="file" class="hidden" />
                    </label>
                </div>
                @error('file')
                    <p class="mt-2 text-xs text-red-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-6">
                <button type="submit" class="flex-grow py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none">Save Chapter</button>
                <a href="{{ route('novels.show', $novel->slug) }}" class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 text-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Cancel</a>
            </div>
        </form>

        <!-- Bulk EPUB Form -->
        <div x-show="uploadMode === 'bulk'">
            <!-- Phase 1: Upload & Parse -->
            <div x-show="step === 'upload'" class="space-y-8" x-transition>
                <div class="p-8 bg-indigo-50 dark:bg-indigo-900/20 rounded-[2rem] border border-indigo-100 dark:border-indigo-800/50">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <div class="flex-grow">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Bulk Document Upload</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">The system will automatically parse your EPUB, DOCX, or PDF files.</p>
                        </div>
                        <a href="{{ route('writer.bulk-guide') }}" target="_blank" class="flex-shrink-0 flex items-center gap-1.5 px-4 py-2 bg-white dark:bg-slate-800 text-indigo-600 dark:text-indigo-400 text-xs font-black rounded-xl border border-indigo-100 dark:border-indigo-800 hover:bg-indigo-50 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            View Guide
                        </a>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Select File (EPUB, DOCX, PDF)</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-indigo-200 dark:border-indigo-800 border-dashed rounded-3xl cursor-pointer bg-white dark:bg-slate-900 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-12 h-12 mb-4 text-indigo-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 font-bold" x-text="docFile ? docFile.name : 'Drag or click to select file'"></p>
                                    <p class="text-[10px] text-slate-400 uppercase mt-2">Maximum 50MB</p>
                                </div>
                                <input type="file" accept=".epub,.docx,.pdf" class="hidden" @change="docFile = $event.target.files[0]" />
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button @click="startParsing()" :disabled="!docFile || isParsing" class="flex-grow py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <template x-if="!isParsing">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                        </template>
                        <template x-if="isParsing">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <span x-text="isParsing ? 'Analyzing Document...' : 'Process & Extract Chapters'"></span>
                    </button>
                    <a href="{{ route('novels.show', $novel->slug) }}" class="px-8 py-4 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-2xl border border-slate-200 dark:border-slate-700 text-center hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">Cancel</a>
                </div>
            </div>

            <!-- Phase 2: Progress Card -->
            <div x-show="step === 'processing'" class="space-y-6" x-transition>
                <div class="bg-slate-50 dark:bg-slate-800/50 rounded-[2.5rem] p-8 border border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-1">Uploading Chapters...</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Please do not close this page until the process is complete.</p>
                        </div>
                        <div class="text-right">
                            <span class="text-3xl font-black text-indigo-600 dark:text-indigo-400" x-text="Math.round(progress) + '%'"></span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="w-full h-4 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden mb-8">
                        <div class="h-full bg-indigo-600 transition-all duration-500" :style="`width: ${progress}%`"></div>
                    </div>

                    <!-- Current Chapter Info -->
                    <div class="flex items-center gap-4 p-5 bg-white dark:bg-slate-900 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm mb-8">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                            <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </div>
                        <div class="flex-grow">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Uploading</p>
                            <h4 class="text-base font-bold text-slate-800 dark:text-white line-clamp-1" x-text="chapters[currentIndex] ? chapters[currentIndex].title : 'Preparing...'"></h4>
                        </div>
                        <div class="text-right text-xs font-bold text-slate-400 whitespace-nowrap">
                            <span x-text="currentIndex + 1"></span> / <span x-text="chapters.length"></span>
                        </div>
                    </div>

                    <!-- Chapter List -->
                    <div class="max-h-64 overflow-y-auto pr-2 space-y-2 custom-scrollbar">
                        <template x-for="(chapter, index) in chapters" :key="index">
                            <div class="flex items-center justify-between p-3 rounded-xl transition-colors" 
                                 :class="index === currentIndex ? 'bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800/50' : 'bg-transparent border border-transparent'">
                                <div class="flex items-center gap-3 overflow-hidden">
                                    <template x-if="index < currentIndex">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    </template>
                                    <template x-if="index === currentIndex">
                                        <div class="w-5 h-5 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin flex-shrink-0"></div>
                                    </template>
                                    <template x-if="index > currentIndex">
                                        <div class="w-5 h-5 rounded-full border-2 border-slate-200 dark:border-slate-700 flex-shrink-0"></div>
                                    </template>
                                    <span class="text-sm font-medium truncate" :class="index === currentIndex ? 'text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-500 dark:text-slate-400'" x-text="chapter.title"></span>
                                </div>
                                <span class="text-[10px] font-bold uppercase tracking-wider" 
                                      :class="index < currentIndex ? 'text-emerald-500' : (index === currentIndex ? 'text-indigo-600' : 'text-slate-400')"
                                      x-text="index < currentIndex ? 'Success' : (index === currentIndex ? 'Processing' : 'Waiting')"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Phase 3: Finished -->
            <div x-show="step === 'finished'" class="text-center py-12" x-transition>
                <div class="w-24 h-24 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-8 shadow-xl shadow-emerald-200/50 dark:shadow-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                </div>
                <h2 class="text-3xl font-black text-slate-900 dark:text-white mb-3">Import Complete!</h2>
                <p class="text-slate-500 dark:text-slate-400 mb-10 max-w-md mx-auto">Congratulations! <span class="font-bold text-slate-800 dark:text-slate-200" x-text="chapters.length"></span> chapters have been successfully extracted and uploaded to your novel.</p>
                <a href="{{ route('novels.show', $novel->slug) }}" class="inline-flex items-center gap-2 px-10 py-4 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-200 dark:shadow-none">
                    Back to Novel
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" /></svg>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function bulkUploadHandler() {
        return {
            uploadMode: 'single',
            step: 'upload', // upload, processing, finished
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
                    const response = await fetch('{{ route('writer.chapters.parse-epub', $novel->id) }}', {
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
                        const response = await fetch('{{ route('writer.chapters.store-bulk', $novel->id) }}', {
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

                        if (!response.ok) throw new Error('Gagal mengupload chapter: ' + this.chapters[i].title);
                        
                    } catch (error) {
                        console.error(error);
                        // Optional: Handle individual chapter failure (e.g., skip or retry)
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
    </div>
</div>
@endsection
