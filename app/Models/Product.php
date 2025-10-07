<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'price',
        'special_price',
        'cold_price',
        'hot_price',
        'category',
        'is_available',
    ];

    protected $casts = [
        'store_id' => 'integer',
        'price' => 'decimal:2',
        'special_price' => 'decimal:2',
        'cold_price' => 'decimal:2',
        'hot_price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('display_order');
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(ProductIngredient::class);
    }

    public function ads(): HasMany
    {
        return $this->hasMany(Ad::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function getPriceForTemperature(string $temperature): float
    {
        return match ($temperature) {
            'hot' => (float) ($this->hot_price ?? $this->price),
            'cold' => (float) ($this->cold_price ?? $this->price),
            default => (float) $this->price,
        };
    }

    public function getEffectivePrice(): float
    {
        return (float) ($this->special_price ?? $this->price);
    }

    public function getPrimaryImage(): ?ProductImage
    {
        return $this->images()->where('is_primary', true)->first() 
            ?? $this->images()->first();
    }

    public function getPrimaryImageAttribute(): ?string
    {
        $primaryImage = $this->getPrimaryImage();
        return $primaryImage ? asset('storage/' . $primaryImage->image_path) : null;
    }
}

