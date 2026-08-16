<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use App\Orders\OrderAccess;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_is_owner_only_for_the_orders_user(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();
        $order = $this->createOrder(['user_id' => $owner->id]);

        $access = $this->app->make(OrderAccess::class);

        $this->assertTrue($access->isOwner($order, $owner));
        $this->assertFalse($access->isOwner($order, $stranger));
        $this->assertFalse($access->isOwner($order, null));
    }

    public function test_can_view_allows_owner_or_just_placed_session(): void
    {
        $owner = User::factory()->create();
        $order = $this->createOrder(['user_id' => $owner->id]);
        $guestOrder = $this->createOrder(['order_number' => 'AA2608162222']);

        $access = $this->app->make(OrderAccess::class);

        $this->assertTrue($access->canView($order, $owner));
        $this->assertFalse($access->canView($order, null));

        $access->markJustPlaced($guestOrder);

        $this->assertTrue($access->canView($guestOrder, null));
    }

    public function test_can_view_denies_unrelated_session(): void
    {
        $order = $this->createOrder();

        $access = $this->app->make(OrderAccess::class);
        $access->markJustPlaced($order);

        // A fresh session (another visitor) must not see the order.
        $this->flushSession();

        $this->assertFalse($this->app->make(OrderAccess::class)->canView($order, null));
    }

    public function test_lookup_finds_order_by_number_and_email(): void
    {
        $order = $this->createOrder([
            'order_number' => 'AA2608163333',
            'customer_email' => 'khach@example.com',
        ]);

        $access = $this->app->make(OrderAccess::class);

        $this->assertTrue(
            $access->lookup('AA2608163333', 'khach@example.com')->is($order)
        );
        $this->assertNull($access->lookup('AA2608163333', 'nguoi-khac@example.com'));
        $this->assertNull($access->lookup('AA2608169999', 'khach@example.com'));
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createOrder(array $overrides = []): Order
    {
        static $sequence = 0;

        $sequence++;

        return Order::query()->create(array_merge([
            'order_number' => 'AA26081611'.str_pad((string) $sequence, 2, '0', STR_PAD_LEFT),
            'customer_name' => 'Khách Test',
            'customer_email' => 'khach'.$sequence.'@example.com',
            'customer_phone' => '0901234567',
            'shipping_address' => '12 Đường Test, Quận 1, TP.HCM',
            'status' => 'pending',
            'subtotal' => 100000,
            'shipping_amount' => 45000,
            'total_amount' => 145000,
        ], $overrides));
    }
}
