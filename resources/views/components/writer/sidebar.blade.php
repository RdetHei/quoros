@props(['novel' => null])

<aside class="fixed inset-y-0 left-0 z-40 w-72 lg:static lg:translate-x-0 lg:shrink-0 bg-black border-r border-neutral-800 transition-transform flex flex-col"
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       aria-label="Sidebar">
    <!-- Logo & Brand -->
    <div class="px-8 pt-8 pb-6">
        <a href="{{ route('home') }}" class="flex items-center gap-3.5 group">
            <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center group-hover:bg-neutral-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-semibold tracking-tight text-white">Quoros</h2>
                <p class="text-[10px] font-medium text-neutral-500 uppercase tracking-[0.15em] leading-none mt-0.5">Author Studio</p>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="px-5 pb-10 space-y-8 overflow-y-auto custom-scrollbar flex-grow">
        <div class="space-y-1.5">
            <p class="px-3 mb-2 text-[10px] font-medium text-neutral-600 uppercase tracking-[0.15em]">Workspace</p>
            <x-writer.nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="home">
                Author Studio
            </x-writer.nav-link>
        </div>

        <div class="space-y-1.5">
            <p class="px-3 mb-2 text-[10px] font-medium text-neutral-600 uppercase tracking-[0.15em]">Community</p>
            <x-writer.nav-link href="{{ route('notifications.index') }}" :active="request()->routeIs('notifications.*')" icon="bell">
                Alerts
                @php($unread = auth()->user()->unreadNotifications->count())
                @if($unread > 0)
                    <span class="ml-auto bg-white text-black text-[10px] font-semibold px-2 py-0.5 rounded-md">{{ $unread }}</span>
                @endif
            </x-writer.nav-link>
        </div>

        <div class="space-y-1.5">
            <p class="px-3 mb-2 text-[10px] font-medium text-neutral-600 uppercase tracking-[0.15em]">Resources</p>
            <x-writer.nav-link href="{{ route('guides.index') }}" :active="request()->routeIs('guides.*')" icon="guide">
                Masterclasses
            </x-writer.nav-link>
        </div>
    </nav>

    <!-- Back to Site -->
    <div class="p-5 mt-auto border-t border-neutral-800">
        <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-3 rounded-lg text-xs font-medium text-neutral-400 hover:text-white hover:bg-neutral-900 transition-all group">
            <div class="w-7 h-7 rounded-md bg-neutral-900 border border-neutral-800 flex items-center justify-center shrink-0 group-hover:bg-white group-hover:text-black group-hover:border-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </div>
            <span class="uppercase tracking-wider">Reader View</span>
        </a>
    </div>
</aside>
