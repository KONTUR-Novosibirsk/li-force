@extends('layouts.other')
@section('content')
   <section class="all-projects">
       <div class="container">
           <h2>{{ settings('name', 'services', 'Услуги') }}</h2>
           <div class="all-projects__list">
               @forelse($services as $service)
                   <div class="all-projects__item">
                       @if($service->image())
                           <img src="{{$service->image()->getFull()}}" alt="">
                       @endif
                       <a href="{{ route('services.show', $service->alias) }}" class="all-projects__item-title">
                           <span> {{ $service->name }}</span>
                       </a>
                   </div>
               @empty
                   <div class="">Записи отсутствуют</div>
               @endforelse
           </div>
           {{ $services->links() }}
       </div>
   </section>
@endsection
