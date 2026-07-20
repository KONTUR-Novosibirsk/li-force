@extends('layouts.hiddenOther')
@section('content')
    <section class="cart">
        <div class="vue_app">
            <cart-component :user="{{$user}}" :products="{{ $products->toJson() }}" :points='@json($points)'
                            privacy-policy-link="{{ route('page.show', settings('personality_page', default: 1)) }}"></cart-component>
        </div>
    </section>
    <x-shop::hit-products limit="1000000"/>
@endsection

