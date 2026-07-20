@if($news->count() > 0)
    <div class="news__container container">


        <div class="news-heading">
            <h2>
                Новости
{{--                <a href="{{ route('news.index') }}">Новости</a>--}}
            </h2>
            <div class="news-swiper_btns">
                <button class="news-swiper_prev">
                    <svg width="10" height="17" viewBox="0 0 10 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.60059 15.6L2.02203 9.24852C1.60778 8.84856 1.40059 8.64852 1.40059 8.4C1.40059 8.15148 1.60778 7.95144 2.02203 7.55148L8.60059 1.2" stroke="#8A8A8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <button class="news-swiper_next">
                    <svg width="10" height="17" viewBox="0 0 10 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.2002 1.2L7.77875 7.55148C8.193 7.95144 8.4002 8.15148 8.4002 8.4C8.4002 8.64852 8.193 8.84856 7.77875 9.24852L1.2002 15.6" stroke="#8A8A8A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
        <div class="news-sorter">
            <a href="#" class="news-sorter-btn active">Новости компании</a>
            <a href="#" class="news-sorter-btn">Новости отрасли</a>
        </div>
        <div class="news-swiper swiper">
            <div class="swiper-wrapper">
                @foreach($news as $item)
                    <div  class="swiper-slide news-item">
                        <div class="news-item__img">
                            @if($item->image())
                                <img src="{{ asset($item->image()->getPreview()) }} " alt="">
                            @else
                                <img src="{{ asset('img/new.png') }}" alt="">
                            @endif
                        </div>
                        <div class="news-item__content">
                            <div class="news-item__text">
                                <h5>{{ $item->title }}</h5>
                                {!! $item->text !!}
                            </div>

                            <a href="{{ route('news.show', $item->alias) }}" class="news-item_link">
                                Читать дальше
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 8H14" stroke="#2B5E07" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9.0002 3L14 8L9.0001 13" stroke="#2B5E07" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
@endif

