<?php

namespace App\Orders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Session\Session;

class OrderAccess
{
    private const JUST_PLACED_SESSION_KEY = 'last_order_id';

    public function __construct(private readonly Session $session) {}

    /**
     * Ownership rule: the registered customer the order belongs to (account pages).
     */
    public function isOwner(Order $order, ?User $user): bool
    {
        return $user !== null && $order->user_id === $user->id;
    }

    /**
     * Visibility rule: the owner, or the session that just placed the order (success page).
     */
    public function canView(Order $order, ?User $user): bool
    {
        return $this->isOwner($order, $user)
            || $this->session->get(self::JUST_PLACED_SESSION_KEY) === $order->id;
    }

    /**
     * Order lookup: guest access by order number plus customer email.
     */
    public function lookup(string $orderNumber, string $customerEmail): ?Order
    {
        return Order::query()
            ->with('items')
            ->where('order_number', $orderNumber)
            ->where('customer_email', $customerEmail)
            ->first();
    }

    public function markJustPlaced(Order $order): void
    {
        $this->session->put(self::JUST_PLACED_SESSION_KEY, $order->id);
    }
}
