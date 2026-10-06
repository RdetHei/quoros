@extends('layouts.dashboard-shell')

@section('content')
@php
    $active = $active ?? 'dashboard';
@endphp

<div>
    <x-writer.header
        :breadcrumbs="$breadcrumbs ?? ['Author Studio', 'Dashboard']"
        :title="$dashboardTitle ?? $title ?? 'Welcome back'"
        :subtitle="$dashboardSubtitle ?? $subtitle ?? null"
        :currentNovel="$currentNovel ?? 'The Glass Orchard'"
        :showCreateBtn="$showCreateBtn ?? true"
    />
    <div class="max-w-[1600px] mx-auto px-5 sm:px-8 lg:px-10 py-6 sm:py-8">
        @yield('dashboard-content')
    </div>
</div>
@endsection
