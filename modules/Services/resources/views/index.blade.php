@extends('layouts.other')
@section('content')
    <div class="container">
        <h1>{{ settings('name', 'services', 'Услуги') }}</h1>
        @forelse($services as $service)
            {{ $service->name }}
        @empty
            <div class="">Записи отсутствуют</div>
        @endforelse

        {{ $services->links() }}
    </div>
@endsection
