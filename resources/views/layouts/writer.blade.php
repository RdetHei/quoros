@extends('layouts.dashboard', [
    'title' => $title ?? 'Author Studio',
    'subtitle' => $subtitle ?? null,
    'active' => $active ?? 'dashboard',
    'breadcrumbs' => $breadcrumbs ?? null,
    'dashboardTitle' => $dashboardTitle ?? null,
    'dashboardSubtitle' => $dashboardSubtitle ?? null,
    'currentNovel' => $currentNovel ?? null,
    'showCreateBtn' => $showCreateBtn ?? true,
])

@section('dashboard-content')
    @yield('content')
@endsection
