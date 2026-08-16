<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Products\ProductDraft;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProductDraftTest extends TestCase
{
    use RefreshDatabase;

    public function test_attributes_builds_persistable_payload(): void
    {
        $attributes = $this->draft()->attributes($this->input());

        $this->assertSame('ban-console-sora', $attributes['slug']);
        $this->assertSame(['https://example.com/a.jpg'], $attributes['gallery']);
        $this->assertSame(['Khung gỗ tần bì'], $attributes['highlights']);
        $this->assertSame(['Chất liệu' => 'Gỗ tần bì'], $attributes['specs']);
        $this->assertTrue($attributes['is_featured']);
    }

    public function test_slug_collisions_get_a_suffix(): void
    {
        $this->createProduct('ban-console-sora');

        $attributes = $this->draft()->attributes($this->input([
            'name' => 'Bàn console Sora',
            'slug' => 'ban-console-sora',
        ]));

        $this->assertSame('ban-console-sora-2', $attributes['slug']);
    }

    public function test_updating_product_keeps_its_own_slug(): void
    {
        $existing = $this->createProduct('ban-console-sora');

        $attributes = $this->draft()->attributes($this->input([
            'name' => 'Bàn console Sora',
            'slug' => 'ban-console-sora',
        ]), $existing);

        $this->assertSame('ban-console-sora', $attributes['slug']);
    }

    public function test_specs_require_label_and_value(): void
    {
        $this->expectException(ValidationException::class);

        $this->draft()->attributes($this->input([
            'specs_text' => 'Chất liệu',
        ]));
    }

    public function test_gallery_lines_must_be_urls(): void
    {
        $this->expectException(ValidationException::class);

        $this->draft()->attributes($this->input([
            'gallery_text' => 'không-phải-url',
        ]));
    }

    public function test_empty_highlights_are_rejected(): void
    {
        $this->expectException(ValidationException::class);

        $this->draft()->attributes($this->input([
            'highlights_text' => "   \n  ",
        ]));
    }

    private function draft(): ProductDraft
    {
        return new ProductDraft;
    }

    private function createProduct(string $slug): Product
    {
        return Product::query()->create([
            'slug' => $slug,
            'name' => 'Bàn console Sora',
            'category' => 'Nội thất',
            'tagline' => 'Mặt gỗ tần bì sáng.',
            'description' => 'Một mẫu bàn console gọn.',
            'price' => 12900000,
            'image_url' => 'https://example.com/img.jpg',
            'gallery' => ['https://example.com/a.jpg'],
            'highlights' => ['Khung gỗ tần bì'],
            'specs' => ['Chất liệu' => 'Gỗ tần bì'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function input(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Bàn console Sora',
            'slug' => 'ban-console-sora',
            'category' => 'Nội thất',
            'tagline' => 'Mặt gỗ tần bì sáng.',
            'description' => 'Một mẫu bàn console gọn.',
            'price' => 12900000,
            'compare_price' => 13900000,
            'rating' => 4.7,
            'reviews_count' => 12,
            'badge' => 'Mới',
            'image_url' => 'https://example.com/img.jpg',
            'gallery_text' => 'https://example.com/a.jpg',
            'highlights_text' => 'Khung gỗ tần bì',
            'specs_text' => 'Chất liệu: Gỗ tần bì',
            'sort_order' => 9,
            'is_featured' => '1',
        ], $overrides);
    }
}
