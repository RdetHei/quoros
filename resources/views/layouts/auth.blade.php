<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Akun') — {{ config('app.name', 'Quoros') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('storage/logo/quorosLogo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Instrument Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .auth-input {
            width: 100%;
            padding: 0.875rem 1rem;
            font-size: 0.875rem;
            color: #f8fafc;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(51, 65, 85, 0.8);
            border-radius: 0.75rem;
            transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
        }
        .auth-input::placeholder { color: #64748b; }
        .auth-input:focus {
            outline: none;
            border-color: rgba(245, 158, 11, 0.5);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.12);
            background: rgba(15, 23, 42, 0.9);
        }
        .auth-input-error { border-color: rgba(244, 63, 94, 0.6); }
        .auth-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.875rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: #0f172a;
            background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
            border-radius: 0.75rem;
            box-shadow: 0 4px 24px rgba(245, 158, 11, 0.2);
            transition: transform 0.15s, box-shadow 0.15s, filter 0.15s;
        }
        .auth-btn-primary:hover {
            filter: brightness(1.05);
            box-shadow: 0 6px 28px rgba(245, 158, 11, 0.28);
        }
        .auth-btn-primary:active { transform: scale(0.99); }
        .auth-visual-panel img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            filter: brightness(0.82) contrast(1.05) saturate(1.15);
        }
        /* Gelap hanya di area teks — bukan seluruh panel, agar tidak menyatu dengan form */
        .auth-visual-overlay {
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 1;
            background:
                linear-gradient(to top, rgba(15, 23, 42, 0.92) 0%, rgba(15, 23, 42, 0.35) 38%, transparent 62%);
        }
        @media (min-width: 1024px) {
            .auth-visual-overlay {
                background:
                    linear-gradient(to right, rgba(15, 23, 42, 0.5) 0%, transparent 42%),
                    linear-gradient(to top, rgba(15, 23, 42, 0.85) 0%, transparent 45%);
            }
        }
        .auth-visual-copy {
            position: relative;
            z-index: 10;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.65);
        }
        .auth-form-panel {
            background: #020617;
            box-shadow: -1px 0 0 rgba(51, 65, 85, 0.45), -24px 0 48px rgba(0, 0, 0, 0.35);
        }
        @media (min-width: 1024px) {
            .auth-form-panel {
                background: linear-gradient(180deg, #0f172a 0%, #020617 100%);
            }
        }
    </style>
    @stack('styles')
</head>
<body class="antialiased bg-slate-950 text-slate-100">
    <div class="min-h-screen lg:min-h-[100dvh] flex flex-col lg:flex-row">
        {{-- Panel visual (kiri di desktop, atas di mobile) --}}
        <div class="auth-visual-panel relative lg:w-[52%] xl:w-[55%] min-h-[220px] sm:min-h-[280px] lg:min-h-0 lg:fixed lg:inset-y-0 lg:left-0 overflow-hidden bg-slate-800">
            <img src="{{ asset('storage/banners/landingBanner.png') }}"
                 alt=""
                 class="absolute inset-0"
                 loading="eager"
                 onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1578632738980-422cc36e2ec9?auto=format&fit=crop&w=2000&q=80'">
            <div class="auth-visual-overlay" aria-hidden="true"></div>

            <div class="auth-visual-copy flex flex-col justify-between h-full min-h-[220px] sm:min-h-[280px] lg:min-h-0 p-6 sm:p-10 lg:p-12">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 w-fit group">
                    <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros" class="h-6 w-auto max-w-[120px] object-contain drop-shadow-md group-hover:opacity-90 transition-opacity" onerror="this.onerror=null; this.src='/error.png'">
                </a>
                <div class="hidden sm:block max-w-md">
                    <p class="text-[10px] font-black uppercase tracking-[0.35em] text-amber-400 mb-3">Quoros Translation</p>
                    <h2 class="text-2xl lg:text-3xl font-extrabold text-white leading-tight tracking-tight">
                        Tempat cerita hidup,<br>
                        <span class="text-amber-300">dalam bahasa yang kamu pahami.</span>
                    </h2>
                    <p class="mt-4 text-sm text-slate-100/90 leading-relaxed">
                        Baca ribuan novel terjemahan, simpan progress, dan ikuti penulis favoritmu.
                    </p>
                </div>
                <p class="text-[10px] text-slate-300/70 hidden lg:block">&copy; {{ date('Y') }} {{ config('app.name', 'Quoros') }}</p>
            </div>
        </div>

        {{-- Panel form (kanan) — terpisah dari panel gambar --}}
        <div class="auth-form-panel relative z-10 flex-1 flex flex-col lg:ml-[52%] xl:ml-[55%] min-h-0">
            <header class="flex items-center justify-between px-5 py-4 lg:px-12 lg:py-6 border-b border-slate-800/80 lg:border-none">
                <a href="{{ route('home') }}" class="lg:hidden inline-flex items-center gap-2 text-sm font-semibold text-slate-400 hover:text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Beranda
                </a>
                <div class="lg:hidden">
                    <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros" class="h-6 w-auto max-w-[100px] object-contain" onerror="this.onerror=null; this.src='/error.png'">
                </div>
                <a href="{{ route('home') }}" class="hidden lg:inline-flex text-sm font-medium text-slate-400 hover:text-white transition-colors">
                    ← Kembali ke beranda
                </a>
            </header>

            <main class="flex-1 flex items-center justify-center px-5 py-8 sm:px-8 lg:px-12 lg:py-12 overflow-y-auto">
                <div class="w-full max-w-[420px]">
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-400 text-sm font-medium">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="mb-8 lg:mb-10">
                        <p class="text-[10px] font-black uppercase tracking-[0.28em] text-amber-500/90 mb-2">@yield('eyebrow', 'Selamat datang')</p>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">@yield('heading')</h1>
                        <p class="mt-2 text-sm text-slate-400 leading-relaxed">@yield('subheading')</p>
                    </div>

                    @yield('content')

                    <p class="mt-8 text-center text-xs text-slate-500 lg:hidden">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Quoros') }}
                    </p>
                </div>
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
