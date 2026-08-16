<?php

namespace App\Http\Controllers\Admin;

use App\Admin\StoreSummary;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Products\ProductDraft;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(StoreSummary $summary): View
    {
        return view('admin.products.index', $summary->dashboard());
    }

    public function store(Request $request, ProductDraft $draft): RedirectResponse
    {
        Product::query()->create($draft->attributes($this->validatedData($request)));

        return redirect()
            ->route('admin.products.index')
            ->with('admin_status', 'Đã thêm sản phẩm mới.');
    }

    public function edit(Product $product, StoreSummary $summary): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'products' => Product::query()->orderBy('sort_order')->orderBy('name')->get(),
            'categories' => $summary->categoryOptions(),
        ]);
    }

    public function update(Request $request, Product $product, ProductDraft $draft): RedirectResponse
    {
        $product->update($draft->attributes($this->validatedData($request), $product));

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('admin_status', 'Đã cập nhật sản phẩm.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('admin_status', 'Đã xóa sản phẩm.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'tagline' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'rating' => ['required', 'numeric', 'between:0,5'],
            'reviews_count' => ['required', 'integer', 'min:0'],
            'badge' => ['nullable', 'string', 'max:255'],
            'image_url' => ['required', 'url', 'max:2048'],
            'gallery_text' => ['required', 'string'],
            'highlights_text' => ['required', 'string'],
            'specs_text' => ['required', 'string'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_featured' => ['nullable', 'boolean'],
        ]);
    }
}
