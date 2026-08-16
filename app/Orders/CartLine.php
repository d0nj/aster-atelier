<?php

namespace App\Orders;

use App\Models\Product;

class CartLine
{
    public readonly float $lineTotal;

    public function __construct(
        public readonly Product $product,
        public readonly int $quantity,
    ) {
        $this->lineTotal = (float) $product->price * $quantity;
    }
}
