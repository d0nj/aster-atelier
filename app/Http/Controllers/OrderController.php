<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\CartStore;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(CartStore $cart): View
    {
        $user = request()->user();

        return view('orders.index', [
            'orders' => $user->orders()->with('items')->latest()->get(),
            'cartCount' => $cart->count(),
            'cartSubtotal' => $cart->subtotal(),
        ]);
    }

    public function show(Order $order, CartStore $cart): View
    {
        abort_unless($order->isOwnedBy(request()->user()), 403);

        $order->load('items');

        return view('orders.show', [
            'order' => $order,
            'cartCount' => $cart->count(),
            'cartSubtotal' => $cart->subtotal(),
        ]);
    }

    public function success(Order $order, CartStore $cart, Request $request): View
    {
        $allowed = $request->session()->get('last_order_id') === $order->id
            || $order->isOwnedBy($request->user());

        abort_unless($allowed, 403);

        $order->load('items');

        return view('orders.success', [
            'order' => $order,
            'cartCount' => $cart->count(),
            'cartSubtotal' => $cart->subtotal(),
        ]);
    }

    public function lookup(CartStore $cart): View
    {
        return view('orders.lookup', [
            'order' => null,
            'cartCount' => $cart->count(),
            'cartSubtotal' => $cart->subtotal(),
        ]);
    }

    public function search(Request $request, CartStore $cart): View
    {
        $request->merge([
            'order_number' => strtoupper(trim((string) $request->input('order_number'))),
            'customer_email' => strtolower(trim((string) $request->input('customer_email'))),
        ]);

        $validated = $request->validate([
            'order_number' => ['required', 'string', 'max:50'],
            'customer_email' => ['required', 'email', 'max:255'],
        ]);

        $order = Order::query()
            ->with('items')
            ->where('order_number', $validated['order_number'])
            ->where('customer_email', $validated['customer_email'])
            ->first();

        return view('orders.lookup', [
            'order' => $order,
            'lookupAttempted' => true,
            'cartCount' => $cart->count(),
            'cartSubtotal' => $cart->subtotal(),
        ]);
    }
}
