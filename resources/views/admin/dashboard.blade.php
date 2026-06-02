@extends('layouts.admin')

@php
    $adminTitle = 'Admin Command Center';
    $adminBreadcrumbs = ['Admin', 'Command Center'];
@endphp

@section('content')
    @include('admin.partials.command-center')
@endsection

