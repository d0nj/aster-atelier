<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Orders\CartStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoReadonlyTest extends TestCase
{
    use RefreshDatabase;

    private function demoAdmin(): User
    {
        $this->seed();

        return User::query()->where('email', 'demo@example.com')->firstOrFail();
    }

    public function test_seeded_demo_account_is_admin_and_readonly(): void
    {
        $demo = $this->demoAdmin();

        $this->assertTrue($demo->is_admin);
        $this->assertTrue($demo->is_readonly);
    }

    public function test_demo_admin_can_view_admin_panel(): void
    {
        $demo = $this->demoAdmin();

        $this->actingAs($demo)
            ->get(route('admin.products.index'))
            ->assertOk()
            ->assertSee('Quản trị sản phẩm')
            ->assertSee('Chế độ xem thử');
    }

    public function test_demo_admin_cannot_create_product(): void
    {
        $demo = $this->demoAdmin();
        $before = Product::query()->count();

        $this->from(route('admin.products.index'))
            ->actingAs($demo)
            ->post(route('admin.products.store'), [
                'name' => 'San pham demo khong duoc tao',
                'category' => 'Nội thất',
                'tagline' => 'x',
                'description' => 'x',
                'price' => 1000000,
                'rating' => 4.5,
                'reviews_count' => 1,
                'image_url' => 'https://example.com/a.jpg',
                'gallery_text' => 'https://example.com/a.jpg',
                'highlights_text' => 'x',
                'specs_text' => 'Chat lieu: Go',
                'sort_order' => 1,
            ])
            ->assertRedirect(route('admin.products.index'));

        $this->assertSame($before, Product::query()->count());
        $this->assertDatabaseMissing('products', ['name' => 'San pham demo khong duoc tao']);
    }

    public function test_demo_admin_cannot_update_product(): void
    {
        $demo = $this->demoAdmin();
        $product = Product::query()->where('slug', 'tidal-pour-over-set')->firstOrFail();
        $originalName = $product->name;

        $this->from(route('admin.products.edit', $product))
            ->actingAs($demo)
            ->put(route('admin.products.update', $product), [
                'name' => 'Da bi doi ten boi demo',
                'category' => $product->category,
                'tagline' => $product->tagline,
                'description' => $product->description,
                'price' => $product->price,
                'rating' => $product->rating,
                'reviews_count' => $product->reviews_count,
                'image_url' => $product->image_url,
                'gallery_text' => implode(PHP_EOL, $product->gallery),
                'highlights_text' => implode(PHP_EOL, $product->highlights),
                'specs_text' => collect($product->specs)->map(fn ($v, $k) => $k.': '.$v)->implode(PHP_EOL),
                'sort_order' => $product->sort_order,
            ])
            ->assertRedirect(route('admin.products.edit', $product));

        $this->assertSame($originalName, $product->fresh()->name);
    }

    public function test_demo_admin_cannot_delete_product(): void
    {
        $demo = $this->demoAdmin();
        $product = Product::query()->where('slug', 'tidal-pour-over-set')->firstOrFail();

        $this->from(route('admin.products.index'))
            ->actingAs($demo)
            ->delete(route('admin.products.destroy', $product))
            ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_demo_admin_can_still_checkout(): void
    {
        $demo = $this->demoAdmin();
        $product = Product::query()->where('slug', 'tidal-pour-over-set')->firstOrFail();

        $cart = $this->app->make(CartStore::class);
        $cart->add($product, 1);

        $response = $this->actingAs($demo)->post(route('checkout.store'), [
            'customer_name' => 'Demo Buyer',
            'customer_email' => 'demo@example.com',
            'customer_phone' => '0900000001',
            'shipping_address' => '1 Duong Demo, Quan 1, TP.HCM',
            'notes' => null,
        ]);

        $order = Order::query()->latest('id')->firstOrFail();
        $response->assertRedirect(route('orders.success', $order));
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'user_id' => $demo->id,
            'customer_email' => 'demo@example.com',
        ]);
    }

    public function test_registration_cannot_self_promote_to_admin(): void
    {
        $this->post(route('register.store'), [
            'name' => 'Ke Tan Cong',
            'email' => 'attacker@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'is_admin' => '1',
            'is_readonly' => '0',
        ])->assertRedirect(route('orders.index'));

        $user = User::query()->where('email', 'attacker@example.com')->firstOrFail();
        $this->assertFalse($user->is_admin);
        $this->assertFalse($user->is_readonly);
    }
}
