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
                currentInput = input;
                currentPreviewId = previewId;
                currentCropOptions = options || {};
                
                const reader = new FileReader();
                reader.onload = function (e) {
                    const modal = document.getElementById('cropping-modal');
                    const image = document.getElementById('cropping-image');
                    image.src = e.target.result;
                    
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    
                    if (currentCropper) {
                        currentCropper.destroy();
                    }
                    
                    // Delay init slightly to ensure image is loaded and modal is visible
                    setTimeout(() => {
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
                        });
                    }, 100);
                };
                reader.readAsDataURL(input.files[0]);
            }
        };

        window.saveCrop = function() {
            if (!currentCropper) return;
            
            const canvas = currentCropper.getCroppedCanvas({
                width: currentCropOptions.width || 400,
                height: currentCropOptions.height || 400,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });
            
            canvas.toBlob((blob) => {
                // Show preview
                const preview = document.getElementById(currentPreviewId);
                if (preview) {
                    preview.src = URL.createObjectURL(blob);
                    preview.classList.remove('hidden');
                    
                    // Special case for profile photo placeholder in dashboard
                    const placeholder = document.getElementById('profile-photo-placeholder');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                
                // Replace file in input using DataTransfer
                const file = new File([blob], 'cropped_image.jpg', { type: 'image/jpeg' });
                const container = new DataTransfer();
                container.items.add(file);
                currentInput.files = container.files;
                
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
            // Reset input if cancelled to allow re-selecting same file
            if (currentInput && !currentCropper) {
                // currentInput.value = ''; // Don't reset if we might have already saved a crop
            }
        };

        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || !('color-theme' in localStorage) || window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>
<body class="font-sans antialiased bg-slate-950 text-slate-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 bg-slate-900/90 backdrop-blur-md border-b border-slate-800"
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
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center gap-4 md:gap-8 flex-1">
                        <!-- Mobile Menu Button -->
                        <button @click="mobileMenuOpen ? closeMobileMenu() : openMobileMenu()"
                                class="lg:hidden p-2 text-slate-400 hover:text-white hover:bg-slate-800 rounded-xl transition-colors"
                                aria-label="Buka navigasi"
                                :aria-expanded="mobileMenuOpen.toString()">
                            <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
                            <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>

                        <a href="{{ url('/') }}" class="flex items-center gap-2 group shrink-0">
                            <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-8 md:h-10 w-auto group-hover:opacity-80 transition-opacity" onerror="this.onerror=null; this.src='/error.png'">
                        </a>
                        
                        <div class="hidden lg:flex items-center gap-6">
                            <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }} hover:text-slate-900 dark:hover:text-white transition-colors">Home</a>
                            <a href="{{ route('novels.updated') }}" class="text-sm font-medium {{ request()->routeIs('novels.updated') ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }} hover:text-slate-900 dark:hover:text-white transition-colors">Updated</a>
                            <a href="{{ route('genres.index') }}" class="text-sm font-medium {{ request()->routeIs('genres.index') ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }} hover:text-slate-900 dark:hover:text-white transition-colors">Genre</a>
                            <a href="{{ route('tags.index') }}" class="text-sm font-medium {{ request()->routeIs('tags.index') ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }} hover:text-slate-900 dark:hover:text-white transition-colors">Tag</a>
                            @auth
                                <a href="{{ route('bookmarks.index') }}" class="text-sm font-medium {{ request()->routeIs('bookmarks.index') ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }} hover:text-slate-900 dark:hover:text-white transition-colors">Bookmark</a>
                                <a href="{{ route('history.index') }}" class="text-sm font-medium {{ request()->routeIs('history.index') ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }} hover:text-slate-900 dark:hover:text-white transition-colors">History</a>
                            @endauth
                        </div>
                    </div>

                    <div class="flex items-center gap-2 md:gap-4">
                        <!-- Search Bar (Desktop - Live Search) -->
                        @include('partials.live-search-partial', [
                            'id'          => 'desktop-search',
                            'placeholder' => 'Cari novel...',
                            'classes'     => 'hidden md:block w-64 lg:w-80',
                        ])
                        <!-- Search Toggle (Mobile) -->
                        <div x-data="{ open: false }" class="md:hidden">
                            <button @click="open = !open" class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </button>

                
                            <!-- Mobile Search Overlay (Live Search) -->
                            <div x-show="open"
                                @click.away="open = false"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute left-0 right-0 top-full bg-slate-900 border-b border-slate-800 p-4 shadow-xl z-50">
                                @include('partials.live-search-partial', [
                                    'id'          => 'mobile-search',
                                    'placeholder' => 'Cari novel...',
                                ])
                            </div>
                        </div>

                        <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>

                        @guest
                            <div class="flex items-center gap-1 md:gap-2">
                                <a href="{{ route('login') }}" class="px-3 md:px-4 py-2 text-xs md:text-sm font-medium hover:text-slate-900 dark:hover:text-white transition-colors">Masuk</a>
                                <a href="{{ route('register') }}" class="px-3 md:px-4 py-2 text-xs md:text-sm font-medium bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-lg hover:bg-slate-800 dark:hover:bg-slate-100 shadow-sm transition-all">Daftar</a>
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
                                    <span class="hidden sm:block text-sm font-medium max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </button>

                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 z-50">
                                    <div class="p-2 border-b border-slate-100 dark:border-slate-800">
                                        <p class="px-3 py-1 text-xs font-semibold text-slate-400 uppercase tracking-wider">Menu</p>
                                        <a href="{{ route('profile.show', Auth::user()->username ?? Auth::user()->id) }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Profil Saya</a>
                                        <a href="{{ route('settings') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Pengaturan</a>
                                        <a href="{{ route('guides.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Guide</a>
                                        @if(Auth::user()->role === 'user')
                                            <form action="{{ route('dashboard.become-writer') }}" method="POST" class="block">
                                                @csrf
                                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-emerald-600 dark:text-emerald-400 rounded-lg hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">Mulai Menulis</button>
                                            </form>
                                        @endif
                                        @if(Auth::user()->role === 'writer' || Auth::user()->role === 'admin')
                                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Dashboard</a>
                                            <a href="{{ route('writer.novels.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Novel Saya</a>
                                        @endif
                                        @if(Auth::user()->role === 'admin')
                                            <div class="mt-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                                                <p class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Admin</p>
                                                <a href="{{ route('admin.genres.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Kelola Genre</a>
                                                <a href="{{ route('admin.tags.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Kelola Tag</a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-2">
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">Keluar</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>

            <!-- Mobile Sidebar Overlay -->
            <div x-show="mobileMenuOpen"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @keydown.escape.window="closeMobileMenu()"
                 class="fixed inset-0 z-[60] lg:hidden" style="display: none;">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm"
                     @click="closeMobileMenu()"
                     @touchmove.prevent></div>
                
                <!-- Content -->
                <div x-show="mobileMenuOpen"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="-translate-x-full"
                     x-transition:enter-end="translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="translate-x-0"
                     x-transition:leave-end="-translate-x-full"
                     class="relative w-[84vw] max-w-[340px] h-[100dvh] bg-white dark:bg-slate-900 shadow-2xl flex flex-col rounded-r-3xl overflow-hidden border-r border-slate-100 dark:border-slate-800"
                     @click.stop>
                    <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-8 w-auto" onerror="this.onerror=null; this.src='/error.png'">
                            <div class="leading-tight">
                                <p class="text-xs font-black uppercase tracking-widest text-slate-400">Menu</p>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">Navigasi</p>
                            </div>
                        </div>
                        <button @click="closeMobileMenu()" class="p-2 text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800/60 rounded-xl transition-colors" aria-label="Tutup navigasi">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                    
                    <div class="flex-1 overflow-y-auto overscroll-contain touch-pan-y p-6 space-y-6" style="-webkit-overflow-scrolling: touch;">
                        @auth
                        <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">
                                    @else
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest truncate">{{ Auth::user()->role }}</p>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3 mt-4">
                                <a href="{{ route('profile.show', Auth::user()->username ?? Auth::user()->id) }}" class="px-4 py-3 text-center text-xs font-bold rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 hover:bg-slate-800 dark:hover:bg-slate-100 transition-colors shadow-lg shadow-slate-900/10">
                                    Profil Saya
                                </a>
                                <a href="{{ route('settings') }}" class="px-4 py-3 text-center text-xs font-bold rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                                    Pengaturan
                                </a>
                                <a href="{{ route('guides.index') }}" class="px-4 py-3 text-center text-xs font-bold rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                                    Guide
                                </a>
                                @if(Auth::user()->role === 'user')
                                    <form action="{{ route('dashboard.become-writer') }}" method="POST" class="block">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-3 text-center text-xs font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-900/10">
                                            Mulai Menulis
                                        </button>
                                    </form>
                                @endif
                                @if(Auth::user()->role === 'writer' || Auth::user()->role === 'admin')
                                    <a href="{{ route('dashboard') }}" class="px-4 py-3 text-center text-xs font-bold rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                                        Dashboard
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endauth

                        <div class="space-y-1">
                            <p class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Navigasi Utama</p>
                            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('home') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                                <span>Home</span>
                            </a>
                            <a href="{{ route('novels.updated') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('novels.updated') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>Updated</span>
                            </a>
                            <a href="{{ route('genres.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('genres.index') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                                <span>Genre</span>
                            </a>
                            <a href="{{ route('tags.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('tags.index') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" /></svg>
                                <span>Tag</span>
                            </a>
                        </div>

                        @auth
                        <div class="space-y-1">
                            <p class="px-3 py-1 text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Aktivitas</p>
                            <a href="{{ route('bookmarks.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('bookmarks.index') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" /></svg>
                                <span>Bookmark</span>
                            </a>
                            <a href="{{ route('history.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold {{ request()->routeIs('history.index') ? 'bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white' : 'text-slate-800 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/50' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span>History</span>
                            </a>
                        </div>
                        @endauth
                    </div>

                    <div class="p-6 border-t border-slate-100 dark:border-slate-800">
                        @guest
                            <div class="grid grid-cols-2 gap-3">
                                <a href="{{ route('login') }}" class="px-4 py-3 text-center text-sm font-bold text-slate-900 dark:text-white bg-slate-100 dark:bg-slate-800 rounded-xl">Masuk</a>
                                <a href="{{ route('register') }}" class="px-4 py-3 text-center text-sm font-bold text-white dark:text-slate-900 bg-slate-900 dark:bg-white rounded-xl">Daftar</a>
                            </div>
                        @else
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-4 py-3 text-center text-sm font-bold text-red-600 bg-red-50 dark:bg-red-900/20 rounded-xl transition-colors">Keluar dari Akun</button>
                            </form>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow {{ request()->routeIs('welcome') ? 'pt-16' : 'pt-24 px-4 sm:px-6 lg:px-8 pb-8' }}">
            <div class="{{ request()->routeIs('welcome') ? '' : 'max-w-7xl mx-auto' }}">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 text-indigo-700 dark:text-indigo-400 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
        <footer class="bg-white dark:bg-slate-900 relative overflow-hidden pt-14 pb-7 px-6 lg:px-10">

    {{-- Decorative top line --}}
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-slate-300/50 dark:via-slate-600/30 to-transparent"></div>

    {{-- Decorative blobs --}}
    <div class="absolute -bottom-20 -left-20 w-56 h-56 bg-slate-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -top-20 -right-20 w-56 h-56 bg-indigo-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative">

        {{-- ===== TOP GRID ===== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-10 mb-14">

            {{-- Brand + Newsletter + Social --}}
            <div class="lg:col-span-4 flex flex-col">

                {{-- Logo --}}
                <a href="{{ route('home') }}" class="inline-block mb-4 group">
                    <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros"
                         class="h-10 w-auto group-hover:scale-105 transition-transform duration-300" onerror="this.onerror=null; this.src='/error.png'">
                </a>

                <p class="text-[13px] leading-relaxed text-slate-500 dark:text-slate-400 max-w-[230px] mb-6">
                    Platform baca novel modern dengan pengalaman yang clean. Temukan ribuan cerita menarik dari penulis berbakat.
                </p>

                {{-- Newsletter --}}
                <p class="text-[10px] uppercase tracking-[0.12em] text-slate-400 dark:text-slate-500 mb-2">Newsletter</p>
                <div class="flex">
                    <input
                        type="email"
                        placeholder="Email kamu..."
                        class="flex-1 min-w-0 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 border-r-0 rounded-l-lg px-3 py-2 text-[12px] text-slate-700 dark:text-slate-300 placeholder-slate-400 dark:placeholder-slate-600 outline-none focus:border-indigo-400 dark:focus:border-indigo-500 transition-colors duration-200"
                    />
                    <button
                        type="button"
                        class="bg-indigo-600 hover:bg-indigo-500 text-white font-medium text-[12px] px-4 py-2 rounded-r-lg transition-colors duration-200 whitespace-nowrap"
                    >
                        Subscribe
                    </button>
                </div>

                {{-- Social Icons --}}
                <div class="flex gap-2 mt-5">
                    <a href="#" aria-label="Facebook"
                        class="w-9 h-9 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="Instagram"
                        class="w-9 h-9 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/20 transition-all duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="#" aria-label="Twitter / X"
                        class="w-9 h-9 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center text-slate-400 hover:text-sky-500 dark:hover:text-sky-400 hover:bg-sky-50 dark:hover:bg-sky-900/20 transition-all duration-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.748l7.73-8.835L2.019 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Nav Columns --}}
            <div class="lg:col-span-8 grid grid-cols-2 sm:grid-cols-3 gap-8">

                {{-- Katalog --}}
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-900 dark:text-white mb-5">Katalog</h4>
                    <ul class="space-y-3.5">
                        <li><a href="{{ route('home') }}"           class="text-[13px] font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-150">Semua Novel</a></li>
                        <li><a href="{{ route('novels.updated') }}" class="text-[13px] font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-150">Baru Diupdate</a></li>
                        <li><a href="{{ route('genres.index') }}"   class="text-[13px] font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-150">Genre</a></li>
                        <li><a href="{{ route('tags.index') }}"     class="text-[13px] font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-150">Tag Populer</a></li>
                    </ul>
                </div>

                {{-- Komunitas --}}
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-900 dark:text-white mb-5">Komunitas</h4>
                    <ul class="space-y-3.5">
                        <li><a href="#" class="text-[13px] font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-150">Tentang Quoros</a></li>
                        <li><a href="#" class="text-[13px] font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-150">Menjadi Penulis</a></li>
                        <li><a href="#" class="text-[13px] font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-150">Pusat Bantuan</a></li>
                        <li><a href="#" class="text-[13px] font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-150">Hubungi Kami</a></li>
                    </ul>
                </div>

                {{-- Legal --}}
                <div class="col-span-2 sm:col-span-1">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-900 dark:text-white mb-5">Legal</h4>
                    <ul class="space-y-3.5">
                        <li><a href="#" class="text-[13px] font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-150">Syarat &amp; Ketentuan</a></li>
                        <li><a href="#" class="text-[13px] font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-150">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-[13px] font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-150">Kebijakan Cookie</a></li>
                    </ul>
                </div>

            </div>
        </div>

        {{-- ===== BOTTOM BAR ===== --}}
        <div class="border-t border-slate-100 dark:border-slate-800 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4">

            <p class="text-[11px] uppercase tracking-widest text-slate-400 dark:text-slate-500 font-bold">
                &copy; {{ date('Y') }} Quoros &mdash; Crafted for Readers
            </p>

            <div class="flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                <span class="text-[11px] uppercase tracking-widest text-slate-400 dark:text-slate-500 font-bold">System Status: Operational</span>
            </div>

        </div>
    </div>
</footer>
       
    </div>

    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (themeToggleDarkIcon && themeToggleLightIcon) {
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                themeToggleLightIcon.classList.remove('hidden');
            } else {
                themeToggleDarkIcon.classList.remove('hidden');
            }
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            // toggle icons inside button
            if (themeToggleDarkIcon && themeToggleLightIcon) {
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');
            }

            // if set via local storage previously
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }

            // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
        }
    
document.addEventListener('DOMContentLoaded', function () {
    const DEBOUNCE_MS  = 220;
    const MIN_CHARS    = 2;
    const API_ENDPOINT = '/api/live-search';

    function buildResultCard(novel) {
        const cover = novel.cover_image
            ? `<img src="${novel.cover_image}" alt="${escHtml(novel.title)}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='/error.png'">`
            : `<div class="w-full h-full bg-slate-800 flex items-center justify-center p-1">
                   <span class="text-[8px] text-slate-500 font-bold text-center leading-tight">${escHtml(novel.title)}</span>
               </div>`;

        const genres = (novel.genres || [])
            .map(g => `<span class="text-[8px] font-bold uppercase tracking-wider text-slate-400 bg-slate-100 dark:bg-slate-800 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-700">${escHtml(g)}</span>`)
            .join('');

        const statusDot = { ongoing: 'bg-indigo-500', complete: 'bg-slate-500', hiatus: 'bg-amber-500' }[novel.status] || 'bg-slate-500';

        return `
        <a href="${novel.url}"
           class="live-search-result flex items-center gap-3 px-3 py-2.5 hover:bg-slate-800/70 transition-colors group outline-none focus:bg-slate-800/70 focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-slate-500/30"
           tabindex="0">
            <div class="w-10 h-[3.35rem] flex-shrink-0 rounded-lg overflow-hidden bg-slate-800 ring-1 ring-slate-700/50 relative">
                ${cover}
                <span class="absolute top-0.5 left-0.5 w-1.5 h-1.5 rounded-full ${statusDot} ring-1 ring-black/40"></span>
            </div>
            <div class="flex-grow min-w-0">
                <p class="text-sm font-bold text-slate-100 group-hover:text-white transition-colors line-clamp-1">${escHtml(novel.title)}</p>
                <p class="text-[10px] text-slate-500 mb-1 line-clamp-1">${escHtml(novel.author)}</p>
                <div class="flex flex-wrap items-center gap-1">${genres}</div>
            </div>
            <div class="flex-shrink-0 flex items-center gap-1 text-amber-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-[10px] font-bold text-slate-300 tabular-nums">${novel.rating_avg}</span>
            </div>
        </a>`;
    }

    function escHtml(str) {
        return String(str ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    function initLiveSearch(wrapper) {
        const id       = wrapper.dataset.componentId;
        const input    = document.getElementById(`${id}-input`);
        const dropdown = document.getElementById(`${id}-dropdown`);
        const loading  = document.getElementById(`${id}-loading`);
        const results  = document.getElementById(`${id}-results`);
        const footer   = document.getElementById(`${id}-footer`);
        const empty    = document.getElementById(`${id}-empty`);
        const seeAll   = document.getElementById(`${id}-see-all`);
        const emptyLink= document.getElementById(`${id}-empty-link`);
        const form     = wrapper.querySelector('.live-search-form');

        if (!input || !dropdown) return;

        let timeout = null;

        input.addEventListener('input', (e) => {
            const query = e.target.value.trim();
            
            clearTimeout(timeout);
            if (query.length < MIN_CHARS) {
                dropdown.classList.add('hidden');
                return;
            }

            timeout = setTimeout(async () => {
                loading.classList.remove('hidden');
                results.innerHTML = '';
                dropdown.classList.remove('hidden');
                footer.classList.add('hidden');
                empty.classList.add('hidden');

                try {
                    const res = await fetch(`${API_ENDPOINT}?q=${encodeURIComponent(query)}`);
                    const data = await res.json();
                    
                    loading.classList.add('hidden');
                    
                    if (data.length > 0) {
                        data.slice(0, 5).forEach(novel => {
                            results.innerHTML += buildResultCard(novel);
                        });
                        footer.classList.remove('hidden');
                        seeAll.href = `/novels/search?q=${encodeURIComponent(query)}`;
                    } else {
                        empty.classList.remove('hidden');
                        emptyLink.href = `/novels/search?q=${encodeURIComponent(query)}`;
                    }
                } catch (err) {
                    console.error('Live search error:', err);
                    loading.classList.add('hidden');
                }
            }, DEBOUNCE_MS);
        });

        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    }

    document.querySelectorAll('.live-search-wrapper').forEach(initLiveSearch);
});
</script>

    <!-- Global Cropping Modal -->
    <div id="cropping-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6 md:p-10">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeCropModal()"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800 flex flex-col max-h-[90vh]">
            <div class="p-4 md:p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Potong Foto</h3>
                <button onclick="closeCropModal()" class="text-slate-400 hover:text-slate-900 dark:hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="flex-grow overflow-hidden bg-slate-100 dark:bg-slate-950 p-4">
                <div class="w-full h-full min-h-[300px] flex items-center justify-center">
                    <img id="cropping-image" src="" class="max-w-full max-h-full" onerror="this.onerror=null; this.src='/error.png'">
                </div>
            </div>
            <div class="p-4 md:p-6 border-t border-slate-100 dark:border-slate-800 flex justify-end gap-3">
                <button onclick="closeCropModal()" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">Batal</button>
                <button onclick="saveCrop()" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 transition-all">Potong & Simpan</button>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
