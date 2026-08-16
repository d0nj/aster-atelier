<?php

namespace App\Orders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class OrderIntake
{
    private const MAX_ATTEMPTS = 5;

    public function __construct(private readonly OrderNumberGenerator $numbers) {}

    /**
     * @param  array{customer_name: string, customer_email: string, customer_phone: string, shipping_address: string, notes: ?string}  $customer
     */
    public function place(CartSnapshot $cart, array $customer, ?User $user = null): Order
    {
        if ($cart->lines->isEmpty()) {
            throw new InvalidArgumentException('Cannot place an order from an empty cart snapshot.');
        }

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            try {
                return DB::transaction(fn (): Order => $this->persist($cart, $customer, $user));
            } catch (UniqueConstraintViolationException) {
                continue;
            }
        }

        throw new RuntimeException(
            'Could not allocate a unique order number after '.self::MAX_ATTEMPTS.' attempts.'
        );
    }

    /**
     * @param  array{customer_name: string, customer_email: string, customer_phone: string, shipping_address: string, notes: ?string}  $customer
     */
    private function persist(CartSnapshot $cart, array $customer, ?User $user): Order
    {
        $order = Order::query()->create([
            'user_id' => $user?->id,
            'order_number' => $this->numbers->next(),
            'customer_name' => $customer['customer_name'],
            'customer_email' => $customer['customer_email'],
            'customer_phone' => $customer['customer_phone'],
            'shipping_address' => $customer['shipping_address'],
            'notes' => $customer['notes'] ?? null,
            'status' => 'pending',
            'subtotal' => $cart->subtotal,
            'shipping_amount' => $cart->shipping,
            'total_amount' => $cart->total,
        ]);

        $order->items()->createMany($cart->lines->map(fn (CartLine $line): array => [
            'product_id' => $line->product->id,
            'product_name' => $line->product->name,
            'product_slug' => $line->product->slug,
            'product_image_url' => $line->product->image_url,
            'unit_price' => $line->product->price,
            'quantity' => $line->quantity,
            'line_total' => $line->lineTotal,
        ])->all());

        return $order;
    }
}
