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
