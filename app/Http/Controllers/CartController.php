<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Orders\CartStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function store(Request $request, Product $product, CartStore $cart): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:8'],
        ]);

        $cart->add($product, $validated['quantity'] ?? 1);

        if ($request->expectsJson()) {
            return response()->json([
                'count' => $cart->snapshot()->count(),
                'message' => "Đã thêm {$product->name} vào giỏ hàng.",
            ]);
        }

        return redirect()
            ->back()
            ->with('status', "Đã thêm {$product->name} vào giỏ hàng.");
    }

    public function update(Request $request, Product $product, CartStore $cart): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        $cart->update($product, $validated['quantity']);

        if ($request->expectsJson()) {
            return $this->cartBodyResponse($cart, "Đã cập nhật số lượng cho {$product->name}.");
        }

        return redirect()
            ->route('cart.index')
            ->with('status', "Đã cập nhật số lượng cho {$product->name}.");
    }

    public function destroy(Request $request, Product $product, CartStore $cart): RedirectResponse|JsonResponse
    {
        $cart->remove($product);

        if ($request->expectsJson()) {
            return $this->cartBodyResponse($cart, "Đã xóa {$product->name} khỏi giỏ hàng.");
        }

        return redirect()
            ->route('cart.index')
            ->with('status', "Đã xóa {$product->name} khỏi giỏ hàng.");
    }

    private function cartBodyResponse(CartStore $cart, string $message): JsonResponse
    {
        $snapshot = $cart->snapshot();

        return response()->json([
            'count' => $snapshot->count(),
            'message' => $message,
            'html' => view('storefront.partials.cart-body', [
                'items' => $snapshot->lines,
                'cartSubtotal' => $snapshot->subtotal,
                'cartShipping' => $snapshot->shipping,
                'cartTotal' => $snapshot->total,
            ])->render(),
        ]);
    }
}
