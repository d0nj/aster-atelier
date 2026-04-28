<?php

namespace Tests\Feature;

use App\Models\Product;
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
        $response->assertSessionHas('store.cart', [
            $product->id => 2,
        ]);
    }

    public function test_cart_item_quantity_can_be_updated(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'ember-reed-diffuser')->firstOrFail();

        $this->withSession([
            'store.cart' => [$product->id => 1],
        ])->patch(route('cart.update', $product), ['quantity' => 4])
            ->assertRedirect(route('cart.index'));

        $this->assertEquals(4, session('store.cart')[$product->id]);
    }

    public function test_cart_page_renders_line_items(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'dune-linen-throw')->firstOrFail();

        $response = $this->withSession([
            'store.cart' => [$product->id => 3],
        ])->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee($product->name);
        $response->assertSee(Product::formatCurrency((float) $product->price * 3), false);
    }
}
