<?php

namespace App\Models;

use App\Support\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Product extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'slug',
        'name',
        'category',
        'tagline',
        'description',
        'price',
        'compare_price',
        'rating',
        'reviews_count',
        'badge',
        'image_url',
        'gallery',
        'highlights',
        'specs',
        'is_featured',
        'sort_order',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'gallery' => 'array',
        'highlights' => 'array',
        'specs' => 'array',
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'rating' => 'decimal:1',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getFormattedPriceAttribute(): string
    {
        return Money::format($this->price);
    }

    public function getFormattedComparePriceAttribute(): ?string
    {
        if ($this->compare_price === null) {
            return null;
        }

        return Money::format($this->compare_price);
    }

    /**
     * @return Collection<int, string>
     */
    public static function categoryOptions(): Collection
    {
        return collect([
            'Ánh sáng',
            'Nội thất',
            'Gốm sứ',
            'Vải vóc',
            'Hương thơm',
            'Lưu trữ',
        ]);
    }
}
