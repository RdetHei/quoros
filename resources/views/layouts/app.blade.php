<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Quoros') }} - Where Story Lives</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/quorosLogo.png') }}">
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <meta name="theme-color" content="#0f172a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ asset('storage/logo/quorosLogo.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>

    <!-- Styles & Scripts -->
    @stack('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Global Cropper Functions
        let currentCropper = null;
        let currentPreviewId = null;
        let currentInput = null;
        let currentCropOptions = {};

        window.initCropper = function(input, previewId, options) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const modal = document.getElementById('cropping-modal');
                    const image = document.getElementById('cropping-image');
                    
                    if (!modal || !image) {
                        console.error('Cropping modal elements not found');
                        return;
                    }

                    // Reset existing cropper
                    if (currentCropper) {
                        currentCropper.destroy();
                        currentCropper = null;
                    }
                    
                    currentInput = input;
                    currentPreviewId = previewId;
                    currentCropOptions = options || {};
                    
                    image.onload = function() {
                        modal.classList.remove('hidden');
                        modal.classList.add('flex');
                        
                        // Small timeout to ensure modal is rendered for dimensions
                        setTimeout(() => {
                            if (currentCropper) {
                                currentCropper.destroy();
                            }
                            
                            currentCropper = new Cropper(image, {
                                aspectRatio: currentCropOptions.aspectRatio || 1,
                                viewMode: 1,
                                dragMode: 'move',
                                autoCropArea: 0.8,
                                restore: false,
                                guides: true,
                                center: true,
                                highlight: false,
                                cropBoxMovable: true,
                                cropBoxResizable: true,
                                toggleDragModeOnDblclick: false,
                                responsive: true,
                                checkOrientation: true,
                            });
                        }, 100);
                    };
                    image.src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
            }
        };

        window.saveCrop = function() {
            if (!currentCropper) return;
            
            let canvasOptions = {
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            };

            if (currentCropOptions.width) canvasOptions.width = currentCropOptions.width;
            if (currentCropOptions.height) canvasOptions.height = currentCropOptions.height;
            
            const canvas = currentCropper.getCroppedCanvas(canvasOptions);
            
            canvas.toBlob((blob) => {
                const preview = document.getElementById(currentPreviewId);
                if (preview) {
                    preview.src = URL.createObjectURL(blob);
                    preview.classList.remove('hidden');
                    
                    // Handle dynamic placeholder ID
                    const placeholderId = currentCropOptions.placeholderId;
                    if (placeholderId) {
                        const placeholder = document.getElementById(placeholderId);
                        if (placeholder) placeholder.classList.add('hidden');
                    }
                    
                    // Fallback for hardcoded cover-placeholder if not provided
                    if (!placeholderId && document.getElementById('cover-placeholder')) {
                        document.getElementById('cover-placeholder').classList.add('hidden');
                    }
                }
                
                // Update file input with cropped image
                const file = new File([blob], 'cropped_image.jpg', { type: 'image/jpeg' });
                const container = new DataTransfer();
                container.items.add(file);
                currentInput.files = container.files;
                
                // Execute callback if exists
                if (currentCropOptions.onSave) {
                    currentCropOptions.onSave();
                }
                
                window.closeCropModal();
            }, 'image/jpeg', 0.9);
        };

        window.closeCropModal = function() {
            const modal = document.getElementById('cropping-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            if (currentCropper) {
                currentCropper.destroy();
                currentCropper = null;
            }
        };

        if (localStorage.getItem('color-theme') === 'dark' || !('color-theme' in localStorage) || window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="font-sans antialiased bg-slate-950 text-slate-100 selection:bg-indigo-500/30 selection:text-indigo-200">
    <div class="min-h-screen flex flex-col bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-900/20 via-slate-950 to-slate-950">
        <!-- Navbar -->
        <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-slate-950 border-b border-white/5 h-16"
             x-data="{
                mobileMenuOpen: false,
                scrollY: 0,
                openMobileMenu() {
                    this.scrollY = window.scrollY || document.documentElement.scrollTop || 0;
                    document.body.style.position = 'fixed';
                    document.body.style.top = `-${this.scrollY}px`;
                    document.body.style.left = '0';
                    document.body.style.right = '0';
                    document.body.style.width = '100%';
                    this.mobileMenuOpen = true;
                },
                closeMobileMenu() {
                    this.mobileMenuOpen = false;
                    const y = this.scrollY || 0;
                    document.body.style.position = '';
                    document.body.style.top = '';
                    document.body.style.left = '';
                    document.body.style.right = '';
                    document.body.style.width = '';
                    window.scrollTo(0, y);
                }
             }">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
                <div class="flex justify-between h-full">
                    <div class="flex items-center gap-4 md:gap-8 flex-1">
                        <!-- Mobile Menu Button -->
                        <button @click="mobileMenuOpen ? closeMobileMenu() : openMobileMenu()"
                                class="lg:hidden p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-colors"
                                aria-label="Open navigation"
                                :aria-expanded="mobileMenuOpen.toString()">
                            <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                            <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>

                        <a href="{{ url('/') }}" class="flex items-center gap-2 group shrink-0">
                            <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-8 md:h-10 w-auto group-hover:opacity-80 transition-opacity" onerror="this.onerror=null; this.src='/error.png'">
                        </a>
                        
                        <div class="hidden lg:flex items-center gap-6">
                            <a href="{{ route('home') }}" class="text-xs font-medium {{ request()->routeIs('home') ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }} hover:text-slate-900 dark:hover:text-white transition-colors">Home</a>
                            <a href="{{ route('novels.updated') }}" class="text-xs font-medium {{ request()->routeIs('novels.updated') ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }} hover:text-slate-900 dark:hover:text-white transition-colors">Updated</a>
                            @auth
                                <a href="{{ route('bookmarks.index') }}" class="text-xs font-medium {{ request()->routeIs('bookmarks.index') ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }} hover:text-slate-900 dark:hover:text-white transition-colors">Bookmarks</a>
                                <a href="{{ route('lists.index') }}" class="text-xs font-medium {{ request()->routeIs('lists.*') ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }} hover:text-slate-900 dark:hover:text-white transition-colors">My Lists</a>
                                <a href="{{ route('history.index') }}" class="text-xs font-medium {{ request()->routeIs('history.index') ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }} hover:text-slate-900 dark:hover:text-white transition-colors">History</a>
                            @endauth
                        </div>
                    </div>

                    <div class="flex items-center gap-2 md:gap-4">
                        @include('partials.live-search-partial', [
                            'id'          => 'desktop-search',
                            'placeholder' => 'Search novels...',
                            'classes'     => 'hidden md:block w-64 lg:w-80',
                        ])
                        <div x-data="{ open: false }" class="md:hidden">
                            <button @click="open = !open" class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </button>
                            <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="absolute left-0 right-0 top-full bg-slate-900 border-b border-slate-800 p-4 shadow-xl z-50">
                                @include('partials.live-search-partial', [ 'id' => 'mobile-search', 'placeholder' => 'Search novels...' ])
                            </div>
                        </div>
                        @auth
                            <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>
                            @include('partials.notification-bell')
                        @endauth
                        @guest
                            <div class="flex items-center gap-1 md:gap-2">
                                <a href="{{ route('login') }}" class="px-3 md:px-4 py-2 text-xs font-medium hover:text-slate-900 dark:hover:text-white transition-colors">Login</a>
                                <a href="{{ route('register') }}" class="px-3 md:px-4 py-2 text-xs font-medium bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-lg hover:bg-slate-800 dark:hover:bg-slate-100 shadow-sm transition-all">Sign Up</a>
                            </div>
                        @else
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center gap-2 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                    <div class="w-8 h-8 rounded-full overflow-hidden bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold text-sm">
                                        @if(Auth::user()->profile_photo_url)
                                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                        @elseif(Auth::user()->profile_photo)
                                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                        @else
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <span class="hidden sm:block text-xs font-medium max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 z-50">
                                    @if(Auth::user()->role === 'writer' || Auth::user()->role === 'admin')
                                        <div class="px-2 py-2">
                                            <a href="{{ route('dashboard') }}" class="flex items-center justify-center gap-3 w-full px-4 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[10px] font-black uppercase tracking-[0.2em] rounded-xl hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-slate-900/10 dark:shadow-white/5 group">
                                                Workspace
                                            </a>
                                        </div>
                                    @endif
                                    <div class="px-2 py-2 space-y-1">
                                        <a href="{{ route('profile.show', Auth::user()->username ?? Auth::user()->id) }}" class="block px-3 py-2 text-sm font-bold text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">My Profile</a>
                                        <a href="{{ route('settings') }}" class="block px-3 py-2 text-sm font-bold text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">Settings</a>
                                        <form action="{{ route('logout') }}" method="POST">@csrf<button type="submit" class="w-full text-left px-3 py-2 text-sm font-bold text-rose-600 dark:text-rose-400 rounded-xl hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all">Logout</button></form>
                                    </div>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Overlay (Simplified for cleanup) -->
        <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-[60] lg:hidden">
            <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm" @click="closeMobileMenu()"></div>
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" class="relative w-[80vw] max-w-[300px] h-full bg-slate-900 shadow-2xl flex flex-col">
                <div class="p-6 space-y-4">
                    <a href="{{ route('home') }}" class="block text-sm font-bold text-white">Home</a>
                    <a href="{{ route('novels.updated') }}" class="block text-sm font-bold text-white">Updated</a>
                    @auth
                        <a href="{{ route('bookmarks.index') }}" class="block text-sm font-bold text-white">Bookmarks</a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-grow pt-16 bg-slate-950">
            <div class="{{ request()->routeIs('welcome', 'home') ? '' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 pt-8' }}">
                @if(session('success'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="p-4 rounded-xl bg-indigo-900/20 border border-indigo-800 text-indigo-400 text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>

        <footer class="bg-slate-900 border-t border-white/5 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                    <div class="md:col-span-2">
                        <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-10 w-auto mb-6 grayscale opacity-80" onerror="this.onerror=null; this.src='/error.png'">
                        <p class="text-sm text-slate-500 max-w-sm leading-relaxed">
                            Quoros adalah platform novel premium yang didedikasikan untuk menghadirkan cerita terbaik dari seluruh dunia dengan pengalaman membaca yang nyaman dan berkualitas.
                        </p>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-[0.2em] text-white mb-6">Navigation</h4>
                        <ul class="space-y-4">
                            <li><a href="{{ route('home') }}" class="text-sm text-slate-500 hover:text-white transition-colors">Home</a></li>
                            <li><a href="{{ route('novels.updated') }}" class="text-sm text-slate-500 hover:text-white transition-colors">Recently Updated</a></li>
                            <li><a href="{{ route('genres.index') }}" class="text-sm text-slate-500 hover:text-white transition-colors">All Genres</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-xs font-black uppercase tracking-[0.2em] text-white mb-6">Community</h4>
                        <ul class="space-y-4">
                            <li><a href="{{ route('guides.index') }}" class="text-sm text-slate-500 hover:text-white transition-colors">Guides</a></li>
                            @guest
                                <li><a href="{{ route('login') }}" class="text-sm text-slate-500 hover:text-white transition-colors">Join Us</a></li>
                            @else
                                <li><a href="{{ route('dashboard') }}" class="text-sm text-slate-500 hover:text-white transition-colors">Writer Workspace</a></li>
                            @endguest
                        </ul>
                    </div>
                </div>
                <div class="pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">&copy; {{ date('Y') }} Quoros &mdash; Crafted for Readers</p>
                    <div class="flex items-center gap-6">
                        <a href="#" class="text-slate-500 hover:text-white transition-colors"><span class="sr-only">Twitter</span><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-1.015-2.174-1.648-3.594-1.648-2.72 0-4.925 2.205-4.925 4.925 0 .386.044.762.128 1.123-4.092-.205-7.719-2.165-10.148-5.144-.424.729-.666 1.576-.666 2.476 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.062c0 2.387 1.699 4.379 3.953 4.83-.414.113-.85.174-1.3.174-.317 0-.626-.03-.926-.086.626 1.956 2.444 3.379 4.6 3.419-1.685 1.321-3.808 2.108-6.115 2.108-.397 0-.79-.023-1.175-.068 2.179 1.397 4.768 2.212 7.548 2.212 9.057 0 13.996-7.502 13.996-13.996 0-.213-.005-.426-.014-.637 1.002-.72 1.815-1.558 2.43-2.527z"/></svg></a>
                        <a href="#" class="text-slate-500 hover:text-white transition-colors"><span class="sr-only">Discord</span><svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.077 0 0 0 .084-.028 14.062 14.062 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.23 10.23 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/></svg></a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @auth @include('partials.report-modal') @endauth
    @include('partials.novel-hover-card')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === Live Search Logic ===
            const searchInputs = document.querySelectorAll('.live-search-input');
            searchInputs.forEach(input => {
                const componentId = input.id.replace('-input', '');
                const dropdown = document.getElementById(`${componentId}-dropdown`);
                const resultsContainer = document.getElementById(`${componentId}-results`);
                const loadingIndicator = document.getElementById(`${componentId}-loading`);
                const footer = document.getElementById(`${componentId}-footer`);
                const emptyState = document.getElementById(`${componentId}-empty`);
                let debounceTimer;

                input.addEventListener('input', function() {
                    const query = this.value.trim();
                    clearTimeout(debounceTimer);

                    if (query.length < 2) {
                        dropdown.style.display = 'none';
                        return;
                    }

                    debounceTimer = setTimeout(async () => {
                        dropdown.style.display = 'block';
                        loadingIndicator.classList.remove('hidden');
                        resultsContainer.innerHTML = '';
                        footer.classList.add('hidden');
                        emptyState.classList.add('hidden');

                        try {
                            const response = await fetch(`/api/live-search?q=${encodeURIComponent(query)}`);
                            const results = await response.json();

                            loadingIndicator.classList.add('hidden');

                            if (results.length > 0) {
                                results.forEach(novel => {
                                    const item = document.createElement('a');
                                    item.href = novel.url;
                                    item.className = 'flex items-center gap-3 p-3 hover:bg-slate-800/50 transition-colors group';
                                    item.innerHTML = `
                                        <div class="w-10 h-14 shrink-0 rounded overflow-hidden bg-slate-800 ring-1 ring-slate-700">
                                            <img src="${novel.cover_image || '/error.png'}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <h4 class="text-xs font-bold text-slate-200 group-hover:text-white truncate">${novel.title}</h4>
                                            <p class="text-[10px] text-slate-500 mt-0.5">${novel.author}</p>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[9px] px-1.5 py-0.5 bg-slate-800 text-slate-400 rounded uppercase tracking-wider font-bold">${novel.type}</span>
                                                <span class="text-[9px] text-amber-500 font-bold flex items-center gap-0.5">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-2.5 w-2.5" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                                    ${novel.rating_avg}
                                                </span>
                                            </div>
                                        </div>
                                    `;
                                    resultsContainer.appendChild(item);
                                });
                                footer.classList.remove('hidden');
                            } else {
                                emptyState.classList.remove('hidden');
                            }
                        } catch (e) {
                            console.error('Search error:', e);
                            loadingIndicator.classList.add('hidden');
                        }
                    }, 300);
                });

                // Close on click away
                document.addEventListener('click', function(e) {
                    if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.style.display = 'none';
                    }
                });
            });

            // === Global Novel Hover Logic ===
            document.addEventListener('mouseover', function(e) {
                const target = e.target.closest('[data-novel-id]');
                if (target) {
                    const rect = target.getBoundingClientRect();
                    window.dispatchEvent(new CustomEvent('novel-hover-show', {
                        detail: {
                            id: target.dataset.novelId,
                            x: rect.right,
                            y: rect.top + (rect.height / 2)
                        }
                    }));
                }
            });

            document.addEventListener('mouseout', function(e) {
                const target = e.target.closest('[data-novel-id]');
                if (target) {
                    window.dispatchEvent(new CustomEvent('novel-hover-hide'));
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>