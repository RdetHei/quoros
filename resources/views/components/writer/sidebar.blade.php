@props(['active' => 'dashboard', 'novel' => null])

<div class="hidden w-[260px] shrink-0 flex-col border-r border-white/5 bg-[#0a0a0a] lg:flex">
    <aside class="flex h-full flex-col" aria-label="Sidebar">
        <div class="border-b border-white/5 px-5 pb-5 pt-6">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg border border-[#c7a64a]/30 bg-[#121212] text-[#c7a64a]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
                <div class="flex items-center gap-3">
                    <div>
                        <h2 class="text-[15px] font-semibold tracking-tight text-[#f2efe8] font-serif">QUOROS</h2>
                        <p class="mt-0.5 text-[9px] font-medium uppercase tracking-[0.22em] text-[#a3a3a3]">Author Studio</p>
                    </div>
                    <span class="rounded border border-[#c7a64a]/40 px-2 py-0.5 text-[9px] font-semibold uppercase tracking-[0.15em] text-[#c7a64a]">STUDIO</span>
                </div>
            </a>
        </div>

        <div class="px-4 pb-4 pt-5">
            <div class="rounded-lg border border-white/5 bg-[#121212] p-3">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-gradient-to-br from-amber-900/60 to-stone-800 text-[13px] font-semibold text-[#f2efe8] overflow-hidden">
                            @if(auth()->user()->profile_photo_url)
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr(auth()->user()->name ?? 'MV', 0, 1)) }}
                            @endif
                        </div>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-[13px] font-semibold text-[#f2efe8] truncate">{{ auth()->user()->name ?? 'Mara Voss' }}</p>
                        <p class="mt-0.5 text-[9px] font-medium uppercase tracking-[0.2em] text-[#a3a3a3]">{{ auth()->user()->bio ? \Illuminate\Support\Str::limit(auth()->user()->bio, 24) : 'Historical Fiction' }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>

        <nav class="flex flex-1 flex-col gap-5 overflow-y-auto px-4 pb-4 custom-scrollbar">
            <div class="space-y-1">
                <p class="px-3 text-[9px] font-medium uppercase tracking-[0.22em] text-[#a3a3a3] mb-2">Overview</p>

                <a href="{{ route('writer.dashboard') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all {{ $active === 'dashboard' ? 'border border-[#c7a64a]/25 bg-[#c7a64a]/10 text-[#f2efe8]' : 'text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]' }}">
                    <span class="flex h-[22px] w-[22px] items-center justify-center text-[#c7a64a]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                        </svg>
                    </span>
                    <span>Dashboard</span>
                    @if($active === 'dashboard')<span class="ml-auto h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>@endif
                </a>

                <a href="{{ route('writer.analytics') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all {{ $active === 'analytics' ? 'border border-[#c7a64a]/25 bg-[#c7a64a]/10 text-[#f2efe8]' : 'text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]' }}">
                    <span class="flex h-[22px] w-[22px] items-center justify-center {{ $active === 'analytics' ? 'text-[#c7a64a]' : 'text-[#a3a3a3] group-hover:text-[#f2efe8]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 15l4-4 3 3 5-6" />
                        </svg>
                    </span>
                    <span>Analytics</span>
                    @if($active === 'analytics')<span class="ml-auto h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>@endif
                </a>

                <a href="{{ route('writer.earnings') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all {{ $active === 'earnings' ? 'border border-[#c7a64a]/25 bg-[#c7a64a]/10 text-[#f2efe8]' : 'text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]' }}">
                    <span class="flex h-[22px] w-[22px] items-center justify-center {{ $active === 'earnings' ? 'text-[#c7a64a]' : 'text-[#a3a3a3] group-hover:text-[#f2efe8]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                    <span>Earnings</span>
                    @if($active === 'earnings')<span class="ml-auto h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>@endif
                </a>
            </div>

            <div class="space-y-1">
                <p class="px-3 text-[9px] font-medium uppercase tracking-[0.22em] text-[#a3a3a3] mb-2">Writing</p>

                <a href="{{ route('writer.novels') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all {{ $active === 'novels' ? 'border border-[#c7a64a]/25 bg-[#c7a64a]/10 text-[#f2efe8]' : 'text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]' }}">
                    <span class="flex h-[22px] w-[22px] items-center justify-center {{ $active === 'novels' ? 'text-[#c7a64a]' : 'text-[#a3a3a3] group-hover:text-[#f2efe8]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 016.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" />
                        </svg>
                    </span>
                    <span>My Novels</span>
                    @if($active === 'novels')<span class="ml-auto h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>@endif
                </a>

                <a href="{{ route('writer.chapters') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all {{ $active === 'chapters' ? 'border border-[#c7a64a]/25 bg-[#c7a64a]/10 text-[#f2efe8]' : 'text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]' }}">
                    <span class="flex h-[22px] w-[22px] items-center justify-center {{ $active === 'chapters' ? 'text-[#c7a64a]' : 'text-[#a3a3a3] group-hover:text-[#f2efe8]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </span>
                    <span>Write Chapter</span>
                    @if($active === 'chapters')<span class="ml-auto h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>@endif
                </a>

                <a href="{{ route('writer.plot') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all {{ $active === 'plot' ? 'border border-[#c7a64a]/25 bg-[#c7a64a]/10 text-[#f2efe8]' : 'text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]' }}">
                    <span class="flex h-[22px] w-[22px] items-center justify-center {{ $active === 'plot' ? 'text-[#c7a64a]' : 'text-[#a3a3a3] group-hover:text-[#f2efe8]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </span>
                    <span>Plot Notes</span>
                    @if($active === 'plot')<span class="ml-auto h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>@endif
                </a>
            </div>

            <div class="space-y-1">
                <p class="px-3 text-[9px] font-medium uppercase tracking-[0.22em] text-[#a3a3a3] mb-2">Community</p>

                <a href="{{ route('writer.comments') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all {{ $active === 'comments' ? 'border border-[#c7a64a]/25 bg-[#c7a64a]/10 text-[#f2efe8]' : 'text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]' }}">
                    <span class="flex h-[22px] w-[22px] items-center justify-center {{ $active === 'comments' ? 'text-[#c7a64a]' : 'text-[#a3a3a3] group-hover:text-[#f2efe8]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </span>
                    <span>Comments</span>
                    <span class="ml-auto flex h-5 min-w-[20px] items-center justify-center rounded-full border border-[#c7a64a]/30 bg-[#c7a64a]/10 text-[10px] font-semibold text-[#c7a64a]">8</span>
                    @if($active === 'comments')<span class="ml-1 h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>@endif
                </a>

                <a href="{{ route('writer.codex') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all {{ $active === 'codex' ? 'border border-[#c7a64a]/25 bg-[#c7a64a]/10 text-[#f2efe8]' : 'text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]' }}">
                    <span class="flex h-[22px] w-[22px] items-center justify-center {{ $active === 'codex' ? 'text-[#c7a64a]' : 'text-[#a3a3a3] group-hover:text-[#f2efe8]' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </span>
                    <span>Character Codex</span>
                    @if($active === 'codex')<span class="ml-auto h-1.5 w-1.5 rounded-full bg-[#c7a64a]"></span>@endif
                </a>
            </div>
        </nav>

        <div class="border-t border-white/5 px-4 py-3">
            <a href="{{ route('writer.reader') }}" @class([
                'flex items-center gap-3 rounded-md px-3 py-2.5 text-[12px] font-medium transition-all hover:bg-white/5 hover:text-[#f2efe8] border bg-[#121212]/50',
                $active === 'reader' ? 'border-[#c7a64a]/40 bg-[#c7a64a]/10 text-[#f2efe8]' : 'border-white/5 text-[#a3a3a3]'
            ])>
                <span class="flex h-[22px] w-[22px] items-center justify-center text-[#c7a64a]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[16px] w-[16px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </span>
                <div class="flex-1">
                    <p class="text-[#f2efe8] font-medium">Reader Mode</p>
                    <p class="text-[9px] uppercase tracking-[0.2em] text-[#a3a3a3]">Focus Preview</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
        </div>
    </aside>
</div>

<aside x-show="sidebarOpen" x-cloak @click.away="sidebarOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="-translate-x-full opacity-0" class="fixed inset-y-0 left-0 z-40 flex w-[260px] flex-col border-r border-white/5 bg-[#0a0a0a] lg:hidden" aria-label="Sidebar mobile">
    <div class="border-b border-white/5 px-5 pb-5 pt-6">
        <a href="{{ route('welcome') }}" class="flex items-center gap-3 group">
            <div class="flex h-9 w-9 items-center justify-center rounded-lg border border-[#c7a64a]/30 bg-[#121212] text-[#c7a64a]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </div>
            <div class="flex items-center gap-3">
                <div>
                    <h2 class="text-[15px] font-semibold tracking-tight text-[#f2efe8] font-serif">QUOROS</h2>
                    <p class="mt-0.5 text-[9px] font-medium uppercase tracking-[0.22em] text-[#a3a3a3]">Author Studio</p>
                </div>
                <span class="rounded border border-[#c7a64a]/40 px-2 py-0.5 text-[9px] font-semibold uppercase tracking-[0.15em] text-[#c7a64a]">STUDIO</span>
            </div>
        </a>
    </div>

    <div class="px-4 pb-4 pt-5">
        <div class="rounded-lg border border-white/5 bg-[#121212] p-3">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-gradient-to-br from-amber-900/60 to-stone-800 text-[13px] font-semibold text-[#f2efe8] overflow-hidden">
                        @if(auth()->user()->profile_photo_url)
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name ?? 'MV', 0, 1)) }}
                        @endif
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[13px] font-semibold text-[#f2efe8] truncate">{{ auth()->user()->name ?? 'Mara Voss' }}</p>
                    <p class="mt-0.5 text-[9px] font-medium uppercase tracking-[0.2em] text-[#a3a3a3]">Historical Fiction</p>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
        </div>
    </div>

    <nav class="flex flex-1 flex-col gap-5 overflow-y-auto px-4 pb-4 custom-scrollbar">
        <div class="space-y-1">
            <p class="px-3 text-[9px] font-medium uppercase tracking-[0.22em] text-[#a3a3a3] mb-2">Overview</p>
            <a href="{{ route('writer.dashboard') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all {{ $active === 'dashboard' ? 'border border-[#c7a64a]/25 bg-[#c7a64a]/10 text-[#f2efe8]' : 'text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]' }}">
                <span class="flex h-[22px] w-[22px] items-center justify-center text-[#c7a64a]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg>
                </span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('writer.analytics') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]">
                <span class="flex h-[22px] w-[22px] items-center justify-center text-[#a3a3a3] group-hover:text-[#f2efe8]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18M7 15l4-4 3 3 5-6" /></svg>
                </span>
                <span>Analytics</span>
            </a>
            <a href="{{ route('writer.earnings') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]">
                <span class="flex h-[22px] w-[22px] items-center justify-center text-[#a3a3a3] group-hover:text-[#f2efe8]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </span>
                <span>Earnings</span>
            </a>
        </div>

        <div class="space-y-1">
            <p class="px-3 text-[9px] font-medium uppercase tracking-[0.22em] text-[#a3a3a3] mb-2">Writing</p>
            <a href="{{ route('writer.novels') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]">
                <span class="flex h-[22px] w-[22px] items-center justify-center text-[#a3a3a3] group-hover:text-[#f2efe8]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19.5A2.5 2.5 0 016.5 17H20M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z" /></svg>
                </span>
                <span>My Novels</span>
            </a>
            <a href="{{ route('writer.chapters') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]">
                <span class="flex h-[22px] w-[22px] items-center justify-center text-[#a3a3a3] group-hover:text-[#f2efe8]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                </span>
                <span>Write Chapter</span>
            </a>
            <a href="{{ route('writer.plot') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]">
                <span class="flex h-[22px] w-[22px] items-center justify-center text-[#a3a3a3] group-hover:text-[#f2efe8]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </span>
                <span>Plot Notes</span>
            </a>
        </div>

        <div class="space-y-1">
            <p class="px-3 text-[9px] font-medium uppercase tracking-[0.22em] text-[#a3a3a3] mb-2">Community</p>
            <a href="{{ route('writer.comments') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]">
                <span class="flex h-[22px] w-[22px] items-center justify-center text-[#a3a3a3] group-hover:text-[#f2efe8]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
                </span>
                <span>Comments</span>
                <span class="ml-auto flex h-5 min-w-[20px] items-center justify-center rounded-full border border-[#c7a64a]/30 bg-[#c7a64a]/10 text-[10px] font-semibold text-[#c7a64a]">8</span>
            </a>
            <a href="{{ route('writer.codex') }}" class="group flex items-center gap-3 rounded-md px-3 py-2 text-[12.5px] font-medium transition-all text-[#a3a3a3] hover:bg-white/5 hover:text-[#f2efe8]">
                <span class="flex h-[22px] w-[22px] items-center justify-center text-[#a3a3a3] group-hover:text-[#f2efe8]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                </span>
                <span>Character Codex</span>
            </a>
        </div>
    </nav>

    <div class="border-t border-white/5 px-4 py-3">
        <a href="{{ route('writer.reader') }}" class="flex items-center gap-3 rounded-md px-3 py-2.5 text-[12px] font-medium text-[#a3a3a3] transition-all hover:bg-white/5 hover:text-[#f2efe8] border border-white/5 bg-[#121212]/50">
            <span class="flex h-[22px] w-[22px] items-center justify-center text-[#c7a64a]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-[16px] w-[16px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </span>
            <div class="flex-1">
                <p class="text-[#f2efe8] font-medium">Reader Mode</p>
                <p class="text-[9px] uppercase tracking-[0.2em] text-[#a3a3a3]">Focus Preview</p>
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
        </a>
    </div>
</aside>
