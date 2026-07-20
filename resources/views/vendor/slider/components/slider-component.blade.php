<div class="container">
    <div class="main-slider__content swiper">
        <div class="main-slider__wrapper swiper-wrapper">
            @foreach($slides as $slide)
                <div class="main-slider__item swiper-slide">
                    <div class="main-slider__item-content">
                        <div class="main-slider__item-content-text">
                            {!! $slide->description !!}

                            <a href="{!! $slide->props['link'] !!}" class="main-slider__item-link">
                                Подробнее
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 8H14" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M9.0002 3L14 8L9.0001 13" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                        @if($slide->image())
                            <div class="main-slider__item-content-img">
                                <img src="{{ $slide->image()?->getFull() }}" alt="{{ $slide->title }}" loading="lazy">
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="main-slider__item swiper-slide">
                <div class="video-bg">
                    <video src="/videos/banner.mp4" type="video/mp4"  autoplay muted playsinline loop preload="auto" ></video>
                </div>
                <div class="main-slider__item-content">
                    <div class="main-slider__item-content-text">
                        <h2>Спроектируйте вашу <span style="color: #439E02">батарею</span></h2>
                        <p>Доверьте производство опытным специалистам</p>
                        <a href="{!! $slide->props['link'] !!}" class="main-slider__item-link">
                            Подробнее
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 8H14" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M9.0002 3L14 8L9.0001 13" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                    @if($slide->image())
                        <div class="main-slider__item-content-img">
                            <img src="{{ $slide->image()?->getFull() }}" alt="{{ $slide->title }}" loading="lazy">
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="main-slider__pagination"></div>
    </div>
</div>
