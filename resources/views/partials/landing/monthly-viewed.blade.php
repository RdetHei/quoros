@php
    $coverOf = function ($novel) {
        return $novel->cover_image_url
            ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
    };
@endphp

@if($monthlyViewed->isNotEmpty())
<section class="lp-section">
    <div class="lp-container">
        <div class="lp-panel">
            <div class="lp-section-head">
                <h2 class="lp-section-title">VIEWED NOVEL IN THE MONTH</h2>
                <a href="{{ route('novels.trending') }}" class="lp-see-all">SEE ALL <span aria-hidden="true">&rarr;</span></a>
            </div>

            <div class="lp-cover-grid lp-cover-grid--7">
                @foreach($monthlyViewed as $novel)
                    @php
                        $cover = $coverOf($novel);
                        $latest = $novel->chapters->first() ?? null;
                        $ago = $latest
                            ? \Illuminate\Support\Carbon::parse($latest->published_at ?? $latest->created_at)->diffForHumans(null, true)
                            : null;
                        $chapterNo = $latest?->order ?? $novel->chapters_count;
                        if ($latest && preg_match('/(\d+)/', $latest->title, $m)) {
                            $chapterNo = $m[1];
                        }
                        $chapterLabel = $chapterNo ? 'CHAPTER '.$chapterNo : 'TRENDING';
                    @endphp
                    <a href="{{ route('novels.show', $novel->slug) }}" class="lp-cover-card">
                        <div class="lp-cover-frame">
                            @if($cover)
                                <img src="{{ $cover }}" alt="{{ $novel->title }}" loading="lazy" onerror="this.src='/error.png'">
                            @endif
                        </div>
                        <h3 class="lp-cover-title">{{ Str::limit($novel->title, 26) }}</h3>
                        <p class="lp-cover-meta">
                            <span class="lp-cover-meta-chapter">{{ $chapterLabel }}</span>
                            @if($ago)
                                <span class="lp-cover-meta-time">{{ strtoupper($ago) }}</span>
                            @endif
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
