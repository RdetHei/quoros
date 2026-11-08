@extends('layouts.app')

@section('content')
@push('preload')
    @if(!empty($heroImage))
        <link rel="preload" as="image" href="{{ $heroImage }}" fetchpriority="high">
    @endif
@endpush

<div class="landing-page">
    @if(!empty($landingHtml))
        {!! $landingHtml !!}
    @else
        @include('partials.landing.body')
    @endif
</div>
@endsection
