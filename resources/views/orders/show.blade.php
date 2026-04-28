@extends('layouts.store', [
    'title' => 'Chi tiết đơn hàng | Aster Atelier',
    'description' => 'Xem chi tiết đơn hàng tại Aster Atelier.',
])

@section('content')
    <section class="section-shell py-10 sm:py-14">
        <div class="mb-8">
            <p class="eyebrow">Chi tiết đơn hàng</p>
            <h1 class="mt-3 text-5xl leading-none display-title">Mã đơn {{ $order->order_number }}</h1>
            <p class="mt-4 text-sm leading-7 text-[color:var(--color-umber)]">
                Trạng thái hiện tại: {{ $order->status === 'pending' ? 'Đang chờ xử lý' : $order->status }}.
            </p>
        </div>

        <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="surface-panel p-6 sm:p-8">
                <div class="space-y-4">
                    @foreach ($order->items as $item)
                        <div class="flex items-start gap-4 border-b border-black/5 pb-4 last:border-b-0 last:pb-0">
                            <img src="{{ $item->product_image_url }}" alt="{{ $item->product_name }}" class="h-20 w-20 rounded-[1.25rem] object-cover">
                            <div class="min-w-0 flex-1">
                                <p class="text-lg font-semibold text-[color:var(--color-ink)]">{{ $item->product_name }}</p>
                                <p class="mt-1 text-sm text-[color:var(--color-umber)]">SL {{ $item->quantity }} x {{ $item->formatted_unit_price }}</p>
                            </div>
                            <p class="text-sm font-semibold text-[color:var(--color-ink)]">{{ $item->formatted_line_total }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <aside class="space-y-5">
                <div class="surface-panel p-6 sm:p-8">
                    <p class="eyebrow">Thông tin nhận hàng</p>
                    <p class="mt-4 text-lg font-semibold text-[color:var(--color-ink)]">{{ $order->customer_name }}</p>
                    <p class="mt-2 text-sm text-[color:var(--color-umber)]">{{ $order->customer_email }}</p>
                    <p class="mt-1 text-sm text-[color:var(--color-umber)]">{{ $order->customer_phone }}</p>
                    <p class="mt-4 text-sm leading-7 text-[color:var(--color-umber)]">{{ $order->shipping_address }}</p>
                    @if ($order->notes)
                        <div class="mt-4 rounded-[1.5rem] bg-[color:var(--color-ivory)] px-4 py-3 text-sm text-[color:var(--color-umber)]">
                            Ghi chú: {{ $order->notes }}
                        </div>
                    @endif
                </div>

                <div class="surface-panel p-6 sm:p-8">
                    <p class="eyebrow">Thanh toán</p>
                    <div class="mt-5 space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-[color:var(--color-umber)]">Tạm tính</span>
                            <span class="font-semibold text-[color:var(--color-ink)]">{{ $order->formatted_subtotal }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[color:var(--color-umber)]">Vận chuyển</span>
                            <span class="font-semibold text-[color:var(--color-ink)]">{{ $order->shipping_amount == 0.0 ? 'Miễn phí' : $order->formatted_shipping_amount }}</span>
                        </div>
                        <div class="flex items-center justify-between border-t border-black/5 pt-4">
                            <span class="text-lg font-semibold text-[color:var(--color-ink)]">Tổng cộng</span>
                            <span class="text-2xl font-semibold text-[color:var(--color-ink)]">{{ $order->formatted_total_amount }}</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection
