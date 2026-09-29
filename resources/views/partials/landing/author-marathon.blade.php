@php
    $marathonCover = null;
    if ($marathonNovel ?? null) {
        $marathonCover = $marathonNovel->cover_image_url
            ?: ($marathonNovel->cover_image ? asset('storage/' . $marathonNovel->cover_image) : null);
    }
@endphp

@if($marathonNovel ?? null)
<a href="{{ route('novels.show', $marathonNovel->slug) }}" class="lp-marathon">
@else
<section class="lp-marathon">
@endif
    <div class="lp-marathon-bg" @if($marathonCover) style="background-image: url('{{ $marathonCover }}');" @endif></div>
    <div class="lp-marathon-shade"></div>
    <div class="lp-marathon-inner">
        <h2 class="lp-marathon-title">AUTHOR MARATHON</h2>
    </div>
@if($marathonNovel ?? null)
</a>
@else
</section>
@endif
