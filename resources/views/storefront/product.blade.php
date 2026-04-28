@extends('layouts.store', [
    'title' => $product->name . ' | Aster Atelier',
    'description' => $product->tagline,
])

@section('content')
    <section class="section-shell py-10 sm:py-14">
        <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr]">
            <div class="space-y-5">
                @foreach ($product->gallery as $image)
                    <div data-reveal class="overflow-hidden rounded-[2.5rem]">
                        <img src="{{ $image }}" alt="{{ $product->name }}" class="h-full min-h-[21rem] w-full object-cover lg:min-h-[26rem]">
                    </div>
                @endforeach
            </div>

            <div data-reveal class="lg:sticky lg:top-28 lg:self-start">
                <p class="eyebrow">{{ $product->category }}</p>
                <h1 class="mt-3 text-balance text-5xl leading-none sm:text-6xl display-title">{{ $product->name }}</h1>
                <p class="mt-5 max-w-xl text-base leading-7 text-[color:var(--color-umber)]">{{ $product->description }}</p>

                <div class="mt-6 flex items-end gap-4">
                    <p class="text-3xl font-semibold text-[color:var(--color-ink)]">{{ $product->formatted_price }}</p>
                    @if ($product->formatted_compare_price)
                        <p class="pb-1 text-base text-[color:var(--color-umber)] line-through">{{ $product->formatted_compare_price }}</p>
                    @endif
                </div>

                <div class="mt-4 flex items-center gap-3 text-sm text-[color:var(--color-umber)]">
                    <span>{{ number_format((float) $product->rating, 1, ',', '.') }}/5</span>
                    <span class="h-1 w-1 rounded-full bg-[color:var(--color-clay)]"></span>
                    <span>{{ $product->reviews_count }} đánh giá xác thực</span>
                </div>

                <form action="{{ route('cart.store', $product) }}" method="POST" class="surface-panel mt-8 p-6">
                    @csrf
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <div>
                            <label for="quantity" class="mb-2 block text-sm font-semibold text-[color:var(--color-umber)]">Số lượng</label>
                            <input
                                id="quantity"
                                name="quantity"
                                type="number"
                                min="1"
                                max="8"
                                value="1"
                                class="quantity-input"
                            >
                        </div>
                        <button type="submit" class="btn-primary mt-2 sm:mt-7">Thêm vào giỏ</button>
                    </div>
                </form>

                <div class="mt-8 grid gap-5">
                    <div class="surface-panel p-6">
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">Điểm nổi bật</p>
                        <ul class="mt-4 space-y-3 text-sm leading-6 text-[color:var(--color-umber)]">
                            @foreach ($product->highlights as $highlight)
                                <li class="flex gap-3">
                                    <span class="mt-2 h-1.5 w-1.5 rounded-full bg-[color:var(--color-clay)]"></span>
                                    <span>{{ $highlight }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="surface-panel p-6">
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">Thông số</p>
                        <dl class="mt-4 space-y-3">
                            @foreach ($product->specs as $label => $value)
                                <div class="flex items-start justify-between gap-6 border-b border-black/5 pb-3 last:border-b-0 last:pb-0">
                                    <dt class="text-sm font-semibold text-[color:var(--color-ink)]">{{ $label }}</dt>
                                    <dd class="text-sm text-right text-[color:var(--color-umber)]">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="section-shell pb-12 pt-8">
            <div data-reveal class="mb-8">
                <p class="eyebrow">Bạn có thể thích</p>
                <h2 class="section-heading mt-3">Các món khác trong nhóm {{ $product->category }}.</h2>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                @foreach ($related as $item)
                    <article data-reveal class="product-card group">
                        <a href="{{ route('products.show', $item) }}" class="product-card-image block">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                        </a>
                        <div class="mt-5">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">{{ $item->category }}</p>
                                @if ($item->badge)
                                    <span class="product-badge">{{ $item->badge }}</span>
                                @endif
                            </div>
                            <h3 class="mt-3 text-2xl display-title">{{ $item->name }}</h3>
                            <p class="mt-2 text-sm leading-6 text-[color:var(--color-umber)]">{{ $item->tagline }}</p>
                        </div>
                        <div class="mt-5 flex items-center justify-between">
                            <p class="text-lg font-semibold">{{ $item->formatted_price }}</p>
                            <a href="{{ route('products.show', $item) }}" class="btn-secondary px-4 py-2.5">Xem</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif
@endsection
