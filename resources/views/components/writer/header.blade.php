@props(['title', 'subtitle' => null])

<header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
    <div>
        <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $title }}</h1>
        @if($subtitle)
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="flex items-center gap-4">
        <!-- User Profile Quick Info -->
        <div class="flex items-center gap-3 px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-sm">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-bold text-slate-900 dark:text-white leading-none">{{ auth()->user()->name }}</p>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">{{ auth()->user()->role }}</p>
            </div>
            <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold border border-slate-200 dark:border-slate-700">
                @if(auth()->user()->profile_photo_url)
                    <img src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                @else
                    {{ substr(auth()->user()->name, 0, 1) }}
                @endif
            </div>
        </div>
        
        <!-- Mobile Menu Toggle -->
        <button @click="sidebarOpen = true" class="lg:hidden p-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
        </button>
    </div>
</header>
