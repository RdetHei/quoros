@php
    $coverOf = function ($novel) {
        return $novel->cover_image_url
            ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
    };
@endphp

@if($forYou->isNotEmpty())
<section class="lp-section lp-section--for-you">
    <div class="lp-container">
        <div class="lp-section-head">
            <h2 class="lp-section-title">FOR YOU ONLY</h2>
        </div>

        <div class="lp-for-you-grid">
            @foreach($forYou as $novel)
                @php
                    $cover = $coverOf($novel);
                    $recentChapters = $novel->chapters->take(3);
                @endphp
                <article class="lp-for-you-card">
                    <a href="{{ route('novels.show', $novel->slug) }}" class="lp-for-you-cover">
                        @if($cover)
                            <img src="{{ $cover }}" alt="{{ $novel->title }}" loading="lazy" onerror="this.src='/error.png'">
                        @endif
                    </a>
                    <div class="lp-for-you-body">
                        <div class="lp-for-you-title-wrap">
                            <a href="{{ route('novels.show', $novel->slug) }}" class="lp-for-you-title">{{ $novel->title }}</a>
                        </div>
                        <p class="lp-for-you-desc">{{ Str::limit(strip_tags($novel->description ?? ''), 140) }}</p>
                        <ul class="lp-chapter-list">
                            @forelse($recentChapters as $chapter)
                                @php
                                    $ago = \Illuminate\Support\Carbon::parse($chapter->published_at ?? $chapter->created_at)->diffForHumans(null, true);
                                    $chapterNo = $chapter->order;
                                    if (preg_match('/(\d+)/', $chapter->title, $m)) {
                                        $chapterNo = $m[1];
                                    }
                                    $label = 'CHAPTER '.$chapterNo;
                                @endphp
                                <li>
                                    <a href="{{ route('chapters.show', [$novel->slug, $chapter->slug]) }}">
                                        <span>{{ $label }}</span>
                                        <span class="lp-chapter-ago">{{ strtoupper($ago) }}</span>
                                    </a>
                                </li>
                            @empty
                                <li class="lp-empty">No chapters yet</li>
                            @endforelse
                        </ul>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="lp-see-more-wrap">
            <a href="{{ route('novels.updated') }}" class="lp-see-more">SEE MORE</a>
        </div>
    </div>
</section>
@endif
