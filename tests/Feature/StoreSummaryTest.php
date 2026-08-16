<?php

namespace Tests\Feature;

use App\Admin\StoreSummary;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreSummaryTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_computes_order_metrics(): void
    {
        $this->seed();

        Order::query()->create([
            'order_number' => 'AA2608161111',
            'customer_name' => 'Khách A',
            'customer_email' => 'a@example.com',
            'customer_phone' => '0900000000',
            'shipping_address' => '1 Test',
            'status' => 'pending',
            'subtotal' => 100000,
            'shipping_amount' => 45000,
            'total_amount' => 145000,
        ]);
        Order::query()->create([
            'order_number' => 'AA2608162222',
            'customer_name' => 'Khách B',
            'customer_email' => 'b@example.com',
            'customer_phone' => '0911111111',
            'shipping_address' => '2 Test',
            'status' => 'pending',
            'subtotal' => 300000,
            'shipping_amount' => 0,
            'total_amount' => 300000,
        ]);

        $dashboard = $this->app->make(StoreSummary::class)->dashboard();

        $this->assertSame(2, $dashboard['totalOrders']);
        $this->assertSame(445000.0, $dashboard['totalRevenue']);
        $this->assertSame(222500.0, $dashboard['averageOrderValue']);
        $this->assertSame(2, $dashboard['guestOrders']);
        $this->assertSame(0, $dashboard['registeredOrders']);
        $this->assertSame(100.0, (float) $dashboard['guestOrderShare']);
        $this->assertSame(Product::query()->count(), $dashboard['totalProducts']);
        $this->assertCount(2, $dashboard['recentOrders']);
    }

    public function test_category_options_merge_curated_and_existing(): void
    {
        $this->seed();

        $options = $this->app->make(StoreSummary::class)->categoryOptions();

        // Curated defaults are present...
        $this->assertTrue($options->contains('Ánh sáng'));
        // ...plus any category already in use by a product.
        $inUse = Product::query()->distinct()->pluck('category');

        foreach ($inUse as $category) {
            $this->assertTrue($options->contains($category));
        }

        $this->assertSame($options->unique()->count(), $options->count());
    }
}
