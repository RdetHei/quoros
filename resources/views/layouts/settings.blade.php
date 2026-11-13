<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Settings') - {{ config('app.name', 'Quoros') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/logo/favicon-32.png') }}">
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <meta name="theme-color" content="#0a0a0a">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#0a0a0a] font-sans text-white antialiased">
    <div class="min-h-screen">
        <header class="sticky top-0 z-30 border-b border-white/[.08] bg-[#0a0a0a]/90 backdrop-blur">
            <div class="mx-auto flex h-[54px] max-w-[1440px] items-center justify-between gap-4 px-5 sm:px-8 lg:px-10">
                <div class="flex min-w-0 items-center gap-3">
                    <a href="{{ $settingsBackUrl ?? route('welcome') }}" class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-white/[.12] text-neutral-300 transition-colors hover:border-[#c7a64a]/60 hover:text-[#c7a64a]" aria-label="Back">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                    <a href="{{ route('welcome') }}" class="flex min-w-0 items-center gap-2.5">
                        <img src="{{ asset('storage/logo/quoros.webp') }}" alt="Quoros" width="74" height="28" class="h-6 w-auto">
                        <span class="hidden text-[13px] font-bold tracking-[0.2em] text-white sm:block">QUOROS</span>
                    </a>
                    <span class="hidden h-4 w-px bg-white/[.12] sm:block"></span>
                    <span class="truncate text-[11px] font-semibold uppercase tracking-[0.16em] text-neutral-400">Account Settings</span>
                </div>
                <div class="flex shrink-0 items-center gap-3">
                    <span class="hidden text-[11px] text-neutral-500 sm:block">{{ auth()->user()->name }}</span>
                    <a href="{{ route('profile.show', auth()->user()->username ?? auth()->user()->id) }}" class="inline-flex h-8 items-center rounded-lg border border-white/[.12] px-3 text-[11px] font-semibold uppercase tracking-[0.12em] text-neutral-300 transition-colors hover:border-[#c7a64a]/60 hover:text-[#c7a64a]">Profile</a>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-[1440px] px-5 py-8 sm:px-8 sm:py-10 lg:px-10">
            @if(session('success'))
                <div class="mx-auto mb-6 max-w-3xl rounded-xl border border-[#c7a64a]/25 bg-[#c7a64a]/10 px-4 py-3 text-[13px] text-[#f0dfae]">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mx-auto mb-6 max-w-3xl rounded-xl border border-rose-500/25 bg-rose-500/10 px-4 py-3 text-[13px] text-rose-200">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
    @auth @include('partials.report-modal') @endauth
    @include('partials.cookie-consent')
    @stack('scripts')
</body>
</html>
