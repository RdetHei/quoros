@extends('layouts.writer', [
    'title' => 'Novel Settings',
    'subtitle' => 'Update your story details, visuals, and categorization.'
])

@section('content')
<div class="space-y-6">
    <div class="bg-neutral-900 rounded-xl p-8 border border-neutral-800">
        <form action="{{ route('writer.novels.update', $novel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <div class="lg:col-span-4 space-y-5">
                    <div class="space-y-3">
                        <label class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Cover Artwork</label>
                        <div class="relative group aspect-[3/4] rounded-lg overflow-hidden bg-neutral-800 border border-dashed border-neutral-700">
                            @if($novel->cover_image_url)
                                <div id="cover-placeholder" class="hidden"></div>
                                <img src="{{ $novel->cover_image_url }}" id="cover-preview" class="w-full h-full object-cover" alt="">
                            @else
                                <div id="cover-placeholder" class="flex flex-col items-center justify-center h-full text-neutral-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p class="text-[10px] font-medium uppercase tracking-wider">No Cover</p>
                                </div>
                                <img src="" id="cover-preview" class="hidden w-full h-full object-cover" alt="">
                            @endif
                            <label class="absolute inset-0 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity bg-black/60 flex items-center justify-center">
                                <input type="file" name="cover_image" class="hidden" onchange="initCropper(this, 'cover-preview', {aspectRatio: 3/4, placeholderId: 'cover-placeholder'})">
                                <span class="px-4 py-2 bg-white text-black rounded-lg text-xs font-medium uppercase tracking-wider">Change Artwork</span>
                            </label>
                        </div>
                    </div>

                    @foreach([
                        ['name' => 'type', 'label' => 'Content Type', 'options' => ['original' => 'Original Story', 'web_novel' => 'Web Novel', 'light_novel' => 'Light Novel']],
                        ['name' => 'content_rating', 'label' => 'Content Rating', 'options' => ['everyone' => 'Everyone', 'teen' => 'Teen (13+)', 'mature' => 'Mature (18+)']],
                        ['name' => 'status', 'label' => 'Release Status', 'options' => ['ongoing' => 'Ongoing', 'complete' => 'Completed', 'hiatus' => 'On Hiatus']],
                        ['name' => 'language', 'label' => 'Language', 'options' => ['id' => 'Indonesian', 'en' => 'English']],
                        ['name' => 'region', 'label' => 'Region', 'options' => ['lokal' => 'Lokal', 'global' => 'Global']],
                    ] as $select)
                        <div class="space-y-2">
                            <label class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">{{ $select['label'] }}</label>
                            <select name="{{ $select['name'] }}" class="w-full px-4 py-3 bg-black border border-neutral-700 rounded-lg focus:ring-1 focus:ring-white focus:border-white transition-all text-white">
                                @foreach($select['options'] as $value => $label)
                                    <option value="{{ $value }}" {{ $novel->{$select['name']} === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>

                <div class="lg:col-span-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="title" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Novel Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $novel->title) }}"
                                class="w-full px-4 py-3 bg-black border border-neutral-700 rounded-lg focus:ring-1 focus:ring-white focus:border-white transition-all text-white" required>
                        </div>
                        <div class="space-y-2">
                            <label for="alternative_title" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Alternative Title</label>
                            <input type="text" name="alternative_title" id="alternative_title" value="{{ old('alternative_title', $novel->alternative_title) }}"
                                class="w-full px-4 py-3 bg-black border border-neutral-700 rounded-lg focus:ring-1 focus:ring-white focus:border-white transition-all text-white">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Synopsis</label>
                        <textarea name="description" id="description" rows="10"
                            class="w-full px-5 py-4 bg-black border border-neutral-700 rounded-lg focus:ring-1 focus:ring-white focus:border-white transition-all text-neutral-200 leading-relaxed" required>{{ old('description', $novel->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-2">
                        <div class="space-y-3">
                            <label class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Genres</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($genres as $genre)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="genres[]" value="{{ $genre->id }}" class="peer hidden"
                                            {{ in_array($genre->id, $novel->genres->pluck('id')->toArray()) ? 'checked' : '' }}>
                                        <div class="px-3 py-2 bg-black border border-neutral-700 rounded-lg text-[10px] font-medium uppercase tracking-wider text-neutral-500 peer-checked:bg-white peer-checked:text-black peer-checked:border-white transition-all text-center">
                                            {{ $genre->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-medium uppercase tracking-wider text-neutral-500">Tags</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="peer hidden"
                                            {{ in_array($tag->id, $novel->tags->pluck('id')->toArray()) ? 'checked' : '' }}>
                                        <div class="px-2.5 py-1.5 bg-black border border-neutral-700 rounded-md text-[9px] font-medium uppercase tracking-wider text-neutral-500 peer-checked:bg-white peer-checked:text-black peer-checked:border-white transition-all">
                                            #{{ $tag->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-neutral-800">
                <a href="{{ route('dashboard', ['tab' => 'library']) }}" class="px-6 py-3 text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-white transition-colors">Cancel</a>
                <button type="submit" class="px-8 py-3 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-lg hover:bg-neutral-200 transition-all">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@include('partials.cropping-modal')
@endsection
