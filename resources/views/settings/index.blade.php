@php
    $role = auth()->user()->role;
    $isUser = $role === 'user';
    $isWriter = $role === 'writer';

    $card = $isWriter
        ? 'bg-neutral-900 rounded-xl border border-neutral-800 overflow-hidden'
        : 'bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden';
    $cardHeader = $isWriter ? 'border-neutral-800' : 'border-slate-100 dark:border-slate-800';
    $titleClass = $isWriter ? 'text-sm font-medium text-white' : 'text-sm font-semibold text-slate-900 dark:text-white';
    $subtitleClass = $isWriter ? 'text-xs text-neutral-500' : 'text-xs text-slate-500 dark:text-slate-400';
    $labelClass = $isWriter ? 'block text-[11px] font-medium text-neutral-500 uppercase tracking-wider mb-1.5' : 'block text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1.5';
    $inputClass = $isWriter
        ? 'w-full px-4 py-2.5 bg-black border border-neutral-700 rounded-lg text-sm text-white focus:ring-1 focus:ring-white focus:border-white transition-all'
        : 'w-full px-4 py-2.5 bg-white dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 rounded-xl focus:ring-2 focus:ring-white/30 focus:border-white dark:text-white text-sm transition-colors';
    $readonlyInputClass = $isWriter
        ? 'w-full px-4 py-2.5 bg-neutral-800/50 border border-neutral-700 rounded-lg text-sm text-neutral-500 cursor-not-allowed'
        : 'w-full px-4 py-2.5 bg-slate-100 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-500 cursor-not-allowed';
    $infoBox = $isWriter
        ? 'mb-6 rounded-xl border border-neutral-800 bg-neutral-900 p-4'
        : 'mb-6 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-4';
    $infoText = $isWriter ? 'text-sm text-neutral-500' : 'text-sm text-slate-500 dark:text-slate-400';
    $errorClass = $isWriter ? 'text-neutral-400 text-xs mt-1' : 'text-rose-500 text-xs mt-1';
    $uploadBtn = $isWriter
        ? 'inline-flex items-center gap-2 px-4 py-2 text-xs font-medium uppercase tracking-wider text-black bg-white rounded-lg hover:bg-neutral-200 cursor-pointer transition-colors'
        : 'inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-200 bg-slate-100 dark:bg-slate-800 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 cursor-pointer transition-colors';
    $cancelBtn = $isWriter
        ? 'px-5 py-2.5 text-xs font-medium uppercase tracking-wider text-neutral-500 hover:text-white transition-colors'
        : 'px-5 py-2.5 text-sm font-semibold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-colors';
    $submitBtn = $isWriter
        ? 'px-6 py-2.5 bg-white text-black text-xs font-medium uppercase tracking-wider rounded-lg hover:bg-neutral-200 transition-all'
        : 'px-6 py-2.5 bg-white text-black text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors';
    $checkboxClass = $isWriter
        ? 'mt-0.5 h-4 w-4 rounded border-neutral-600 bg-black text-white focus:ring-white focus:ring-offset-neutral-900'
        : 'mt-0.5 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500';
@endphp

@extends('layouts.settings', [
    'title' => 'Account Settings',
    'settingsBackUrl' => $role === 'admin' ? route('admin.dashboard') : route('dashboard'),
])

