<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Settings') - {{ config('app.name', 'Quoros') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/quorosLogo.png') }}">
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <meta name="theme-color" content="#0b0b0b">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-neutral-950 font-sans text-white antialiased">
    <div class="min-h-screen">
        <header class="border-b border-white/10 bg-neutral-950/95 backdrop-blur">
            <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
                <div class="flex min-w-0 items-center gap-4">
                    <a href="{{ $settingsBackUrl ?? route('welcome') }}" class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-neutral-700 text-neutral-300 transition-colors hover:border-white hover:bg-white hover:text-black" aria-label="Back">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                    <a href="{{ route('welcome') }}" class="flex min-w-0 items-center gap-3">
                        <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros" class="h-7 w-auto">
                        <span class="hidden text-sm font-bold tracking-wide text-white sm:block">QUOROS</span>
                    </a>
                    <span class="hidden h-5 w-px bg-neutral-700 sm:block"></span>
                    <span class="truncate text-sm font-semibold text-neutral-300">Account Settings</span>
                </div>
                <div class="flex shrink-0 items-center gap-3">
                    <span class="hidden text-xs text-neutral-500 sm:block">{{ auth()->user()->name }}</span>
                    <a href="{{ route('profile.show', auth()->user()->username ?? auth()->user()->id) }}" class="inline-flex h-9 items-center rounded-lg border border-neutral-700 px-3 text-xs font-semibold text-neutral-300 transition-colors hover:border-white hover:text-white">Profile</a>
                </div>
            </div>
        </header>

        <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 sm:py-10 lg:px-8 lg:py-12">
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-neutral-700 bg-neutral-900 px-4 py-3 text-sm text-neutral-200">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-6 rounded-xl border border-neutral-700 bg-neutral-900 px-4 py-3 text-sm text-neutral-200">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>
    @auth @include('partials.report-modal') @endauth
    @include('partials.cookie-consent')
    @stack('scripts')
</body>
</html>
