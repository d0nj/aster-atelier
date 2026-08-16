<?php

namespace App\Http\Controllers;

use App\Orders\CartStore;
use App\Orders\OrderAccess;
use App\Orders\OrderIntake;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function create(Request $request, CartStore $cart): View|RedirectResponse
    {
        $snapshot = $cart->snapshot();

        if ($snapshot->lines->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('status', 'Giỏ hàng đang trống, chưa thể đặt hàng.');
        }

        return view('storefront.checkout', [
            'items' => $snapshot->lines,
            'cartSubtotal' => $snapshot->subtotal,
            'cartShipping' => $snapshot->shipping,
            'cartTotal' => $snapshot->total,
            'defaultName' => $request->user()?->name ?? '',
            'defaultEmail' => $request->user()?->email ?? '',
        ]);
    }

    public function store(Request $request, CartStore $cart, OrderIntake $intake, OrderAccess $access): RedirectResponse
    {
        $snapshot = $cart->snapshot();

        if ($snapshot->lines->isEmpty()) {
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

        $order = $intake->place($snapshot, $validated, $request->user());

        $cart->clear();
        $access->markJustPlaced($order);

        return redirect()->route('orders.success', $order)
            ->with('status', 'Đơn hàng đã được tạo thành công.');
    }
}
