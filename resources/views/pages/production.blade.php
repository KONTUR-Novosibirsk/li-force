@extends('layouts.other')
@section('content')
    <section class="production">
        <div class="container">
            <h2>{{ $page->title }}</h2>
            <div class="production__content">
                {!! $page->text !!}
            </div>
            <div class="production__list">
                <div class="production__item">
                    <div class="production__item-video">
                        <div class="video-play-btn">
                            <svg  data-fancybox="" data-src="/videos/video.mp4" data-caption="Обзор всех штепсельных разъемов" width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg" >
                                <circle cx="28" cy="28" r="28" fill="#44A300"/>
                                <path d="M39.5 27.134C40.1667 27.5189 40.1667 28.4811 39.5 28.866L23 38.3923C22.3333 38.7772 21.5 38.2961 21.5 37.5263L21.5 18.4737C21.5 17.7039 22.3333 17.2228 23 17.6077L39.5 27.134Z" fill="white"/>
                            </svg>
                        </div>
                        <video src="/videos/video.mp4" type="video/mp4" poster="/images/poster.jpg"></video>
                    </div>
                    <div class="production__item-title"><span>Обзор всех штепсельных разъемов</span></div>
                </div>
                @foreach(iblock()->getById(11)?->elements as $element)
                    <div class="production__item">
                        <div class="production__item-img" data-fancybox="" data-src="{{$element->image()->getFull()}}" data-caption="{{$element->title}}">
                            <img src="{{$element->image()->getFull()}}" alt="">
                        </div>
                        <div class="production__item-title"><span>{{$element->title}}</span></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
