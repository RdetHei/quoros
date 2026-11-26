<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Quoros') }} - Author Studio</title>

    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/logo/favicon-32.png') }}">
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <meta name="theme-color" content="#0a0a0a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ asset('storage/logo/app-icon-192.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        .font-serif { font-family: 'Cormorant Garamond', serif; }
    </style>

    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#0a0a0a] text-[#f2efe8] h-dvh overflow-hidden" x-data="{ sidebarOpen: false }">
    <div class="flex h-dvh overflow-hidden">
        <x-writer.sidebar :active="$active ?? (request()->routeIs('writer.dashboard') ? 'dashboard' : (request()->routeIs('writer.analytics') ? 'analytics' : (request()->routeIs('writer.earnings') ? 'earnings' : (request()->routeIs('writer.novels*') && !request()->routeIs('writer.novels.create') ? 'novels' : (request()->routeIs('writer.chapters') ? 'chapters' : 'dashboard')))))" />

        <div x-show="sidebarOpen"
             x-cloak
             @click="sidebarOpen = false"
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-30 bg-black/70 backdrop-blur-sm lg:hidden"></div>

        <div class="flex min-h-0 min-w-0 flex-1 flex-col overflow-hidden">
            <div class="flex min-h-0 flex-1 flex-col overflow-y-auto bg-[#0a0a0a] custom-scrollbar">
                <header class="lg:hidden flex items-center justify-between h-14 px-5 sm:px-8 bg-[#0a0a0a] border-b border-white/5 sticky top-0 z-40">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('storage/logo/quoros.webp') }}" alt="Quoros Logo" width="74" height="28" class="h-7 w-auto">
                        <span class="text-base font-semibold tracking-tight text-[#f2efe8]">Quoros</span>
                    </div>
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-[#a3a3a3] hover:text-[#f2efe8] transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                </header>

                <main class="flex-grow py-0 px-0 w-full">
                @yield('content')
            </main>
            </div>
        </div>
    </div>

    <div id="cropping-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6 md:p-10">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeCropModal()"></div>
        <div class="relative w-full max-w-2xl bg-[#121212] rounded-xl shadow-2xl overflow-hidden border border-white/5 flex flex-col max-h-[90vh]">
            <div class="p-4 md:p-6 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-[#f2efe8]">Crop Photo</h3>
                <button onclick="closeCropModal()" class="text-[#a3a3a3] hover:text-[#f2efe8] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="flex-grow overflow-hidden bg-black p-4">
                <div class="w-full h-full min-h-[300px] flex items-center justify-center">
                    <img id="cropping-image" src="" class="max-w-full max-h-full" onerror="this.onerror=null; this.src='/error.png'">
                </div>
            </div>
            <div class="p-4 md:p-6 border-t border-white/5 flex justify-end gap-3">
                <button onclick="closeCropModal()" class="px-5 py-2.5 text-sm font-medium text-[#a3a3a3] hover:text-[#f2efe8] hover:bg-white/5 rounded-lg transition-all">Cancel</button>
                <button onclick="saveCrop()" class="px-6 py-2.5 bg-[#c7a64a] text-black text-sm font-semibold rounded-lg hover:bg-[#d4b55a] transition-all">Crop & Save</button>
            </div>
        </div>
    </div>

    @auth
        @include('partials.report-modal')
    @endauth

    @include('partials.cookie-consent')
    @include('partials.toast')
    @stack('scripts')
</body>
</html>
