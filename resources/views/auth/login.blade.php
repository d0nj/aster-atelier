@extends('layouts.store', [
    'title' => 'Đăng nhập | Aster Atelier',
    'description' => 'Đăng nhập để xem đơn hàng và lưu thông tin mua sắm của bạn.',
])

@section('content')
    <section class="section-shell py-12 sm:py-16">
        <div class="auth-card">
            <p class="eyebrow">Tài khoản</p>
            <h1 class="mt-3 text-5xl leading-none display-title">Đăng nhập</h1>
            <p class="mt-4 text-sm leading-7 text-[color:var(--color-umber)]">
                Đăng nhập để xem lịch sử đơn hàng. Bạn vẫn có thể mua hàng mà không cần tài khoản.
            </p>

            <form action="{{ route('login.store') }}" method="POST" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label for="email" class="label-text">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" class="input-shell w-full">
                    @error('email')<p class="label-note text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="label-text">Mật khẩu</label>
                    <input id="password" name="password" type="password" class="input-shell w-full">
                    @error('password')<p class="label-note text-red-600">{{ $message }}</p>@enderror
                </div>
                <label class="flex items-center gap-3 text-sm font-semibold text-[color:var(--color-ink)]">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-[color:var(--color-umber)]/20">
                    Ghi nhớ đăng nhập
                </label>
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn-primary">Đăng nhập</button>
                    <a href="{{ route('register') }}" class="btn-secondary">Tạo tài khoản</a>
                </div>
            </form>
        </div>
    </section>
@endsection
