@if($hotTake)
@php
    $cover = $hotTake->cover_image_url
        ?: ($hotTake->cover_image ? asset('storage/' . $hotTake->cover_image) : null);
    $firstChapter = $hotTake->chapters->sortBy('order')->first()
        ?? $hotTake->chapters->first();
    $readUrl = $firstChapter
        ? route('chapters.show', [$hotTake->slug, $firstChapter->slug])
        : route('novels.show', $hotTake->slug);
    $genre = $hotTake->genres->first()?->name;

    $catchphrase = $hotTake->catchphrase ?? $hotTake->tagline ?? null;
    if (!$catchphrase) {
        $descPlain = trim(strip_tags($hotTake->description ?? ''));
        $sentences = preg_split('/(?<=[.!?])\s+/', $descPlain, -1, PREG_SPLIT_NO_EMPTY);
        $first = $sentences[0] ?? $descPlain;
        $catchphrase = Str::limit($first, 90);
    }
@endphp

<section class="lp-section" style="padding-top: 1.65rem;">
    <div class="lp-container">
        <div class="lp-hot-takes">
            <div class="lp-hot-takes-bg" @if($cover) style="background-image: url('{{ $cover }}');" @endif></div>
            <div class="lp-hot-takes-shade"></div>
            <div class="lp-hot-takes-inner">
                <a href="{{ route('novels.show', $hotTake->slug) }}" class="lp-hot-cover">
                    @if($cover)
                        <img src="{{ $cover }}" alt="{{ $hotTake->title }}" loading="lazy" onerror="this.src='/error.png'">
                    @endif
                </a>
                <div class="lp-hot-copy">
                    <div class="lp-hot-labels">
                        <span class="lp-badge">HOT TAKES</span>
                        @if($genre)
                            <span class="lp-badge lp-badge--muted">{{ strtoupper($genre) }}</span>
                        @endif
                    </div>
                    <div class="lp-hot-title-wrap">
                        <h2 class="lp-hot-title">{{ $hotTake->title }}</h2>
                    </div>
                    <p class="lp-hot-tagline">{{ $catchphrase }}</p>
                    <a href="{{ $readUrl }}" class="lp-btn-gold lp-btn-gold--sm">
                        START READING <span aria-hidden="true">+</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
