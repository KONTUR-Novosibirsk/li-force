@if(settings('with_trade_offers', 'shop', false))
    <div class="products-list">
        @forelse($offers as $offer)
            @include('shop::partial.product_card', ['category' => $category, 'product' => $offer->products->first()])
        @empty
            <div class="">Товары отсутствуют</div>
        @endforelse
    </div>
    {{ $offers->links() }}
@else
    <div class="products-list">
        @if(isset($products))
            @forelse($products as $product)
                @include('shop::partial.product_card', ['category' => $category, 'product' => $product])
            @empty
                <div class="">Товары отсутствуют</div>
            @endforelse
        @endif
    </div>
    {{ $products->links() }}
@endif

