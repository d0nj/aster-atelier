<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Support\CartStore;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function create(Request $request, CartStore $cart): View|RedirectResponse
    {
        if ($cart->items()->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('status', 'Giỏ hàng đang trống, chưa thể đặt hàng.');
        }

        return view('storefront.checkout', [
            'items' => $cart->items(),
            'cartSubtotal' => $cart->subtotal(),
            'cartShipping' => $cart->shipping(),
            'cartTotal' => $cart->total(),
            'cartCount' => $cart->count(),
            'defaultName' => $request->user()?->name ?? '',
            'defaultEmail' => $request->user()?->email ?? '',
        ]);
    }

    public function store(Request $request, CartStore $cart): RedirectResponse
    {
        $items = $cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('status', 'Giỏ hàng đang trống, chưa thể đặt hàng.');
        }

        $request->merge([
            'customer_email' => strtolower(trim((string) $request->input('customer_email'))),
        ]);

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string', 'max:1000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $order = DB::transaction(function () use ($request, $cart, $items, $validated) {
            $order = Order::query()->create([
                'user_id' => $request->user()?->id,
                'order_number' => $this->generateOrderNumber(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
                'subtotal' => $cart->subtotal(),
                'shipping_amount' => $cart->shipping(),
                'total_amount' => $cart->total(),
            ]);

            $payload = $items->map(function (array $item) use ($order): array {
                /** @var Product $product */
                $product = $item['product'];

                return [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_slug' => $product->slug,
                    'product_image_url' => $product->image_url,
                    'unit_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'line_total' => $item['line_total'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->all();

            OrderItem::query()->insert($payload);
            $cart->clear();

            return $order;
        });

        $request->session()->put('last_order_id', $order->id);

        return redirect()->route('orders.success', $order)
            ->with('status', 'Đơn hàng đã được tạo thành công.');
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'AA' . now()->format('ymd') . random_int(1000, 9999);
        } while (Order::query()->where('order_number', $number)->exists());

        return $number;
    }
}
