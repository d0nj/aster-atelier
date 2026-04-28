<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::query()->orderBy('sort_order')->orderBy('name')->get();
        $totalOrders = Order::query()->count();
        $totalRevenue = (float) Order::query()->sum('total_amount');
        $guestOrders = Order::query()->whereNull('user_id')->count();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => $this->categories(),
            'totalProducts' => $products->count(),
            'featuredProducts' => Product::query()->where('is_featured', true)->count(),
            'categoryCount' => Product::query()->distinct('category')->count('category'),
            'saleProducts' => Product::query()->whereNotNull('compare_price')->count(),
            'totalOrders' => $totalOrders,
            'pendingOrders' => Order::query()->where('status', 'pending')->count(),
            'guestOrders' => $guestOrders,
            'registeredOrders' => max($totalOrders - $guestOrders, 0),
            'guestOrderShare' => $totalOrders > 0 ? round(($guestOrders / $totalOrders) * 100) : 0,
            'totalRevenue' => $totalRevenue,
            'averageOrderValue' => $totalOrders > 0 ? $totalRevenue / $totalOrders : 0,
            'recentOrders' => Order::query()->latest()->take(5)->get(),
            'recentlyUpdatedProducts' => Product::query()->latest('updated_at')->take(6)->get(),
            'topCategories' => Product::query()
                ->selectRaw('category, COUNT(*) as total')
                ->groupBy('category')
                ->orderByDesc('total')
                ->take(5)
                ->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Product::query()->create($this->validatedData($request));

        return redirect()
            ->route('admin.products.index')
            ->with('admin_status', 'Đã thêm sản phẩm mới.');
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product' => $product,
            'products' => Product::query()->orderBy('sort_order')->orderBy('name')->get(),
            'categories' => $this->categories(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validatedData($request, $product));

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
    private function validatedData(Request $request, ?Product $product = null): array
    {
        $validated = $request->validate([
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

        $gallery = $this->parseUrlLines($validated['gallery_text'], 'gallery_text');
        $highlights = $this->parseLines($validated['highlights_text'], 'highlights_text');
        $specs = $this->parseSpecs($validated['specs_text']);

        return [
            'slug' => $this->uniqueSlug($validated['slug'] ?: $validated['name'], $product),
            'name' => $validated['name'],
            'category' => $validated['category'],
            'tagline' => $validated['tagline'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'compare_price' => $validated['compare_price'] ?: null,
            'rating' => $validated['rating'],
            'reviews_count' => $validated['reviews_count'],
            'badge' => $validated['badge'] ?: null,
            'image_url' => $validated['image_url'],
            'gallery' => $gallery,
            'highlights' => $highlights,
            'specs' => $specs,
            'sort_order' => $validated['sort_order'],
            'is_featured' => $request->boolean('is_featured'),
        ];
    }

    /**
     * @return Collection<int, string>
     */
    private function categories(): Collection
    {
        return Product::categoryOptions()
            ->merge(Product::query()->select('category')->distinct()->pluck('category'))
            ->unique()
            ->values();
    }

    /**
     * @return array<int, string>
     */
    private function parseLines(string $value, string $field): array
    {
        $lines = collect(preg_split('/\r\n|\r|\n/', $value) ?: [])
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();

        if ($lines === []) {
            throw ValidationException::withMessages([
                $field => 'Vui lòng nhập ít nhất một dòng dữ liệu.',
            ]);
        }

        return $lines;
    }

    /**
     * @return array<int, string>
     */
    private function parseUrlLines(string $value, string $field): array
    {
        $lines = $this->parseLines($value, $field);

        foreach ($lines as $line) {
            if (! filter_var($line, FILTER_VALIDATE_URL)) {
                throw ValidationException::withMessages([
                    $field => 'Mỗi dòng phải là một URL hợp lệ.',
                ]);
            }
        }

        return $lines;
    }

    /**
     * @return array<string, string>
     */
    private function parseSpecs(string $value): array
    {
        $lines = $this->parseLines($value, 'specs_text');
        $specs = [];

        foreach ($lines as $line) {
            $parts = explode(':', $line, 2);

            if (count($parts) !== 2) {
                throw ValidationException::withMessages([
                    'specs_text' => 'Mỗi dòng thông số phải theo định dạng "Tên: Giá trị".',
                ]);
            }

            $label = trim($parts[0]);
            $content = trim($parts[1]);

            if ($label === '' || $content === '') {
                throw ValidationException::withMessages([
                    'specs_text' => 'Tên và giá trị thông số không được để trống.',
                ]);
            }

            $specs[$label] = $content;
        }

        return $specs;
    }

    private function uniqueSlug(string $value, ?Product $product = null): string
    {
        $base = Str::slug($value) ?: 'san-pham';
        $slug = $base;
        $index = 2;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when($product, fn ($query) => $query->whereKeyNot($product->getKey()))
                ->exists()
        ) {
            $slug = $base . '-' . $index;
            $index++;
        }

        return $slug;
    }
}
