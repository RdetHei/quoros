@php
    // =========================================================
    //  Marathon / Event banner — KONFIGURASI FLEKSIBEL
    //
    //  CARA MENGGANTI FOTO, JUDUL, LINK:
    //  ---------------------------------------------------------
    //  OPSI 1 (cepat, tanpa ubah controller):
    //    UBAH params di file welcome.blade.php (line 27):
    //    @include('partials.landing.author-marathon', [
    //        'title'     => 'EVENT BARU KAMU',
    //        'cover_url' => asset('storage/foto-event-baru.jpg'),
    //        'link_url'  => 'https://link-event-kamu.com',
    //        'link_label'=> 'LIHAT EVENT',   // opsional, default >
    //    ])
    //
    //  OPSI 2 (via controller, untuk data dinamis):
    //    Ubah $marathonNovel / $marathonEvent di NovelController line 117
    //    (atau kirim variable ke view dari controller)
    //
    //  OPSI 3 (tetap pakai novel marathon):
    //    Biarkan seperti ini — banner otomatis pakai $marathonNovel
    // =========================================================

    // Priority: 1) explicit params, 2) $marathonEvent, 3) $marathonNovel, 4) default
    $customTitle = $title ?? null;
    $customCover = $cover_url ?? null;
    $customLink  = $link_url ?? null;
    $customLinkLabel = $link_label ?? null;

    $hasNovel = ($marathonNovel ?? null) !== null;
    $hasEvent = ($marathonEvent ?? null) !== null;

    // COVER IMAGE
    if ($customCover) {
        $marathonCover = $customCover;
    } elseif ($hasEvent && !empty($marathonEvent->cover)) {
        $marathonCover = $marathonEvent->cover;
    } elseif ($hasNovel) {
        $marathonCover = $marathonNovel->cover_image_url
            ?: ($marathonNovel->cover_image ? asset('storage/' . $marathonNovel->cover_image) : null);
    } else {
        $marathonCover = null;
    }

    // TITLE
    if ($customTitle) {
        $marathonTitle = strtoupper($customTitle);
    } elseif ($hasEvent && !empty($marathonEvent->title)) {
        $marathonTitle = strtoupper($marathonEvent->title);
    } else {
        $marathonTitle = 'AUTHOR MARATHON';
    }

    // LINK HREF
    if ($customLink) {
        $marathonLink = $customLink;
    } elseif ($hasEvent && !empty($marathonEvent->link)) {
        $marathonLink = $marathonEvent->link;
    } elseif ($hasNovel) {
        $marathonLink = route('novels.show', $marathonNovel->slug);
    } else {
        $marathonLink = null;
    }

    // LINK LABEL (opsional — jika ingin tombol / link text di sebelah kanan)
    if ($customLinkLabel) {
        $marathonLinkLabel = $customLinkLabel;
    } elseif ($hasEvent && !empty($marathonEvent->link_label)) {
        $marathonLinkLabel = $marathonEvent->link_label;
    } else {
        $marathonLinkLabel = null; // default: hanya tampilkan title saja (center)
    }

    $isClickable = filled($marathonLink);
@endphp

@if($isClickable)
<a href="{{ $marathonLink }}" class="lp-marathon" @if(str_starts_with($marathonLink, 'http') && !str_starts_with($marathonLink, request()->getSchemeAndHttpHost())) target="_blank" rel="noopener" @endif>
@else
<section class="lp-marathon">
@endif
    <div class="lp-marathon-bg" @if($marathonCover) style="background-image: url('{{ $marathonCover }}');" @endif></div>
    <div class="lp-marathon-shade"></div>
    <div class="lp-marathon-inner">
        <h2 class="lp-marathon-title">{{ $marathonTitle }}</h2>
        @if(filled($marathonLinkLabel))
            <span class="lp-marathon-link">
                {{ $marathonLinkLabel }}
                @svg('heroicon-s-arrow-right', ['style' => 'width:0.9em;height:0.9em;flex-shrink:0;'])
            </span>
        @endif
    </div>
@if($isClickable)
</a>
@else
</section>
@endif
