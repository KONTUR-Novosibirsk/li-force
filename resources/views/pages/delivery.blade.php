@extends('layouts.other')
@section('content')
    <section class="delivery">
        <div class="container">
            <h2>{{ $page->title }}</h2>
            <div class="delivery__content">
                {!! $page->text !!}
            </div>

            @if(iblock()->getById(9))
               <div class="faq">
                   <div class="faq__title">Часто задаваемые вопросы</div>
                   <div class="faq__items">
                       @foreach(iblock()->getById(9)?->elements as $element)
                           <div class="faq__item">
                               <div class="faq__item-ttl">
                                   {{$element->title}}
                                   <span>
                                       <svg width="32" height="24" viewBox="0 0 32 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M28.0508 6L17.0239 16.0506C16.3295 16.6835 15.9822 17 15.5508 17C15.1193 17 14.772 16.6835 14.0777 16.0506L3.05078 6" stroke="#44A300" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                       </svg>
                                   </span>
                               </div>
                               <div class="faq__item-desc">
                                   @if($element->image())
                                       <img src="{{$element->image()->getFull()}}" alt="">
                                   @endif
                                   {!! $element->description !!}
                               </div>
                           </div>
                       @endforeach
                   </div>
               </div>
            @endif
        </div>
    </section>
@endsection
