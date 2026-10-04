@extends('layouts.app')

@section('content')
@php
    $featuredCarouselData = $featuredNovels->map(function ($n) {
        $firstChapter = $n->chapters->first();
        $cover = $n->cover_image_url ?: ($n->cover_image ? asset('storage/' . $n->cover_image) : null);

        return [
            'id'          => $n->id,
            'slug'        => $n->slug,
            'url'         => route('novels.show', $n->slug),
            'read_url'    => $firstChapter
                ? route('chapters.show', [$n->slug, $firstChapter->route_identifier])
                : route('novels.show', $n->slug),
            'title'       => $n->title,
            'author'      => $n->author->name ?? 'Unknown',
            'description' => Str::limit(strip_tags($n->description ?? ''), 180),
            'genre'       => $n->genres->first()?->name,
            'cover'       => $cover,
        ];
    })->values();
@endphp

<div class="landing-page">
    @include('partials.landing.hero', ['featuredCarouselData' => $featuredCarouselData])

    {{-- =========================================================
         Author Marathon / Event Banner — 2 CARA PAKAI:
         1) DEFAULT (dari controller $marathonNovel) — biarkan seperti ini:
            @include('partials.landing.author-marathon')

         2) EVENT CUSTOM (foto + link + judul sendiri) — hapus komentar dan edit dibawah:

            @include('partials.landing.author-marathon', [
                'title'      => 'HALLOWEEN SPECIAL EVENT',
                'cover_url'  => asset('storage/banners/halloween-2026.jpg'),
                'link_url'   => route('events.show', 'halloween-2026'),  // atau URL penuh 'https://...'
                'link_label' => 'JOIN NOW',  // opsional — text di kanan title
            ])
         ========================================================= --}}
    @include('partials.landing.author-marathon')

    @include('partials.landing.project-update')
    @include('partials.landing.hot-takes')
    @include('partials.landing.monthly-viewed')
    @include('partials.landing.for-you')
</div>
@endsection
