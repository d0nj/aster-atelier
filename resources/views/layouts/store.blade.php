<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Aster Atelier' }}</title>
        <meta
            name="description"
            content="{{ $description ?? 'Aster Atelier là cửa hàng tuyển chọn nội thất, gốm sứ, ánh sáng và phụ kiện sống chậm.' }}"
        >
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Prata&display=swap" rel="stylesheet">
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen antialiased">
        <div class="pointer-events-none fixed inset-0 grain-overlay"></div>

        <header
            data-site-header
            class="fixed inset-x-0 top-0 z-40 transition duration-300"
        >
            <div class="section-shell">
                <div class="mt-4 rounded-full border border-white/60 bg-white/72 shadow-[0_8px_30px_rgba(24,22,17,0.05)] backdrop-blur-md">
                    <div class="flex items-center justify-between gap-4 px-4 py-3 sm:px-6">
                        <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[color:var(--color-ink)] text-sm font-bold text-[color:var(--color-ivory)]">AA</span>
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-semibold uppercase tracking-[0.28em] text-[color:var(--color-clay)]">Aster Atelier</span>
                                <span class="hidden truncate text-xs text-[color:var(--color-umber)]/75 xl:block">Đồ vật cho những căn phòng sống chậm</span>
                            </span>
                        </a>

                        <nav class="hidden lg:flex items-center gap-6 xl:gap-8">
                            <a href="{{ route('home') }}" class="nav-link">Trang chủ</a>
                            <a href="{{ route('shop.index') }}" class="nav-link">Cửa hàng</a>
                            <a href="{{ route('orders.lookup') }}" class="nav-link">Tra cứu đơn</a>
                        </nav>

                        <div class="flex items-center gap-2 sm:gap-3">
                            <a href="{{ route('cart.index') }}" class="btn-secondary px-3 py-2.5 sm:px-4">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M3 4h2l2.6 10.4a1 1 0 0 0 1 .76h8.96a1 1 0 0 0 .98-.8L20 7H7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="10" cy="20" r="1.5" fill="currentColor"/>
                                    <circle cx="18" cy="20" r="1.5" fill="currentColor"/>
                                </svg>
                                <span class="hidden sm:inline">Giỏ hàng</span>
                                <span class="rounded-full bg-[color:var(--color-ink)] px-2 py-0.5 text-xs font-bold text-[color:var(--color-ivory)]">{{ $cartCount ?? 0 }}</span>
                            </a>

                            <div class="hidden lg:flex items-center gap-4">
                                @auth
                                    <a href="{{ route('orders.index') }}" class="nav-link">Đơn hàng</a>
                                    @if (auth()->user()?->is_admin)
                                        <a href="{{ route('admin.products.index') }}" class="nav-link">Admin</a>
                                    @endif
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-secondary px-4 py-2.5">Đăng xuất</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="nav-link">Đăng nhập</a>
                                    <a href="{{ route('register') }}" class="btn-primary px-4 py-2.5">Đăng ký</a>
                                @endauth
                            </div>

                            <button
                                type="button"
                                data-mobile-toggle
                                class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-[color:var(--color-umber)]/15 bg-white/85 text-[color:var(--color-ink)] lg:hidden"
                                aria-label="Toggle navigation"
                            >
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    data-mobile-menu
                    data-open="false"
                    class="surface-panel mt-3 hidden p-4 lg:hidden"
                >
                    <div class="flex flex-col gap-3 text-sm font-semibold text-[color:var(--color-ink)]">
                        @auth
                            <div class="rounded-[1.5rem] bg-[color:var(--color-ivory)] px-4 py-3">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">Tài khoản</p>
                                <p class="mt-2 text-sm font-semibold text-[color:var(--color-ink)]">{{ auth()->user()->name }}</p>
                            </div>
                        @endauth
                        <a href="{{ route('home') }}">Trang chủ</a>
                        <a href="{{ route('shop.index') }}">Xem toàn bộ</a>
                        <a href="{{ route('shop.index', ['category' => 'Nội thất']) }}">Nội thất</a>
                        <a href="{{ route('shop.index', ['category' => 'Gốm sứ']) }}">Gốm sứ</a>
                        <a href="{{ route('orders.lookup') }}">Tra cứu đơn hàng</a>
                        @auth
                            <a href="{{ route('orders.index') }}">Đơn hàng của tôi</a>
                            @if (auth()->user()?->is_admin)
                                <a href="{{ route('admin.products.index') }}">Quản trị sản phẩm</a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-left">Đăng xuất</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}">Đăng nhập</a>
                            <a href="{{ route('register') }}">Đăng ký</a>
                        @endauth
                    </div>
                </div>
            </div>
        </header>

        @if (session('status'))
            <div class="section-shell relative z-30 pt-24 lg:pt-28">
                <div class="surface-panel mt-4 flex items-center justify-between gap-4 px-5 py-4 text-sm text-[color:var(--color-ink)]">
                    <span>{{ session('status') }}</span>
                    <a href="{{ route('cart.index') }}" class="font-semibold text-[color:var(--color-clay)]">Xem giỏ hàng</a>
                </div>
            </div>
        @endif

        <main class="relative z-10 pt-24 lg:pt-28">
            @yield('content')
        </main>

        <footer class="relative z-10 mt-24 border-t border-black/5 py-10">
            <div class="section-shell grid gap-8 md:grid-cols-[1.4fr_1fr_1fr]">
                <div>
                    <p class="eyebrow">Aster Atelier</p>
                    <h2 class="mt-4 max-w-md text-3xl leading-tight text-balance display-title">
                        Những món đồ đủ tĩnh để ở lâu, đủ sắc để đổi cảm giác của cả căn phòng.
                    </h2>
                </div>
                <div class="space-y-3 text-sm text-[color:var(--color-umber)]">
                    <p class="font-semibold text-[color:var(--color-ink)]">Trải nghiệm</p>
                    <p>Đặt lịch xem mẫu tại showroom</p>
                    <p>Mở cửa từ thứ Ba đến thứ Bảy</p>
                    <p>10:00 đến 18:00</p>
                </div>
                <div class="space-y-3 text-sm text-[color:var(--color-umber)]">
                    <p class="font-semibold text-[color:var(--color-ink)]">Dịch vụ</p>
                    <p>Giao toàn quốc và hỗ trợ đơn studio</p>
                    <p>Bảng giá riêng cho đối tác nội thất</p>
                    <p>Hướng dẫn bảo quản đi kèm từng đơn hàng</p>
                </div>
            </div>
        </footer>
    </body>
</html>
