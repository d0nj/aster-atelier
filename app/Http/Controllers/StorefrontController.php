<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\CartStore;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StorefrontController extends Controller
{
    public function home(CartStore $cart): View
    {
        $featured = Product::query()
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(4)
            ->get();

        $newArrivals = Product::query()
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $collections = Product::query()
            ->select('category')
            ->selectRaw('count(*) as total')
            ->groupBy('category')
            ->orderBy('category')
            ->get();

        return view('storefront.home', [
            'featured' => $featured,
            'newArrivals' => $newArrivals,
            'collections' => $collections,
            ...$this->sharedData($cart),
        ]);
    }

    public function shop(Request $request, CartStore $cart): View
    {
        $selectedCategory = $request->string('category')->toString();
        $selectedSort = $request->string('sort')->toString() ?: 'curated';

        $categories = Product::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $products = Product::query()
            ->when($selectedCategory, function (Builder $query, string $category): void {
                $query->where('category', $category);
            });

        $products = match ($selectedSort) {
            'price_asc' => $products->orderBy('price'),
            'price_desc' => $products->orderByDesc('price'),
            'name' => $products->orderBy('name'),
            default => $products->orderBy('sort_order'),
        };

        return view('storefront.shop', [
            'products' => $products->get(),
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'selectedSort' => $selectedSort,
            ...$this->sharedData($cart),
        ]);
    }

    public function show(Product $product, CartStore $cart): View
    {
        $related = Product::query()
            ->where('category', $product->category)
            ->whereKeyNot($product->getKey())
            ->orderBy('sort_order')
            ->take(3)
            ->get();

        return view('storefront.product', [
            'product' => $product,
            'related' => $related,
            ...$this->sharedData($cart),
        ]);
    }

    public function cart(CartStore $cart): View
    {
        $items = $cart->items();

        return view('storefront.cart', [
            'items' => $items,
            'cartSubtotal' => $cart->subtotal(),
            'cartShipping' => $cart->shipping(),
            'cartTotal' => $cart->total(),
            'cartCount' => $cart->count(),
        ]);
    }

    /**
     * @return array<string, int|float|Collection<int, Product>>
     */
    private function sharedData(CartStore $cart): array
    {
        return [
            'cartCount' => $cart->count(),
            'cartSubtotal' => $cart->subtotal(),
        ];
    }
}
