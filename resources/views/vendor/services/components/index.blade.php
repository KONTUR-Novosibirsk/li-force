<section class="projects">
    <div class="container">
        <h2>Наши проекты</h2>
        <p>Хотим поделиться с вами интересными решениями, которые разработали наши специалисты</p>
        <div class="projects__list">
            <a href="{{ route('services.show', $services[0]->alias) }}" class="projects__item">
                {!! $services[0]->short_description !!}
            </a>
            <a href="{{ route('services.show', $services[1]->alias) }}" class="projects__item">
                <img src="{{$services[1]->image()->getFull()}}" alt="">
                <div class="projects__item-content">
                    <div class="projects__item-title">{{$services[1]->name}}</div>
                    {!! $services[1]->short_description !!}
                </div>
            </a>
            <a href="{{ route('services.show', $services[2]->alias) }}" class="projects__item">
                <img src="{{$services[2]->image()->getFull()}}" alt="">
            </a>
            <a href="{{ route('services.show', $services[3]->alias) }}" class="projects__item">
                <img src="{{$services[3]->image()->getFull()}}" alt="">
            </a>
            <a href="{{ route('services.index') }}" class="projects__link">
                <div class="projects__link-content">
                    <span class="projects__count">48+</span>
                    Смотреть все проекты
                </div>
                <span class="projects__link-btn">
                    <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18.1182 2V33" stroke="white" stroke-width="4" stroke-linecap="round"/>
                        <path d="M32.7399 17.1555H2.83203" stroke="white" stroke-width="4" stroke-linecap="round"/>
                    </svg>
                </span>
            </a>
{{--            @forelse($services as $service)--}}
{{--                <a href="{{ route('services.show', $service->alias) }}" class="services__list-item">--}}
{{--                    <h3>{{ $service->name }}</h3>--}}
{{--                    <div>--}}
{{--                        <img src="{{$service->image()?->getPreview()}}" alt="">--}}
{{--                    </div>--}}
{{--                </a>--}}
{{--            @empty--}}
{{--                <div class="">Записи отсутствуют</div>--}}
{{--            @endforelse--}}
        </div>
    </div>
</section>

