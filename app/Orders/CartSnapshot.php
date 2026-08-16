<?php

namespace App\Orders;

use Illuminate\Support\Collection;

class CartSnapshot
{
    /**
     * @param  Collection<int, CartLine>  $lines
     */
    public function __construct(
        public readonly Collection $lines,
        public readonly float $subtotal,
        public readonly float $shipping,
        public readonly float $total,
    ) {}

    public function count(): int
    {
        return (int) $this->lines->sum(fn (CartLine $line): int => $line->quantity);
    }
}
