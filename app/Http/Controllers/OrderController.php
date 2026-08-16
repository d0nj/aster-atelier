<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Orders\OrderAccess;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(): View
    {
        $user = request()->user();

        return view('orders.index', [
            'orders' => $user->orders()->with('items')->latest()->get(),
        ]);
    }

    public function show(Order $order, OrderAccess $access): View
    {
        abort_unless($access->isOwner($order, request()->user()), 403);

        $order->load('items');

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    public function success(Order $order, OrderAccess $access): View
    {
        abort_unless($access->canView($order, request()->user()), 403);

        $order->load('items');

        return view('orders.success', [
            'order' => $order,
        ]);
    }

    public function lookup(): View
    {
        return view('orders.lookup', [
            'order' => null,
        ]);
    }

    public function search(Request $request, OrderAccess $access): View
    {
        $request->merge([
            'order_number' => strtoupper(trim((string) $request->input('order_number'))),
            'customer_email' => strtolower(trim((string) $request->input('customer_email'))),
        ]);

        $validated = $request->validate([
            'order_number' => ['required', 'string', 'max:50'],
            'customer_email' => ['required', 'email', 'max:255'],
        ]);

        $order = $access->lookup($validated['order_number'], $validated['customer_email']);

        return view('orders.lookup', [
            'order' => $order,
            'lookupAttempted' => true,
        ]);
    }
}
