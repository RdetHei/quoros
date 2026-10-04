@extends('layouts.app', ['title' => 'Create Reading List · Quoros'])

@push('styles')
<style>
    .ul-page {
        --ul-bg: #0a0a0a;
        --ul-card: #131313;
        --ul-border: rgba(255, 255, 255, 0.10);
        --ul-gold: #c7a64a;
        --ul-gold-soft: #e8d39a;
        --ul-title: #f2efe8;
        --ul-ink: #eeeae1;
        --ul-muted: #a3a3a3;
        --ul-subtle: #737373;
        background: var(--ul-bg);
    }
    body:has(.ul-page) { background-color: var(--ul-bg) !important; }

    .ul-kicker { font-size: 9px; letter-spacing: 0.22em; font-weight: 650; text-transform: uppercase; color: var(--ul-gold); }
    .ul-kicker .sep { color: var(--ul-subtle); margin: 0 8px; }
    .ul-divider-grad { height: 1px; background: linear-gradient(to right, var(--ul-gold), transparent); }

    .ul-hero-title {
        font-family: 'Cormorant Garamond', Georgia, serif;
        font-weight: 600;
        font-size: 44px;
        line-height: 1;
        letter-spacing: 0.015em;
        text-transform: uppercase;
        color: var(--ul-title);
    }
    @media (max-width: 639px) { .ul-hero-title { font-size: 30px; } }

    .ul-hero-sub { font-size: 12px; line-height: 1.7; color: var(--ul-muted); max-width: 560px; }

    .ul-form-panel {
        background: var(--ul-card);
        border: 1px solid rgba(199,166,74,0.18);
        border-radius: 6px;
        padding: 28px 30px;
    }
    @media (max-width: 639px) { .ul-form-panel { padding: 22px 20px; } }

    .ul-field-label {
        display: block;
        margin-bottom: 8px;
        font-size: 8px;
        letter-spacing: 0.2em;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--ul-gold);
    }
    .ul-field-label .req { color: var(--ul-gold-soft); margin-left: 3px; }

    .ul-input {
        appearance: none;
        -webkit-appearance: none;
        width: 100%;
        padding: 11px 14px;
        background: #0f0f0f;
        color: var(--ul-ink);
        border: 1px solid var(--ul-border);
        border-radius: 4px;
        font-size: 13px;
        line-height: 1.5;
        font-weight: 500;
        outline: none;
        transition: border-color .15s ease, box-shadow .15s ease;
    }
    .ul-input::placeholder { color: #5a5a5a; font-weight: 500; }
    .ul-input:hover { border-color: rgba(199,166,74,0.35); }
    .ul-input:focus { border-color: rgba(199,166,74,0.7); box-shadow: 0 0 0 3px rgba(199,166,74,0.08); }

    textarea.ul-input { resize: vertical; min-height: 96px; }

    .ul-input-error { border-color: rgba(220, 38, 38, 0.55) !important; }
    .ul-error-text { margin-top: 6px; font-size: 9.5px; color: #fca5a5; letter-spacing: .01em; }

    /* Custom checkbox */
    .ul-check {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        padding: 10px 12px;
        border: 1px solid var(--ul-border);
        background: #101010;
        border-radius: 4px;
        user-select: none;
        transition: border-color .15s ease, background-color .15s ease;
    }
    .ul-check:hover { border-color: rgba(199,166,74,0.35); background: #131313; }
    .ul-check input[type="checkbox"] { position: absolute; opacity: 0; pointer-events: none; }
    .ul-check-box {
        width: 15px; height: 15px; flex-shrink: 0;
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 2px;
        background: #0a0a0a;
        display: inline-flex; align-items: center; justify-content: center;
        transition: border-color .15s ease, background-color .15s ease;
    }
    .ul-check input[type="checkbox"]:checked + .ul-check-box {
        background: var(--ul-gold);
        border-color: var(--ul-gold);
    }
    .ul-check input[type="checkbox"]:checked + .ul-check-box svg { opacity: 1; }
    .ul-check-box svg { width: 9px; height: 9px; opacity: 0; color: #0f0f0f; transition: opacity .12s ease; }
    .ul-check-label { font-size: 11px; color: var(--ul-muted); font-weight: 500; letter-spacing: .01em; }
    .ul-check-label strong { color: var(--ul-ink); font-weight: 600; }

    .ul-btn-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 9px 15px;
        font-size: 9px;
        letter-spacing: 0.16em;
        font-weight: 650;
        text-transform: uppercase;
        border-radius: 2px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: var(--ul-muted);
        background: transparent;
        transition: border-color .15s ease, color .15s ease, background-color .15s ease;
    }
    .ul-btn-outline:hover {
        border-color: rgba(199, 166, 74, 0.55);
        color: var(--ul-gold-soft);
        background: rgba(199, 166, 74, 0.06);
    }

    .ul-btn-solid {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 10px 18px;
        font-size: 9.5px;
        letter-spacing: 0.18em;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 2px;
        background: var(--ul-gold);
        color: #101010;
        transition: filter .15s ease, transform .12s ease;
        white-space: nowrap;
        border: 1px solid transparent;
        cursor: pointer;
    }
    .ul-btn-solid:hover { filter: brightness(1.08); }
    .ul-btn-solid:active { transform: translateY(1px); }

    .ul-charcount {
        display: block;
        text-align: right;
        margin-top: 5px;
        font-size: 7.5px;
        letter-spacing: .12em;
        font-weight: 600;
        text-transform: uppercase;
        color: var(--ul-subtle);
    }
</style>
@endpush

@section('content')
<main class="ul-page min-h-screen w-full overflow-x-hidden pt-24 sm:pt-28 pb-16">
    <div class="mx-auto w-full max-w-[820px] px-5 sm:px-7 lg:px-10">

        {{-- Breadcrumb --}}
        <a href="{{ route('lists.index') }}" class="inline-flex items-center gap-6 text-[9px] uppercase tracking-[.16em] font-semibold text-[#a3a3a3] mb-6 hover:text-[#e8d39a] transition-colors">
            <span class="inline-flex items-center gap-1.5">
                <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 12H5"/><path d="m12 19-7-7 7-7"/></svg>
                Back to Reading Lists
            </span>
        </a>

        {{-- Header --}}
        <header class="mb-9 border-b border-white/[.08] pb-9">
            <p class="mb-3 ul-kicker">
                Your Archive <span class="sep">/</span> Reading Lists <span class="sep">/</span>
                <span style="color: var(--ul-subtle); letter-spacing:0.14em;">New</span>
            </p>
            <h1 class="ul-hero-title">Create a Shelf</h1>
            <p class="ul-hero-sub mt-4">
                A shelf is a small, private library — give it a title that hints at what's inside. When you're ready, you can flip the lock and share it with other readers.
            </p>
            <div class="mt-4 ul-divider-grad w-44"></div>
        </header>

        <form action="{{ route('lists.store') }}" method="POST" class="ul-form-panel">
            @csrf

            {{-- Title --}}
            <div class="mb-6">
                <label for="list-title" class="ul-field-label">Shelf Title <span class="req">*</span></label>
                <input
                    type="text"
                    id="list-title"
                    name="title"
                    value="{{ old('title') }}"
                    required
                    maxlength="120"
                    placeholder="e.g. 2026 — Dark Academia Lineup"
                    class="ul-input @error('title') ul-input-error @enderror"
                >
                <span class="ul-charcount" id="list-title-count">000 / 120</span>
                @error('title')<div class="ul-error-text">{{ $message }}</div>@enderror
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="list-desc" class="ul-field-label">Description</label>
                <textarea
                    id="list-desc"
                    name="description"
                    rows="4"
                    maxlength="1000"
                    placeholder="What unites these titles? A mood? A shared setting? A villain you can't shake?"
                    class="ul-input @error('description') ul-input-error @enderror"
                >{{ old('description') }}</textarea>
                <span class="ul-charcount" id="list-desc-count">000 / 1000</span>
                @error('description')<div class="ul-error-text">{{ $message }}</div>@enderror
            </div>

            {{-- Visibility --}}
            <div class="mb-8">
                <div class="ul-field-label mb-2">Visibility</div>
                <label class="ul-check w-full max-w-md">
                    <input type="checkbox" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                    <span class="ul-check-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m5 13 4 4L19 7"/></svg>
                    </span>
                    <span class="ul-check-label">
                        <strong>Public shelf</strong> — shareable link, visible on your profile and discoverable by other readers.
                    </span>
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">
                <a href="{{ route('lists.index') }}" class="ul-btn-outline sm:justify-start">
                    Cancel
                </a>
                <button type="submit" class="ul-btn-solid w-full sm:w-auto">
                    <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    Create Shelf
                </button>
            </div>
        </form>

    </div>
</main>

@push('scripts')
<script>
(function () {
    function bindCount(input, out, max) {
        if (!input || !out) return;
        var pad = function(n) { return n < 10 ? '00' + n : n < 100 ? '0' + n : '' + n; };
        var update = function() { out.textContent = pad(Math.min(input.value.length, max)) + ' / ' + max; };
        input.addEventListener('input', update);
        update();
    }
    bindCount(document.getElementById('list-title'), document.getElementById('list-title-count'), 120);
    bindCount(document.getElementById('list-desc'),  document.getElementById('list-desc-count'),  1000);
})();
</script>
@endpush
@endsection
