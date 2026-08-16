<?php

namespace Tests\Unit;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function test_discount_percent_rounds_from_compare_price(): void
    {
        $product = new Product(['price' => 10900000, 'compare_price' => 12400000]);

        $this->assertSame(12, $product->discount_percent);
    }

    public function test_discount_percent_is_null_without_compare_price(): void
    {
        $product = new Product(['price' => 5000000, 'compare_price' => null]);

        $this->assertNull($product->discount_percent);
    }

    public function test_discount_percent_is_zero_when_compare_price_not_higher(): void
    {
        $product = new Product(['price' => 5000000, 'compare_price' => 5000000]);

        $this->assertSame(0, $product->discount_percent);
    }
}
