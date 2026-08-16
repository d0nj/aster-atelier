<?php

namespace App\Orders;

use App\Models\Product;
use Illuminate\Contracts\Session\Session;

class CartStore
{
    private const SESSION_KEY = 'store.cart';

    private const MAX_QUANTITY_PER_PRODUCT = 8;

    private const FREE_SHIPPING_THRESHOLD = 5000000.0;

    private const SHIPPING_FLAT = 45000.0;

    public function __construct(private readonly Session $session) {}

    public function snapshot(): CartSnapshot
    {
        $quantities = collect($this->itemsMap());

        $products = $quantities->isEmpty()
            ? collect()
            : Product::query()
                ->whereIn('id', $quantities->keys())
                ->get()
                ->keyBy(fn (Product $product) => (string) $product->id);

        $lines = $quantities
            ->map(fn (int $quantity, string $productId) => $products->get($productId))
            ->filter()
            ->map(fn (Product $product, string $productId) => new CartLine($product, $quantities[$productId]))
            ->values();

        $subtotal = (float) $lines->sum(fn (CartLine $line): float => $line->lineTotal);
        $shipping = $subtotal >= self::FREE_SHIPPING_THRESHOLD ? 0.0 : self::SHIPPING_FLAT;

        return new CartSnapshot($lines, $subtotal, $shipping, $subtotal + $shipping);
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $items = $this->itemsMap();
        $current = $items[$product->id] ?? 0;

        $items[$product->id] = min(self::MAX_QUANTITY_PER_PRODUCT, $current + $quantity);

        $this->session->put(self::SESSION_KEY, $items);
    }

    public function update(Product $product, int $quantity): void
    {
        $items = $this->itemsMap();

        if ($quantity <= 0) {
            unset($items[$product->id]);
        } else {
            $items[$product->id] = min(self::MAX_QUANTITY_PER_PRODUCT, $quantity);
        }

        $this->session->put(self::SESSION_KEY, $items);
    }

    public function remove(Product $product): void
    {
        $items = $this->itemsMap();
        unset($items[$product->id]);

        $this->session->put(self::SESSION_KEY, $items);
    }

    public function clear(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }

    /**
     * @return array<int, int>
     */
    private function itemsMap(): array
    {
        /** @var array<int, int> $items */
        $items = $this->session->get(self::SESSION_KEY, []);

        return $items;
    }
}
