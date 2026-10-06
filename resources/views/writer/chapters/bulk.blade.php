@extends('layouts.writer', [
    'title' => 'Bulk Chapter Manager',
    'subtitle' => $novel->title
])

@section('content')
<div class="space-y-6" x-data="bulkChapterManager()">
    <div class="bg-neutral-900 rounded-xl border border-neutral-800 overflow-hidden">
        <div class="flex items-center justify-between px-8 py-5 border-b border-neutral-800">
            <div>
                <h2 class="text-lg font-semibold text-white">Bulk Import &amp; Chapter Order</h2>
                <p class="text-sm text-neutral-400 mt-1">Upload multi-chapter documents or reorder existing chapters.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('writer.novels.chapters.create', $novel) }}" class="px-4 py-2.5 text-xs font-medium uppercase tracking-wider text-neutral-400 hover:text-white border border-neutral-700 rounded-lg transition-colors">
                    Single Write
                </a>
                <a href="{{ route('writer.novels.workspace', $novel) }}" class="px-4 py-2.5 text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-white transition-colors">
                    Workspace
                </a>
            </div>
        </div>

        <div class="px-8 py-7 border-b border-neutral-800">
            <form action="{{ route('writer.novels.chapters.bulk-store', $novel) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="p-8 bg-black/40 rounded-xl border border-neutral-800 text-center max-w-3xl mx-auto">
                    <div class="w-16 h-16 bg-[#c7a64a]/10 rounded-xl flex items-center justify-center text-[#c7a64a] mx-auto mb-5 border border-[#c7a64a]/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">Multi-Chapter Document Import</h3>
                    <p class="text-sm text-neutral-400 mb-8 leading-relaxed">Upload EPUB, DOCX, PDF, or ZIP. Chapters are auto-split by heading and appended to your library.</p>

                    <label class="relative group cursor-pointer block">
                        <div class="p-8 border border-dashed border-neutral-700 rounded-xl bg-neutral-900 group-hover:border-[#c7a64a]/40 transition-all">
                            <p class="text-sm font-medium text-white uppercase tracking-wider" x-text="docFile ? docFile.name : 'Choose file or drag &amp; drop here'"></p>
                            <p class="text-[10px] text-neutral-500 uppercase tracking-[0.2em] mt-2">EPUB · DOCX · PDF · ZIP — Max 50MB</p>
                        </div>
                        <input type="file" name="file" accept=".epub,.docx,.pdf,.zip" class="hidden" @change="docFile = $event.target.files[0]" />
                    </label>
                </div>

                <div class="flex items-center justify-center gap-3">
                    <button type="submit" :disabled="!docFile" class="px-8 py-3 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-lg hover:bg-neutral-200 transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" /></svg>
                        Import Chapters
                    </button>
                    <button type="button" @click="startParsing()" :disabled="!docFile || isParsing" class="px-8 py-3 border border-neutral-700 text-neutral-300 text-xs font-medium uppercase tracking-wider rounded-lg hover:bg-white/5 hover:text-white transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-3">
                        <template x-if="isParsing">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        </template>
                        <span x-text="isParsing ? 'Analyzing...' : 'Preview &amp; Edit First'"></span>
                    </button>
                </div>
            </form>
        </div>

        <form action="{{ route('writer.novels.chapters.reorder', $novel) }}" method="POST" class="px-8 py-7" x-data="{ chapterOrder: @js($existingChapters->pluck('id')->values()) }">
            @csrf
            <input type="hidden" name="order" x-bind:value="JSON.stringify(chapterOrder)">

            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-semibold text-white">Current Chapter Order</h3>
                    <p class="text-xs text-neutral-500 uppercase tracking-[0.2em] mt-1">
                        {{ $existingChapters->count() }} {{ Str::plural('chapter', $existingChapters->count()) }} · Drag handles or use arrows
                    </p>
                </div>
                <button type="submit" :disabled="!isDirty()" class="px-6 py-2.5 bg-[#c7a64a] text-black text-[11px] font-semibold uppercase tracking-[0.2em] rounded-md hover:bg-[#d4b356] transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                    Save New Order
                </button>
            </div>

            @if($existingChapters->isEmpty())
                <div class="py-16 text-center border border-dashed border-neutral-800 rounded-xl bg-black/30">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-neutral-800 flex items-center justify-center text-neutral-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                    </div>
                    <p class="text-sm font-medium text-neutral-400">No chapters yet for this novel.</p>
                    <p class="text-xs text-neutral-600 mt-1 uppercase tracking-[0.2em]">Import a document or write your first chapter.</p>
                </div>
            @else
                <ul class="border border-neutral-800 rounded-xl overflow-hidden divide-y divide-neutral-800 bg-black/30">
                    @foreach($existingChapters as $chap)
                        @php
                            $statusBadgeClass = match($chap->status) {
                                'published' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                'scheduled' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                default => 'bg-neutral-500/10 text-neutral-400 border-neutral-500/20',
                            };
                        @endphp
                        <li class="group flex items-center gap-3 px-4 py-3.5 hover:bg-white/[0.02] transition-colors"
                            x-bind:data-id="{{ $chap->id }}">
                            <button type="button" class="flex flex-col gap-0.5 text-neutral-600 hover:text-neutral-300 transition-colors shrink-0 p-1"
                                @click="moveItem({{ $loop->index }}, -1)"
                                :disabled="{{ $loop->first ? 'true' : 'false' }}"
                                :class="{{ $loop->first ? "'opacity-20 cursor-not-allowed'" : "''" }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" /></svg>
                            </button>
                            <button type="button" class="flex flex-col gap-0.5 text-neutral-600 hover:text-neutral-300 transition-colors shrink-0 p-1"
                                @click="moveItem({{ $loop->index }}, 1)"
                                :disabled="{{ $loop->last ? 'true' : 'false' }}"
                                :class="{{ $loop->last ? "'opacity-20 cursor-not-allowed'" : "''" }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </button>

                            <div class="cursor-grab text-neutral-600 hover:text-neutral-300 shrink-0 p-1 select-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M7 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 2zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 7 14zm6-8a2 2 0 1 0-.001-4.001A2 2 0 0 0 13 6zm0 2a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 8zm0 6a2 2 0 1 0 .001 4.001A2 2 0 0 0 13 14z" />
                                </svg>
                            </div>

                            <div class="flex items-center justify-center h-8 w-8 rounded-md border border-neutral-800 bg-neutral-900 text-[11px] font-semibold text-neutral-400 tabular-nums shrink-0">
                                {{ $chap->order > 0 ? $chap->order : str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $chap->title }}</p>
                            </div>

                            <span class="inline-flex items-center px-2.5 py-1 rounded-md border text-[10px] font-semibold uppercase tracking-[0.18em] shrink-0 {{ $statusBadgeClass }}">
                                {{ $chap->status }}
                            </span>

                            <div class="flex items-center gap-1 shrink-0">
                                <a href="{{ route('writer.novels.chapters.edit', [$novel, $chap]) }}" class="p-2 rounded-md text-neutral-500 hover:text-white hover:bg-white/5 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                </a>
                                <form method="POST" action="{{ route('writer.novels.chapters.destroy', [$novel, $chap]) }}" onsubmit="return confirm('Delete this chapter permanently?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-md text-neutral-500 hover:text-red-400 hover:bg-red-500/5 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

            <script>
                function bulkChapterManager() {
                    return {
                        docFile: null,
                        isParsing: false,
                        chapters: [],
                        initialOrder: @json($existingChapters->pluck('id')->values()),
                        isDirty() {
                            if (typeof this.chapterOrder === 'undefined') return false;
                            return JSON.stringify(this.chapterOrder) !== JSON.stringify(this.initialOrder);
                        },
                        moveItem(index, direction) {
                            const target = index + direction;
                            if (target < 0 || target >= this.chapterOrder.length) return;
                            const tmp = this.chapterOrder[index];
                            this.chapterOrder.splice(index, 1);
                            this.chapterOrder.splice(target, 0, tmp);
                        },
                        async startParsing() {
                            if (!this.docFile) return;
                            this.isParsing = true;
                            const formData = new FormData();
                            formData.append('file', this.docFile);
                            formData.append('_token', '{{ csrf_token() }}');
                            try {
                                const res = await fetch('{{ route('writer.novels.chapters.bulk-parse', $novel->id) }}', {
                                    method: 'POST',
                                    body: formData,
                                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                                });
                                const data = await res.json();
                                if (!res.ok) throw new Error(data.error || 'Failed to parse');
                                this.chapters = data.chapters;
                                alert(data.chapters.length + ' chapters detected. Use Import to save them.');
                            } catch (err) {
                                alert(err.message);
                            } finally {
                                this.isParsing = false;
                            }
                        }
                    }
                }
            </script>
        </form>
    </div>
</div>
@endsection
