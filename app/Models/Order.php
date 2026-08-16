<?php

namespace App\Models;

use App\Support\Money;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'notes',
        'status',
        'subtotal',
        'shipping_amount',
        'total_amount',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return Money::format($this->subtotal);
    }

    public function getFormattedShippingAmountAttribute(): string
    {
        return Money::format($this->shipping_amount);
    }

    public function getFormattedTotalAmountAttribute(): string
    {
        return Money::format($this->total_amount);
    }
}
