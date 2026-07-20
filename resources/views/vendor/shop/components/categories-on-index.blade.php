@if($categories->count() > 0)
    <section class="categories">
        <div class="categories__container container">
            <div class="categories-list">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        @foreach($categories as $category)
                            <div class="swiper-slide">
                                <a href="{{ $category->createUrl() }}" class="categories-item">
                                    <div class="categories-item__content">
                                        <div class="categories-item__title">
                                            {{ $category->title }}
                                        </div>
                                        <div class="categories-item__img">
                                            @if($category->image()?->getPreview())
                                                <img src="{{ $category->image()?->getPreview() }} " alt="">
                                            @else
                                                <img src="{{ asset('img/new.png') }}" alt="">
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
                <button class="swiper-prev">
                    <svg width="10" height="17" viewBox="0 0 10 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.20001 15.4L1.62146 9.04854C1.2072 8.64858 1.00001 8.44854 1.00001 8.20002C1.00001 7.9515 1.2072 7.75146 1.62146 7.3515L8.20001 1.00003" stroke="#8A8A8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <button class="swiper-next">
                    <svg width="10" height="17" viewBox="0 0 10 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 15.4L7.57855 9.04854C7.99281 8.64858 8.2 8.44854 8.2 8.20002C8.2 7.9515 7.99281 7.75146 7.57855 7.3515L1 1.00003" stroke="#8A8A8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </section>
@endif
