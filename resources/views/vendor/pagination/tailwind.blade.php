@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex justify-center mt-10">
        <ul class="flex items-center gap-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                    <span class="inline-flex items-center justify-center w-10 h-10 text-slate-500 bg-slate-800/50 rounded-xl border border-slate-700 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-10 h-10 text-slate-300 bg-slate-800/50 rounded-xl border border-slate-700 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all" aria-label="{{ __('pagination.previous') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li aria-disabled="true">
                        <span class="inline-flex items-center justify-center w-10 h-10 text-slate-500">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page">
                                <span class="inline-flex items-center justify-center w-10 h-10 text-white bg-emerald-600 rounded-xl border border-emerald-600 font-bold">
                                    {{ $page }}
                                </span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}" class="inline-flex items-center justify-center w-10 h-10 text-slate-300 bg-slate-800/50 rounded-xl border border-slate-700 hover:bg-slate-800 hover:text-white hover:border-slate-600 transition-all" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-10 h-10 text-slate-300 bg-slate-800/50 rounded-xl border border-slate-700 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 transition-all" aria-label="{{ __('pagination.next') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </li>
            @else
                <li aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                    <span class="inline-flex items-center justify-center w-10 h-10 text-slate-500 bg-slate-800/50 rounded-xl border border-slate-700 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
