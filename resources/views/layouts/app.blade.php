<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Quoros') . ' - Where Story Lives')</title>
    <meta name="description" content="@yield('meta_description', 'Quoros adalah platform novel premium yang didedikasikan untuk menghadirkan cerita terbaik dengan pengalaman membaca yang nyaman.')">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name', 'Quoros'))">
    <meta property="og:description" content="@yield('meta_description', 'Quoros adalah platform novel premium.')">
    <meta property="og:image" content="{{ asset('storage/logo/quorosLogo.png') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', config('app.name', 'Quoros'))">
    <meta property="twitter:description" content="@yield('meta_description', 'Quoros adalah platform novel premium.')">
    <meta property="twitter:image" content="{{ asset('storage/logo/quorosLogo.png') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('storage/logo/quorosLogo.png') }}">
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <meta name="theme-color" content="#000000">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ asset('storage/logo/quorosLogo.png') }}">
    
    <!-- Preload Critical Assets -->
    @stack('preload')
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://res.cloudinary.com">
    
    <!-- Optimized Font Loading -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Outfit:wght@300;400;500;600;700;800&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    </noscript>

    <!-- Styles & Scripts -->
    <style>[x-cloak] { display: none !important; }</style>
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#0a0a0a] text-white selection:bg-[#d4af37]/30 selection:text-white">
    <div class="min-h-screen flex flex-col bg-[#0a0a0a]"
         x-data="{
            mobileMenuOpen: false,
            scrolled: false,
            scrollY: 0,
            init() {
                const onScroll = () => { this.scrolled = window.scrollY > 24; };
                onScroll();
                window.addEventListener('scroll', onScroll, { passive: true });
            },
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
        <!-- Navbar -->
        <nav id="navbar"
             class="fixed top-0 left-0 right-0 z-50 h-[54px]"
             :class="{ 'is-scrolled': scrolled || !{{ request()->routeIs('welcome') ? 'true' : 'false' }} }">
            <div class="max-w-[1440px] mx-auto px-5 sm:px-8 lg:px-10 h-full">
                <div class="flex items-center h-full gap-4">
                    <div class="flex items-center gap-2 min-w-0 shrink-0">
                        <button @click="mobileMenuOpen ? closeMobileMenu() : openMobileMenu()"
                                class="lg:hidden h-8 w-8 inline-flex items-center justify-center rounded-md transition-colors shrink-0 text-[#f5f1e8] hover:bg-white/5"
                                aria-label="Open navigation"
                                :aria-expanded="mobileMenuOpen.toString()">
                            <svg x-show="!mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h16M4 17h16" /></svg>
                            <svg x-show="mobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>

                        <a href="{{ url('/') }}" class="flex items-center gap-2 group shrink-0">
                            <img src="{{ asset('storage/logo/quorosLogo.png') }}" onerror="this.onerror=null; this.src='{{ asset('error.png') }}'" alt="Quoros Logo" class="h-[30px] w-auto group-hover:opacity-85 transition-opacity" fetchpriority="high">
                            <span class="site-brand text-[11px] sm:text-[12px] tracking-[0.28em]">QUOROS</span>
                        </a>
                    </div>

                    <div class="hidden lg:flex items-center flex-1 px-3">
                        @include('partials.live-search-partial', [
                            'id'          => 'desktop-search',
                            'placeholder' => 'SEARCH THE LEGEND',
                            'classes'     => 'w-full max-w-xl',
                        ])
                    </div>

                    <div class="flex items-center gap-2 sm:gap-2.5 shrink-0 ml-auto">
                        <div x-data="{ open: false }" class="lg:hidden relative">
                            <button @click="open = !open"
                                    class="h-8 w-8 inline-flex items-center justify-center rounded-md text-[#f5f1e8] hover:bg-white/5 transition-colors"
                                    aria-label="Search">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </button>
                            <div x-show="open"
                                 @click.away="open = false"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute right-0 top-full mt-2 w-[min(92vw,20rem)] bg-[#111111]/98 backdrop-blur border border-[#d4af37]/20 px-3 py-3 shadow-xl z-50 rounded-lg">
                                @include('partials.live-search-partial', [ 'id' => 'mobile-search', 'placeholder' => 'SEARCH THE LEGEND' ])
                            </div>
                        </div>

                        <div class="hidden lg:flex items-center gap-0.5">
                            @php
                                $navLinks = [];
                                $navLinks[] = ['route' => 'welcome', 'label' => 'Home', 'active' => request()->routeIs('welcome')];
                                $navLinks[] = ['route' => 'novels.updated', 'label' => 'Updates', 'active' => request()->routeIs('novels.updated')];
                                if (Auth::check()) {
                                    $navLinks[] = ['route' => 'bookmarks.index', 'label' => 'Bookmark', 'active' => request()->routeIs('bookmarks.index')];
                                    $navLinks[] = ['route' => 'history.index', 'label' => 'History', 'active' => request()->routeIs('history.index')];
                                } else {
                                    $navLinks[] = ['route' => 'genres.index', 'label' => 'Genres', 'active' => request()->routeIs('genres.*')];
                                    $navLinks[] = ['route' => 'novels.trending', 'label' => 'Trending', 'active' => request()->routeIs('novels.trending')];
                                }
                            @endphp
                            @foreach($navLinks as $link)
                                <a href="{{ route($link['route']) }}"
                                   class="nav-link h-8 inline-flex items-center px-3 text-[10px] font-semibold uppercase tracking-[0.14em] whitespace-nowrap rounded-md {{ $link['active'] ? 'active' : '' }}"
                                   @if($link['active']) aria-current="page" @endif>
                                    {{ $link['label'] }}
                                </a>
                            @endforeach
                        </div>

                        @auth
                            <div class="hidden sm:block w-px h-4 bg-white/10"></div>
                            @include('partials.notification-bell')
                        @endauth

                        @guest
                            <div class="flex items-center gap-1">
                                <a href="{{ route('login') }}" class="h-8 inline-flex items-center px-2.5 text-[10px] font-semibold uppercase tracking-[0.14em] text-[#f5f1e8]/80 hover:text-white hover:bg-white/5 rounded-md transition-colors whitespace-nowrap">
                                    Login
                                </a>
                                <a href="{{ route('register') }}" class="nav-cta h-8 inline-flex items-center px-3 rounded-md text-[9px] font-bold uppercase tracking-[0.16em] whitespace-nowrap">
                                    Sign Up
                                </a>
                            </div>
                        @else
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="h-8 inline-flex items-center gap-1.5 pl-1 pr-1 rounded-md hover:bg-white/5 transition-colors">
                                    <div class="w-6.5 h-6.5 rounded-full overflow-hidden bg-[#1a1a1a] ring-1 ring-[#d4af37]/25 flex items-center justify-center text-[#d4af37] font-bold text-[9px] shrink-0" style="width:26px;height:26px;">
                                        @if(Auth::user()->profile_photo_url)
                                            <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                        @elseif(Auth::user()->profile_photo)
                                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null; this.src='/error.png'">
                                        @else
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        @endif
                                    </div>
                                </button>
                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95 translate-y-1"
                                     x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                                     class="absolute right-0 top-full mt-2 w-56 bg-[#121212] rounded-xl shadow-2xl shadow-black/50 border border-[#d4af37]/15 z-50 overflow-hidden">
                                    <div class="px-3.5 py-3 border-b border-white/5">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-[#d4af37] mb-1">Signed in as</p>
                                        <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-[#8b857d] truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    @if(Auth::user()->role === 'writer' || Auth::user()->role === 'admin')
                                        <div class="p-2">
                                            <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-full h-10 px-3 bg-[#d4af37] text-[#0e0c0a] text-[10px] font-bold uppercase tracking-[0.16em] rounded-lg hover:brightness-110 transition-all">
                                                Workspace
                                            </a>
                                        </div>
                                    @endif
                                    <div class="p-2 space-y-0.5">
                                        <a href="{{ route('profile.show', Auth::user()->username ?? Auth::user()->id) }}" class="h-9 flex items-center px-3 text-sm font-medium text-[#e8e4dc] rounded-lg hover:bg-white/5 transition-all">My Profile</a>
                                        <a href="{{ route('lists.index') }}" class="h-9 flex items-center px-3 text-sm font-medium text-[#e8e4dc] rounded-lg hover:bg-white/5 transition-all">My Lists</a>
                                        <a href="{{ route('settings') }}" class="h-9 flex items-center px-3 text-sm font-medium text-[#e8e4dc] rounded-lg hover:bg-white/5 transition-all">Settings</a>
                                        @if(Auth::user()->role === 'user')
                                            <a href="{{ route('guides.index') }}" class="h-9 flex items-center px-3 text-sm font-medium text-[#d4af37] rounded-lg hover:bg-[#d4af37]/8 transition-all">Become a Writer</a>
                                        @endif
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full h-9 flex items-center px-3 text-sm font-medium text-rose-400 rounded-lg hover:bg-rose-500/10 transition-all text-left">Logout</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>


        <div x-show="mobileMenuOpen" x-cloak class="fixed inset-0 z-[60] lg:hidden">
            <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="closeMobileMenu()"></div>
            <div x-show="mobileMenuOpen"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="mobile-menu-panel relative w-[84vw] max-w-[340px] h-full shadow-2xl flex flex-col overflow-y-auto">
                <div class="px-5 py-5 border-b border-[#d4af37]/12 flex items-center justify-between sticky top-0 bg-[#0c0c0c]/95 backdrop-blur z-10">
                    <div class="flex items-center gap-2.5">
                        <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-8 w-auto">
                        <span class="text-xs font-bold uppercase tracking-[0.28em] text-[#f5f1e8]">QUOROS</span>
                    </div>
                    <button @click="closeMobileMenu()" class="h-9 w-9 inline-flex items-center justify-center rounded-md text-[#f5f1e8] hover:bg-white/5 transition-colors" aria-label="Close menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div class="p-4 space-y-1 flex-grow">
                    <p class="px-2 pb-2 pt-1 text-[10px] font-bold uppercase tracking-[0.22em] text-[#d4af37]">Menu</p>
                    <a href="{{ route('welcome') }}" class="menu-item h-11 flex items-center px-3 rounded-lg text-sm font-medium {{ request()->routeIs('welcome') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('novels.updated') }}" class="menu-item h-11 flex items-center px-3 rounded-lg text-sm font-medium {{ request()->routeIs('novels.updated') ? 'active' : '' }}">Updates</a>
                    <a href="{{ route('novels.trending') }}" class="menu-item h-11 flex items-center px-3 rounded-lg text-sm font-medium {{ request()->routeIs('novels.trending') ? 'active' : '' }}">Trending</a>
                    <a href="{{ route('genres.index') }}" class="menu-item h-11 flex items-center px-3 rounded-lg text-sm font-medium {{ request()->routeIs('genres.*') ? 'active' : '' }}">Genres</a>
                    <a href="{{ route('tags.index') }}" class="menu-item h-11 flex items-center px-3 rounded-lg text-sm font-medium {{ request()->routeIs('tags.*') ? 'active' : '' }}">Tags</a>
                    @auth
                        <div class="pt-3 mt-2 border-t border-[#d4af37]/10">
                            <p class="px-2 pb-2 text-[10px] font-bold uppercase tracking-[0.22em] text-[#d4af37]">Library</p>
                            <a href="{{ route('bookmarks.index') }}" class="menu-item h-11 flex items-center px-3 rounded-lg text-sm font-medium {{ request()->routeIs('bookmarks.index') ? 'active' : '' }}">Bookmark</a>
                            <a href="{{ route('lists.index') }}" class="menu-item h-11 flex items-center px-3 rounded-lg text-sm font-medium {{ request()->routeIs('lists.*') ? 'active' : '' }}">My Lists</a>
                            <a href="{{ route('history.index') }}" class="menu-item h-11 flex items-center px-3 rounded-lg text-sm font-medium {{ request()->routeIs('history.index') ? 'active' : '' }}">History</a>
                        </div>
                        @if(Auth::user()->role === 'user')
                            <div class="pt-3 mt-2">
                                <a href="{{ route('guides.index') }}" class="menu-cta h-11 flex items-center justify-center gap-2 rounded-lg px-3 text-[11px] font-bold uppercase tracking-[0.16em]">Write a Story</a>
                            </div>
                        @endif
                    @endauth
                </div>

                <div class="p-4 border-t border-[#d4af37]/10 space-y-2 sticky bottom-0 bg-[#0c0c0c]">
                    @guest
                        <a href="{{ route('login') }}" class="menu-item h-11 flex items-center justify-center rounded-lg text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="menu-cta h-11 flex items-center justify-center rounded-lg text-sm font-bold">Create Free Account</a>
                    @else
                        <a href="{{ route('profile.show', Auth::user()->username ?? Auth::user()->id) }}" class="menu-item h-11 flex items-center justify-center rounded-lg text-sm font-medium">My Profile</a>
                        <form action="{{ route('logout') }}" method="POST" class="w-full">
                            @csrf
                            <button type="submit" class="h-11 w-full flex items-center justify-center rounded-lg text-sm font-semibold text-rose-400 hover:bg-rose-500/10 transition-all border border-rose-500/15">Logout</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>

        <main class="flex-grow {{ request()->routeIs('welcome') ? 'pt-4' : 'pt-[70px]' }} bg-[#0a0a0a]">
            <div class="{{ request()->routeIs('welcome') ? '' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 pt-8' }}">
                @if(session('success'))
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                        <div class="p-4 rounded-xl bg-[#d4af37]/10 border border-[#d4af37]/25 text-[#f5e0b7] text-sm font-medium">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>

        <footer class="site-footer py-14">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-10 md:grid-cols-12 mb-12">
                    <div class="md:col-span-5">
                        <div class="flex items-center gap-3 mb-5">
                            <img src="{{ asset('storage/logo/quorosLogo.png') }}" alt="Quoros Logo" class="h-10 w-auto" onerror="this.onerror=null; this.src='/error.png'">
                            <span class="footer-brand">QUOROS</span>
                        </div>
                        <p class="text-sm text-[#9a9488] max-w-sm leading-relaxed">
                            Platform novel dengan ruang baca yang gelap, tenang, dan fokus — temukan cerita pilihan, update harian, dan bacaan yang cocok dengan ritmemu.
                        </p>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="footer-title">Browse</h4>
                        <ul class="space-y-3 mt-4">
                            <li><a href="{{ route('welcome') }}" class="text-sm">Home</a></li>
                            <li><a href="{{ route('novels.updated') }}" class="text-sm">Updates</a></li>
                            <li><a href="{{ route('novels.trending') }}" class="text-sm">Trending</a></li>
                            <li><a href="{{ route('novels.search') }}" class="text-sm">Search</a></li>
                        </ul>
                    </div>
                    <div class="md:col-span-2">
                        <h4 class="footer-title">Discover</h4>
                        <ul class="space-y-3 mt-4">
                            <li><a href="{{ route('genres.index') }}" class="text-sm">Genres</a></li>
                            <li><a href="{{ route('tags.index') }}" class="text-sm">Tags</a></li>
                            <li><a href="{{ route('guides.index') }}" class="text-sm">Writer Guides</a></li>
                            @guest
                                <li><a href="{{ route('register') }}" class="text-sm">Join Free</a></li>
                            @else
                                <li><a href="{{ route('dashboard') }}" class="text-sm">Dashboard</a></li>
                            @endguest
                        </ul>
                    </div>
                    <div class="md:col-span-3">
                        <h4 class="footer-title">Community</h4>
                        <ul class="space-y-3 mt-4">
                            <li><a href="#" class="text-sm">Discord</a></li>
                            <li><a href="#" class="text-sm">Support</a></li>
                            <li><a href="#" class="text-sm">Privacy</a></li>
                            <li><a href="#" class="text-sm">Terms</a></li>
                        </ul>
                    </div>
                </div>
                <div class="pt-8 border-t border-[#d4af37]/10 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[11px] text-[#6f6a62] font-medium uppercase tracking-[0.2em]">&copy; {{ date('Y') }} Quoros</p>
                    <div class="flex items-center gap-5 text-[#9a9488]">
                        <a href="#" class="hover:text-[#d4af37] transition-colors" aria-label="X / Twitter"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.9 2h3.68l-8.04 9.19L24 22h-7.32l-5.72-8.39L4.76 22H1.07l8.6-9.83L0 2h7.5l5.17 7.68L18.9 2zm-1.29 18h2.03L7.09 3.9H4.95L17.61 20z"/></svg></a>
                        <a href="#" class="hover:text-[#d4af37] transition-colors" aria-label="Discord"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.37a19.79 19.79 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.267 18.267 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.062 14.062 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.23 10.23 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/></svg></a>
                        <a href="#" class="hover:text-[#d4af37] transition-colors" aria-label="Instagram"><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.583-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.645-.07-4.849 0-3.204.012-3.584.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.07 4.849-.07zm0 1.802c-3.162 0-3.519.013-4.751.068-2.459.112-3.444 1.152-3.556 3.556-.055 1.232-.068 1.589-.068 4.751s.013 3.519.068 4.751c.112 2.404 1.097 3.444 3.556 3.556 1.232.055 1.589.068 4.751.068s3.519-.013 4.751-.068c2.444-.112 3.444-1.152 3.556-3.556.055-1.232.068-1.589.068-4.751s-.013-3.519-.068-4.751c-.112-2.404-1.112-3.444-3.556-3.556-1.232-.055-1.589-.068-4.751-.068zm0 3.647a4.388 4.388 0 1 1 0 8.776 4.388 4.388 0 0 1 0-8.776zm0 1.802a2.586 2.586 0 1 0 0 5.172 2.586 2.586 0 0 0 0-5.172zm5.374-2.585a1.025 1.025 0 1 1 0 2.05 1.025 1.025 0 0 1 0-2.05z"/></svg></a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    @auth @include('partials.report-modal') @endauth
    @include('partials.novel-hover-card')
    @stack('scripts')
</body>
</html>
