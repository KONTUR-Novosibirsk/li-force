@extends('layouts.app')
@section('app.content')
    <div class="container hidden">
        <x-laravel-seo::breadcrumbs/>
    </div>
    @yield('content')
@endsection
