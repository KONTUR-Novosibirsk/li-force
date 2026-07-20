@extends('layouts.other')
@section('content')
    <div class="container">
        <h1>{{ $service->name }}</h1>
        @if($service->price)
            <div class="">
                <span>Стоимость:</span> {{ $service->price }}
            </div>
        @endif
        <div class="ck-content">
            {!! $service->description !!}
        </div>
    </div>
@endsection
