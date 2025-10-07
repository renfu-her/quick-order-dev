<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'session_id',
        'subtotal',
        'discount_amount',
        'total_amount',
        'coupon_id',
        'status',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function getSubtotal(): float
    {
        return (float) $this->subtotal;
    }

    public function getDiscount(): float
    {
        return (float) $this->discount_amount;
    }

    public function getTotal(): float
    {
        return (float) $this->total_amount;
    }

    public function getTotalItems(): int
    {
        return $this->items->sum('quantity');
    }

    public function calculateSubtotal(): float
    {
        return $this->items->sum(function (CartItem $item) {
            return $item->subtotal;
        });
    }

    public function calculateDiscount(): float
    {
        if (!$this->coupon) {
            return 0;
        }

        return $this->coupon->calculateDiscount($this->calculateSubtotal());
    }

    public function calculateTotal(): float
    {
        return max(0, $this->calculateSubtotal() - $this->calculateDiscount());
    }
}

