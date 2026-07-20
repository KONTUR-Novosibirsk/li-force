@extends('layouts.hiddenOther')
@section('content')
    <div class="container">
        <section class="about-banner">
            @if(iblock()->getById(4)?->elements[0]->image())
                <div class="about-banner__img">
                    <img src=" {{iblock()->getById(4)?->elements[0]->image()->getFull()}}" alt="{{iblock()->getById(4)?->title}}">
                </div>
            @endif
            <div class="about-banner_content">
                <div class="about-banner_content__text">
                    {!! iblock()->getById(4)?->description !!}
                </div>
                <a href="/page/konfigurator" class="btn">Изучить наш конфигуратор</a>
            </div>
        </section>
        <section class="progress">
            <div class="range">
                <div class="range__content">
                    <div class="range__content-top">
                        <span>{{iblock()->getById(6)?->title}}</span>
                        {!! iblock()->getById(6)?->description !!}
                    </div>

                    <div class="range__content-bottom">
                        {!! iblock()->getById(6)?->elements[0]?->description !!}
                    </div>
                </div>
                <div class="range__img">
                    <img src="{{iblock()->getById(6)?->elements[0]?->image()?->getFull()}}" alt="">
                </div>
            </div>
            <div class="progress__content">
                <div class="progress__list">
                    {!! iblock()->getById(5)?->description !!}
                </div>
                <div class="progress__logos">
                    @foreach(iblock()->getById(5)?->elements as $element)
                        <div class="progress__logos-item">
                            <img src="{{$element->image()?->getFull()}}" alt="">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="works">
            <h2>{{iblock()->getById(7)?->title}}</h2>

            <div class="works__swiper swiper">
                <div class="swiper-wrapper">
                    @foreach(iblock()->getById(7)?->elements as $element)
                        <div class="swiper-slide works__item">
                            <div class="works__item-img">
                                <img src="{{$element->image()?->getFull()}}" alt="">
                            </div>
                            <div class="works__item-text">
                                {!! $element->description !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </section>
    </div>
@endsection
