@extends('layouts.writer', [
    'title' => 'Synopsis & Visuals',
    'subtitle' => 'Step 2: Tell us what your story is about and upload a cover.'
])

@section('content')
<div class="space-y-8">
    @include('writer.novels._wizard-steps', ['currentStep' => 2])

    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] p-10 border border-slate-200 dark:border-slate-800 shadow-sm">
        <form action="{{ route('writer.novels.update.step-2', $novel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-10">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Synopsis Area -->
                <div class="lg:col-span-2 space-y-4">
                    <label for="description" class="text-xs font-black uppercase tracking-widest text-slate-400">Synopsis / Description</label>
                    <textarea name="description" id="description" rows="12"
                        class="w-full px-6 py-5 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-3xl focus:ring-2 focus:ring-indigo-500 transition-all font-medium text-slate-700 dark:text-slate-200 leading-relaxed"
                        placeholder="Write a compelling summary to attract readers..." required>{{ old('description', $novel->description) }}</textarea>
                    @error('description') <p class="text-rose-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Recommended: 200-500 words for best engagement.</p>
                </div>

                <!-- Cover Area -->
                <div class="space-y-4">
                    <label class="text-xs font-black uppercase tracking-widest text-slate-400">Novel Cover</label>
                    <div class="relative group">
                        <div class="aspect-[3/4] rounded-3xl overflow-hidden bg-slate-100 dark:bg-slate-800 border-2 border-dashed border-slate-200 dark:border-slate-700 flex flex-col items-center justify-center relative shadow-inner">
                            @if($novel->cover_image_url)
                                <img src="{{ $novel->cover_image_url }}" id="cover-preview" class="w-full h-full object-cover">
                            @else
                                <div id="cover-placeholder" class="flex flex-col items-center justify-center h-full text-slate-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p class="text-xs font-black uppercase tracking-[0.2em]">Upload Artwork</p>
                                </div>
                                <img src="" id="cover-preview" class="hidden w-full h-full object-cover">
                            @endif
                            
                            <label class="absolute inset-0 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity bg-black/40 flex items-center justify-center">
                                <input type="file" name="cover_image" class="hidden" onchange="initCropper(this, 'cover-preview', {aspectRatio: 3/4, placeholderId: 'cover-placeholder'})">
                                <span class="px-6 py-3 bg-white text-slate-900 rounded-xl text-xs font-black uppercase tracking-widest shadow-lg">Choose File</span>
                            </label>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest text-center">Ratio 3:4, Max 2MB (JPG/PNG)</p>
                </div>
            </div>

            <div class="flex items-center justify-between pt-10 border-t border-slate-100 dark:border-slate-800">
                <a href="{{ route('writer.novels.create.step-1') }}" class="px-8 py-4 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Previous
                </a>
                <button type="submit" class="px-10 py-4 bg-indigo-600 text-white text-xs font-black uppercase tracking-widest rounded-2xl hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20">
                    Next Step
                </button>
            </div>
        </form>
    </div>
</div>

@include('partials.cropping-modal')
@endsection
