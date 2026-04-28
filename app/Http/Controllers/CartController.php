<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Support\CartStore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request, Product $product, CartStore $cart): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:8'],
        ]);

        $cart->add($product, $validated['quantity'] ?? 1);

        return redirect()
            ->back()
            ->with('status', "Đã thêm {$product->name} vào giỏ hàng.");
    }

    public function update(Request $request, Product $product, CartStore $cart): RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        $cart->update($product, $validated['quantity']);

        return redirect()
            ->route('cart.index')
            ->with('status', "Đã cập nhật số lượng cho {$product->name}.");
    }

    public function destroy(Product $product, CartStore $cart): RedirectResponse
    {
        $cart->remove($product);

        return redirect()
            ->route('cart.index')
            ->with('status', "Đã xóa {$product->name} khỏi giỏ hàng.");
    }
}
