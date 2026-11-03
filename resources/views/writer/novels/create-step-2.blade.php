@extends('layouts.writer', [
    'active' => 'novels',
    'breadcrumbs' => ['Author Studio', 'My Novels', $novel->title, 'Synopsis & Cover'],
    'title' => 'Synopsis & Visuals',
    'subtitle' => 'Step 2: Tell us what your story is about and upload a cover.',
    'currentNovel' => $novel->title,
    'currentNovelId' => $novel->id,
    'showCreateBtn' => false,
])

@section('content')
<div class="space-y-6">
    @include('writer.novels._wizard-steps', ['currentStep' => 2, 'novel' => $novel])

    <div class="rounded-md border border-white/5 bg-[#121212] p-6 sm:p-8">
        <form action="{{ route('writer.novels.update.step-2', $novel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                <div class="space-y-3 lg:col-span-2">
                    <label for="description" class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">Synopsis / Description</label>
                    <textarea name="description" id="description" rows="12"
                        class="w-full rounded-md border border-white/10 bg-[#0a0a0a] px-5 py-4 text-[13px] leading-relaxed text-[#f2efe8] placeholder:text-[#5f5f5f] transition-all focus:border-[#c7a64a]/50 focus:outline-none focus:ring-1 focus:ring-[#c7a64a]/40"
                        placeholder="Write a compelling summary to attract readers...">{{ old('description', $novel->description) }}</textarea>
                    @error('description') <p class="mt-1.5 text-[11px] text-red-400/90">{{ $message }}</p> @enderror
                    <p class="text-[10px] uppercase tracking-[0.18em] text-[#5f5f5f]">Recommended: 200–500 words for best engagement.</p>
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">Novel Cover</label>
                    <div class="group relative">
                        <div class="relative flex aspect-[3/4] flex-col items-center justify-center overflow-hidden rounded-md border border-dashed border-white/10 bg-[#0a0a0a]">
                            @if($novel->cover_image_url)
                                <img src="{{ $novel->cover_image_url }}" id="cover-preview" class="h-full w-full object-cover" alt="">
                            @else
                                <div id="cover-placeholder" class="flex h-full flex-col items-center justify-center text-[#5f5f5f]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mb-3 h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em]">Upload Artwork</p>
                                </div>
                                <img id="cover-preview" class="hidden h-full w-full object-cover" alt="">
                            @endif
                            <label class="absolute inset-0 flex cursor-pointer items-center justify-center bg-black/60 opacity-0 transition-opacity group-hover:opacity-100">
                                <input type="file" name="cover_image" accept="image/*" class="hidden" onchange="initCropper(this, 'cover-preview', {aspectRatio: 3/4, placeholderId: 'cover-placeholder'})">
                                <span class="rounded-md bg-[#c7a64a] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-black">Choose File</span>
                            </label>
                        </div>
                    </div>
                    @error('cover_image') <p class="mt-1.5 text-[11px] text-red-400/90">{{ $message }}</p> @enderror
                    <p class="text-center text-[10px] uppercase tracking-[0.18em] text-[#5f5f5f]">Ratio 3:4, Max 2MB (JPG/PNG)</p>
                </div>
            </div>

            <div class="flex items-center justify-between border-t border-white/5 pt-6">
                <a href="{{ route('writer.novels.create.step-1', ['novel' => $novel->id]) }}" class="flex items-center gap-2 rounded-md border border-white/10 px-5 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#a3a3a3] transition-all hover:border-white/20 hover:text-[#f2efe8]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Previous
                </a>
                <button type="submit" class="rounded-md bg-[#c7a64a] px-6 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a]">Next Step</button>
            </div>
        </form>
    </div>
</div>

@include('partials.cropping-modal')
@endsection
