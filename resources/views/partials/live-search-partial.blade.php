{{--
    Live Search Component
    Usage: @include('partials.live-search', ['id' => 'desktop-search', 'placeholder' => '...', 'classes' => '...'])

    Props:
    - id         : unique ID for this instance (default: 'live-search')
    - placeholder: input placeholder text
    - classes    : extra wrapper classes (e.g. 'hidden md:flex')
--}}

@php
    $componentId  = $id ?? 'live-search';
    $placeholder  = $placeholder ?? 'Cari novel...';
    $wrapperClass = $classes ?? '';
@endphp

<div class="live-search-wrapper {{ $wrapperClass }}"
     data-component-id="{{ $componentId }}"
     style="position: relative;">

    <form action="{{ route('novels.search') }}" method="GET"
          class="live-search-form"
          data-target="{{ $componentId }}">
        <div class="relative w-full">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input type="text"
                   name="q"
                   id="{{ $componentId }}-input"
                   autocomplete="off"
                   spellcheck="false"
                   value="{{ request('q') }}"
                   placeholder="{{ $placeholder }}"
                   class="live-search-input w-full pl-9 pr-3 py-2 text-sm rounded-xl
                          bg-slate-800 text-slate-100 placeholder-slate-500
                          border border-slate-700 shadow-inner shadow-black/20
                          focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500
                          hover:border-slate-600 transition-colors">
        </div>
    </form>

    <div id="{{ $componentId }}-dropdown"
         class="live-search-dropdown absolute left-0 right-0 top-[calc(100%+8px)] z-[200]
                rounded-2xl border border-slate-700/90 bg-slate-900
                shadow-2xl shadow-black/50 overflow-hidden"
         style="display:none; min-width: 320px;">

        <div id="{{ $componentId }}-loading" class="hidden flex px-4 py-4 items-center gap-3 text-slate-400 text-sm">
            <svg class="animate-spin h-4 w-4 shrink-0 text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <span>Mencari…</span>
        </div>

        <div id="{{ $componentId }}-results" class="max-h-[min(70vh,360px)] overflow-y-auto overscroll-contain divide-y divide-slate-800/90"></div>

        <div id="{{ $componentId }}-footer" class="hidden border-t border-slate-800 px-3 py-2.5 bg-slate-800/40">
            <a id="{{ $componentId }}-see-all"
               href="{{ route('novels.search') }}"
               class="flex items-center justify-between gap-2 rounded-lg px-2 py-2 text-xs font-bold text-indigo-400 hover:text-indigo-300 hover:bg-slate-800/80 transition-colors group">
                <span>Advanced search</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 group-hover:text-indigo-400 group-hover:translate-x-0.5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div id="{{ $componentId }}-empty" class="hidden px-4 py-8 text-center border-t border-transparent">
            <p class="text-sm text-slate-400">Tidak ada novel yang cocok.</p>
            <a id="{{ $componentId }}-empty-link" href="{{ route('novels.search') }}"
               class="inline-block mt-3 text-xs font-bold text-indigo-400 hover:text-indigo-300 transition-colors">
                Coba di Advanced Search →
            </a>
        </div>
    </div>
</div>
