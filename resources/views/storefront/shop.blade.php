@extends('layouts.store', [
    'title' => 'Cửa hàng | Aster Atelier',
    'description' => 'Duyệt toàn bộ bộ sưu tập Aster Atelier.',
])

@section('content')
    <section class="section-shell py-10 sm:py-14">
        <div data-reveal class="surface-panel overflow-hidden p-8 sm:p-10">
            <div class="grid gap-8 lg:grid-cols-[1fr_0.9fr] lg:items-center">
                <div>
                    <p class="eyebrow">Bộ sưu tập</p>
                    <h1 class="mt-3 max-w-2xl text-balance text-5xl leading-none sm:text-6xl display-title">
                        Chọn những món đồ giữ căn phòng gọn, ấm và có điểm nhấn.
                    </h1>
                    <p class="mt-5 max-w-xl text-base leading-7 text-[color:var(--color-umber)]">
                        Danh mục tập trung vào nội thất, gốm sứ, ánh sáng và đồ dùng hằng ngày được chọn theo độ ấm, chất cảm và khả năng ở lại lâu trong không gian.
                    </p>
                </div>
                <div class="overflow-hidden rounded-[2rem]">
                    <img
                        src="https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1400&q=80"
                        alt="Không gian trưng bày Aster Atelier"
                        class="h-full min-h-[18rem] w-full object-cover"
                    >
                </div>
            </div>
        </div>
    </section>

    <section class="section-shell py-6">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div data-reveal class="flex flex-wrap gap-3">
                <a href="{{ route('shop.index') }}" class="pill-link {{ $selectedCategory === '' ? 'is-active' : '' }}">Tất cả</a>
                @foreach ($categories as $category)
                    <a
                        href="{{ route('shop.index', ['category' => $category, 'sort' => $selectedSort]) }}"
                        class="pill-link {{ $selectedCategory === $category ? 'is-active' : '' }}"
                    >
                        {{ $category }}
                    </a>
                @endforeach
            </div>

            <form data-reveal method="GET" action="{{ route('shop.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-center">
                @if ($selectedCategory)
                    <input type="hidden" name="category" value="{{ $selectedCategory }}">
                @endif
                <label for="sort" class="text-sm font-semibold text-[color:var(--color-umber)]">Sắp xếp</label>
                <select id="sort" name="sort" class="input-shell min-w-48" onchange="this.form.submit()">
                    <option value="curated" @selected($selectedSort === 'curated')>Chọn lọc</option>
                    <option value="price_asc" @selected($selectedSort === 'price_asc')>Giá tăng dần</option>
                    <option value="price_desc" @selected($selectedSort === 'price_desc')>Giá giảm dần</option>
                    <option value="name" @selected($selectedSort === 'name')>Tên sản phẩm</option>
                </select>
            </form>
        </div>
    </section>

    <section class="section-shell pb-12 pt-8">
        <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
            @forelse ($products as $product)
                <article data-reveal class="product-card group">
                    <a href="{{ route('products.show', $product) }}" class="product-card-image block">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    </a>

                    <div class="mt-5">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--color-clay)]">{{ $product->category }}</p>
                            @if ($product->badge)
                                <span class="product-badge">{{ $product->badge }}</span>
                            @endif
                        </div>
                        <h2 class="mt-3 text-2xl leading-tight display-title">
                            <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                        </h2>
                    </div>

                    <p class="mt-3 text-sm leading-6 text-[color:var(--color-umber)]">{{ $product->tagline }}</p>

                    <div class="mt-5 flex items-center justify-between">
                        <div>
                            <p class="text-lg font-semibold">{{ $product->formatted_price }}</p>
                            <p class="text-sm text-[color:var(--color-umber)]">{{ number_format((float) $product->rating, 1, ',', '.') }}/5</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('products.show', $product) }}" class="btn-secondary px-4 py-2.5">Xem</a>
                            <form action="{{ route('cart.store', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-primary px-4 py-2.5">Thêm</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="surface-panel col-span-full p-8 text-center">
                    <p class="text-lg font-semibold text-[color:var(--color-ink)]">Không có sản phẩm nào khớp bộ lọc hiện tại.</p>
                    <p class="mt-2 text-sm text-[color:var(--color-umber)]">Hãy thử danh mục khác hoặc quay lại toàn bộ bộ sưu tập.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
