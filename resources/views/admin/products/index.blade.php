@extends('admin.layout', ['title' => 'Quản trị sản phẩm | Aster Atelier'])

@section('content')
    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="admin-stat">
            <p class="eyebrow">Doanh thu</p>
            <p class="admin-kpi-value">{{ \App\Models\Product::formatCurrency($totalRevenue) }}</p>
            <p class="mt-3 text-sm text-[color:var(--color-umber)]">Giá trị trung bình mỗi đơn: {{ \App\Models\Product::formatCurrency($averageOrderValue) }}</p>
        </div>
        <div class="admin-stat">
            <p class="eyebrow">Đơn hàng</p>
            <p class="admin-kpi-value">{{ number_format($totalOrders, 0, ',', '.') }}</p>
            <p class="mt-3 text-sm text-[color:var(--color-umber)]">{{ number_format($pendingOrders, 0, ',', '.') }} đơn đang chờ xử lý</p>
        </div>
        <div class="admin-stat">
            <p class="eyebrow">Khách vãng lai</p>
            <p class="admin-kpi-value">{{ number_format($guestOrderShare, 0, ',', '.') }}%</p>
            <p class="mt-3 text-sm text-[color:var(--color-umber)]">{{ number_format($guestOrders, 0, ',', '.') }} đơn guest, {{ number_format($registeredOrders, 0, ',', '.') }} từ tài khoản</p>
        </div>
        <div class="admin-stat">
            <p class="eyebrow">Danh mục</p>
            <p class="admin-kpi-value">{{ number_format($totalProducts, 0, ',', '.') }}</p>
            <p class="mt-3 text-sm text-[color:var(--color-umber)]">{{ number_format($featuredProducts, 0, ',', '.') }} nổi bật, {{ number_format($categoryCount, 0, ',', '.') }} danh mục</p>
        </div>
    </section>

    <section class="mt-8 grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
        <section class="surface-panel p-6 sm:p-8">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="eyebrow">Đơn hàng gần đây</p>
                    <h2 class="mt-3 text-4xl display-title">Theo dõi bán hàng</h2>
                </div>
                <a href="{{ route('orders.lookup') }}" class="btn-secondary px-4 py-2.5">Tra cứu đơn</a>
            </div>

            <div class="mt-6 overflow-hidden rounded-[1.5rem] border border-black/5">
                @forelse ($recentOrders as $order)
                    <div class="grid gap-3 border-t border-black/5 bg-white/55 px-4 py-4 first:border-t-0 md:grid-cols-[1fr_auto_auto] md:items-center">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-[color:var(--color-ink)]">{{ $order->order_number }}</p>
                            <p class="mt-1 truncate text-sm text-[color:var(--color-umber)]">{{ $order->customer_name }} • {{ $order->customer_email }}</p>
                        </div>
                        <span class="admin-soft-chip">
                            {{ $order->status === 'pending' ? 'Đang chờ xử lý' : ucfirst($order->status) }}
                        </span>
                        <p class="text-sm font-semibold text-[color:var(--color-ink)]">{{ $order->formatted_total_amount }}</p>
                    </div>
                @empty
                    <div class="px-4 py-6 text-sm text-[color:var(--color-umber)]">
                        Chưa có đơn hàng nào trong hệ thống.
                    </div>
                @endforelse
            </div>
        </section>

        <section class="surface-panel p-6 sm:p-8">
            <p class="eyebrow">Nhịp catalog</p>
            <h2 class="mt-3 text-4xl display-title">Nhịp cập nhật sản phẩm</h2>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-[1.5rem] border border-black/5 bg-[color:var(--color-ivory)] px-5 py-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">Khuyến mại</p>
                    <p class="mt-3 text-3xl display-title">{{ number_format($saleProducts, 0, ',', '.') }}</p>
                </div>
                <div class="rounded-[1.5rem] border border-black/5 bg-[color:var(--color-ivory)] px-5 py-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">Đẩy trang chủ</p>
                    <p class="mt-3 text-3xl display-title">{{ number_format($featuredProducts, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-8">
                <p class="text-sm font-semibold text-[color:var(--color-ink)]">Cập nhật gần đây</p>
                <div class="mt-4 space-y-3">
                    @foreach ($recentlyUpdatedProducts as $item)
                        <a href="{{ route('admin.products.edit', $item) }}" class="flex items-center justify-between gap-4 rounded-[1.25rem] border border-black/5 bg-white/55 px-4 py-3 transition hover:border-[color:var(--color-clay)]/30">
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-semibold text-[color:var(--color-ink)]">{{ $item->name }}</span>
                                <span class="mt-1 block text-xs text-[color:var(--color-umber)]">{{ $item->updated_at?->format('d/m/Y H:i') }}</span>
                            </span>
                            <span class="text-sm font-semibold text-[color:var(--color-clay)]">Mở</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    </section>

    <section class="mt-8 surface-panel p-6 sm:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="eyebrow">Bảng tồn kho</p>
                <h2 class="mt-3 text-4xl display-title">Danh mục đang bán</h2>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-[color:var(--color-umber)]">
                    Bảng này chỉ giữ thông tin vận hành cần xem nhanh: giá, merchandising, độ tin cậy và lần cập nhật gần nhất.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <span class="admin-soft-chip">{{ number_format($categoryCount, 0, ',', '.') }} danh mục</span>
                <span class="admin-soft-chip">{{ number_format($saleProducts, 0, ',', '.') }} khuyến mại</span>
                <a href="#product-composer" class="btn-primary px-4 py-2.5">Thêm sản phẩm</a>
            </div>
        </div>

        <div class="mt-8 overflow-hidden rounded-[1.75rem] border border-black/5 bg-white/55">
            <div class="hidden lg:block overflow-x-auto">
                <table class="min-w-full table-fixed text-left">
                    <colgroup>
                        <col class="w-[34%]">
                        <col class="w-[12%]">
                        <col class="w-[15%]">
                        <col class="w-[11%]">
                        <col class="w-[13%]">
                        <col class="w-[15%]">
                    </colgroup>
                    <thead class="bg-[color:var(--color-ivory)]/85 text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--color-umber)]">
                        <tr>
                            <th class="px-5 py-4">Sản phẩm</th>
                            <th class="px-5 py-4">Giá</th>
                            <th class="px-5 py-4">Merchandising</th>
                            <th class="px-5 py-4">Đánh giá</th>
                            <th class="px-5 py-4">Cập nhật</th>
                            <th class="px-5 py-4 text-right">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="border-t border-black/5 align-top">
                                <td class="px-5 py-5">
                                    <div class="flex items-start gap-4">
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-18 w-18 shrink-0 rounded-[1.2rem] object-cover">
                                        <div class="min-w-0">
                                            <p class="truncate text-base font-semibold text-[color:var(--color-ink)]">{{ $product->name }}</p>
                                            <p class="mt-1 text-sm font-semibold text-[color:var(--color-clay)]">{{ $product->category }}</p>
                                            <p class="mt-2 line-clamp-2 text-sm leading-6 text-[color:var(--color-umber)]">{{ $product->tagline }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-5">
                                    <p class="text-sm font-semibold text-[color:var(--color-ink)]">{{ $product->formatted_price }}</p>
                                    @if ($product->formatted_compare_price)
                                        <p class="mt-1 text-xs text-[color:var(--color-umber)] line-through">{{ $product->formatted_compare_price }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-5">
                                    <div class="flex flex-wrap gap-2">
                                        @if ($product->is_featured)
                                            <span class="admin-soft-chip">Nổi bật</span>
                                        @endif
                                        @if ($product->compare_price)
                                            <span class="admin-soft-chip">Khuyến mại</span>
                                        @endif
                                        @if ($product->badge)
                                            <span class="product-badge">{{ $product->badge }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-5">
                                    <p class="text-sm font-semibold text-[color:var(--color-ink)]">{{ number_format((float) $product->rating, 1, ',', '.') }}/5</p>
                                    <p class="mt-1 text-xs text-[color:var(--color-umber)]">{{ number_format($product->reviews_count, 0, ',', '.') }} đánh giá</p>
                                </td>
                                <td class="px-5 py-5">
                                    <p class="text-sm font-semibold text-[color:var(--color-ink)]">Thứ tự {{ $product->sort_order }}</p>
                                    <p class="mt-1 text-xs text-[color:var(--color-umber)]">{{ $product->updated_at?->format('d/m/Y H:i') }}</p>
                                </td>
                                <td class="px-5 py-5">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <a href="{{ route('products.show', $product) }}" class="btn-secondary px-4 py-2.5">Xem</a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn-secondary px-4 py-2.5">Sửa</a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-full border border-red-200 px-4 py-2.5 text-sm font-semibold text-red-700 transition hover:bg-red-50">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-sm text-[color:var(--color-umber)]">
                                    Chưa có sản phẩm nào trong hệ thống.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="space-y-4 p-4 lg:hidden">
                @forelse ($products as $product)
                    <article class="rounded-[1.5rem] border border-black/5 bg-white/70 p-4">
                        <div class="flex items-start gap-4">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-18 w-18 shrink-0 rounded-[1.2rem] object-cover">
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-base font-semibold text-[color:var(--color-ink)]">{{ $product->name }}</p>
                                <p class="mt-1 text-sm font-semibold text-[color:var(--color-clay)]">{{ $product->category }}</p>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @if ($product->is_featured)
                                        <span class="admin-soft-chip">Nổi bật</span>
                                    @endif
                                    @if ($product->compare_price)
                                        <span class="admin-soft-chip">Khuyến mại</span>
                                    @endif
                                    @if ($product->badge)
                                        <span class="product-badge">{{ $product->badge }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 grid gap-3 sm:grid-cols-3">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--color-umber)]">Giá</p>
                                <p class="mt-2 text-sm font-semibold text-[color:var(--color-ink)]">{{ $product->formatted_price }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--color-umber)]">Đánh giá</p>
                                <p class="mt-2 text-sm font-semibold text-[color:var(--color-ink)]">{{ number_format((float) $product->rating, 1, ',', '.') }}/5</p>
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[color:var(--color-umber)]">Thứ tự</p>
                                <p class="mt-2 text-sm font-semibold text-[color:var(--color-ink)]">{{ $product->sort_order }}</p>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('products.show', $product) }}" class="btn-secondary px-4 py-2.5">Xem</a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-secondary px-4 py-2.5">Sửa</a>
                        </div>
                    </article>
                @empty
                    <div class="px-2 py-4 text-sm text-[color:var(--color-umber)]">
                        Chưa có sản phẩm nào trong hệ thống.
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="product-composer" class="mt-8 surface-panel p-6 sm:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="eyebrow">Trình tạo sản phẩm</p>
                <h2 class="mt-3 text-4xl display-title">Tạo SKU mới</h2>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-[color:var(--color-umber)]">
                    Khu nhập liệu được tách riêng khỏi inventory board để bạn thao tác catalog mà không làm rối màn hình quản trị.
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                @foreach ($categories->take(6) as $category)
                    <span class="admin-soft-chip">{{ $category }}</span>
                @endforeach
            </div>
        </div>

        <div class="mt-8">
            @include('admin.products._form', [
                'action' => route('admin.products.store'),
                'submitLabel' => 'Lưu sản phẩm',
                'product' => null,
            ])
        </div>
    </section>
@endsection
