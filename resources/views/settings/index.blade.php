@php
    $role = auth()->user()->role;
@endphp

@extends('layouts.settings', [
    'title' => 'Account Settings',
    'settingsBackUrl' => $role === 'admin' ? route('admin.dashboard') : route('dashboard'),
])

@push('styles')
<style>
    .st-serif { font-family: 'Cormorant Garamond', Georgia, serif; }
    .st-page {
        --st-border: rgba(255, 255, 255, 0.09);
        --st-border-strong: rgba(255, 255, 255, 0.16);
        --st-gold: #c7a64a;
        --st-ink: #eeeae1;
        --st-title: #f2efe8;
        --st-muted: #a3a3a3;
        --st-subtle: #737373;
    }

    .st-kicker {
        font-size: 9px;
        letter-spacing: 0.24em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--st-gold);
    }

    .st-section {
        background: linear-gradient(180deg, #151515 0%, #101010 100%);
        border: 1px solid var(--st-border);
        border-radius: 12px;
        overflow: hidden;
    }
    .st-section-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        padding: 17px 20px;
        border-bottom: 1px solid var(--st-border);
        background: rgba(255, 255, 255, 0.012);
    }
    .st-section-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-size: 19px;
        font-weight: 600;
        line-height: 1.1;
        color: var(--st-title);
    }
    .st-section-sub {
        font-size: 11.5px;
        line-height: 1.5;
        color: var(--st-subtle);
        margin-top: 3px;
    }
    .st-section-body { padding: 20px; }

    .st-label {
        display: block;
        margin-bottom: 7px;
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--st-muted);
    }
    .st-input {
        width: 100%;
        padding: 11px 14px;
        font-size: 13.5px;
        background: #0c0c0c;
        color: var(--st-ink);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 9px;
        transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
    }
    .st-input::placeholder { color: #565656; }
    .st-input:focus {
        outline: none;
        background: #0e0e0e;
        border-color: rgba(199, 166, 74, 0.6);
        box-shadow: 0 0 0 3px rgba(199, 166, 74, 0.13);
    }
    .st-input[readonly] { background: #0b0b0b; color: var(--st-subtle); cursor: not-allowed; }
    .st-hint { margin-top: 6px; font-size: 11px; color: var(--st-subtle); }
    .st-error { margin-top: 6px; font-size: 11px; color: #e6a6a0; }

    .st-avatar {
        width: 84px;
        height: 84px;
        border-radius: 14px;
        overflow: hidden;
        background: #1a1a1a;
        border: 2px solid rgba(199, 166, 74, 0.45);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.5);
    }
    .st-upload {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 15px;
        border-radius: 9px;
        cursor: pointer;
        border: 1px solid var(--st-border-strong);
        color: var(--st-title);
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        transition: border-color .18s ease, background .18s ease;
    }
    .st-upload:hover { border-color: rgba(199, 166, 74, 0.55); background: rgba(199, 166, 74, 0.06); }

    .st-check { width: 17px; height: 17px; margin-top: 1px; flex-shrink: 0; accent-color: var(--st-gold); cursor: pointer; }

    .st-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 22px;
        border-radius: 9px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.14em;
        text-transform: uppercase;
        transition: background .18s ease, color .18s ease, border-color .18s ease;
    }
    .st-btn-primary { background: var(--st-gold); color: #17130a; }
    .st-btn-primary:hover { background: #d6b65f; }
    .st-btn-ghost { border: 1px solid var(--st-border-strong); color: var(--st-muted); }
    .st-btn-ghost:hover { color: var(--st-title); }
</style>
@endpush

@section('content')
<div class="st-page mx-auto w-full max-w-3xl pb-24" x-data="{
    profilePhotoPreview: null,
    updateProfilePhotoPreview(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            initCropper(input, 'profile-photo-img', {
                aspectRatio: 1,
                width: 400,
                height: 400,
                onSave: () => {
                    const form = document.getElementById('settings-profile-form');
                    if (form) form.submit();
                }
            });
        }
    }
}">
    {{-- ─── HEADER ─────────────────────────────────────────────────────── --}}
    <div class="mb-8 flex flex-col gap-4 border-b border-white/[.08] pb-7 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="st-kicker">Account <span class="mx-1 text-neutral-700">/</span> Settings</p>
            <p class="mt-3 max-w-xl text-[13px] leading-6 text-neutral-400">Manage your public profile, privacy, reading experience, and account security from one place.</p>
        </div>
        <span class="inline-flex w-fit items-center gap-2 rounded-full border border-white/[.12] bg-white/[.02] px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.14em] text-neutral-400">
            <span class="h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>
            Changes save to your account
        </span>
    </div>

    <form id="settings-profile-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- ─── PUBLIC PROFILE ─────────────────────────────────────────── --}}
        <section class="st-section">
            <div class="st-section-head">
                <div>
                    <h2 class="st-section-title">Public Profile</h2>
                    <p class="st-section-sub">Identity and display shown on your profile page.</p>
                </div>
            </div>
            <div class="st-section-body space-y-5">
                <div class="flex items-center gap-4">
                    <div class="st-avatar shrink-0">
                        @if($user->profile_photo_url)
                            <img id="profile-photo-img" src="{{ $user->profile_photo_url }}" class="h-full w-full object-cover" alt="" width="84" height="84">
                        @elseif($user->profile_photo)
                            <img id="profile-photo-img" src="{{ asset('storage/' . $user->profile_photo) }}" class="h-full w-full object-cover" alt="" width="84" height="84">
                        @else
                            <span id="profile-photo-placeholder" class="flex h-full w-full items-center justify-center st-serif text-3xl font-semibold uppercase text-[#c7a64a]/60">{{ substr($user->name, 0, 1) }}</span>
                            <img id="profile-photo-img" src="" class="hidden h-full w-full object-cover" alt="" width="84" height="84">
                        @endif
                    </div>
                    <div>
                        <label for="profile_photo" class="st-upload">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            Upload Photo
                            <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*" @change="updateProfilePhotoPreview">
                        </label>
                        <p class="st-hint">JPG or PNG, max 2 MB.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="st-label">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="st-input">
                        @error('name') <p class="st-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="username" class="st-label">Username</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-[13px] text-neutral-600">@</span>
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" class="st-input pl-8" placeholder="username">
                        </div>
                        @error('username') <p class="st-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="bio" class="st-label">Bio</label>
                    <textarea name="bio" id="bio" rows="3" maxlength="500" class="st-input resize-none" placeholder="Short description...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio') <p class="st-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </section>

        {{-- ─── ACCOUNT ────────────────────────────────────────────────── --}}
        <section class="st-section">
            <div class="st-section-head">
                <div>
                    <h2 class="st-section-title">Account</h2>
                    <p class="st-section-sub">Login identity tied to your account.</p>
                </div>
            </div>
            <div class="st-section-body">
                <label for="email" class="st-label">Email</label>
                <input type="email" id="email" value="{{ $user->email }}" readonly class="st-input">
                <p class="st-hint">Verified email. Contact admin to change it.</p>
            </div>
        </section>

        {{-- ─── PRIVACY ────────────────────────────────────────────────── --}}
        <section class="st-section">
            <div class="st-section-head">
                <div>
                    <h2 class="st-section-title">Privacy</h2>
                    <p class="st-section-sub">Control your data visibility.</p>
                </div>
            </div>
            <div class="st-section-body">
                <label class="flex cursor-pointer items-start gap-3">
                    <input type="hidden" name="is_public_reading_list" value="0">
                    <input type="checkbox" name="is_public_reading_list" value="1"
                        {{ old('is_public_reading_list', $user->is_public_reading_list) ? 'checked' : '' }}
                        class="st-check">
                    <span>
                        <span class="block text-[13.5px] font-medium text-[#eeeae1]">Show reading list on public profile</span>
                        <span class="mt-0.5 block text-[11.5px] leading-5 text-neutral-500">If disabled, your bookmark list is only visible to you.</span>
                    </span>
                </label>
            </div>
        </section>

        {{-- ─── READING EXPERIENCE ─────────────────────────────────────── --}}
        <section class="st-section">
            <div class="st-section-head">
                <div>
                    <h2 class="st-section-title">Reading Experience</h2>
                    <p class="st-section-sub">Choose how the next chapter appears while reading.</p>
                </div>
            </div>
            <div class="st-section-body">
                <label class="flex cursor-pointer items-start gap-3">
                    <input type="hidden" name="auto_load_chapters" value="0">
                    <input type="checkbox" name="auto_load_chapters" value="1"
                        {{ old('auto_load_chapters', $user->auto_load_chapters ?? true) ? 'checked' : '' }}
                        class="st-check">
                    <span>
                        <span class="block text-[13.5px] font-medium text-[#eeeae1]">Auto-load next chapters</span>
                        <span class="mt-0.5 block text-[11.5px] leading-5 text-neutral-500">Load the next chapter automatically when you reach the end of the current one.</span>
                    </span>
                </label>
            </div>
        </section>

        {{-- ─── PASSWORD & RECOVERY ────────────────────────────────────── --}}
        <section class="st-section">
            <div class="st-section-head">
                <div>
                    <h2 class="st-section-title">Password &amp; Recovery</h2>
                    <p class="st-section-sub">Account credential management.</p>
                </div>
                <span class="inline-flex shrink-0 items-center rounded-full border border-white/[.12] px-2.5 py-1 text-[9px] font-semibold uppercase tracking-[0.14em] text-neutral-500">Phase 2</span>
            </div>
            <div class="st-section-body text-[13px] leading-6 text-neutral-500">
                Password change flow is not implemented yet. Use admin support when a credential reset is needed.
            </div>
        </section>

        {{-- ─── ACTIONS ────────────────────────────────────────────────── --}}
        <div class="flex items-center justify-end gap-3 pt-1">
            <a href="{{ route('profile.show', $user->username ?? $user->id) }}" class="st-btn st-btn-ghost">Cancel</a>
            <button type="submit" class="st-btn st-btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                Save Changes
            </button>
        </div>
    </form>
</div>
@include('partials.cropping-modal')
@endsection
