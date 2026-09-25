@props(['novel' => null])

<div class="hidden w-[290px] shrink-0 flex-col border-r border-neutral-800 bg-[#0b0e13] shadow-[0_0_0_1px_rgba(255,255,255,0.02)] lg:flex">
    <aside class="flex h-full flex-col" aria-label="Sidebar">
        <div class="border-b border-neutral-800 px-5 pb-5 pt-7">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3.5 group">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-neutral-700 bg-neutral-900 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold tracking-tight text-white">Quoros</h2>
                    <p class="mt-0.5 text-[10px] font-medium uppercase tracking-[0.2em] text-neutral-500">Author Studio</p>
                </div>
            </a>
        </div>

        <div class="px-4 pb-4 pt-5">
            <div class="rounded-2xl border border-neutral-800 bg-[#10161d] p-3">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="flex h-11 w-11 items-center justify-center rounded-full border border-neutral-700 bg-gradient-to-br from-stone-200 to-neutral-500 text-sm font-semibold text-neutral-900">
                            {{ strtoupper(substr(auth()->user()->name ?? 'AV', 0, 1)) }}
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-[#0b0e13] bg-emerald-400"></span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-medium uppercase tracking-[0.25em] text-neutral-400">Verified Author</p>
                        <p class="mt-1 truncate text-sm font-semibold text-white">{{ auth()->user()->name ?? 'Aurelia Vale' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <nav class="flex flex-1 flex-col gap-6 overflow-y-auto px-4 pb-4 custom-scrollbar">
            <div class="space-y-2">
                <p class="px-3 text-[10px] font-medium uppercase tracking-[0.25em] text-neutral-500">Overview</p>
                <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-xl border border-neutral-700 bg-[#121a22] px-3 py-2.5 text-sm font-medium text-white transition-all">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h14V10" /></svg>
                    </span>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('dashboard', ['tab' => 'analytics']) }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300 group-hover:bg-neutral-700 group-hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V9m5 7V5m5 11v-8" /></svg>
                    </span>
                    <span>Analytics</span>
                </a>
                <a href="{{ route('dashboard', ['tab' => 'analytics']) }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300 group-hover:bg-neutral-700 group-hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 100 7H14.5a3.5 3.5 0 110 7H6" /></svg>
                    </span>
                    <span>Earnings</span>
                </a>
            </div>

            <div class="space-y-2">
                <p class="px-3 text-[10px] font-medium uppercase tracking-[0.25em] text-neutral-500">Management</p>
                <a href="{{ route('dashboard', ['tab' => 'library']) }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300 group-hover:bg-neutral-700 group-hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6.5A2.5 2.5 0 016.5 4h11A2.5 2.5 0 0120 6.5v11A2.5 2.5 0 0117.5 20h-11A2.5 2.5 0 014 17.5v-11zM8 8h8M8 12h8M8 16h6" /></svg>
                    </span>
                    <span>My Novels</span>
                </a>
                <a href="{{ route('writer.novels.create') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300 group-hover:bg-neutral-700 group-hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                    </span>
                    <span>New Chapter</span>
                </a>
                <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300 group-hover:bg-neutral-700 group-hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 4h10a2 2 0 012 2v12l-4-2-4 2-4-2-4 2V6a2 2 0 012-2z" /></svg>
                    </span>
                    <span>Plot Notes</span>
                </a>
            </div>

            <div class="space-y-2">
                <p class="px-3 text-[10px] font-medium uppercase tracking-[0.25em] text-neutral-500">Engagement</p>
                <a href="{{ route('dashboard', ['tab' => 'community']) }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300 group-hover:bg-neutral-700 group-hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h5m-5 8l-2-2H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2H9l-2 2z" /></svg>
                    </span>
                    <span>Comments</span>
                    <span class="ml-auto flex h-5 min-w-[22px] items-center justify-center rounded-full border border-neutral-700 bg-neutral-800 text-[10px] font-semibold text-neutral-200">24</span>
                </a>
            </div>
        </nav>

        <div class="border-t border-neutral-800 p-4 pt-3">
            <div class="space-y-1.5">
                <a href="{{ route('settings') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </span>
                    <span>Settings</span>
                </a>
                <a href="{{ route('welcome') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                    <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    </span>
                    <span>Reader View</span>
                </a>
            </div>
        </div>
    </aside>
</div>

<aside x-show="sidebarOpen" x-cloak @click.away="sidebarOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="-translate-x-full opacity-0" class="fixed inset-y-0 left-0 z-40 flex w-[290px] flex-col border-r border-neutral-800 bg-[#0b0e13] shadow-[0_0_0_1px_rgba(255,255,255,0.02)] lg:hidden" aria-label="Sidebar mobile">
    <div class="border-b border-neutral-800 px-5 pb-5 pt-7">
        <a href="{{ route('welcome') }}" class="flex items-center gap-3.5 group">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-neutral-700 bg-neutral-900 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold tracking-tight text-white">Quoros</h2>
                <p class="mt-0.5 text-[10px] font-medium uppercase tracking-[0.2em] text-neutral-500">Author Studio</p>
            </div>
        </a>
    </div>

    <div class="px-4 pb-4 pt-5">
        <div class="rounded-2xl border border-neutral-800 bg-[#10161d] p-3">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="flex h-11 w-11 items-center justify-center rounded-full border border-neutral-700 bg-gradient-to-br from-stone-200 to-neutral-500 text-sm font-semibold text-neutral-900">
                        {{ strtoupper(substr(auth()->user()->name ?? 'AV', 0, 1)) }}
                    </div>
                    <span class="absolute -bottom-0.5 -right-0.5 h-3.5 w-3.5 rounded-full border-2 border-[#0b0e13] bg-emerald-400"></span>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-medium uppercase tracking-[0.25em] text-neutral-400">Verified Author</p>
                    <p class="mt-1 truncate text-sm font-semibold text-white">{{ auth()->user()->name ?? 'Aurelia Vale' }}</p>
                </div>
            </div>
        </div>
    </div>

    <nav class="flex flex-1 flex-col gap-6 overflow-y-auto px-4 pb-4 custom-scrollbar">
        <div class="space-y-2">
            <p class="px-3 text-[10px] font-medium uppercase tracking-[0.25em] text-neutral-500">Overview</p>
            <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-xl border border-neutral-700 bg-[#121a22] px-3 py-2.5 text-sm font-medium text-white transition-all">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h14V10" /></svg>
                </span>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('dashboard', ['tab' => 'analytics']) }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300 group-hover:bg-neutral-700 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 16V9m5 7V5m5 11v-8" /></svg>
                </span>
                <span>Analytics</span>
            </a>
            <a href="{{ route('dashboard', ['tab' => 'analytics']) }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300 group-hover:bg-neutral-700 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 1v22M17 5H9.5a3.5 3.5 0 100 7H14.5a3.5 3.5 0 110 7H6" /></svg>
                </span>
                <span>Earnings</span>
            </a>
        </div>

        <div class="space-y-2">
            <p class="px-3 text-[10px] font-medium uppercase tracking-[0.25em] text-neutral-500">Management</p>
            <a href="{{ route('dashboard', ['tab' => 'library']) }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300 group-hover:bg-neutral-700 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6.5A2.5 2.5 0 016.5 4h11A2.5 2.5 0 0120 6.5v11A2.5 2.5 0 0117.5 20h-11A2.5 2.5 0 014 17.5v-11zM8 8h8M8 12h8M8 16h6" /></svg>
                </span>
                <span>My Novels</span>
            </a>
            <a href="{{ route('writer.novels.create') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300 group-hover:bg-neutral-700 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" /></svg>
                </span>
                <span>New Chapter</span>
            </a>
            <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300 group-hover:bg-neutral-700 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 4h10a2 2 0 012 2v12l-4-2-4 2-4-2-4 2V6a2 2 0 012-2z" /></svg>
                </span>
                <span>Plot Notes</span>
            </a>
        </div>

        <div class="space-y-2">
            <p class="px-3 text-[10px] font-medium uppercase tracking-[0.25em] text-neutral-500">Engagement</p>
            <a href="{{ route('dashboard', ['tab' => 'community']) }}" class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300 group-hover:bg-neutral-700 group-hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h5m-5 8l-2-2H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2H9l-2 2z" /></svg>
                </span>
                <span>Comments</span>
                <span class="ml-auto flex h-5 min-w-[22px] items-center justify-center rounded-full border border-neutral-700 bg-neutral-800 text-[10px] font-semibold text-neutral-200">24</span>
            </a>
        </div>
    </nav>

    <div class="border-t border-neutral-800 p-4 pt-3">
        <div class="space-y-1.5">
            <a href="{{ route('settings') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </span>
                <span>Settings</span>
            </a>
            <a href="{{ route('welcome') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-neutral-300 transition-all hover:bg-white/5 hover:text-white">
                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-neutral-800 text-neutral-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-[17px] w-[17px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                </span>
                <span>Reader View</span>
            </a>
        </div>
    </div>
</aside>
