@extends('layouts.other')
@section('content')
    <section>
        <div class="container">
            <h2>{{ $page->title }}</h2>
            <div class="ck-content">
                {!! $page->text !!}
            </div>
        </div>
    </section>
@endsection
