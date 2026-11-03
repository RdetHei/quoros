@props(['breadcrumbs' => ['Dashboard'], 'title' => 'Welcome back', 'subtitle' => null, 'currentNovel' => null, 'currentNovelId' => null, 'novels' => null, 'showCreateBtn' => true])

@php
    $novelList = $novels instanceof \Illuminate\Support\Collection ? $novels : collect();
    $hasNovelList = $novelList->isNotEmpty();
    $currentRouteName = request()->route()?->getName();
    $baseQuery = request()->query();
    $currentNovelName = $currentNovel
        ?: optional($novelList->firstWhere('id', (int) ($currentNovelId ?? 0)))->title
        ?: optional($novelList->first())->title;
@endphp

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
                <div class="relative" x-data="{ novelOpen: false }" @click.outside="novelOpen = false" @keydown.escape.window="novelOpen = false">
                    <button type="button" @click="novelOpen = !novelOpen" :aria-expanded="novelOpen"
                        class="flex items-center gap-2.5 rounded-md border border-white/5 bg-[#121212] px-3.5 py-2.5 hover:border-white/10 transition-all min-w-[220px]">
                        <span class="flex h-7 w-7 items-center justify-center rounded border border-white/5 bg-[#0a0a0a] text-[#c7a64a]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                            </svg>
                        </span>
                        <div class="flex-1 text-left min-w-0">
                            <p class="text-[9px] font-medium uppercase tracking-[0.18em] text-[#a3a3a3]">Current Novel</p>
                            <p class="text-[12px] font-semibold text-[#f2efe8] truncate">{{ $currentNovelName ?? 'No novel yet' }}</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-[#a3a3a3] transition-transform" :class="novelOpen && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="novelOpen" x-cloak x-transition.origin.top.right
                        class="absolute right-0 z-50 mt-2 w-[280px] overflow-hidden rounded-md border border-white/10 bg-[#121212] shadow-2xl shadow-black/50">
                        @if($hasNovelList)
                            <div class="max-h-[280px] overflow-y-auto custom-scrollbar py-1">
                                @foreach($novelList as $n)
                                    @php
                                        $isCurrent = (int) ($currentNovelId ?? 0) === (int) $n->id;
                                        $novelUrl = route('writer.dashboard', ['novel_id' => $n->id]);
                                        if ($currentRouteName && \Illuminate\Support\Facades\Route::has($currentRouteName)) {
                                            try {
                                                $novelUrl = route($currentRouteName, array_merge($baseQuery, ['novel_id' => $n->id]));
                                            } catch (\Throwable $e) {
                                                $novelUrl = route('writer.dashboard', ['novel_id' => $n->id]);
                                            }
                                        }
                                    @endphp
                                    <a href="{{ $novelUrl }}"
                                        class="flex items-center gap-2.5 px-3 py-2.5 text-[12px] transition-colors {{ $isCurrent ? 'bg-[#c7a64a]/10 text-[#c7a64a]' : 'text-[#f2efe8] hover:bg-white/5' }}">
                                        <span class="flex h-7 w-7 shrink-0 items-center justify-center overflow-hidden rounded border border-white/5 bg-[#0a0a0a]">
                                            @if($n->cover_url)
                                                <img src="{{ $n->cover_url }}" alt="{{ $n->title }}" class="h-full w-full object-cover">
                                            @else
                                                <span class="text-[10px] font-semibold text-[#c7a64a]">{{ strtoupper(substr($n->title, 0, 1)) }}</span>
                                            @endif
                                        </span>
                                        <span class="min-w-0 flex-1 truncate font-medium">{{ $n->title }}</span>
                                        @if($isCurrent)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                            <a href="{{ route('writer.novels.create') }}" class="flex items-center gap-2 border-t border-white/5 px-3 py-2.5 text-[11px] font-semibold uppercase tracking-[0.12em] text-[#a3a3a3] hover:text-[#c7a64a] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                                </svg>
                                New Novel
                            </a>
                        @else
                            <div class="px-3 py-3">
                                <p class="text-[12px] text-[#a3a3a3] leading-relaxed">You haven't created any novels yet.</p>
                                <a href="{{ route('writer.novels.create') }}" class="mt-2.5 flex items-center justify-center gap-2 rounded bg-[#c7a64a] px-3 py-2 text-[10px] font-semibold uppercase tracking-[0.12em] text-black hover:bg-[#d4b55a] transition-all">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14" />
                                    </svg>
                                    Create Novel
                                </a>
                            </div>
                        @endif
                    </div>
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
