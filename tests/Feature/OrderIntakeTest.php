<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Orders\CartLine;
use App\Orders\CartSnapshot;
use App\Orders\OrderIntake;
use App\Orders\OrderNumberGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use RuntimeException;
use Tests\TestCase;

class OrderIntakeTest extends TestCase
{
    use RefreshDatabase;

    public function test_place_persists_order_with_snapshotted_prices(): void
    {
        $product = $this->createProduct(['price' => 250000]);
        $snapshot = new CartSnapshot(
            collect([new CartLine($product, 2)]),
            500000.0,
            45000.0,
            545000.0,
        );

        $order = $this->intake()->place($snapshot, $this->customer(), null);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'customer_email' => 'khach@example.com',
            'status' => 'pending',
            'subtotal' => '500000.00',
            'shipping_amount' => '45000.00',
            'total_amount' => '545000.00',
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'product_slug' => $product->slug,
            'unit_price' => '250000.00',
            'quantity' => 2,
            'line_total' => '500000.00',
        ]);
        $this->assertMatchesRegularExpression('/^AA\d{10}$/', $order->order_number);
    }

    public function test_place_refuses_an_empty_snapshot(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->intake()->place(
            new CartSnapshot(collect(), 0.0, 0.0, 0.0),
            $this->customer(),
            null,
        );
    }

    public function test_place_retries_when_order_number_collides(): void
    {
        $existing = $this->createOrderWithNumber('AA2608161234');
        $product = $this->createProduct();

        $intake = $this->intake(new class extends OrderNumberGenerator
        {
            private int $calls = 0;

            public function next(): string
            {
                $this->calls++;

                return $this->calls === 1 ? 'AA2608161234' : 'AA2608165678';
            }
        });

        $order = $intake->place(
            new CartSnapshot(collect([new CartLine($product, 1)]), 250000.0, 45000.0, 295000.0),
            $this->customer(),
            null,
        );

        $this->assertSame('AA2608165678', $order->order_number);
        $this->assertSame(2, Order::query()->count());
        $this->assertNotNull($existing->fresh());
    }

    public function test_place_gives_up_after_max_attempts(): void
    {
        $this->createOrderWithNumber('AA2608161234');
        $product = $this->createProduct();

        $intake = $this->intake(new class extends OrderNumberGenerator
        {
            public function next(): string
            {
                return 'AA2608161234';
            }
        });

        try {
            $intake->place(
                new CartSnapshot(collect([new CartLine($product, 1)]), 250000.0, 45000.0, 295000.0),
                $this->customer(),
                null,
            );
            $this->fail('Expected RuntimeException after exhausting order-number attempts.');
        } catch (RuntimeException $e) {
            $this->assertStringContainsString('order number', $e->getMessage());
        }

        $this->assertSame(1, Order::query()->count());
    }

    private function intake(?OrderNumberGenerator $numbers = null): OrderIntake
    {
        return new OrderIntake($numbers ?? new OrderNumberGenerator);
    }

    /**
     * @return array{customer_name: string, customer_email: string, customer_phone: string, shipping_address: string, notes: ?string}
     */
    private function customer(): array
    {
        return [
            'customer_name' => 'Khách Test',
            'customer_email' => 'khach@example.com',
            'customer_phone' => '0901234567',
            'shipping_address' => '12 Đường Test, Quận 1, TP.HCM',
            'notes' => null,
        ];
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createProduct(array $overrides = []): Product
    {
        static $sequence = 0;

        $sequence++;

        return Product::query()->create(array_merge([
            'slug' => 'san-pham-test-'.$sequence,
            'name' => 'Sản phẩm test '.$sequence,
            'category' => 'Gốm sứ',
            'tagline' => 'Tagline test.',
            'description' => 'Mô tả test.',
            'price' => 250000,
            'image_url' => 'https://example.com/img.jpg',
            'gallery' => ['https://example.com/img.jpg'],
            'highlights' => ['Điểm nổi bật'],
            'specs' => ['Chất liệu' => 'Test'],
        ], $overrides));
    }

    private function createOrderWithNumber(string $number): Order
    {
        return Order::query()->create([
            'order_number' => $number,
            'customer_name' => 'Đơn cũ',
            'customer_email' => 'cu@example.com',
            'customer_phone' => '0900000000',
            'shipping_address' => 'Địa chỉ cũ',
            'status' => 'pending',
            'subtotal' => 100000,
            'shipping_amount' => 45000,
            'total_amount' => 145000,
        ]);
    }
}
