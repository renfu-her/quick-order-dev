<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code' => 'WELCOME10',
                'discount_type' => 'percentage',
                'discount_value' => 10,
                'min_purchase_amount' => 20,
                'max_discount_amount' => 5,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(3),
                'usage_limit' => 100,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'SAVE5',
                'discount_type' => 'fixed',
                'discount_value' => 5,
                'min_purchase_amount' => 30,
                'max_discount_amount' => null,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(1),
                'usage_limit' => 50,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'SUMMER20',
                'discount_type' => 'percentage',
                'discount_value' => 20,
                'min_purchase_amount' => 50,
                'max_discount_amount' => 15,
                'starts_at' => now()->subDays(7),
                'ends_at' => now()->addMonths(2),
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'discount_type' => 'fixed',
                'discount_value' => 3,
                'min_purchase_amount' => 25,
                'max_discount_amount' => null,
                'starts_at' => now(),
                'ends_at' => now()->addMonths(6),
                'usage_limit' => null,
                'used_count' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }
    }
}

