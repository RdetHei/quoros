@extends('layouts.dashboard-shell')

@section('content')
@php
    $dashboardTitle = $dashboardTitle ?? $title ?? 'Dashboard';
    $dashboardSubtitle = $dashboardSubtitle ?? $subtitle ?? 'Overview';
    $dashboardBreadcrumbs = $dashboardBreadcrumbs ?? ['Dashboard'];
@endphp

<div class="space-y-6">
    <x-writer.header :title="$dashboardTitle" :subtitle="$dashboardSubtitle" />
    @yield('dashboard-content')
</div>
@endsection
