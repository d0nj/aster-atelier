<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_seeded_products(): void
    {
        $this->seed();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Aster Atelier');
        $response->assertSee('Đèn sàn Solstice');
        $response->assertSee('Mua theo bộ sưu tập');
    }

    public function test_shop_page_filters_by_category(): void
    {
        $this->seed();

        $response = $this->get('/shop?category=Gốm sứ');

        $response->assertOk();
        $response->assertSee('Bộ pour-over Tidal');
        $response->assertSee('Bộ bát stoneware Aster');
        $response->assertDontSee('Đèn sàn Solstice');
    }

    public function test_product_page_uses_slug_routing(): void
    {
        $this->seed();

        $product = Product::query()->where('slug', 'halo-travertine-side-table')->firstOrFail();

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee($product->name);
        $response->assertSee('Thông số');
    }
}
