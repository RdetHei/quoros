@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto" x-data="{
    profilePhotoPreview: null,
    updateProfilePhotoPreview(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            initCropper(input, 'profile-photo-img', {
                aspectRatio: 1,
                width: 400,
                height: 400
            });
        }
    }
}">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-1.5 h-10 bg-emerald-600 rounded-full shrink-0"></div>
        <div>
            <h1 class="text-2xl font-bold text-white">Settings</h1>
            <p class="text-sm text-slate-500 mt-0.5">Manage account, privacy, and technical preferences.</p>
        </div>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Account --}}
        <section class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-800">
                <h2 class="text-sm font-semibold text-white">Account</h2>
                <p class="text-xs text-slate-500 mt-0.5">Login information and basic identity.</p>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 mb-1.5">Email</label>
                    <input type="email" id="email" value="{{ $user->email }}" readonly
                        class="w-full px-4 py-2.5 bg-slate-800/40 border border-slate-700 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                    <p class="text-xs text-slate-400 mt-1">Verified email. Contact admin for changes.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-400 mb-1.5">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full px-4 py-2.5 bg-slate-800/50 border border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-white text-sm transition-colors">
                        @error('name') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="username" class="block text-xs font-semibold text-slate-400 mb-1.5">Username</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm">@</span>
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                                class="w-full pl-8 pr-4 py-2.5 bg-slate-800/50 border border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-white text-sm transition-colors"
                                placeholder="username">
                        </div>
                        @error('username') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- Public Profile --}}
        <section class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-800">
                <h2 class="text-sm font-semibold text-white">Public Profile</h2>
                <p class="text-xs text-slate-500 mt-0.5">The display visible on your profile page.</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="flex items-center gap-5">
                    <div class="relative shrink-0">
                        <div class="w-20 h-20 rounded-xl overflow-hidden bg-slate-800 ring-1 ring-slate-700 flex items-center justify-center">
                            @if($user->profile_photo_url)
                                <img id="profile-photo-img" src="{{ $user->profile_photo_url }}" class="w-full h-full object-cover" alt="">
                            @elseif($user->profile_photo)
                                <img id="profile-photo-img" src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover" alt="">
                            @else
                                <span id="profile-photo-placeholder" class="text-2xl font-bold text-slate-400">{{ substr($user->name, 0, 1) }}</span>
                                <img id="profile-photo-img" src="" class="hidden w-full h-full object-cover" alt="">
                            @endif
                        </div>
                    </div>
                    <div>
                        <label for="profile_photo" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-200 bg-slate-800 rounded-lg hover:bg-slate-700 cursor-pointer transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Upload Photo
                            <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" @change="updateProfilePhotoPreview">
                        </label>
                        <p class="text-xs text-slate-400 mt-2">JPG/PNG, max 2 MB.</p>
                    </div>
                </div>
                <div>
                    <label for="bio" class="block text-xs font-semibold text-slate-400 mb-1.5">Bio</label>
                    <textarea name="bio" id="bio" rows="3" maxlength="500"
                        class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-white text-sm transition-colors resize-none"
                        placeholder="Short description...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio') <p class="text-rose-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        {{-- Privacy --}}
        <section class="bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-800">
                <h2 class="text-sm font-semibold text-white">Privacy</h2>
                <p class="text-xs text-slate-500 mt-0.5">Control your data visibility.</p>
            </div>
            <div class="p-6">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="hidden" name="is_public_reading_list" value="0">
                    <input type="checkbox" name="is_public_reading_list" value="1"
                        {{ old('is_public_reading_list', $user->is_public_reading_list) ? 'checked' : '' }}
                        class="mt-0.5 h-4 w-4 rounded border-slate-600 text-emerald-500 focus:ring-emerald-500 bg-slate-800">
                    <div>
                        <span class="text-sm font-medium text-white">Show reading list on public profile</span>
                        <p class="text-xs text-slate-500 mt-0.5">If disabled, your bookmark list is only visible to you.</p>
                    </div>
                </label>
            </div>
        </section>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('profile.show', $user->username ?? $user->id) }}" class="px-5 py-2.5 text-sm font-semibold text-slate-400 hover:text-slate-300 transition-colors">Cancel</a>
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
