@extends('layouts.hiddenOther')
@section('content')
    <section class="rules">
        <div class="container">
            <div class="rules__content">
                <div class="rules-tabs">
                    <div class="rules-tabs__item" data-tab="rule1">{{iblock()->getById(15)?->title}}</div>
                    <div class="rules-tabs__item" data-tab="rule2">{{iblock()->getById(16)?->title}}</div>
                    <div class="rules-tabs__item" data-tab="rule3">{{iblock()->getById(9)?->title}}</div>
                    <div class="rules-tabs__item" data-tab="rule4">{{iblock()->getById(17)?->title}}</div>
                </div>

                <div class="rules-heading">
                    <h3 class="rules__content-title" data-tab="rule1">{{iblock()->getById(15)?->title}}</h3>
                    <h3 class="rules__content-title" data-tab="rule2">{{iblock()->getById(16)?->title}}</h3>
                    <h3 class="rules__content-title" data-tab="rule3">{{iblock()->getById(9)?->title}}</h3>
                    <h3 class="rules__content-title" data-tab="rule4">{{iblock()->getById(17)?->title}}</h3>
                </div>

                <div class="rules__content-info">
                    <div class="rules-tabs__content" data-tab="rule1">
                        <h3>{{iblock()->getById(15)?->elements[0]?->title}}</h3>
                        <span>Доступные службы доставки:</span>
                        <div class="delivery-imgs">
                            @foreach(iblock()->getById(15)?->elements as $element)
                                <div class="delivery-imgs__item">
                                    <img src="{{$element->image()?->getFull()}}" alt="{{$element->title}}">
                                </div>
                            @endforeach
                        </div>
                        <div class="delivery-list">
                            {!! iblock()->getById(15)?->elements[0]?->description !!}

                            {!! iblock()->getById(15)?->description !!}
                        </div>

                    </div>
                    <div class="rules-tabs__content" data-tab="rule2">
                        <div class="rules-acc">
                            @foreach(iblock()->getById(16)?->elements as $element)
                                <div class="rules-acc__item">
                                    <h3 class="rules-acc__title">
                                        {{$element->title}}

                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.0861 3.5H11.0607C10.991 3.5 10.9254 3.53418 10.8844 3.59023L7.00017 8.94414L3.11599 3.59023C3.07498 3.53418 3.00935 3.5 2.93963 3.5H1.91424C1.82537 3.5 1.77342 3.60117 1.82537 3.67363L6.64607 10.3195C6.82107 10.5602 7.17927 10.5602 7.35291 10.3195L12.1736 3.67363C12.2269 3.60117 12.175 3.5 12.0861 3.5Z" fill="black" fill-opacity="0.88"/>
                                        </svg>
                                    </h3>
                                    <div class="rules-acc__content">
                                        {!! $element->description !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>

                       <div style="margin-top: 30px">
                           {!! iblock()->getById(16)?->description !!}
                           <div class="rules-contacts">
                               <div class="rules-contacts__item">
                                   <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                       <g clip-path="url(#clip0_8603_19995)">
                                           <path d="M24.8867 12.4961L22.8066 10.418C22.6599 10.2706 22.4855 10.1537 22.2934 10.0739C22.1014 9.99412 21.8955 9.95308 21.6875 9.95313C21.2637 9.95313 20.8652 10.1191 20.5664 10.418L18.3281 12.6562C18.1807 12.803 18.0638 12.9774 17.984 13.1694C17.9043 13.3615 17.8632 13.5674 17.8633 13.7754C17.8633 14.1992 18.0293 14.5977 18.3281 14.8965L19.9648 16.5332C19.5817 17.3777 19.0491 18.1459 18.3926 18.8008C17.7377 19.4589 16.9696 19.9935 16.125 20.3789L14.4883 18.7422C14.3416 18.5948 14.1671 18.4779 13.9751 18.3981C13.783 18.3183 13.5771 18.2773 13.3691 18.2773C12.9453 18.2773 12.5469 18.4434 12.248 18.7422L10.0078 20.9785C9.86026 21.1255 9.74322 21.3002 9.66345 21.4926C9.58367 21.685 9.54273 21.8913 9.54297 22.0996C9.54297 22.5234 9.70899 22.9219 10.0078 23.2207L12.084 25.2969C12.5605 25.7754 13.2188 26.0469 13.8945 26.0469C14.0371 26.0469 14.1738 26.0352 14.3086 26.0117C16.9414 25.5781 19.5527 24.1777 21.6602 22.0723C23.7656 19.9688 25.1641 17.3594 25.6035 14.7188C25.7363 13.9121 25.4687 13.082 24.8867 12.4961Z" fill="black" fill-opacity="0.45"/>
                                           <path d="M18 35.5C27.665 35.5 35.5 27.665 35.5 18C35.5 8.33502 27.665 0.5 18 0.5C8.33502 0.5 0.5 8.33502 0.5 18C0.5 27.665 8.33502 35.5 18 35.5Z" stroke="#C2C2C2"/>
                                       </g>
                                       <defs>
                                           <clipPath id="clip0_8603_19995">
                                               <rect width="36" height="36" fill="white"/>
                                           </clipPath>
                                       </defs>
                                   </svg>

                                   <div>
                                       <a href="tel:{{settings('phone')}}" class="phone">{{settings('phone')}}</a>
                                       <a href="tel:{{settings('phone2')}}" class="phone">{{settings('phone2')}}</a>
                                   </div>
                               </div>
                               <div class="rules-contacts__item">
                                   <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                       <g clip-path="url(#clip0_8603_20001)">
                                           <path d="M25.7148 11.125H9.46484C9.11914 11.125 8.83984 11.4043 8.83984 11.75V24.25C8.83984 24.5957 9.11914 24.875 9.46484 24.875H25.7148C26.0605 24.875 26.3398 24.5957 26.3398 24.25V11.75C26.3398 11.4043 26.0605 11.125 25.7148 11.125ZM24.1367 13.252L17.9746 18.0469C17.8223 18.166 17.6094 18.166 17.457 18.0469L11.293 13.252C11.2697 13.234 11.2527 13.2093 11.2442 13.1812C11.2358 13.1531 11.2363 13.123 11.2458 13.0952C11.2553 13.0674 11.2732 13.0433 11.2971 13.0263C11.3209 13.0092 11.3496 13 11.3789 13H24.0508C24.0801 13 24.1087 13.0092 24.1326 13.0263C24.1565 13.0433 24.1744 13.0674 24.1839 13.0952C24.1934 13.123 24.1939 13.1531 24.1855 13.1812C24.177 13.2093 24.16 13.234 24.1367 13.252Z" fill="black" fill-opacity="0.45"/>
                                           <path d="M18 35.5C27.665 35.5 35.5 27.665 35.5 18C35.5 8.33502 27.665 0.5 18 0.5C8.33502 0.5 0.5 8.33502 0.5 18C0.5 27.665 8.33502 35.5 18 35.5Z" stroke="#C2C2C2"/>
                                       </g>
                                       <defs>
                                           <clipPath id="clip0_8603_20001">
                                               <rect width="36" height="36" fill="white"/>
                                           </clipPath>
                                       </defs>
                                   </svg>
                                   <a href="mailto:{{settings('emailPublic')}}">{{settings('emailPublic')}}</a>
                               </div>
                           </div>
                       </div>
                    </div>
                    <div class="rules-tabs__content" data-tab="rule3">
                        <div class="rules-acc">
                            @foreach(iblock()->getById(9)?->elements as $element)
                                <div class="rules-acc__item">
                                    <h3 class="rules-acc__title">
                                        {{$element->title}}

                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12.0861 3.5H11.0607C10.991 3.5 10.9254 3.53418 10.8844 3.59023L7.00017 8.94414L3.11599 3.59023C3.07498 3.53418 3.00935 3.5 2.93963 3.5H1.91424C1.82537 3.5 1.77342 3.60117 1.82537 3.67363L6.64607 10.3195C6.82107 10.5602 7.17927 10.5602 7.35291 10.3195L12.1736 3.67363C12.2269 3.60117 12.175 3.5 12.0861 3.5Z" fill="black" fill-opacity="0.88"/>
                                        </svg>
                                    </h3>
                                    <div class="rules-acc__content">
                                        {!! $element->description !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="rules-tabs__content" data-tab="rule4">
                        {!! iblock()->getById(17)?->description !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

