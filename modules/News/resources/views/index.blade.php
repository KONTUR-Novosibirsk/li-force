@extends('layouts.other')
@section('content')
    <section class="news">
        <div class="news-container container">
            <h1>Новости</h1>
            <div class="news-list">
                @foreach($news as $item)
                    @include('news::partial.news', ['news' => $item])
                @endforeach
            </div>
            {{ $news->links() }}
        </div>
    </section>
@endsection
