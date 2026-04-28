@extends('layouts.store', [
    'title' => 'Thanh toán | Aster Atelier',
    'description' => 'Hoàn tất đơn hàng tại Aster Atelier.',
])

@section('content')
    <section class="section-shell py-10 sm:py-14">
        <div class="mb-8">
            <p class="eyebrow">Thanh toán</p>
            <h1 class="mt-3 text-5xl leading-none display-title">Hoàn tất đơn hàng.</h1>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-[color:var(--color-umber)]">
                Bạn có thể đặt hàng với tư cách khách. Nếu đã có tài khoản, đăng nhập để lưu lịch sử đơn hàng vào hồ sơ của bạn.
            </p>
        </div>

        <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="surface-panel p-6 sm:p-8">
                <form action="{{ route('checkout.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="customer_name" class="label-text">Họ và tên</label>
                            <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name', $defaultName) }}" class="input-shell w-full">
                            @error('customer_name')<p class="label-note text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="customer_phone" class="label-text">Số điện thoại</label>
                            <input id="customer_phone" name="customer_phone" type="text" value="{{ old('customer_phone') }}" class="input-shell w-full">
                            @error('customer_phone')<p class="label-note text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div>
                        <label for="customer_email" class="label-text">Email nhận thông tin đơn hàng</label>
                        <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email', $defaultEmail) }}" class="input-shell w-full">
                        @error('customer_email')<p class="label-note text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="shipping_address" class="label-text">Địa chỉ giao hàng</label>
                        <textarea id="shipping_address" name="shipping_address" class="textarea-shell w-full">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')<p class="label-note text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="notes" class="label-text">Ghi chú đơn hàng</label>
                        <textarea id="notes" name="notes" class="textarea-shell w-full">{{ old('notes') }}</textarea>
                        @error('notes')<p class="label-note text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="submit" class="btn-primary">Đặt hàng ngay</button>
                        @guest
                            <a href="{{ route('login') }}" class="btn-secondary">Đăng nhập trước</a>
                        @endguest
                    </div>
                </form>
            </div>

            <aside class="surface-panel h-fit p-6 sm:p-8 lg:sticky lg:top-28">
                <p class="eyebrow">Tóm tắt đơn hàng</p>
                <div class="mt-6 space-y-4">
                    @foreach ($items as $item)
                        <div class="flex items-start gap-4">
                            <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}" class="h-18 w-18 rounded-[1.25rem] object-cover">
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-[color:var(--color-ink)]">{{ $item['product']->name }}</p>
                                <p class="mt-1 text-xs text-[color:var(--color-umber)]">SL {{ $item['quantity'] }}</p>
                            </div>
                            <p class="text-sm font-semibold text-[color:var(--color-ink)]">{{ \App\Models\Product::formatCurrency($item['line_total']) }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 space-y-4 border-t border-black/5 pt-6 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-[color:var(--color-umber)]">Tạm tính</span>
                        <span class="font-semibold text-[color:var(--color-ink)]">{{ \App\Models\Product::formatCurrency($cartSubtotal) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-[color:var(--color-umber)]">Vận chuyển</span>
                        <span class="font-semibold text-[color:var(--color-ink)]">{{ $cartShipping == 0.0 ? 'Miễn phí' : \App\Models\Product::formatCurrency($cartShipping) }}</span>
                    </div>
                    <div class="flex items-center justify-between border-t border-black/5 pt-4">
                        <span class="text-lg font-semibold text-[color:var(--color-ink)]">Tổng cộng</span>
                        <span class="text-2xl font-semibold text-[color:var(--color-ink)]">{{ \App\Models\Product::formatCurrency($cartTotal) }}</span>
                    </div>
                </div>
            </aside>
        </div>
    </section>
@endsection
