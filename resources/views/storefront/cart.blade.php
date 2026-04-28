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

        @if ($items->isEmpty())
            <div data-reveal class="surface-panel p-8 sm:p-10">
                <p class="text-2xl display-title">Giỏ hàng vẫn còn trống.</p>
                <p class="mt-3 max-w-xl text-sm leading-7 text-[color:var(--color-umber)]">
                    Hãy bắt đầu với một món nền tảng, rồi thêm gốm sứ, hương thơm và linen để hoàn thiện không gian.
                </p>
                <a href="{{ route('shop.index') }}" class="btn-primary mt-6 w-fit">Tiếp tục mua sắm</a>
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="space-y-5">
                    @foreach ($items as $item)
                        <article data-reveal class="surface-panel overflow-hidden p-4 sm:p-5">
                            <div class="grid gap-5 sm:grid-cols-[11rem_1fr]">
                                <div class="overflow-hidden rounded-[1.75rem]">
                                    <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}" class="h-full min-h-[12rem] w-full object-cover">
                                </div>
                                <div class="flex flex-col justify-between gap-5">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">{{ $item['product']->category }}</p>
                                            <h2 class="mt-2 text-3xl leading-tight display-title">{{ $item['product']->name }}</h2>
                                            <p class="mt-3 text-sm leading-6 text-[color:var(--color-umber)]">{{ $item['product']->tagline }}</p>
                                        </div>
                                        <p class="text-lg font-semibold text-[color:var(--color-ink)]">{{ \App\Models\Product::formatCurrency($item['line_total']) }}</p>
                                    </div>

                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                        <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="flex items-center gap-3">
                                            @csrf
                                            @method('PATCH')
                                            <label for="quantity-{{ $item['product']->id }}" class="text-sm font-semibold text-[color:var(--color-umber)]">SL</label>
                                            <input
                                                id="quantity-{{ $item['product']->id }}"
                                                name="quantity"
                                                type="number"
                                                min="1"
                                                max="8"
                                                value="{{ $item['quantity'] }}"
                                                class="quantity-input"
                                            >
                                            <button type="submit" class="btn-secondary px-4 py-2.5">Cập nhật</button>
                                        </form>

                                        <form action="{{ route('cart.destroy', $item['product']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-semibold text-[color:var(--color-clay)]">Xóa</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <aside data-reveal class="surface-panel h-fit p-6 sm:p-8 lg:sticky lg:top-28">
                    <p class="eyebrow">Tóm tắt đơn hàng</p>
                    <div class="mt-6 space-y-4 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-[color:var(--color-umber)]">Số sản phẩm</span>
                            <span class="font-semibold text-[color:var(--color-ink)]">{{ $cartCount }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[color:var(--color-umber)]">Tạm tính</span>
                            <span class="font-semibold text-[color:var(--color-ink)]">{{ \App\Models\Product::formatCurrency($cartSubtotal) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[color:var(--color-umber)]">Vận chuyển</span>
                            <span class="font-semibold text-[color:var(--color-ink)]">
                                {{ $cartShipping == 0.0 ? 'Miễn phí' : \App\Models\Product::formatCurrency($cartShipping) }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 rounded-[1.5rem] bg-[color:var(--color-ivory)] px-4 py-3 text-xs text-[color:var(--color-umber)]">
                        Miễn phí vận chuyển cho đơn từ {{ \App\Models\Product::formatCurrency(5000000) }}.
                    </div>

                    <div class="mt-6 border-t border-black/5 pt-6">
                        <div class="flex items-center justify-between">
                            <span class="text-lg font-semibold text-[color:var(--color-ink)]">Tổng cộng</span>
                            <span class="text-2xl font-semibold text-[color:var(--color-ink)]">{{ \App\Models\Product::formatCurrency($cartTotal) }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout.create') }}" class="btn-primary mt-8 flex w-full justify-center">Tiến hành đặt hàng</a>
                    <a href="{{ route('shop.index') }}" class="btn-secondary mt-3 flex w-full justify-center">Tiếp tục mua sắm</a>
                </aside>
            </div>
        @endif
    </section>
@endsection
