@if($recentlyUpdated->count() > 0)
<section class="latest-updates" style="padding:2.5rem 0 3rem; border-top:1px solid rgba(255,255,255,0.05); background:#000000;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="section-heading">
            <div class="section-heading-bar" style="background:linear-gradient(to bottom,#22c55e,#16a34a);"></div>
            <h2>Fresh Picks</h2>
            <a href="{{ route('novels.updated') }}">
                Lihat semua
                <svg style="width:11px;height:11px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        @php
            $featureNovel = $recentlyUpdated->first();
            $featureCover = $featureNovel?->cover_image_url ?: ($featureNovel?->cover_image ? asset('storage/' . $featureNovel->cover_image) : null);
            $featureLatestCh = $featureNovel?->chapters->first();
            $featureLatestAgo = $featureLatestCh ? \Illuminate\Support\Carbon::parse($featureLatestCh->published_at ?? $featureLatestCh->created_at)->diffForHumans(null, true) : null;
        @endphp

        <div class="fresh-picks">
            @if($featureNovel)
            <div class="fresh-feature">
                <a href="{{ $featureLatestCh ? route('chapters.show', [$featureNovel->slug, $featureLatestCh->slug]) : route('novels.show', $featureNovel->slug) }}" class="block h-full w-full">
                    @if($featureCover)<img src="{{ $featureCover }}" alt="{{ $featureNovel->title }}" loading="lazy" onerror="this.src='/error.png'">@endif
                    <div class="fresh-feature-content">
                        <span class="fresh-kicker">Rilis baru</span>
                        <h3 class="fresh-feature-title">{{ $featureNovel->title }}</h3>
                        <div class="fresh-feature-meta">
                            @if($featureLatestAgo)<span>{{ $featureLatestAgo }}</span>@endif
                            @if($featureLatestCh)<span>&middot; {{ $featureLatestCh->title }}</span>@endif
                        </div>
                    </div>
                </a>
            </div>
            @endif

            <div class="fresh-list">
                @foreach($recentlyUpdated->skip(1)->take(4) as $novel)
                @php
                    $coverUrl = $novel->cover_image_url ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
                    $latestCh = $novel->chapters->first();
                    $latestAgo = $latestCh ? \Illuminate\Support\Carbon::parse($latestCh->published_at ?? $latestCh->created_at)->diffForHumans(null, true) : null;
                @endphp
                <a href="{{ $latestCh ? route('chapters.show', [$novel->slug, $latestCh->slug]) : route('novels.show', $novel->slug) }}" class="fresh-item">
                    <div class="fresh-item-cover">@if($coverUrl)<img src="{{ $coverUrl }}" alt="{{ $novel->title }}" loading="lazy" onerror="this.src='/error.png'">@endif</div>
                    <div class="fresh-item-info">
                        <span class="fresh-item-tag">Update</span>
                        <span class="fresh-item-title">{{ $novel->title }}</span>
                        <div class="fresh-item-meta">
                            @if($latestAgo)<span>{{ $latestAgo }}</span>@endif
                            @if($latestCh)<span>&middot; {{ $latestCh->title }}</span>@endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
