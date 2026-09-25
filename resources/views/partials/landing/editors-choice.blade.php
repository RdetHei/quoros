@if($featuredNovels->count() > 0)
<section style="padding:3rem 0 2.5rem;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-heading">
            <div class="section-heading-bar" style="background: linear-gradient(to bottom,#818cf8,#6366f1);"></div>
            <h2>Pilihan Editor</h2>
            <a href="{{ route('welcome') }}">
                Lihat semua
                <svg style="width:11px;height:11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
            @foreach($featuredNovels->take(6) as $novel)
            @php
                $coverUrl = $novel->cover_image_url ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
                $rating = $novel->rating_avg ?? 4.8;
            @endphp
            <a href="{{ route('novels.show', $novel->slug) }}" class="ec-card">
                <div class="ec-cover">
                    @if($coverUrl)
                    <img src="{{ $coverUrl }}" alt="{{ $novel->title }}" width="48" height="72" loading="lazy" onerror="this.src='/error.png'">
                    @endif
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        @if($novel->genres->first())
                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-full" style="color:#818cf8; background:rgba(99,102,241,0.12); border:1px solid rgba(99,102,241,0.2);">{{ $novel->genres->first()->name }}</span>
                        @endif
                        <span class="ec-rating">
                            <svg style="width:10px;height:10px;" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            {{ number_format($rating, 1) }}
                        </span>
                    </div>
                    <h3 class="text-[13px] font-bold line-clamp-1 leading-snug" style="color:#e2e8f0;">{{ $novel->title }}</h3>
                    <p class="text-[10px] mt-0.5 truncate" style="color:#475569;">{{ $novel->author->name }} &middot; {{ $novel->chapters_count }} bab</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
