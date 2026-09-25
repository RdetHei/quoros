@props(['title', 'subtitle' => null])

<header class="mb-8 pb-4 border-b border-white/10">
    <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-[10px] font-medium text-violet-300 uppercase tracking-[0.28em] mb-2">Author Studio</p>
            <h1 class="text-2xl md:text-3xl font-semibold text-white tracking-tight">{{ $title }}</h1>
            @if($subtitle)
                <p class="mt-2 text-sm text-slate-400 max-w-xl leading-relaxed">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="flex items-center gap-3 self-start md:self-auto">
            <div class="flex items-center gap-3 px-3 py-2 rounded-xl border border-white/10 bg-white/5 backdrop-blur-sm">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-medium text-white leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-500 uppercase tracking-[0.2em] mt-1">{{ auth()->user()->role ?? 'Author' }}</p>
                </div>
                <div class="w-10 h-10 rounded-full overflow-hidden bg-gradient-to-br from-violet-500 via-indigo-500 to-sky-500 flex items-center justify-center text-sm font-semibold text-white ring-1 ring-white/10">
                    @if(auth()->user()->profile_photo_url)
                        <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
            </div>

            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2.5 rounded-xl border border-white/10 bg-white/5 text-slate-300 hover:text-white transition-colors" aria-label="Toggle sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16m-7 6h7" /></svg>
            </button>
        </div>
    </div>
</header>
