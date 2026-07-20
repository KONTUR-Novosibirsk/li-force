@extends('layouts.other')
@section('content')
    <section class="team">
        <div class="container">
            <h2>{{ $page->title }}</h2>
            <div class="team__items">
                @foreach(iblock()->getById(8)?->elements as $element)
                    <div class="team__item">
                        <div class="team__item-title">{{$element->title}}</div>
                        {!! $element->description !!}
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
