<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_product_index_loads(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'test@example.com')->firstOrFail();

        $response = $this->actingAs($admin)->get(route('admin.products.index'));

        $response->assertOk();
        $response->assertSee('Quản trị sản phẩm');
        $response->assertSee('Đèn sàn Solstice');
    }

    public function test_admin_can_create_a_product(): void
    {
        $this->seed();

        $admin = User::query()->where('email', 'test@example.com')->firstOrFail();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'Bàn console Sora',
            'slug' => '',
            'category' => 'Nội thất',
            'tagline' => 'Mặt gỗ tần bì sáng, dáng mảnh và giữ nhịp cho lối vào.',
            'description' => 'Một mẫu bàn console gọn, phù hợp cho sảnh vào nhà, sau sofa hoặc dưới tranh lớn. Phom mảnh nhưng vẫn đủ chắc cho việc trưng đồ và đặt đèn bàn.',
            'price' => 12900000,
            'compare_price' => 13900000,
            'rating' => 4.7,
            'reviews_count' => 12,
            'badge' => 'Mới',
            'image_url' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1200&q=80',
            'gallery_text' => implode(PHP_EOL, [
                'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
            ]),
            'highlights_text' => implode(PHP_EOL, [
                'Khung gỗ tần bì phủ dầu mờ',
                'Chiều sâu gọn cho hành lang và foyer',
                'Mặt bàn đủ cho đèn, sách và đồ decor',
            ]),
            'specs_text' => implode(PHP_EOL, [
                'Chất liệu: Gỗ tần bì',
                'Chiều dài: 140 cm',
                'Chiều sâu: 35 cm',
            ]),
            'sort_order' => 9,
            'is_featured' => '1',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'Bàn console Sora',
            'category' => 'Nội thất',
            'slug' => 'ban-console-sora',
        ]);

        $product = Product::query()->where('slug', 'ban-console-sora')->firstOrFail();
        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Bàn console Sora');
    }

    public function test_guest_cannot_access_admin_panel(): void
    {
        $this->seed();

        $this->get(route('admin.products.index'))
            ->assertRedirect(route('login'));
    }
}
