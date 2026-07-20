@extends('layouts.other')
@section('content')
    <div class="container">
        <h1>{{ $news->title }}</h1>
        <div class="ck-content">
            {!! $news->text !!}
        </div>
    </div>
@endsection
