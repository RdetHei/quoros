@extends('layouts.writer', [
    'title' => 'Synopsis & Visuals',
    'subtitle' => 'Step 2: Tell us what your story is about and upload a cover.'
])

@section('content')
<div class="space-y-6">
    @include('writer.novels._wizard-steps', ['currentStep' => 2])

    <div class="bg-neutral-900 rounded-xl p-8 border border-neutral-800">
        <form action="{{ route('writer.novels.update.step-2', $novel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-3">
                    <label for="description" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Synopsis / Description</label>
                    <textarea name="description" id="description" rows="12"
                        class="w-full px-5 py-4 bg-black border border-neutral-700 rounded-lg focus:ring-1 focus:ring-white focus:border-white transition-all text-neutral-200 leading-relaxed placeholder:text-neutral-600"
                        placeholder="Write a compelling summary to attract readers..." required>{{ old('description', $novel->description) }}</textarea>
                    @error('description') <p class="text-neutral-400 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-[10px] text-neutral-600 uppercase tracking-wider">Recommended: 200–500 words for best engagement.</p>
                </div>

                <div class="space-y-3">
                    <label class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Novel Cover</label>
                    <div class="relative group">
                        <div class="aspect-[3/4] rounded-lg overflow-hidden bg-neutral-800 border border-dashed border-neutral-700 flex flex-col items-center justify-center relative">
                            @if($novel->cover_image_url)
                                <img src="{{ $novel->cover_image_url }}" id="cover-preview" class="w-full h-full object-cover" alt="">
                            @else
                                <div id="cover-placeholder" class="flex flex-col items-center justify-center h-full text-neutral-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p class="text-[10px] font-medium uppercase tracking-wider">Upload Artwork</p>
                                </div>
                                <img src="" id="cover-preview" class="hidden w-full h-full object-cover" alt="">
                            @endif
                            <label class="absolute inset-0 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity bg-black/60 flex items-center justify-center">
                                <input type="file" name="cover_image" class="hidden" onchange="initCropper(this, 'cover-preview', {aspectRatio: 3/4, placeholderId: 'cover-placeholder'})">
                                <span class="px-4 py-2 bg-white text-black rounded-lg text-xs font-medium uppercase tracking-wider">Choose File</span>
                            </label>
                        </div>
                    </div>
                    <p class="text-[10px] text-neutral-600 uppercase tracking-wider text-center">Ratio 3:4, Max 2MB (JPG/PNG)</p>
                </div>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-neutral-800">
                <a href="{{ route('writer.novels.create.step-1') }}" class="px-6 py-3 text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-white transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Previous
                </a>
                <button type="submit" class="px-8 py-3 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-lg hover:bg-neutral-200 transition-all">Next Step</button>
            </div>
        </form>
    </div>
</div>

@include('partials.cropping-modal')
@endsection
