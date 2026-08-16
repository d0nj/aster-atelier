<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Orders\CartStore;
use App\Support\Money;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_added_to_cart(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'tidal-pour-over-set')->firstOrFail();

        $response = $this->post(route('cart.store', $product), ['quantity' => 2]);

        $response->assertRedirect();

        $snapshot = $this->app->make(CartStore::class)->snapshot();

        $this->assertSame(2, $snapshot->count());
        $this->assertSame($product->id, $snapshot->lines->first()->product->id);
    }

    public function test_product_can_be_added_to_cart_via_json(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'tidal-pour-over-set')->firstOrFail();

        $response = $this->postJson(route('cart.store', $product), ['quantity' => 2]);

        $response->assertOk()
            ->assertJson([
                'count' => 2,
                'message' => "Đã thêm {$product->name} vào giỏ hàng.",
            ]);

        $this->assertSame(2, $this->app->make(CartStore::class)->snapshot()->count());
    }

    public function test_cart_item_quantity_can_be_updated(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'ember-reed-diffuser')->firstOrFail();

        $cart = $this->app->make(CartStore::class);
        $cart->add($product, 1);

        $this->patch(route('cart.update', $product), ['quantity' => 4])
            ->assertRedirect(route('cart.index'));

        $this->assertSame(4, $cart->snapshot()->count());
    }

    public function test_cart_item_quantity_can_be_updated_via_json(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'ember-reed-diffuser')->firstOrFail();

        $cart = $this->app->make(CartStore::class);
        $cart->add($product, 1);

        $response = $this->patchJson(route('cart.update', $product), ['quantity' => 4]);

        $response->assertOk()
            ->assertJson([
                'count' => 4,
                'message' => "Đã cập nhật số lượng cho {$product->name}.",
            ]);

        $this->assertStringContainsString($product->name, $response->json('html'));
        $this->assertSame(4, $cart->snapshot()->count());
    }

    public function test_cart_item_can_be_removed_via_json(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'ember-reed-diffuser')->firstOrFail();

        $cart = $this->app->make(CartStore::class);
        $cart->add($product, 2);

        $response = $this->deleteJson(route('cart.destroy', $product));

        $response->assertOk()
            ->assertJson([
                'count' => 0,
                'message' => "Đã xóa {$product->name} khỏi giỏ hàng.",
            ]);

        $this->assertSame(0, $cart->snapshot()->count());
        $this->assertStringContainsString('Giỏ hàng vẫn còn trống', $response->json('html'));
    }

    public function test_cart_page_renders_line_items(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'dune-linen-throw')->firstOrFail();

        $this->app->make(CartStore::class)->add($product, 3);

        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee($product->name);
        $response->assertSee(Money::format((float) $product->price * 3), false);
    }
}
