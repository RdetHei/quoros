@extends('layouts.writer', [
    'active' => 'novels',
    'breadcrumbs' => ['Author Studio', 'My Novels', $novel->title, 'Novel Settings'],
    'title' => 'Novel Settings',
    'subtitle' => 'Update your story details, visuals, and categorization.',
    'currentNovel' => $novel->title,
    'currentNovelId' => $novel->id,
    'showCreateBtn' => false,
])

@section('content')
@php
    $inputClass = 'w-full rounded-md border border-white/10 bg-[#0a0a0a] px-4 py-3 text-[13px] text-[#f2efe8] placeholder:text-[#5f5f5f] transition-all focus:border-[#c7a64a]/50 focus:outline-none focus:ring-1 focus:ring-[#c7a64a]/40';
    $labelClass = 'text-[10px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]';
    $selectClass = $inputClass . ' appearance-none pr-10';
    $selectedGenres = old('genres', $novel->genres->pluck('id')->toArray());
    $selectedTags = old('tags', $novel->tags->pluck('id')->toArray());
@endphp

<div class="space-y-6">
    <div class="rounded-md border border-white/5 bg-[#121212] p-6 sm:p-8">
        <form action="{{ route('writer.novels.update', $novel->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                <div class="space-y-5 lg:col-span-4">
                    <div class="space-y-3">
                        <label class="text-[10px] font-semibold uppercase tracking-[0.2em] text-[#a3a3a3]">Cover Artwork</label>
                        <div class="group relative aspect-[3/4] overflow-hidden rounded-md border border-dashed border-white/10 bg-[#0a0a0a]">
                            @if($novel->cover_image_url)
                                <div id="cover-placeholder" class="hidden"></div>
                                <img src="{{ $novel->cover_image_url }}" id="cover-preview" class="h-full w-full object-cover" alt="">
                            @else
                                <div id="cover-placeholder" class="flex h-full flex-col items-center justify-center text-[#5f5f5f]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mb-3 h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em]">No Cover</p>
                                </div>
                                <img id="cover-preview" class="hidden h-full w-full object-cover" alt="">
                            @endif
                            <label class="absolute inset-0 flex cursor-pointer items-center justify-center bg-black/60 opacity-0 transition-opacity group-hover:opacity-100">
                                <input type="file" name="cover_image" accept="image/*" class="hidden" onchange="initCropper(this, 'cover-preview', {aspectRatio: 3/4, placeholderId: 'cover-placeholder'})">
                                <span class="rounded-md bg-[#c7a64a] px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.12em] text-black">Change Artwork</span>
                            </label>
                        </div>
                        @error('cover_image') <p class="mt-1.5 text-[11px] text-red-400/90">{{ $message }}</p> @enderror
                    </div>

                    @foreach([
                        ['name' => 'type', 'label' => 'Content Type', 'options' => ['original' => 'Original Story', 'web_novel' => 'Web Novel', 'light_novel' => 'Light Novel']],
                        ['name' => 'content_rating', 'label' => 'Content Rating', 'options' => ['everyone' => 'Everyone', 'teen' => 'Teen (13+)', 'mature' => 'Mature (18+)']],
                        ['name' => 'status', 'label' => 'Release Status', 'options' => ['ongoing' => 'Ongoing', 'complete' => 'Completed', 'hiatus' => 'On Hiatus']],
                        ['name' => 'language', 'label' => 'Language', 'options' => ['id' => 'Indonesian', 'en' => 'English']],
                        ['name' => 'region', 'label' => 'Region', 'options' => ['lokal' => 'Lokal', 'global' => 'Global']],
                    ] as $select)
                        <div class="space-y-2">
                            <label for="{{ $select['name'] }}" class="{{ $labelClass }}">{{ $select['label'] }}</label>
                            <div class="relative">
                                <select name="{{ $select['name'] }}" id="{{ $select['name'] }}" class="{{ $selectClass }}">
                                    @foreach($select['options'] as $value => $label)
                                        <option value="{{ $value }}" {{ old($select['name'], $novel->{$select['name']}) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-4 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                            @error($select['name']) <p class="mt-1.5 text-[11px] text-red-400/90">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>

                <div class="space-y-6 lg:col-span-8">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="title" class="{{ $labelClass }}">Novel Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $novel->title) }}" class="{{ $inputClass }}" required>
                            @error('title') <p class="mt-1.5 text-[11px] text-red-400/90">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="alternative_title" class="{{ $labelClass }}">Alternative Title</label>
                            <input type="text" name="alternative_title" id="alternative_title" value="{{ old('alternative_title', $novel->alternative_title) }}" class="{{ $inputClass }}">
                            @error('alternative_title') <p class="mt-1.5 text-[11px] text-red-400/90">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="{{ $labelClass }}">Synopsis</label>
                        <textarea name="description" id="description" rows="10" class="{{ $inputClass }} leading-relaxed" required>{{ old('description', $novel->description) }}</textarea>
                        @error('description') <p class="mt-1.5 text-[11px] text-red-400/90">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-8 pt-2 md:grid-cols-2">
                        <div class="space-y-3">
                            <label class="{{ $labelClass }}">Genres</label>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($genres as $genre)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="genres[]" value="{{ $genre->id }}" class="peer hidden" @checked(in_array($genre->id, $selectedGenres))>
                                        <div class="rounded-md border border-white/10 bg-[#0a0a0a] px-3 py-2.5 text-center text-[11.5px] font-medium text-[#a3a3a3] transition-all peer-checked:border-[#c7a64a]/50 peer-checked:bg-[#c7a64a]/12 peer-checked:text-[#c7a64a]">
                                            {{ $genre->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('genres') <p class="mt-1.5 text-[11px] text-red-400/90">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="{{ $labelClass }}">Tags</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="peer hidden" @checked(in_array($tag->id, $selectedTags))>
                                        <div class="rounded-md border border-white/10 bg-[#0a0a0a] px-3 py-1.5 text-[10px] font-medium uppercase tracking-[0.12em] text-[#a3a3a3] transition-all peer-checked:border-[#c7a64a]/50 peer-checked:bg-[#c7a64a]/12 peer-checked:text-[#c7a64a]">
                                            #{{ $tag->name }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            @error('tags') <p class="mt-1.5 text-[11px] text-red-400/90">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t border-white/5 pt-6">
                <a href="{{ route('writer.novels') }}" class="rounded-md border border-white/10 px-5 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#a3a3a3] transition-all hover:border-white/20 hover:text-[#f2efe8]">Cancel</a>
                <button type="submit" class="rounded-md bg-[#c7a64a] px-6 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-black transition-all hover:bg-[#d4b55a]">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@include('partials.cropping-modal')
@endsection
