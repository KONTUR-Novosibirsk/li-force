@if($items)
    <ul class="sub-menu">
        @foreach($items as $item)
            <li>
                <a href="{{ $item->link() }}"> {{ $item->name }} </a>
                <svg width="6" height="12" viewBox="0 0 6 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.49961 6.05007L5.19961 5.35007L5.89961 6.05007L5.19961 6.75007L4.49961 6.05007ZM1.49961 1.55007L5.19961 5.35007L3.79961 6.75007L0.0996094 2.95007L1.49961 1.55007ZM5.19961 6.75007L1.49961 10.4501L0.0996094 9.05007L3.79961 5.25007L5.19961 6.75007Z" fill="#2B5E07"/>
                </svg>
            @if($item->children->count() > 0)
                    <x-menu::nested-menu-component :items="$item->children"/>
                @endif
            </li>
        @endforeach
    </ul>
@endif
