@extends('layouts.hiddenOther')
@section('content')
    <section class="rules">
        <div class="container">
            <div class="rules__content">
                <div class="rules-tabs">
                    <div class="rules-tabs__item" data-tab="rule1">{{iblock()->getById(3)?->title}}</div>
                    <div class="rules-tabs__item" data-tab="rule2">{{iblock()->getById(13)?->title}}</div>
                    <div class="rules-tabs__item" data-tab="rule3">{{iblock()->getById(14)?->title}}</div>
                </div>
                <h3 class="rules__content-title">Правовая информация</h3>
                <div class="rules__content-info">
                    <div class="rules-tabs__content" data-tab="rule1">
                        {!! iblock()->getById(3)?->description !!}
                    </div>
                    <div class="rules-tabs__content" data-tab="rule2">
                        {!! iblock()->getById(13)?->description !!}
                    </div>
                    <div class="rules-tabs__content" data-tab="rule3">
                        {!! iblock()->getById(14)?->description !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
