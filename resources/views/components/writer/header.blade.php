@props(['title', 'subtitle' => null])

<header class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10 pb-8 border-b border-neutral-800">
    <div>
        <p class="text-[10px] font-medium text-neutral-500 uppercase tracking-[0.2em] mb-2">Dashboard</p>
        <h1 class="text-2xl md:text-3xl font-semibold text-white tracking-tight">{{ $title }}</h1>
        @if($subtitle)
            <p class="text-sm text-neutral-400 mt-2 max-w-xl leading-relaxed">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="flex items-center gap-3">
        <div class="flex items-center gap-3 px-3 py-2 bg-neutral-900 border border-neutral-800 rounded-lg">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-medium text-white leading-none">{{ auth()->user()->name }}</p>
                <p class="text-[10px] text-neutral-500 uppercase tracking-wider mt-1">{{ auth()->user()->role }}</p>
            </div>
            <div class="w-9 h-9 rounded-md overflow-hidden bg-neutral-800 flex items-center justify-center text-neutral-300 text-sm font-medium border border-neutral-700">
                @if(auth()->user()->profile_photo_url)
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                @else
                    {{ substr(auth()->user()->name, 0, 1) }}
                @endif
            </div>
        </div>

        <button @click="sidebarOpen = true" class="lg:hidden p-2.5 bg-neutral-900 border border-neutral-800 rounded-lg text-neutral-400 hover:text-white hover:border-neutral-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16m-7 6h7" /></svg>
        </button>
    </div>
</header>