@section('content')
<div class="max-w-5xl mx-auto pb-20" x-data="{
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
    <div class="mb-10 flex flex-col gap-3 border-b border-neutral-800 pb-8 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="mb-2 text-[10px] font-black uppercase tracking-[0.25em] text-neutral-500">{{ ucfirst($role) }} account</p>
            <h1 class="text-3xl font-black tracking-tight text-white sm:text-4xl">Settings</h1>
            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-neutral-400">Manage your profile, reading experience, privacy, and account security from one place.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full border border-neutral-700 bg-neutral-900 px-3 py-1.5 text-xs font-semibold text-neutral-300">
            <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
            Changes save to your account
        </span>
    </div>

    <div class="{{ $infoBox }}">
        <p class="{{ $infoText }}">
            Public Profile controls how people see you. Account Security keeps login identity and privacy settings safe.
        </p>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <section class="{{ $card }}">
            <div class="px-6 py-4 border-b {{ $cardHeader }}">
                <h2 class="{{ $titleClass }}">Account Security</h2>
                <p class="{{ $subtitleClass }} mt-0.5">Login identity, username, and privacy visibility.</p>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label for="email" class="{{ $labelClass }}">Email</label>
                    <input type="email" id="email" value="{{ $user->email }}" readonly class="{{ $readonlyInputClass }}">
                    <p class="text-xs {{ $isWriter ? 'text-neutral-600' : 'text-slate-400' }} mt-1">Verified email. Contact admin for changes.</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="{{ $labelClass }}">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="{{ $inputClass }}">
                        @error('name') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="username" class="{{ $labelClass }}">Username</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 {{ $isWriter ? 'text-neutral-600' : 'text-slate-400' }} text-sm">@</span>
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                                class="{{ $inputClass }} pl-8" placeholder="username">
                        </div>
                        @error('username') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </section>

        <section class="{{ $card }}">
            <div class="px-6 py-4 border-b {{ $cardHeader }}">
                <h2 class="{{ $titleClass }}">Public Profile</h2>
                <p class="{{ $subtitleClass }} mt-0.5">The display visible on your profile page.</p>
            </div>
            <div class="p-6 space-y-5">
                <div class="flex items-center gap-5">
                    <div class="relative shrink-0">
                        <div @class([
                            'w-20 h-20 rounded-xl overflow-hidden flex items-center justify-center',
                            $isWriter ? 'bg-neutral-800 border border-neutral-700' : 'bg-slate-100 dark:bg-slate-800 ring-1 ring-slate-200 dark:ring-slate-700',
                        ])>
                            @if($user->profile_photo_url)
                                <img id="profile-photo-img" src="{{ $user->profile_photo_url }}" class="w-full h-full object-cover" alt="">
                            @elseif($user->profile_photo)
                                <img id="profile-photo-img" src="{{ asset('storage/' . $user->profile_photo) }}" class="w-full h-full object-cover" alt="">
                            @else
                                <span id="profile-photo-placeholder" class="text-2xl font-bold {{ $isWriter ? 'text-neutral-500' : 'text-slate-400' }}">{{ substr($user->name, 0, 1) }}</span>
                                <img id="profile-photo-img" src="" class="hidden w-full h-full object-cover" alt="">
                            @endif
                        </div>
                    </div>
                    <div>
                        <label for="profile_photo" class="{{ $uploadBtn }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Upload Photo
                            <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" @change="updateProfilePhotoPreview">
                        </label>
                        <p class="text-xs {{ $isWriter ? 'text-neutral-600' : 'text-slate-400' }} mt-2">JPG/PNG, max 2 MB.</p>
                    </div>
                </div>
                <div>
                    <label for="bio" class="{{ $labelClass }}">Bio</label>
                    <textarea name="bio" id="bio" rows="3" maxlength="500" class="{{ $inputClass }} resize-none" placeholder="Short description...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio') <p class="{{ $errorClass }}">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        <section class="{{ $card }}">
            <div class="px-6 py-4 border-b {{ $cardHeader }}">
                <h2 class="{{ $titleClass }}">Privacy</h2>
                <p class="{{ $subtitleClass }} mt-0.5">Control your data visibility.</p>
            </div>
            <div class="p-6">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="hidden" name="is_public_reading_list" value="0">
                    <input type="checkbox" name="is_public_reading_list" value="1"
                        {{ old('is_public_reading_list', $user->is_public_reading_list) ? 'checked' : '' }}
                        class="{{ $checkboxClass }}">
                    <div>
                        <span class="text-sm font-medium {{ $isWriter ? 'text-white' : 'text-slate-900 dark:text-white' }}">Show reading list on public profile</span>
                        <p class="{{ $subtitleClass }} mt-0.5">If disabled, your bookmark list is only visible to you.</p>
                    </div>
                </label>
            </div>
        </section>

        <section class="{{ $card }}">
            <div class="px-6 py-4 border-b {{ $cardHeader }}">
                <h2 class="{{ $titleClass }}">Reading Experience</h2>
                <p class="{{ $subtitleClass }} mt-0.5">Choose how the next chapter appears while reading.</p>
            </div>
            <div class="p-6">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="hidden" name="auto_load_chapters" value="0">
                    <input type="checkbox" name="auto_load_chapters" value="1"
                        {{ old('auto_load_chapters', $user->auto_load_chapters ?? true) ? 'checked' : '' }}
                        class="{{ $checkboxClass }}">
                    <div>
                        <span class="text-sm font-medium {{ $isWriter ? 'text-white' : 'text-slate-900 dark:text-white' }}">Auto-load next chapters</span>
                        <p class="{{ $subtitleClass }} mt-0.5">Load the next chapter automatically when you reach the end of the current one.</p>
                    </div>
                </label>
            </div>
        </section>

        <section class="{{ $card }}">
            <div class="px-6 py-4 border-b {{ $cardHeader }}">
                <h2 class="{{ $titleClass }}">Password & Recovery</h2>
                <p class="{{ $subtitleClass }} mt-0.5">Security upgrade placeholder for Phase 2.</p>
            </div>
            <div class="p-6 text-sm {{ $isWriter ? 'text-neutral-500' : 'text-slate-500 dark:text-slate-400' }}">
                Password change flow is not implemented yet in this phase. Use admin support when credential reset is needed.
            </div>
        </section>

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('profile.show', $user->username ?? $user->id) }}" class="{{ $cancelBtn }}">Cancel</a>
            <button type="submit" class="{{ $submitBtn }}">Save Changes</button>
        </div>
    </form>
</div>
@endsection
