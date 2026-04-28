@extends('layouts.store', [
    'title' => 'Đặt hàng thành công | Aster Atelier',
    'description' => 'Đơn hàng của bạn đã được tạo thành công.',
])

@section('content')
    <section class="section-shell py-12 sm:py-16">
        <div class="auth-card max-w-3xl">
            <p class="eyebrow">Đặt hàng thành công</p>
            <h1 class="mt-3 text-5xl leading-none display-title">Cảm ơn bạn đã đặt hàng.</h1>
            <p class="mt-4 text-sm leading-7 text-[color:var(--color-umber)]">
                Mã đơn của bạn là <span class="font-semibold text-[color:var(--color-ink)]">{{ $order->order_number }}</span>.
                Chúng tôi sẽ liên hệ qua email hoặc số điện thoại bạn đã cung cấp.
            </p>

            <div class="mt-8 grid gap-5 md:grid-cols-2">
                <div class="surface-panel p-5">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">Thông tin đơn</p>
                    <p class="mt-3 text-sm text-[color:var(--color-umber)]">Tổng giá trị: <span class="font-semibold text-[color:var(--color-ink)]">{{ $order->formatted_total_amount }}</span></p>
                    <p class="mt-2 text-sm text-[color:var(--color-umber)]">Trạng thái: Đang chờ xử lý</p>
                </div>
                <div class="surface-panel p-5">
                    <p class="text-sm font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">Tra cứu sau này</p>
                    <p class="mt-3 text-sm text-[color:var(--color-umber)]">
                        Bạn có thể tra cứu đơn bằng mã đơn và email tại trang tra cứu đơn hàng, kể cả khi mua với tư cách khách.
                    </p>
                </div>
            </div>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('orders.lookup') }}" class="btn-primary">Tra cứu đơn hàng</a>
                @auth
                    <a href="{{ route('orders.show', $order) }}" class="btn-secondary">Xem trong tài khoản</a>
                @else
                    <a href="{{ route('register') }}" class="btn-secondary">Tạo tài khoản để lưu đơn</a>
                @endauth
            </div>
        </div>
    </section>
@endsection
