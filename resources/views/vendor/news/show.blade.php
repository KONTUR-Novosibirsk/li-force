@extends('layouts.other')
@section('content')
    <section>
        <div class="container">
            <h1>{{ $news->title }}</h1>
            <div class="ck-content">
                {!! $news->text !!}
            </div>
        </div>
    </section>
@endsection
