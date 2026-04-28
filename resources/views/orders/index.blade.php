@extends('layouts.store', [
    'title' => 'Đơn hàng của tôi | Aster Atelier',
    'description' => 'Xem lịch sử đơn hàng trong tài khoản của bạn.',
])

@section('content')
    <section class="section-shell py-10 sm:py-14">
        <div class="mb-8">
            <p class="eyebrow">Tài khoản</p>
            <h1 class="mt-3 text-5xl leading-none display-title">Đơn hàng của tôi</h1>
        </div>

        <div class="space-y-5">
            @forelse ($orders as $order)
                <article class="surface-panel p-6 sm:p-8">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">Mã đơn {{ $order->order_number }}</p>
                            <h2 class="mt-2 text-3xl display-title">{{ $order->customer_name }}</h2>
                            <p class="mt-2 text-sm text-[color:var(--color-umber)]">Trạng thái: {{ $order->status === 'pending' ? 'Đang chờ xử lý' : $order->status }}</p>
                            <p class="mt-1 text-sm text-[color:var(--color-umber)]">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="sm:text-right">
                            <p class="text-lg font-semibold text-[color:var(--color-ink)]">{{ $order->formatted_total_amount }}</p>
                            <a href="{{ route('orders.show', $order) }}" class="btn-secondary mt-3 px-4 py-2.5">Xem chi tiết</a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="surface-panel p-8 sm:p-10">
                    <p class="text-2xl display-title">Bạn chưa có đơn hàng nào.</p>
                    <a href="{{ route('shop.index') }}" class="btn-primary mt-6 w-fit">Bắt đầu mua sắm</a>
                </div>
            @endforelse
        </div>
    </section>
@endsection
