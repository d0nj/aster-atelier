<?php

namespace App\Products;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductDraft
{
    /**
     * Turn validated form input into persistable product attributes.
     *
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    public function attributes(array $input, ?Product $product = null): array
    {
        return [
            'slug' => $this->uniqueSlug($input['slug'] ?: $input['name'], $product),
            'name' => $input['name'],
            'category' => $input['category'],
            'tagline' => $input['tagline'],
            'description' => $input['description'],
            'price' => $input['price'],
            'compare_price' => $input['compare_price'] ?: null,
            'rating' => $input['rating'],
            'reviews_count' => $input['reviews_count'],
            'badge' => $input['badge'] ?: null,
            'image_url' => $input['image_url'],
            'gallery' => $this->parseUrlLines($input['gallery_text'], 'gallery_text'),
            'highlights' => $this->parseLines($input['highlights_text'], 'highlights_text'),
            'specs' => $this->parseSpecs($input['specs_text']),
            'sort_order' => $input['sort_order'],
            'is_featured' => (bool) ($input['is_featured'] ?? false),
        ];
    }

    /**
     * @return array<int, string>
     */
    private function parseLines(string $value, string $field): array
    {
        $lines = collect(preg_split('/\r\n|\r|\n/', $value) ?: [])
            ->map(fn (string $line): string => trim($line))
            ->filter()
            ->values()
            ->all();

        if ($lines === []) {
            throw ValidationException::withMessages([
                $field => 'Vui lòng nhập ít nhất một dòng dữ liệu.',
            ]);
        }

        return $lines;
    }

    /**
     * @return array<int, string>
     */
    private function parseUrlLines(string $value, string $field): array
    {
        $lines = $this->parseLines($value, $field);

        foreach ($lines as $line) {
            if (! filter_var($line, FILTER_VALIDATE_URL)) {
                throw ValidationException::withMessages([
                    $field => 'Mỗi dòng phải là một URL hợp lệ.',
                ]);
            }
        }

        return $lines;
    }

    /**
     * @return array<string, string>
     */
    private function parseSpecs(string $value): array
    {
        $lines = $this->parseLines($value, 'specs_text');
        $specs = [];

        foreach ($lines as $line) {
            $parts = explode(':', $line, 2);

            if (count($parts) !== 2) {
                throw ValidationException::withMessages([
                    'specs_text' => 'Mỗi dòng thông số phải theo định dạng "Tên: Giá trị".',
                ]);
            }

            $label = trim($parts[0]);
            $content = trim($parts[1]);

            if ($label === '' || $content === '') {
                throw ValidationException::withMessages([
                    'specs_text' => 'Tên và giá trị thông số không được để trống.',
                ]);
            }

            $specs[$label] = $content;
        }

        return $specs;
    }

    private function uniqueSlug(string $value, ?Product $product = null): string
    {
        $base = Str::slug($value) ?: 'san-pham';
        $slug = $base;
        $index = 2;

        while (
            Product::query()
                ->where('slug', $slug)
                ->when($product, fn ($query) => $query->whereKeyNot($product->getKey()))
                ->exists()
        ) {
            $slug = $base.'-'.$index;
            $index++;
        }

        return $slug;
    }
}
