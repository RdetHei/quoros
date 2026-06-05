<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Quoros') }} - Dashboard</title>

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
        body { font-family: 'Instrument Sans', sans-serif; }
    </style>

    @stack('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        // Global Cropper Functions (shared with settings/profile flows)
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

<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100" x-data="{ sidebarOpen: false }">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <x-writer.sidebar />

        <!-- Backdrop for mobile -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-30 bg-slate-950/60 backdrop-blur-sm lg:hidden"></div>

        <!-- Main Content Area -->
        <div class="flex-grow lg:ml-72 flex flex-col min-w-0">
            <!-- Mobile Header -->
            <header class="lg:hidden flex items-center justify-between h-16 px-4 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-40">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-8 w-auto">
                    <span class="text-lg font-black tracking-tight text-slate-900 dark:text-white">Quoros</span>
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-slate-500 dark:text-slate-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </header>

            <main class="flex-grow py-8 px-4 sm:px-6 lg:px-10 overflow-y-auto w-full max-w-7xl mx-auto">
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
            </main>
        </div>
    </div>

    <div id="cropping-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6 md:p-10">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeCropModal()"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-slate-900 rounded-3xl shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800 flex flex-col max-h-[90vh]">
            <div class="p-4 md:p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Crop Photo</h3>
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
                <button onclick="closeCropModal()" class="px-5 py-2.5 text-sm font-bold text-slate-500 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all">Cancel</button>
                <button onclick="saveCrop()" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-600/20 transition-all">Crop & Save</button>
            </div>
        </div>
    </div>

    @auth
        @include('partials.report-modal')
    @endauth

    @stack('scripts')
</body>
</html>

