@extends('layouts.store', [
    'title' => 'Aster Atelier | Cửa hàng nội thất tuyển chọn',
    'description' => 'Khám phá nội thất, gốm sứ, ánh sáng và phụ kiện sống chậm từ Aster Atelier.',
])

@section('content')
    <section class="relative overflow-hidden bg-ink text-[color:var(--color-ivory)]">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(182,109,75,0.45),_transparent_28%),radial-gradient(circle_at_right,_rgba(234,220,198,0.18),_transparent_22%)]"></div>
        <div class="section-shell relative grid gap-12 py-14 sm:py-18 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:py-24">
            <div data-reveal class="max-w-xl">
                <p class="eyebrow text-[color:var(--color-latte)]">Tuyển chọn mùa xuân 2026</p>
                <h1 class="mt-5 max-w-3xl text-balance text-5xl leading-none sm:text-6xl lg:text-7xl display-title">
                    Căn phòng đẹp hơn khi mỗi món đồ đều có lý do để ở lại.
                </h1>
                <p class="mt-6 max-w-lg text-base leading-7 text-[color:var(--color-latte)]/86 sm:text-lg">
                    Aster Atelier tuyển chọn nội thất, vải vóc, gốm sứ và ánh sáng với con mắt gallery nhưng vẫn đủ mềm để sống cùng mỗi ngày.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('shop.index') }}" class="btn-primary bg-[color:var(--color-latte)] text-[color:var(--color-ink)] hover:bg-white">
                        Mua theo bộ sưu tập
                    </a>
                    <a href="#new-arrivals" class="btn-secondary border-white/15 bg-white/8 text-[color:var(--color-ivory)] hover:bg-white/14">
                        Xem hàng mới
                    </a>
                </div>

                <div class="mt-10 grid grid-cols-3 gap-4 border-t border-white/12 pt-8 text-left">
                    <div>
                        <p class="stat-number">48h</p>
                        <p class="mt-2 text-sm text-[color:var(--color-latte)]/74">xử lý đơn với sản phẩm có sẵn</p>
                    </div>
                    <div>
                        <p class="stat-number">17</p>
                        <p class="mt-2 text-sm text-[color:var(--color-latte)]/74">studio nội thất đặt sourcing mỗi tháng</p>
                    </div>
                    <div>
                        <p class="stat-number">4,9</p>
                        <p class="mt-2 text-sm text-[color:var(--color-latte)]/74">điểm đánh giá trung bình từ khách mua</p>
                    </div>
                </div>
            </div>

            <div data-reveal class="relative min-h-[27rem] lg:min-h-[38rem]">
                <div class="absolute inset-y-6 right-0 left-[8%] overflow-hidden rounded-[2.5rem]">
                    <img
                        src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1400&q=80"
                        alt="Không gian phòng khách tuyển chọn"
                        class="h-full w-full object-cover"
                    >
                </div>
                <div class="absolute bottom-4 left-0 max-w-xs rounded-[2rem] border border-white/12 bg-black/22 p-5 shadow-[0_30px_80px_rgba(0,0,0,0.28)] backdrop-blur-xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--color-latte)]">Ghi chú curator</p>
                    <p class="mt-3 text-sm leading-6 text-[color:var(--color-ivory)]/85">
                        Hãy chọn một món anchor lớn rồi thêm vài vật liệu nhỏ có chất cảm mạnh. Căn phòng sẽ được “biên tập” thay vì bị lấp đầy.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-14 sm:py-18">
        <div class="section-shell grid gap-4 md:grid-cols-3">
            @foreach ($collections as $collection)
                <a
                    data-reveal
                    href="{{ route('shop.index', ['category' => $collection->category]) }}"
                    class="surface-panel flex min-h-44 flex-col justify-between p-6 transition duration-500 hover:-translate-y-1"
                >
                    <p class="eyebrow">{{ $collection->total }} sản phẩm</p>
                    <div>
                        <h2 class="text-3xl display-title">{{ $collection->category }}</h2>
                        <p class="mt-2 text-sm leading-6 text-[color:var(--color-umber)]">
                            Chọn để đi cùng gỗ ấm, đá tự nhiên và ánh sáng buổi tối.
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <section id="new-arrivals" class="py-10 sm:py-14">
        <div class="section-shell">
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div data-reveal>
                    <p class="eyebrow">Hàng mới về</p>
                    <h2 class="section-heading mt-3">Một kiểu sang tinh lặng, được chọn vì chất cảm trước khi theo xu hướng.</h2>
                </div>
                <a data-reveal href="{{ route('shop.index') }}" class="pill-link w-fit">Xem toàn bộ sản phẩm</a>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($newArrivals as $product)
                    <article data-reveal class="product-card group">
                        <a href="{{ route('products.show', $product) }}" class="product-card-image block">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                        </a>

                        <div class="mt-5">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--color-clay)]">
                                    {{ $product->category }}
                                </p>
                                @if ($product->badge)
                                    <span class="product-badge">{{ $product->badge }}</span>
                                @endif
                            </div>
                            <h3 class="mt-3 text-2xl leading-tight display-title">
                                <a href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
                            </h3>
                            <p class="mt-2 max-w-sm text-sm leading-6 text-[color:var(--color-umber)]">
                                {{ $product->tagline }}
                            </p>
                        </div>

                        <div class="mt-6 flex items-center justify-between gap-4">
                            <div>
                                <p class="text-lg font-semibold text-[color:var(--color-ink)]">{{ $product->formatted_price }}</p>
                                <p class="text-sm text-[color:var(--color-umber)]">{{ number_format((float) $product->rating, 1, ',', '.') }}/5 từ {{ $product->reviews_count }} đánh giá</p>
                            </div>
                            <form action="{{ route('cart.store', $product) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-secondary px-5 py-2.5">Thêm vào giỏ</button>
                            </form>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-14 sm:py-18">
        <div class="section-shell grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
            <div data-reveal class="overflow-hidden rounded-[2.5rem]">
                <img
                    src="https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1600&q=80"
                    alt="Không gian nội thất được biên tập"
                    class="h-full min-h-[26rem] w-full object-cover"
                >
            </div>
            <div data-reveal class="surface-panel flex flex-col justify-between p-8 sm:p-10">
                <div>
                    <p class="eyebrow">Nhịp điệu không gian</p>
                    <h2 class="section-heading mt-3">Những căn phòng đẹp luôn được chọn lọc, không phải chất đầy.</h2>
                    <p class="mt-5 max-w-xl text-base leading-7 text-[color:var(--color-umber)]">
                        Danh mục của chúng tôi cố ý hẹp lại. Ít lựa chọn hơn nhưng phom dáng tốt hơn, bề mặt tốt hơn và ít nhiễu thị giác hơn khi đặt vào nhà.
                    </p>
                </div>

                <div class="mt-8 grid gap-5 sm:grid-cols-2">
                    <div class="rounded-[1.75rem] bg-[color:var(--color-ivory)] p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">Ưu tiên vật liệu</p>
                        <p class="mt-3 text-sm leading-6 text-[color:var(--color-umber)]">Đá, linen, gỗ sồi và gốm được chọn vì độ patina theo thời gian, không vì vẻ bóng bẩy ngắn hạn.</p>
                    </div>
                    <div class="rounded-[1.75rem] bg-[color:var(--color-ivory)] p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">Sẵn sàng cho studio</p>
                        <p class="mt-3 text-sm leading-6 text-[color:var(--color-umber)]">Có bảng thông số, hỗ trợ sourcing và gợi ý mix vật liệu cho các dự án residential.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-8 pt-14 sm:pb-12 sm:pt-18">
        <div class="section-shell">
            <div data-reveal class="mb-8 flex items-end justify-between gap-4">
                <div>
                    <p class="eyebrow">Tâm điểm bộ sưu tập</p>
                    <h2 class="section-heading mt-3">Những món đủ mạnh để làm điểm tựa cho cả căn phòng.</h2>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                @foreach ($featured->take(2) as $product)
                    <article data-reveal class="surface-panel overflow-hidden">
                        <div class="grid gap-6 p-4 sm:p-5 lg:grid-cols-[0.95fr_1.05fr] lg:items-center">
                            <a href="{{ route('products.show', $product) }}" class="overflow-hidden rounded-[2rem]">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full min-h-[22rem] w-full object-cover">
                            </a>
                            <div class="p-3 sm:p-4">
                                <p class="eyebrow">{{ $product->category }}</p>
                                <h3 class="mt-3 text-4xl leading-tight display-title">{{ $product->name }}</h3>
                                <p class="mt-4 text-sm leading-7 text-[color:var(--color-umber)]">{{ $product->description }}</p>
                                <div class="mt-6 flex items-center gap-4">
                                    <span class="text-2xl font-semibold text-[color:var(--color-ink)]">{{ $product->formatted_price }}</span>
                                    @if ($product->formatted_compare_price)
                                        <span class="text-sm text-[color:var(--color-umber)] line-through">{{ $product->formatted_compare_price }}</span>
                                    @endif
                                </div>
                                <div class="mt-8 flex flex-wrap gap-3">
                                    <a href="{{ route('products.show', $product) }}" class="btn-primary">Xem chi tiết</a>
                                    <form action="{{ route('cart.store', $product) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-secondary">Thêm vào giỏ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@endsection
