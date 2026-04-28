<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Quản trị sản phẩm | Aster Atelier' }}</title>
        <meta name="description" content="Khu vực quản trị sản phẩm của Aster Atelier.">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Prata&display=swap" rel="stylesheet">
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen antialiased">
        <div class="pointer-events-none fixed inset-0 grain-overlay"></div>

        <div class="relative z-10 min-h-screen">
            <header class="section-shell py-6">
                <div class="surface-panel flex flex-col gap-5 px-5 py-5 sm:px-6">
                    <div class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">Bảng điều hành thương mại</p>
                            <h1 class="mt-2 text-3xl display-title">Quản trị sản phẩm</h1>
                            <p class="mt-3 max-w-2xl text-sm leading-7 text-[color:var(--color-umber)]">
                                Theo dõi đơn hàng, doanh thu và merchandising trong cùng một màn hình điều hành.
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <span class="admin-soft-chip">Store đang hoạt động</span>
                            @auth
                                <span class="admin-soft-chip">{{ \Illuminate\Support\Str::limit(auth()->user()->name, 24) }}</span>
                            @endauth
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.products.index') }}" class="btn-primary">Bảng điều khiển</a>
                        <a href="{{ route('home') }}" class="btn-secondary">Về storefront</a>
                        <a href="{{ route('orders.lookup') }}" class="btn-secondary">Tra cứu đơn</a>
                        @auth
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn-secondary">Đăng xuất</button>
                            </form>
                        @endauth
                    </div>
                </div>
            </header>

            @if (session('admin_status'))
                <div class="section-shell pb-2">
                    <div class="surface-panel flex items-center justify-between gap-4 px-5 py-4 text-sm text-[color:var(--color-ink)]">
                        <span>{{ session('admin_status') }}</span>
                        <a href="{{ route('admin.products.index') }}" class="font-semibold text-[color:var(--color-clay)]">Xem danh sách</a>
                    </div>
                </div>
            @endif

            <main class="section-shell pb-14 pt-4">
                @yield('content')
            </main>
        </div>
    </body>
</html>
