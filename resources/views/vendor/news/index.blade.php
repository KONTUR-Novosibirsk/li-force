@extends('layouts.other')
@section('content')
    <section class="news">
        <div class="news-container container">
            <div class="news-top">
                <div class="news-buttons">
                    <input type="radio" id="all" name="news" checked>
                    <label for="all">Все публикации</label>

                    <input type="radio" id="news" name="news">
                    <label for="news">Новости</label>

                    <input type="radio" id="articles" name="news">
                    <label for="articles">Статьи</label>

                    <input type="radio" id="reviews" name="news">
                    <label for="reviews">Обзоры</label>
                </div>
            </div>
            <div class="news-list">
                @foreach($news as $item)
                    @include('news::partial.news', ['news' => $item])
                @endforeach
            </div>
            {{ $news->links() }}
        </div>
    </section>
@endsection
