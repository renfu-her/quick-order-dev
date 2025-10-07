<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'address',
        'phone',
        'email',
        'hours',
        'is_active',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'hours' => 'array',
    ];

    public function images(): HasMany
    {
        return $this->hasMany(StoreImage::class)->orderBy('display_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getPrimaryImage(): ?StoreImage
    {
        return $this->images()->where('is_primary', true)->first() 
            ?? $this->images()->first();
    }

    public function getActiveProducts(): HasMany
    {
        return $this->products()->where('is_available', true);
    }

    public function getPrimaryImageAttribute(): ?string
    {
        $primaryImage = $this->getPrimaryImage();
        return $primaryImage ? asset('storage/' . $primaryImage->image_path) : null;
    }
}
