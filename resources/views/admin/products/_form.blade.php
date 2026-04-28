@php
    $specsValue = old(
        'specs_text',
        isset($product) && $product
            ? collect($product->specs)->map(fn ($value, $label) => $label . ': ' . $value)->implode(PHP_EOL)
            : ''
    );
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @isset($method)
        @method($method)
    @endisset

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="name" class="label-text">Tên sản phẩm</label>
            <input id="name" name="name" type="text" value="{{ old('name', $product->name ?? '') }}" class="input-shell w-full">
            @error('name')<p class="label-note text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="slug" class="label-text">Slug</label>
            <input id="slug" name="slug" type="text" value="{{ old('slug', $product->slug ?? '') }}" class="input-shell w-full">
            <p class="label-note">Có thể để trống, hệ thống sẽ tự tạo từ tên sản phẩm.</p>
            @error('slug')<p class="label-note text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="category" class="label-text">Danh mục</label>
            <input id="category" name="category" list="category-options" type="text" value="{{ old('category', $product->category ?? '') }}" class="input-shell w-full">
            <datalist id="category-options">
                @foreach ($categories as $category)
                    <option value="{{ $category }}"></option>
                @endforeach
            </datalist>
            @error('category')<p class="label-note text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="badge" class="label-text">Nhãn nổi bật</label>
            <input id="badge" name="badge" type="text" value="{{ old('badge', $product->badge ?? '') }}" class="input-shell w-full">
            <p class="label-note">Ví dụ: Bán chạy, Mới, Giới hạn.</p>
            @error('badge')<p class="label-note text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="tagline" class="label-text">Tagline ngắn</label>
        <input id="tagline" name="tagline" type="text" value="{{ old('tagline', $product->tagline ?? '') }}" class="input-shell w-full">
        @error('tagline')<p class="label-note text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="description" class="label-text">Mô tả chi tiết</label>
        <textarea id="description" name="description" class="textarea-shell w-full">{{ old('description', $product->description ?? '') }}</textarea>
        @error('description')<p class="label-note text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-4">
        <div>
            <label for="price" class="label-text">Giá bán (VND)</label>
            <input id="price" name="price" type="number" step="1000" min="0" value="{{ old('price', $product->price ?? '') }}" class="input-shell w-full">
            @error('price')<p class="label-note text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="compare_price" class="label-text">Giá gạch (VND)</label>
            <input id="compare_price" name="compare_price" type="number" step="1000" min="0" value="{{ old('compare_price', $product->compare_price ?? '') }}" class="input-shell w-full">
            @error('compare_price')<p class="label-note text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="rating" class="label-text">Điểm đánh giá</label>
            <input id="rating" name="rating" type="number" step="0.1" min="0" max="5" value="{{ old('rating', $product->rating ?? '4.8') }}" class="input-shell w-full">
            @error('rating')<p class="label-note text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="reviews_count" class="label-text">Số review</label>
            <input id="reviews_count" name="reviews_count" type="number" min="0" value="{{ old('reviews_count', $product->reviews_count ?? '0') }}" class="input-shell w-full">
            @error('reviews_count')<p class="label-note text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <label for="image_url" class="label-text">Ảnh chính</label>
            <input id="image_url" name="image_url" type="url" value="{{ old('image_url', $product->image_url ?? '') }}" class="input-shell w-full">
            @error('image_url')<p class="label-note text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="sort_order" class="label-text">Thứ tự hiển thị</label>
            <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $product->sort_order ?? '0') }}" class="input-shell w-full">
            <label class="mt-4 flex items-center gap-3 text-sm font-semibold text-[color:var(--color-ink)]">
                <input type="checkbox" name="is_featured" value="1" class="h-4 w-4 rounded border-[color:var(--color-umber)]/20" @checked(old('is_featured', $product->is_featured ?? false))>
                Đưa vào nhóm nổi bật
            </label>
            @error('sort_order')<p class="label-note text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label for="gallery_text" class="label-text">Bộ ảnh phụ</label>
        <textarea id="gallery_text" name="gallery_text" class="textarea-shell w-full">{{ old('gallery_text', isset($product) && $product ? implode(PHP_EOL, $product->gallery ?? []) : '') }}</textarea>
        <p class="label-note">Mỗi dòng là một URL ảnh.</p>
        @error('gallery_text')<p class="label-note text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="highlights_text" class="label-text">Điểm nổi bật</label>
        <textarea id="highlights_text" name="highlights_text" class="textarea-shell w-full">{{ old('highlights_text', isset($product) && $product ? implode(PHP_EOL, $product->highlights ?? []) : '') }}</textarea>
        <p class="label-note">Mỗi dòng là một bullet.</p>
        @error('highlights_text')<p class="label-note text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="specs_text" class="label-text">Thông số kỹ thuật</label>
        <textarea id="specs_text" name="specs_text" class="textarea-shell w-full">{{ $specsValue }}</textarea>
        <p class="label-note">Nhập theo định dạng: Tên thông số: Giá trị</p>
        @error('specs_text')<p class="label-note text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="btn-primary">{{ $submitLabel }}</button>
        <a href="{{ route('admin.products.index') }}" class="btn-secondary">Quay lại danh sách</a>
    </div>
</form>
