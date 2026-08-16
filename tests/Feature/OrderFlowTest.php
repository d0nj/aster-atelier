<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Orders\CartStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_checkout_without_account(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'tidal-pour-over-set')->firstOrFail();

        $cart = $this->app->make(CartStore::class);
        $cart->add($product, 2);

        $response = $this->post(route('checkout.store'), [
            'customer_name' => 'Tran Thi B',
            'customer_email' => 'guest@example.com',
            'customer_phone' => '0900000000',
            'shipping_address' => '123 Duong Le Loi, Quan 1, TP.HCM',
            'notes' => 'Giao buoi chieu',
        ]);

        $order = Order::query()->firstOrFail();

        $response->assertRedirect(route('orders.success', $order));
        $this->assertDatabaseHas('orders', [
            'order_number' => $order->order_number,
            'customer_email' => 'guest@example.com',
            'user_id' => null,
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
        $this->assertTrue($cart->snapshot()->lines->isEmpty());
    }

    public function test_authenticated_user_can_view_their_orders(): void
    {
        $this->seed();

        $user = User::factory()->create([
            'name' => 'Pham Van C',
            'email' => 'pham@example.com',
            'password' => 'password123',
        ]);

        $product = Product::query()->where('slug', 'ember-reed-diffuser')->firstOrFail();

        $this->app->make(CartStore::class)->add($product, 1);

        $this->actingAs($user)
            ->post(route('checkout.store'), [
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '0911111111',
                'shipping_address' => '456 Nguyen Hue, Quan 1, TP.HCM',
                'notes' => '',
            ]);

        $order = Order::query()->where('user_id', $user->id)->firstOrFail();

        $this->actingAs($user)
            ->get(route('orders.index'))
            ->assertOk()
            ->assertSee($order->order_number)
            ->assertSee('Đơn hàng của tôi');

        $this->actingAs($user)
            ->get(route('orders.show', $order))
            ->assertOk()
            ->assertSee($product->name);
    }

    public function test_guest_can_lookup_order_by_number_and_email(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'dune-linen-throw')->firstOrFail();

        $this->app->make(CartStore::class)->add($product, 1);

        $this->post(route('checkout.store'), [
            'customer_name' => 'Le Thi D',
            'customer_email' => 'lookup@example.com',
            'customer_phone' => '0922222222',
            'shipping_address' => '789 Hai Ba Trung, Quan 3, TP.HCM',
            'notes' => '',
        ]);

        $order = Order::query()->where('customer_email', 'lookup@example.com')->firstOrFail();

        $this->post(route('orders.lookup.search'), [
            'order_number' => strtolower($order->order_number),
            'customer_email' => 'Lookup@Example.com',
        ])->assertOk()
            ->assertSee($order->order_number)
            ->assertSee($product->name);
    }

    public function test_checkout_page_renders_cart_summary(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'tidal-pour-over-set')->firstOrFail();

        $this->app->make(CartStore::class)->add($product, 2);

        $this->get(route('checkout.create'))
            ->assertOk()
            ->assertSee($product->name)
            ->assertSee('SL 2')
            ->assertSee('Tóm tắt đơn hàng');
    }

    public function test_success_page_is_visible_right_after_checkout_only(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'tidal-pour-over-set')->firstOrFail();

        $this->app->make(CartStore::class)->add($product, 1);

        $this->post(route('checkout.store'), [
            'customer_name' => 'Guest E',
            'customer_email' => 'e@example.com',
            'customer_phone' => '0933333333',
            'shipping_address' => '1 Duong Test, Quan 1, TP.HCM',
            'notes' => '',
        ]);

        $order = Order::query()->where('customer_email', 'e@example.com')->firstOrFail();

        $this->get(route('orders.success', $order))->assertOk();

        $this->flushSession();

        $this->get(route('orders.success', $order))->assertForbidden();
    }

    public function test_user_cannot_view_another_users_order(): void
    {
        $this->seed();

        $buyer = User::factory()->create();
        $product = Product::query()->where('slug', 'ember-reed-diffuser')->firstOrFail();

        $this->app->make(CartStore::class)->add($product, 1);

        $this->actingAs($buyer)->post(route('checkout.store'), [
            'customer_name' => $buyer->name,
            'customer_email' => $buyer->email,
            'customer_phone' => '0944444444',
            'shipping_address' => '2 Duong Test, Quan 1, TP.HCM',
            'notes' => '',
        ]);

        $order = Order::query()->where('user_id', $buyer->id)->firstOrFail();

        $stranger = User::factory()->create();

        $this->actingAs($stranger)
            ->get(route('orders.show', $order))
            ->assertForbidden();
    }
}
