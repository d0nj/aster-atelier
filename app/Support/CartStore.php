<?php

namespace App\Support;

use App\Models\Product;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Collection;

class CartStore
{
    private const SESSION_KEY = 'store.cart';

    public function __construct(private readonly Session $session)
    {
    }

    public function count(): int
    {
        return array_sum($this->itemsMap());
    }

    public function items(): Collection
    {
        $quantities = collect($this->itemsMap());

        if ($quantities->isEmpty()) {
            return collect();
        }

        $products = Product::query()
            ->whereIn('id', $quantities->keys())
            ->get()
            ->keyBy(fn (Product $product) => (string) $product->id);

        return $quantities
            ->map(function (int $quantity, string $productId) use ($products): ?array {
                /** @var Product|null $product */
                $product = $products->get($productId);

                if (! $product) {
                    return null;
                }

                $lineTotal = (float) $product->price * $quantity;

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                ];
            })
            ->filter()
            ->values();
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum('line_total');
    }

    public function shipping(): float
    {
        return $this->subtotal() >= 5000000 ? 0.0 : 45000.0;
    }

    public function total(): float
    {
        return $this->subtotal() + $this->shipping();
    }

    public function add(Product $product, int $quantity = 1): void
    {
        $items = $this->itemsMap();
        $current = $items[$product->id] ?? 0;

        $items[$product->id] = min(8, $current + $quantity);

        $this->session->put(self::SESSION_KEY, $items);
    }

    public function update(Product $product, int $quantity): void
    {
        $items = $this->itemsMap();

        if ($quantity <= 0) {
            unset($items[$product->id]);
        } else {
            $items[$product->id] = min(8, $quantity);
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
