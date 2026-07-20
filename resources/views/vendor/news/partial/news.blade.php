<a href="{{ route('news.show', $news->alias) }}" class="news-item">
    <div class="news-item__img">
        @if($news->image())
            <img src="{{ asset($news->image()->getPreview()) }} " alt="">
        @endif
    </div>
    <div class="news-item__content">
        <h5 class="news-item__ttl">
            {{ $news->title }}
        </h5>
        <div class="news-item__text">
            {!! $news->text !!}
        </div>
        <div class="news-item__date">
            {{ $item->publication_date->format('d.m.Y') }}
        </div>
    </div>
</a>
