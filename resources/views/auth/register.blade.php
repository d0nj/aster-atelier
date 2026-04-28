@extends('layouts.store', [
    'title' => 'Đăng ký | Aster Atelier',
    'description' => 'Tạo tài khoản để quản lý đơn hàng tại Aster Atelier.',
])

@section('content')
    <section class="section-shell py-12 sm:py-16">
        <div class="auth-card">
            <p class="eyebrow">Tài khoản</p>
            <h1 class="mt-3 text-5xl leading-none display-title">Đăng ký</h1>
            <p class="mt-4 text-sm leading-7 text-[color:var(--color-umber)]">
                Tạo tài khoản để theo dõi đơn hàng và lưu thông tin mua sắm cho những lần sau.
            </p>

            <form action="{{ route('register.store') }}" method="POST" class="mt-8 space-y-5">
                @csrf
                <div>
                    <label for="name" class="label-text">Họ và tên</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" class="input-shell w-full">
                    @error('name')<p class="label-note text-red-600">{{ $message }}</p>@enderror
                </div>
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
                <div>
                    <label for="password_confirmation" class="label-text">Xác nhận mật khẩu</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="input-shell w-full">
                </div>
                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="btn-primary">Tạo tài khoản</button>
                    <a href="{{ route('login') }}" class="btn-secondary">Đã có tài khoản</a>
                </div>
            </form>
        </div>
    </section>
@endsection
