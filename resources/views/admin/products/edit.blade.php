@extends('admin.layout', ['title' => 'Chỉnh sửa sản phẩm | Aster Atelier'])

@section('content')
    <section class="grid gap-8 xl:grid-cols-[0.95fr_1.05fr]">
        <div class="surface-panel p-6 sm:p-8">
            <p class="eyebrow">Chỉnh sửa</p>
            <h2 class="mt-3 text-4xl display-title">{{ $product->name }}</h2>
            <p class="mt-3 max-w-xl text-sm leading-7 text-[color:var(--color-umber)]">
                Cập nhật nội dung, giá bán, ảnh và thông số. Thay đổi sẽ phản ánh ngay trên storefront.
            </p>

            <div class="mt-8">
                @include('admin.products._form', [
                    'action' => route('admin.products.update', $product),
                    'method' => 'PUT',
                    'submitLabel' => 'Lưu thay đổi',
                    'product' => $product,
                ])
            </div>
        </div>

        <div class="space-y-6">
            <div class="surface-panel overflow-hidden p-4 sm:p-5">
                <div class="overflow-hidden rounded-[2rem]">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full min-h-[24rem] w-full object-cover">
                </div>
                <div class="mt-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[color:var(--color-clay)]">{{ $product->category }}</p>
                    <h3 class="mt-2 text-3xl display-title">{{ $product->name }}</h3>
                    <p class="mt-3 text-sm leading-7 text-[color:var(--color-umber)]">{{ $product->description }}</p>
                    <div class="mt-5 flex items-center gap-4">
                        <span class="text-2xl font-semibold text-[color:var(--color-ink)]">{{ $product->formatted_price }}</span>
                        @if ($product->formatted_compare_price)
                            <span class="text-sm text-[color:var(--color-umber)] line-through">{{ $product->formatted_compare_price }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="surface-panel p-6 sm:p-8">
                <p class="eyebrow">Sản phẩm khác</p>
                <div class="mt-5 space-y-3">
                    @foreach ($products->where('id', '!=', $product->id)->take(5) as $item)
                        <a href="{{ route('admin.products.edit', $item) }}" class="flex items-center justify-between rounded-[1.25rem] border border-black/5 bg-white/70 px-4 py-3 text-sm transition hover:border-[color:var(--color-clay)]/30">
                            <span class="font-semibold text-[color:var(--color-ink)]">{{ $item->name }}</span>
                            <span class="text-[color:var(--color-umber)]">{{ $item->formatted_price }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection
