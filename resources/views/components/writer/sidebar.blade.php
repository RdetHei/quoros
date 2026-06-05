@props(['novel' => null])

<aside class="fixed top-0 left-0 z-40 w-72 h-screen transition-transform bg-slate-900 border-r border-slate-800 lg:translate-x-0" 
       :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       aria-label="Sidebar">
    <div class="h-full px-6 py-8 overflow-y-auto flex flex-col">
        <!-- Logo & Brand -->
        <div class="flex items-center gap-3 mb-10 px-2">
            <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-600/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-black tracking-tight text-white">Quoros</h2>
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Writer Workspace</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="space-y-8 flex-grow">
            <!-- Main Workspace -->
            <div>
                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">Workspace</p>
                <div class="space-y-1">
                    <x-writer.nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')" icon="home">
                        Dashboard
                    </x-writer.nav-link>
                    <x-writer.nav-link href="{{ route('writer.novels.index') }}" :active="request()->routeIs('writer.novels.*')" icon="book">
                        My Novels
                    </x-writer.nav-link>
                    <x-writer.nav-link href="{{ route('writer.analytics.pro') }}" :active="request()->routeIs('writer.analytics.pro')" icon="chart">
                        Analytics
                    </x-writer.nav-link>
                </div>
            </div>

            <!-- Interaction -->
            <div>
                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">Interactions</p>
                <div class="space-y-1">
                    <x-writer.nav-link href="{{ route('writer.feedback.hub') }}" :active="request()->routeIs('writer.feedback.hub')" icon="chat">
                        Feedback Hub
                    </x-writer.nav-link>
                    <x-writer.nav-link href="{{ route('notifications.index') }}" :active="request()->routeIs('notifications.index')" icon="bell">
                        Notifications
                        @php($unread = auth()->user()->unreadNotifications->count())
                        @if($unread > 0)
                            <span class="ml-auto bg-indigo-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $unread }}</span>
                        @endif
                    </x-writer.nav-link>
                </div>
            </div>

            <!-- Help -->
            <div>
                <p class="px-4 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">Resources</p>
                <div class="space-y-1">
                    <x-writer.nav-link href="{{ route('guides.index') }}" icon="guide">
                        Writing Guides
                    </x-writer.nav-link>
                </div>
            </div>
        </nav>

        <!-- Back to Site -->
        <div class="mt-auto pt-6 border-t border-slate-800">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold text-slate-400 hover:text-white hover:bg-slate-800 transition-all group">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Reader Site
            </a>
        </div>
    </div>
</aside>
