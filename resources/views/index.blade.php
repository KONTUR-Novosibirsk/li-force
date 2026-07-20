@extends('layouts.app')
@section('app.content')
    @module('slider')
    <section class="main-slider">
        <x-slider::slider-component code="main"/>
    </section>
    @endmodule
    @module('shop')
    <x-shop::categories-on-index limit="100000"/>
    @endmodule
    <section class="about">
        <x-shop::categories-on-index-about />
    </section>
    <section class="feedback">
        <div class="container feedback_container">
            <div class="vue_app feedback-form">
                <feedback-form-component
                    privacy-policy-link="{{ route('page.show', settings('policy_page', default: 1)) }}" personality-link="{{route('page.show', settings('personality_page', default: 1)) }}"></feedback-form-component>
            </div>
            <div class="feedback_right">
                <form class="feedback-config">
                    <div class="feedback-config__heading">Или воспользуйтесь конфигуратором</div>

                    <div class="config-select-container" id="configSelect1">
                        <div class="config-select-trigger">Конфигуратор батареи</div>
                        <div class="config-select-dropdown">
                            <div class="config-select-option" data-value="1">Опция 1</div>
                            <div class="config-select-option" data-value="2">Опция 2</div>
                            <div class="config-select-option" data-value="3">Опция 3</div>
                            <div class="config-select-option" data-value="4">Опция 4</div>
                            <div class="config-select-option" data-value="5">Опция 5</div>
                        </div>
                    </div>

                    <!-- Скрытый нативный select для отправки формы -->
                    <select id="nativeSelect1" class="hidden-native-select">
                        <option value="1">Опция 1</option>
                        <option value="2">Опция 2</option>
                        <option value="3">Опция 3</option>
                        <option value="4">Опция 4</option>
                        <option value="5">Опция 5</option>
                    </select>

                    <div class="feedback-config__fields">
                       <div class="feedback-config__field">
                           <label for="voltage">
                               Напряжение, В
                               <span title="lorem10">
                                <svg  width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_1937_6579)">
                                    <g clip-path="url(#clip1_1937_6579)">
                                    <g clip-path="url(#clip2_1937_6579)">
                                    <path d="M8.04372 0C12.5027 0 16.0874 3.5847 16.0874 8.04372C16.0874 12.5027 12.5027 16.0874 8.04372 16.0874C3.5847 16.0874 0 12.4153 0 8.04372C0 3.67213 3.5847 0 8.04372 0ZM8.04372 1.57377C6.38251 1.57377 4.72131 2.27322 3.49727 3.40984C2.27322 4.63388 1.6612 6.20765 1.6612 7.95629C1.6612 9.70492 2.36066 11.2787 3.49727 12.5027C4.72131 13.7268 6.29508 14.3388 8.04372 14.3388C9.79235 14.3388 11.3661 13.6393 12.5902 12.5027C13.8142 11.2787 14.4262 9.70492 14.4262 7.95629C14.4262 6.20765 13.7268 4.63388 12.5902 3.40984C11.3661 2.18579 9.79235 1.57377 8.04372 1.57377Z" fill="#8A8A8A"/>
                                    <path d="M7.34428 9.44259C7.34428 8.918 7.34428 8.56827 7.43171 8.30597C7.43171 8.04368 7.60657 7.86882 7.78144 7.69395C7.9563 7.51909 8.13116 7.43166 8.30603 7.25679C8.48089 7.16936 8.56832 7.08193 8.74319 6.90707C8.83062 6.81964 8.91805 6.64477 9.00548 6.55734C9.00548 6.38248 9.09291 6.29505 9.09291 6.12018C9.09291 5.94532 9.09291 5.77046 9.00548 5.68302C9.00548 5.59559 8.83062 5.42073 8.65575 5.42073C8.48089 5.42073 8.39346 5.3333 8.2186 5.3333C8.04373 5.3333 7.86887 5.3333 7.78144 5.42073C7.60657 5.42073 7.51914 5.59559 7.43171 5.77046C7.34428 5.94532 7.25685 6.12018 7.25685 6.29505H5.77051C5.77051 5.77046 5.94537 5.42073 6.12023 5.071C6.38253 4.72128 6.64482 4.54641 6.99455 4.37155C7.34428 4.19668 7.78144 4.10925 8.2186 4.10925C8.65575 4.10925 9.18034 4.10925 9.53007 4.37155C9.8798 4.54641 10.2295 4.80871 10.4044 5.071C10.5793 5.42073 10.7541 5.77046 10.7541 6.20761C10.7541 6.64477 10.7541 6.7322 10.5793 6.9945C10.4918 7.25679 10.317 7.43166 10.1421 7.60652C9.96723 7.78138 9.79237 7.95625 9.53007 8.04368C9.35521 8.13111 9.18034 8.30597 9.00548 8.39341C8.91805 8.48084 8.83062 8.6557 8.74319 8.83056C8.74319 9.00543 8.65575 9.18029 8.65575 9.44259C8.65575 9.53002 7.25685 9.53002 7.25685 9.53002L7.34428 9.44259ZM8.04373 11.8032C7.78144 11.8032 7.60657 11.8032 7.43171 11.5409C7.25685 11.3661 7.16941 11.1912 7.16941 10.9289C7.16941 10.6666 7.16941 10.4918 7.43171 10.3169C7.60657 10.142 7.78144 10.0546 8.04373 10.0546C8.30603 10.0546 8.48089 10.0546 8.65575 10.3169C8.83062 10.4918 8.91805 10.6666 8.91805 10.9289C8.91805 11.1038 8.91805 11.2787 8.83062 11.3661C8.83062 11.5409 8.65575 11.6284 8.48089 11.7158C8.30603 11.7158 8.2186 11.8032 8.04373 11.8032Z" fill="#8A8A8A"/>
                                    </g>
                                    </g>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_1937_6579">
                                    <rect width="16" height="16" fill="white"/>
                                    </clipPath>
                                    <clipPath id="clip1_1937_6579">
                                    <rect width="16" height="16" fill="white"/>
                                    </clipPath>
                                    <clipPath id="clip2_1937_6579">
                                    <rect width="16" height="16" fill="white"/>
                                    </clipPath>
                                    </defs>
                                </svg>
                            </span>
                           </label>
                           <input type="number" id="voltage" min="1" max="100" value="3">
                       </div>
                        <div class="feedback-config__field">
                            <label for="capacity">
                                Емкость, А*ч
                                <span title="lorem10">
                                <svg  width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_1937_6579)">
                                    <g clip-path="url(#clip1_1937_6579)">
                                    <g clip-path="url(#clip2_1937_6579)">
                                    <path d="M8.04372 0C12.5027 0 16.0874 3.5847 16.0874 8.04372C16.0874 12.5027 12.5027 16.0874 8.04372 16.0874C3.5847 16.0874 0 12.4153 0 8.04372C0 3.67213 3.5847 0 8.04372 0ZM8.04372 1.57377C6.38251 1.57377 4.72131 2.27322 3.49727 3.40984C2.27322 4.63388 1.6612 6.20765 1.6612 7.95629C1.6612 9.70492 2.36066 11.2787 3.49727 12.5027C4.72131 13.7268 6.29508 14.3388 8.04372 14.3388C9.79235 14.3388 11.3661 13.6393 12.5902 12.5027C13.8142 11.2787 14.4262 9.70492 14.4262 7.95629C14.4262 6.20765 13.7268 4.63388 12.5902 3.40984C11.3661 2.18579 9.79235 1.57377 8.04372 1.57377Z" fill="#8A8A8A"/>
                                    <path d="M7.34428 9.44259C7.34428 8.918 7.34428 8.56827 7.43171 8.30597C7.43171 8.04368 7.60657 7.86882 7.78144 7.69395C7.9563 7.51909 8.13116 7.43166 8.30603 7.25679C8.48089 7.16936 8.56832 7.08193 8.74319 6.90707C8.83062 6.81964 8.91805 6.64477 9.00548 6.55734C9.00548 6.38248 9.09291 6.29505 9.09291 6.12018C9.09291 5.94532 9.09291 5.77046 9.00548 5.68302C9.00548 5.59559 8.83062 5.42073 8.65575 5.42073C8.48089 5.42073 8.39346 5.3333 8.2186 5.3333C8.04373 5.3333 7.86887 5.3333 7.78144 5.42073C7.60657 5.42073 7.51914 5.59559 7.43171 5.77046C7.34428 5.94532 7.25685 6.12018 7.25685 6.29505H5.77051C5.77051 5.77046 5.94537 5.42073 6.12023 5.071C6.38253 4.72128 6.64482 4.54641 6.99455 4.37155C7.34428 4.19668 7.78144 4.10925 8.2186 4.10925C8.65575 4.10925 9.18034 4.10925 9.53007 4.37155C9.8798 4.54641 10.2295 4.80871 10.4044 5.071C10.5793 5.42073 10.7541 5.77046 10.7541 6.20761C10.7541 6.64477 10.7541 6.7322 10.5793 6.9945C10.4918 7.25679 10.317 7.43166 10.1421 7.60652C9.96723 7.78138 9.79237 7.95625 9.53007 8.04368C9.35521 8.13111 9.18034 8.30597 9.00548 8.39341C8.91805 8.48084 8.83062 8.6557 8.74319 8.83056C8.74319 9.00543 8.65575 9.18029 8.65575 9.44259C8.65575 9.53002 7.25685 9.53002 7.25685 9.53002L7.34428 9.44259ZM8.04373 11.8032C7.78144 11.8032 7.60657 11.8032 7.43171 11.5409C7.25685 11.3661 7.16941 11.1912 7.16941 10.9289C7.16941 10.6666 7.16941 10.4918 7.43171 10.3169C7.60657 10.142 7.78144 10.0546 8.04373 10.0546C8.30603 10.0546 8.48089 10.0546 8.65575 10.3169C8.83062 10.4918 8.91805 10.6666 8.91805 10.9289C8.91805 11.1038 8.91805 11.2787 8.83062 11.3661C8.83062 11.5409 8.65575 11.6284 8.48089 11.7158C8.30603 11.7158 8.2186 11.8032 8.04373 11.8032Z" fill="#8A8A8A"/>
                                    </g>
                                    </g>
                                    </g>
                                    <defs>
                                    <clipPath id="clip0_1937_6579">
                                    <rect width="16" height="16" fill="white"/>
                                    </clipPath>
                                    <clipPath id="clip1_1937_6579">
                                    <rect width="16" height="16" fill="white"/>
                                    </clipPath>
                                    <clipPath id="clip2_1937_6579">
                                    <rect width="16" height="16" fill="white"/>
                                    </clipPath>
                                    </defs>
                                </svg>
                            </span>
                            </label>
                            <input type="number" id="capacity" min="1" max="100" value="3">
                        </div>
                    </div>
                    <button class="feedback-config__btn">Подобрать конфигурацию</button>
                </form>
            </div>
        </div>
    </section>
    <section class="advantages">
        <div class="container">
            <div class="advantages__content">
                <div class="advantages-info">
                    <div class="advantages-info__img">
                        <img src="{{ iblock()->getById(2)?->elements[0]?->image()?->getFull() }}" alt="">
                    </div>
                    {!! iblock()->getById(2)?->elements[0]?->description !!}
                </div>
                <div class="advantages-description">
                    <div class="advantages-description__content">
                        {!! iblock()->getById(2)?->description !!}
                    </div>
                </div>
                <div class="advantages-list">
                    @foreach(iblock()->getById(2)->elements->skip(1) as $element)
                       <div class="advantages-item">
                           <div class="advantages-item__img">
                               <img src="{{$element->image()?->getFull()}}" alt="">
                           </div>
                           <div class="advantages-item__description">
                               {!! $element->description !!}
                           </div>
                       </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <x-shop::hit-products limit="1000000"/>

@endsection

