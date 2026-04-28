@extends('layouts.store', [
    'title' => 'Tra cứu đơn hàng | Aster Atelier',
    'description' => 'Tra cứu đơn hàng bằng mã đơn và email.',
])

@section('content')
    <section class="section-shell py-10 sm:py-14">
        <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="auth-card max-w-none">
                <p class="eyebrow">Tra cứu đơn</p>
                <h1 class="mt-3 text-5xl leading-none display-title">Tìm đơn hàng của bạn.</h1>
                <p class="mt-4 text-sm leading-7 text-[color:var(--color-umber)]">
                    Dùng mã đơn và email đã đặt để xem trạng thái và chi tiết đơn hàng, kể cả khi bạn mua mà không đăng nhập.
                </p>

                <form action="{{ route('orders.lookup.search') }}" method="POST" class="mt-8 space-y-5">
                    @csrf
                    <div>
                        <label for="order_number" class="label-text">Mã đơn hàng</label>
                        <input id="order_number" name="order_number" type="text" value="{{ old('order_number') }}" class="input-shell w-full">
                        @error('order_number')<p class="label-note text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="customer_email" class="label-text">Email đặt hàng</label>
                        <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email') }}" class="input-shell w-full">
                        @error('customer_email')<p class="label-note text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <button type="submit" class="btn-primary">Tra cứu</button>
                </form>
            </div>

            <div class="space-y-5">
                @if (($lookupAttempted ?? false) && ! $order)
                    <div class="surface-panel p-8">
                        <p class="text-2xl display-title">Không tìm thấy đơn phù hợp.</p>
                        <p class="mt-3 text-sm leading-7 text-[color:var(--color-umber)]">
                            Hãy kiểm tra lại mã đơn và email đã dùng khi thanh toán.
                        </p>
                    </div>
                @endif

                @if ($order)
                    <div class="surface-panel p-6 sm:p-8">
                        <p class="eyebrow">Kết quả tra cứu</p>
                        <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <h2 class="text-4xl display-title">{{ $order->order_number }}</h2>
                                <p class="mt-2 text-sm text-[color:var(--color-umber)]">Người nhận: {{ $order->customer_name }}</p>
                                <p class="mt-1 text-sm text-[color:var(--color-umber)]">Trạng thái: {{ $order->status === 'pending' ? 'Đang chờ xử lý' : $order->status }}</p>
                            </div>
                            <p class="text-xl font-semibold text-[color:var(--color-ink)]">{{ $order->formatted_total_amount }}</p>
                        </div>
                    </div>

                    <div class="surface-panel p-6 sm:p-8">
                        <div class="space-y-4">
                            @foreach ($order->items as $item)
                                <div class="flex items-start gap-4 border-b border-black/5 pb-4 last:border-b-0 last:pb-0">
                                    <img src="{{ $item->product_image_url }}" alt="{{ $item->product_name }}" class="h-18 w-18 rounded-[1.25rem] object-cover">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-[color:var(--color-ink)]">{{ $item->product_name }}</p>
                                        <p class="mt-1 text-xs text-[color:var(--color-umber)]">SL {{ $item->quantity }} x {{ $item->formatted_unit_price }}</p>
                                    </div>
                                    <p class="text-sm font-semibold text-[color:var(--color-ink)]">{{ $item->formatted_line_total }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
