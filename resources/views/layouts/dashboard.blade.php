@extends('layouts.dashboard-shell')

@section('content')
@php
    $dashboardTitle = $dashboardTitle ?? 'Dashboard';
    $dashboardSubtitle = $dashboardSubtitle ?? 'Overview';
    $dashboardBreadcrumbs = $dashboardBreadcrumbs ?? ['Dashboard'];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <aside class="lg:col-span-3 lg:sticky lg:top-8 self-start">
            @include('dashboard.partials.sidebar')
    </aside>

    <main class="lg:col-span-9 space-y-6">
        @include('dashboard.partials.topbar')
        @yield('dashboard-content')
    </main>
</div>
@endsection
