<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Quoros') }} - Dashboard</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/logo/quorosLogo.png') }}">
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <meta name="theme-color" content="#000000">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ asset('storage/logo/quorosLogo.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>

    <!-- Styles & Scripts -->
    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#050a13] text-neutral-100 min-h-screen" x-data="{ sidebarOpen: false }" style="background-image: radial-gradient(circle at top left, rgba(139,92,246,0.18), transparent 28%), radial-gradient(circle at bottom right, rgba(59,130,246,0.12), transparent 32%), #050a13;">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-writer.sidebar />

        <!-- Backdrop for mobile -->
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

        <!-- Main Content Area -->
        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">
            <div class="flex h-full flex-col overflow-y-auto bg-neutral-900 custom-scrollbar">
                <!-- Mobile Header -->
                <header class="lg:hidden flex items-center justify-between h-14 px-4 bg-neutral-950 border-b border-neutral-800 sticky top-0 z-40">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-7 w-auto">
                        <span class="text-base font-semibold tracking-tight text-white">Quoros</span>
                    </div>
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-neutral-400 hover:text-white transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                    </button>
                </header>

                <main class="flex-grow py-8 px-4 sm:px-6 lg:px-10 w-full max-w-7xl mx-auto">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-neutral-900 border border-neutral-700 text-white flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-300" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-lg bg-neutral-900 border border-neutral-600 text-neutral-200 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-neutral-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                        <span class="text-sm">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <div id="cropping-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6 md:p-10">
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeCropModal()"></div>
        <div class="relative w-full max-w-2xl bg-neutral-900 rounded-xl shadow-2xl overflow-hidden border border-neutral-800 flex flex-col max-h-[90vh]">
            <div class="p-4 md:p-6 border-b border-neutral-800 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Crop Photo</h3>
                <button onclick="closeCropModal()" class="text-neutral-500 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="flex-grow overflow-hidden bg-black p-4">
                <div class="w-full h-full min-h-[300px] flex items-center justify-center">
                    <img id="cropping-image" src="" class="max-w-full max-h-full" onerror="this.onerror=null; this.src='/error.png'">
                </div>
            </div>
            <div class="p-4 md:p-6 border-t border-neutral-800 flex justify-end gap-3">
                <button onclick="closeCropModal()" class="px-5 py-2.5 text-sm font-medium text-neutral-400 hover:text-white hover:bg-neutral-800 rounded-lg transition-all">Cancel</button>
                <button onclick="saveCrop()" class="px-6 py-2.5 bg-white text-black text-sm font-medium rounded-lg hover:bg-neutral-200 transition-all">Crop & Save</button>
            </div>
        </div>
    </div>

    @auth
        @include('partials.report-modal')
    @endauth

    @include('partials.cookie-consent')
    @stack('scripts')
</body>
</html>

