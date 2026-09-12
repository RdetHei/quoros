@extends('layouts.dashboard', [
    'title' => $title ?? 'Author Studio',
    'subtitle' => $subtitle ?? null,
])

@section('dashboard-content')
    @yield('content')
@endsection
