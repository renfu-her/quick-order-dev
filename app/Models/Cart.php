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
        'user_id',
        'session_id',
        'coupon_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
        return $this->items->sum(function (CartItem $item) {
            return $item->unit_price * $item->quantity;
        });
    }

    public function getDiscount(): float
    {
        if (!$this->coupon) {
            return 0;
        }

        return $this->coupon->calculateDiscount($this->getSubtotal());
    }

    public function getTotal(): float
    {
        return max(0, $this->getSubtotal() - $this->getDiscount());
    }

    public function getTotalItems(): int
    {
        return $this->items->sum('quantity');
    }
}

