@extends('layouts.store', [
    'title' => 'Giỏ hàng | Aster Atelier',
    'description' => 'Xem lại các sản phẩm đã chọn tại Aster Atelier.',
])

@section('content')
    <section class="section-shell py-10 sm:py-14">
        <div data-reveal class="mb-8">
            <p class="eyebrow">Giỏ hàng</p>
            <h1 class="mt-3 text-balance text-5xl leading-none sm:text-6xl display-title">Những món bạn đang chọn.</h1>
        </div>

        <div data-cart-body>
            @include('storefront.partials.cart-body')
        </div>
    </section>
@endsection
