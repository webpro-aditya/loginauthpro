@extends('admin.layouts.dashboard')

@section('page_title', __('Admin Dashboard Home'))

@section('dashboard')
<div class="wrapper">
    @include('admin.pages.sidebar')
    <div class="main">
        @include('admin.pages.header')
        @yield('content')
        @include('admin.pages.footer')
    </div>
</div>
@endsection