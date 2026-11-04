@php
    $isEdit = isset($character);
    $inputClass = 'w-full px-4 py-3 text-sm bg-[#0a0a0a] border border-white/10 rounded-sm text-[#f2efe8] placeholder:text-[#a3a3a3] focus:outline-none focus:border-[#c7a64a]/40 transition-colors';
    $labelClass = 'block text-[10px] uppercase tracking-[0.2em] text-[#a3a3a3] mb-2';
@endphp

<form
    action="{{ $isEdit ? route('writer.novels.characters.update', [$novel, $character]) : route('writer.novels.characters.store', $novel) }}"
    method="POST"
    enctype="multipart/form-data"
    class="space-y-8"
>
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1fr)_260px] gap-8">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="name" class="{{ $labelClass }}">Character Name <span class="text-[#c7a64a]">*</span></label>
                    <input type="text" name="name" id="name" required value="{{ old('name', $character->name ?? '') }}" placeholder="e.g. Arthur Leywin" class="{{ $inputClass }}">
                    @error('name') <p class="mt-2 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tagline" class="{{ $labelClass }}">Tagline</label>
                    <input type="text" name="tagline" id="tagline" value="{{ old('tagline', $character->tagline ?? '') }}" placeholder="e.g. The returning son" class="{{ $inputClass }}">
                    @error('tagline') <p class="mt-2 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="role" class="{{ $labelClass }}">Story Role</label>
                    <input type="text" name="role" id="role" value="{{ old('role', $character->role ?? '') }}" placeholder="e.g. Protagonist" class="{{ $inputClass }}">
                    @error('role') <p class="mt-2 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="cast_type" class="{{ $labelClass }}">Cast</label>
                    @php $castType = old('cast_type', $character->cast_type ?? 'supporting'); @endphp
                    <select name="cast_type" id="cast_type" class="{{ $inputClass }}">
                        <option value="main" @selected($castType === 'main')>Main cast</option>
                        <option value="supporting" @selected($castType === 'supporting')>Supporting</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="tags" class="{{ $labelClass }}">Tags <span class="text-[#a3a3a3] normal-case tracking-normal">(pisahkan dengan koma)</span></label>
                <input type="text" name="tags" id="tags" value="{{ old('tags', !empty($character->tags) ? implode(', ', $character->tags) : '') }}" placeholder="Homecoming, Memory, Inheritance" class="{{ $inputClass }}">
                @error('tags') <p class="mt-2 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="anchor" class="{{ $labelClass }}">Anchor Setting</label>
                    <input type="text" name="anchor" id="anchor" value="{{ old('anchor', $character->anchor ?? '') }}" placeholder="e.g. The Northern House" class="{{ $inputClass }}">
                    @error('anchor') <p class="mt-2 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="current_chapter" class="{{ $labelClass }}">Current Chapter</label>
                    <input type="text" name="current_chapter" id="current_chapter" value="{{ old('current_chapter', $character->current_chapter ?? '') }}" placeholder="e.g. 12 · The Language of Frost" class="{{ $inputClass }}">
                    @error('current_chapter') <p class="mt-2 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="description" class="{{ $labelClass }}">Character Description / Bio</label>
                <textarea name="description" id="description" rows="5" placeholder="Describe your character's personality, history, and appearance..." class="{{ $inputClass }}">{{ old('description', $character->description ?? '') }}</textarea>
                @error('description') <p class="mt-2 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="wants" class="{{ $labelClass }}">What they want</label>
                    <textarea name="wants" id="wants" rows="4" placeholder="Their driving desire..." class="{{ $inputClass }}">{{ old('wants', $character->wants ?? '') }}</textarea>
                    @error('wants') <p class="mt-2 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="fears" class="{{ $labelClass }}">What holds them back</label>
                    <textarea name="fears" id="fears" rows="4" placeholder="Their fear or flaw..." class="{{ $inputClass }}">{{ old('fears', $character->fears ?? '') }}</textarea>
                    @error('fears') <p class="mt-2 text-xs text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="lg:border-l lg:border-white/10 lg:pl-8">
            <label class="{{ $labelClass }}">Character Portrait</label>
            <div class="relative group aspect-square rounded-sm overflow-hidden bg-[#0a0a0a] border border-dashed border-white/15">
                @if(isset($character) && $character->image_url)
                    <div id="character-placeholder" class="hidden"></div>
                    <img src="{{ $character->image_url }}" id="character-preview" class="w-full h-full object-cover" alt="">
                @else
                    <div id="character-placeholder" class="flex flex-col items-center justify-center h-full text-[#a3a3a3]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <p class="text-[9px] font-semibold uppercase tracking-[0.2em]">Optional</p>
                    </div>
                    <img src="" id="character-preview" class="hidden w-full h-full object-cover" alt="">
                @endif
                <label class="absolute inset-0 cursor-pointer opacity-0 group-hover:opacity-100 transition-opacity bg-black/60 flex items-center justify-center">
                    <input type="file" name="photo" id="image" class="hidden" accept="image/*" onchange="initCropper(this, 'character-preview', {aspectRatio: 1, placeholderId: 'character-placeholder'})">
                    <span class="px-4 py-2 bg-[#c7a64a] text-black rounded-sm text-[10px] font-semibold uppercase tracking-[0.15em]">Change Image</span>
                </label>
            </div>
            @error('photo') <p class="mt-2 text-xs text-red-400">{{ $message }}</p> @enderror
            <p class="mt-3 text-[11px] text-[#a3a3a3] leading-relaxed">Square image recommended, up to 2 MB.</p>
        </div>
    </div>

    <div class="flex items-center justify-between gap-3 pt-6 border-t border-white/10">
        <a href="{{ route('writer.codex', ['novel_id' => $novel->id]) }}" class="px-4 py-2.5 rounded-sm border border-white/10 text-[#a3a3a3] text-[11px] uppercase tracking-[0.2em] hover:bg-white/5 hover:text-[#f2efe8] transition-colors">Cancel</a>
        <button type="submit" class="px-6 py-2.5 bg-[#c7a64a] text-black text-[11px] uppercase tracking-[0.15em] font-semibold rounded-sm hover:bg-[#d4b65a] transition-colors">
            {{ $isEdit ? 'Update Character' : 'Create Character' }}
        </button>
    </div>
</form>
