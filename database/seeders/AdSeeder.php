<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\Product;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            return;
        }

        $ads = [
            [
                'title' => 'Summer Special - 20% Off Burgers!',
                'description' => 'Get 20% off all burgers this summer season',
                'product_id' => $products->where('category', 'Burgers')->first()?->id,
                'is_active' => true,
                'display_order' => 1,
                'starts_at' => now()->subDays(7),
                'ends_at' => now()->addDays(30),
            ],
            [
                'title' => 'New Pizza Flavors!',
                'description' => 'Try our new gourmet pizza selection',
                'product_id' => $products->where('category', 'Pizza')->first()?->id,
                'is_active' => true,
                'display_order' => 2,
                'starts_at' => now()->subDays(3),
                'ends_at' => now()->addDays(60),
            ],
            [
                'title' => 'Happy Hour Drinks',
                'description' => 'All beverages at special prices during happy hour',
                'product_id' => $products->where('category', 'Beverages')->first()?->id,
                'is_active' => true,
                'display_order' => 3,
                'starts_at' => now(),
                'ends_at' => now()->addDays(14),
            ],
        ];

        foreach ($ads as $adData) {
            Ad::create($adData);
        }
    }
}

