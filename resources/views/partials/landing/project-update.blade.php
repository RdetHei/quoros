@php
    $coverOf = function ($novel) {
        return $novel->cover_image_url
            ?: ($novel->cover_image ? asset('storage/' . $novel->cover_image) : null);
    };
@endphp

<section class="lp-section">
    <div class="lp-container">
        <div class="lp-split">
            <div class="lp-split-main">
                <div class="lp-panel">
                    <div class="lp-section-head">
                        <h2 class="lp-section-title">PROJECT UPDATE</h2>
                        <a href="{{ route('novels.updated') }}" class="lp-see-all">SEE ALL <span aria-hidden="true">&rarr;</span></a>
                    </div>

                    @if($projectUpdates->isNotEmpty())
                        <div class="lp-cover-grid lp-cover-grid--5">
                            @foreach($projectUpdates as $novel)
                                @php
                                    $cover = $coverOf($novel);
                                    $latest = $novel->chapters->first();
                                    $ago = $latest
                                        ? \Illuminate\Support\Carbon::parse($latest->published_at ?? $latest->created_at)->diffForHumans(null, true)
                                        : null;
                                    $chapterNo = $latest?->order;
                                    if ($latest && preg_match('/(\d+)/', $latest->title, $m)) {
                                        $chapterNo = $m[1];
                                    }
                                    $chapterLabel = $chapterNo ? 'CHAPTER '.$chapterNo : 'NEW';
                                @endphp
                                <a href="{{ route('novels.show', $novel->slug) }}" class="lp-cover-card">
                                    <div class="lp-cover-frame">
                                        @if($cover)
                                            <img src="{{ $cover }}" alt="{{ $novel->title }}" loading="lazy" onerror="this.src='/error.png'">
                                        @endif
                                    </div>
                                    <h3 class="lp-cover-title">{{ Str::limit($novel->title, 28) }}</h3>
                                    <p class="lp-cover-meta">
                                        <span class="lp-cover-meta-chapter">{{ $chapterLabel }}</span>
                                        @if($ago)
                                            <span class="lp-cover-meta-time">{{ strtoupper($ago) }}</span>
                                        @endif
                                    </p>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="lp-empty">Belum ada update project.</p>
                    @endif
                </div>
            </div>

            <aside class="lp-split-side">
                <div class="lp-side-block lp-side-panel">
                    <div class="lp-side-head">
                        <h2 class="lp-section-title">POPULAR GENRE</h2>
                        <a href="{{ route('genres.index') }}" class="lp-see-all-pill">SEE ALL</a>
                    </div>
                    <p class="lp-side-head-sub">We take the most popular genre lately</p>
                    <div class="lp-pill-cloud">
                        @forelse($popularGenres as $genre)
                            <a href="{{ route('novels.search', ['genre' => $genre->slug]) }}" class="lp-pill">{{ $genre->name }}</a>
                        @empty
                            <span class="lp-empty">No genres yet</span>
                        @endforelse
                    </div>
                </div>

                <div class="lp-side-block lp-side-panel">
                    <div class="lp-side-head">
                        <h2 class="lp-section-title">POPULAR TAG</h2>
                        <a href="{{ route('tags.index') }}" class="lp-see-all-pill">SEE ALL</a>
                    </div>
                    <p class="lp-side-head-sub">We take the most popular tags lately</p>
                    <div class="lp-pill-cloud">
                        @forelse($popularTags as $tag)
                            <a href="{{ route('novels.search', ['tag' => $tag->slug]) }}" class="lp-pill">{{ strtoupper($tag->name) }}</a>
                        @empty
                            <span class="lp-empty">No tags yet</span>
                        @endforelse
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
