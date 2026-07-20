@if($categories->count() > 0)
    <div class="container about_container">
        <div class="about_content">
            <div class="about-description">
                {!! iblock()->getById(1)?->description !!}
                <form class="about__search" action="#">
                    <input type="text" name="search" placeholder="Для чего вам нужено решение?">
                    <button type="submit" class="btn">
                        Найти
                    </button>
                </form>
            </div>
            <div class="about-list">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        @foreach($categories as $category)
                            <div class="swiper-slide">
                                <a href="{{ $category->createUrl() }}">
                                <div class="about-list_item">
                                    <div class="about-list_item-img">
                                        @if($category->image())
                                        <img src="{{$category->image()->getPreview()}}" alt="">
                                        @endif
                                    </div>
                                    <div class="about-list_item-ttl">
                                        {{$category->title}}
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
    </div>
@endif
