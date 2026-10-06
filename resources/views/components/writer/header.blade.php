@props(['breadcrumbs' => ['Dashboard'], 'title' => 'Welcome back', 'subtitle' => null, 'currentNovel' => 'The Glass Orchard', 'showCreateBtn' => true])

<header class="border-b border-white/5 bg-[#0a0a0a]">
    <div class="max-w-[1600px] mx-auto px-5 sm:px-8 lg:px-10 py-4 sm:py-5">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-[9.5px] font-semibold uppercase tracking-[0.2em] text-[#c7a64a] mb-2">
                    @foreach($breadcrumbs as $i => $crumb)
                        <span class="{{ $i === count($breadcrumbs) - 1 ? '' : 'text-[#a3a3a3]' }}">{{ $crumb }}</span>
                        @if($i < count($breadcrumbs) - 1)<span class="mx-1.5 text-[#a3a3a3]">/</span>@endif
                    @endforeach
                </p>
                <h1 class="text-[26px] sm:text-[30px] lg:text-[32px] font-medium text-[#f2efe8] tracking-tight font-serif leading-tight">{{ $title }}</h1>
                @if($subtitle)
                    <p class="mt-1.5 text-[13px] text-[#a3a3a3] max-w-xl leading-relaxed">{{ $subtitle }}</p>
                @endif
            </div>

            <div class="flex items-center gap-3 self-start lg:self-auto shrink-0">
                <div class="relative">
                    <button class="flex items-center gap-2.5 rounded-md border border-white/5 bg-[#121212] px-3.5 py-2.5 hover:border-white/10 transition-all min-w-[220px]">
                        <span class="flex h-7 w-7 items-center justify-center rounded border border-white/5 bg-[#0a0a0a] text-[#c7a64a]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                        </span>
                        <div class="flex-1 text-left min-w-0">
                            <p class="text-[9px] font-medium uppercase tracking-[0.18em] text-[#a3a3a3]">Current Novel</p>
                            <p class="text-[12px] font-semibold text-[#f2efe8] truncate">{{ $currentNovel }}</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#a3a3a3]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>

                @if($showCreateBtn)
                <a href="{{ route('writer.novels.create') }}" class="flex items-center gap-2 rounded-md bg-[#c7a64a] px-4 py-2.5 hover:bg-[#d4b55a] transition-all group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                    </svg>
                    <span class="text-[11px] font-semibold uppercase tracking-[0.12em] text-black">Create Novel</span>
                </a>
                @endif
            </div>
        </div>
    </div>
</header>
