@props(['novel' => null])

<aside class="fixed inset-y-0 left-0 z-40 w-72 lg:static lg:translate-x-0 lg:shrink-0 bg-slate-950 border-r border-white/5 transition-transform flex flex-col" 
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       aria-label="Sidebar">
    <!-- Logo & Brand -->
    <div class="p-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
            <div class="w-10 h-10 bg-indigo-600 rounded-2xl flex items-center justify-center group-hover:rotate-6 transition-transform shadow-lg shadow-indigo-600/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-black tracking-tighter text-white uppercase">Quoros</h2>
                <p class="text-[9px] font-black text-slate-500 uppercase tracking-[0.2em] leading-none mt-0.5">Author Studio</p>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="px-6 pb-10 space-y-10 overflow-y-auto custom-scrollbar flex-grow">
        <!-- Main Workspace -->
        <div class="space-y-4">
            <p class="px-3 text-[10px] font-black text-slate-500/80 uppercase tracking-[0.2em]">Creative Hub</p>
            <div class="space-y-1">
                <x-writer.nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="home">
                    Main Desk
                </x-writer.nav-link>
                <x-writer.nav-link href="{{ route('writer.novels.index') }}" :active="request()->routeIs('writer.novels.*')" icon="book">
                    Catalog
                </x-writer.nav-link>
                <x-writer.nav-link href="{{ route('writer.analytics.pro') }}" :active="request()->routeIs('writer.analytics.pro')" icon="chart">
                    Insights
                </x-writer.nav-link>
            </div>
        </div>

        <!-- Interaction -->
        <div class="space-y-4">
            <p class="px-3 text-[10px] font-black text-slate-500/80 uppercase tracking-[0.2em]">Community</p>
            <div class="space-y-1">
                <x-writer.nav-link href="{{ route('writer.feedback.hub') }}" :active="request()->routeIs('writer.feedback.hub')" icon="chat">
                    Reader Feed
                </x-writer.nav-link>
                <x-writer.nav-link href="{{ route('notifications.index') }}" :active="request()->routeIs('notifications.index')" icon="bell">
                    Alerts
                    @php($unread = auth()->user()->unreadNotifications->count())
                    @if($unread > 0)
                        <span class="ml-auto bg-indigo-600 text-white text-[10px] font-black px-2 py-0.5 rounded-lg">{{ $unread }}</span>
                    @endif
                </x-writer.nav-link>
            </div>
        </div>

        <!-- Help -->
        <div class="space-y-4">
            <p class="px-3 text-[10px] font-black text-slate-500/80 uppercase tracking-[0.2em]">Guidance</p>
            <div class="space-y-1">
                <x-writer.nav-link href="{{ route('guides.index') }}" icon="guide">
                    Masterclasses
                </x-writer.nav-link>
            </div>
        </div>
    </nav>

    <!-- Back to Site -->
    <div class="p-6 mt-auto">
        <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-4 rounded-[1.5rem] text-xs font-black text-slate-400 hover:text-white hover:bg-white/5 transition-all group border border-white/5 bg-white/5">
            <div class="w-8 h-8 rounded-xl bg-slate-900 flex items-center justify-center border border-white/10 shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors text-slate-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </div>
            <span>READER VIEW</span>
        </a>
    </div>
</aside>
