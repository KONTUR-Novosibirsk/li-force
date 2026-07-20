@extends('layouts.hiddenOther')

@section('content')
    @if(isset($category))
        <section class="products">
        <div class="products-container container">
            <div class="products-head">
                <h1>{{ $category->title }}</h1>
                {!! $category->description !!}
                <a href="#" data-src="#filter-mobile" data-auto-focus="false" data-fancybox
                   class="products-filter__btn_mobile" data-options='{"touch" : false}'>
                    <svg width="22" height="23" viewBox="0 0 22 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.2498 3.25H4.58317C4.34006 3.25 4.1069 3.34658 3.93499 3.51849C3.76308 3.69039 3.6665 3.92355 3.6665 4.16667V6.54083C3.6665 7.02025 3.86175 7.49142 4.20092 7.83058L9.1665 12.7962V19.75C9.16668 19.9062 9.20672 20.0597 9.28283 20.1961C9.35893 20.3325 9.46859 20.4472 9.60141 20.5293C9.73423 20.6115 9.88582 20.6584 10.0418 20.6656C10.1978 20.6727 10.3531 20.64 10.4929 20.5704L14.1596 18.7371C14.4703 18.5813 14.6665 18.2641 14.6665 17.9167V12.7962L19.6321 7.83058C19.9713 7.49142 20.1665 7.02025 20.1665 6.54083V4.16667C20.1665 3.92355 20.0699 3.69039 19.898 3.51849C19.7261 3.34658 19.493 3.25 19.2498 3.25ZM13.1018 11.7686C13.0165 11.8536 12.9488 11.9546 12.9027 12.0658C12.8566 12.177 12.833 12.2963 12.8332 12.4167V17.3502L10.9998 18.2668V12.4167C11 12.2963 10.9764 12.177 10.9303 12.0658C10.8842 11.9546 10.8165 11.8536 10.7313 11.7686L5.49984 6.54083V5.08333H18.3341L18.3359 6.53442L13.1018 11.7686Z" fill="#333333"/>
                    </svg>
                    Фильтр
                </a>
            </div>
            <div class="products-content">
                <div class="products-content_left">
                    @if($category->children->count() > 0)
                        <h3>Категории</h3>
                        <div class="products-menu category__products-menu">
                            <ul>
                                @if($category->parent)
                                    <li class="products-menu__main-item">
                                        <a href="{{ $category->parent->createUrl() }}" class="products-menu__main-item__arrow">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12.7266 3.83661V2.47782C12.7266 2.36005 12.5912 2.29501 12.4998 2.36708L4.57561 8.55634C4.50828 8.6087 4.45381 8.67574 4.41633 8.75236C4.37886 8.82898 4.35937 8.91314 4.35938 8.99843C4.35937 9.08372 4.37886 9.16788 4.41633 9.2445C4.45381 9.32112 4.50828 9.38816 4.57561 9.44052L12.4998 15.6298C12.593 15.7018 12.7266 15.6368 12.7266 15.519V14.1602C12.7266 14.0741 12.6862 13.9915 12.6194 13.9388L6.29124 8.99931L12.6194 4.0581C12.6862 4.00536 12.7266 3.92275 12.7266 3.83661V3.83661Z" fill="black" fill-opacity="0.88"/>
                                            </svg>
                                        </a>
                                        {{ $category->title }}
                                    </li>
                                @else
                                    <li class="products-menu__main-item">
                                        <a href="/shop" class="products-menu__main-item__arrow">
                                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12.7266 3.83661V2.47782C12.7266 2.36005 12.5912 2.29501 12.4998 2.36708L4.57561 8.55634C4.50828 8.6087 4.45381 8.67574 4.41633 8.75236C4.37886 8.82898 4.35937 8.91314 4.35938 8.99843C4.35937 9.08372 4.37886 9.16788 4.41633 9.2445C4.45381 9.32112 4.50828 9.38816 4.57561 9.44052L12.4998 15.6298C12.593 15.7018 12.7266 15.6368 12.7266 15.519V14.1602C12.7266 14.0741 12.6862 13.9915 12.6194 13.9388L6.29124 8.99931L12.6194 4.0581C12.6862 4.00536 12.7266 3.92275 12.7266 3.83661V3.83661Z" fill="black" fill-opacity="0.88"/>
                                            </svg>
                                        </a>
                                        {{ $category->title }}
                                    </li>
                                @endif
                                @foreach($categories as $childCategory)
                                    <li>
                                        <a href="{{ $childCategory->createUrl() }}">{{ $childCategory->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if($filterableAttributes)
                        <div class="products-filter">
                            <div class="products-filter__head">
                                <div class="products-filter__heading">Фильтр</div>
                            </div>
                            <div class="products-filter__body vue_app">
                                <product-filter-component url="{{ url()->current() }}"
                                                          :filterable-attributes="{{ $filterableAttributes?->toJson() }}"
                                                          :data="{{ json_encode($filterData) }}"></product-filter-component>
                            </div>
                        </div>
                            <div class="products-filter filter-mobile" id="filter-mobile">
                                <div class="products-filter__head">
                                    <div class="products-filter__heading">Фильтр</div>
                                </div>
                                <div class="products-filter__body vue_app">
                                    <product-filter-component url="{{ url()->current() }}"
                                                              :filterable-attributes="{{ $filterableAttributes?->toJson() }}"
                                                              :data="{{ json_encode($filterData) }}"></product-filter-component>
                                </div>
                            </div>
                    @endif
                </div>
                <div class="products-content_right">
                    <div class="battery-select" id="batteryTypeSelect">
                        <div class="battery-select__trigger">
                                <span class="battery-select__value">
                                    @if($activeBatteryType == 1)
                                        Литиевые аккумуляторы
                                    @elseif($activeBatteryType == 2)
                                        Батарейки
                                    @else
                                        Без сортировки
                                    @endif
                                </span>
                            <span class="battery-select__arrow"></span>
                        </div>
                        <div class="battery-select__options">
                            <div class="battery-select__option" data-value="0" {{ !$activeBatteryType ? 'selected' : '' }}>
                                <a href="{{ request()->fullUrlWithQuery(['battery_type' => null]) }}">
                                    Без сортировки
                                </a>
                            </div>
                            <div class="battery-select__option" data-value="1" {{ $activeBatteryType == 1 ? 'selected' : '' }}>
                                <a href="{{ request()->fullUrlWithQuery(['battery_type' => 1]) }}">
                                    Литиевые аккумуляторы
                                </a>
                            </div>
                            <div class="battery-select__option" data-value="2" {{ $activeBatteryType == 2 ? 'selected' : '' }}>
                                <a href="{{ request()->fullUrlWithQuery(['battery_type' => 2]) }}">
                                    Батарейки
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="vue_app">
                        <product-sorter-component
                            url="{{ url()->current() }}" :data="{{ json_encode($filterData) }}"
                            :attributes="{{ json_encode($sortAttributes) }}">
                        </product-sorter-component>
                    </div>

                    <div class="js-filter-products">
                        @include('shop::partial.products')
                    </div>
                </div>
            </div>
        </div>
    </section>
    @else
        nety
    @endif
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const customSelect = document.getElementById('batteryTypeSelect');
        const trigger = customSelect.querySelector('.battery-select__trigger');
        const valueSpan = customSelect.querySelector('.battery-select__value');

        let currentValue = 0;
        const urlParams = new URLSearchParams(window.location.search);
        const activeType = urlParams.get('battery_type');
        if (activeType === '1') {
            currentValue = 1;
            valueSpan.textContent = 'Литиевые аккумуляторы';
        } else if (activeType === '2') {
            currentValue = 2;
            valueSpan.textContent = 'Батарейки';
        } else {
            currentValue = 0;
            valueSpan.textContent = 'Без сортировки';
        }
        document.querySelectorAll('.battery-select__option').forEach(option => {
            const optionValue = parseInt(option.dataset.value);
            if (optionValue === currentValue) {
                option.classList.add('selected');
            }
        });


        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            customSelect.classList.toggle('open');
        });


        document.addEventListener('click', function(e) {
            if (!customSelect.contains(e.target)) {
                customSelect.classList.remove('open');
            }
        });
        const options = document.querySelectorAll('.battery-select__option');
        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                const value = parseInt(this.dataset.value);
                const text = this.textContent;

                valueSpan.textContent = text;
                currentValue = value;

                options.forEach(opt => opt.classList.remove('selected'));
                this.classList.add('selected');

                customSelect.classList.remove('open');
            });
        });
    });
</script>

<style>

</style>
