<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Store;
use App\Models\StoreImage;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $stores = [
            [
                'name' => 'Quick Order Main Branch',
                'description' => 'Our flagship store in downtown. Offering the full menu with dine-in, takeout, and delivery options.',
                'address' => '123 Main Street, Downtown, City',
                'phone' => '+1 (555) 100-0001',
                'email' => 'main@quickorder.com',
                'hours' => [
                    'Monday' => '09:00 - 22:00',
                    'Tuesday' => '09:00 - 22:00',
                    'Wednesday' => '09:00 - 22:00',
                    'Thursday' => '09:00 - 22:00',
                    'Friday' => '09:00 - 23:00',
                    'Saturday' => '10:00 - 23:00',
                    'Sunday' => '10:00 - 21:00',
                ],
                'is_active' => true,
                'latitude' => 25.0330,
                'longitude' => 121.5654,
            ],
            [
                'name' => 'Quick Order North Branch',
                'description' => 'Convenient location in the north district. Perfect for quick takeout and delivery.',
                'address' => '456 North Avenue, North District, City',
                'phone' => '+1 (555) 200-0002',
                'email' => 'north@quickorder.com',
                'hours' => [
                    'Monday' => '10:00 - 21:00',
                    'Tuesday' => '10:00 - 21:00',
                    'Wednesday' => '10:00 - 21:00',
                    'Thursday' => '10:00 - 21:00',
                    'Friday' => '10:00 - 22:00',
                    'Saturday' => '10:00 - 22:00',
                    'Sunday' => '11:00 - 20:00',
                ],
                'is_active' => true,
                'latitude' => 25.0520,
                'longitude' => 121.5430,
            ],
            [
                'name' => 'Quick Order Express (East)',
                'description' => 'Express service location focusing on quick orders and delivery.',
                'address' => '789 East Road, East District, City',
                'phone' => '+1 (555) 300-0003',
                'email' => 'east@quickorder.com',
                'hours' => [
                    'Monday' => '11:00 - 20:00',
                    'Tuesday' => '11:00 - 20:00',
                    'Wednesday' => '11:00 - 20:00',
                    'Thursday' => '11:00 - 20:00',
                    'Friday' => '11:00 - 21:00',
                    'Saturday' => '11:00 - 21:00',
                    'Sunday' => 'Closed',
                ],
                'is_active' => true,
                'latitude' => 25.0419,
                'longitude' => 121.5819,
            ],
            [
                'name' => 'Quick Order West Branch',
                'description' => 'Family-friendly location with spacious dining area.',
                'address' => '321 West Boulevard, West District, City',
                'phone' => '+1 (555) 400-0004',
                'email' => 'west@quickorder.com',
                'hours' => [
                    'Monday' => '09:00 - 21:00',
                    'Tuesday' => '09:00 - 21:00',
                    'Wednesday' => '09:00 - 21:00',
                    'Thursday' => '09:00 - 21:00',
                    'Friday' => '09:00 - 22:00',
                    'Saturday' => '09:00 - 22:00',
                    'Sunday' => '10:00 - 21:00',
                ],
                'is_active' => true,
                'latitude' => 25.0251,
                'longitude' => 121.5134,
            ],
            [
                'name' => 'Quick Order Airport (Temporarily Closed)',
                'description' => 'Airport location - currently under renovation.',
                'address' => '999 Airport Terminal, Airport District, City',
                'phone' => '+1 (555) 500-0005',
                'email' => 'airport@quickorder.com',
                'hours' => [
                    'Monday' => 'Closed',
                    'Tuesday' => 'Closed',
                    'Wednesday' => 'Closed',
                    'Thursday' => 'Closed',
                    'Friday' => 'Closed',
                    'Saturday' => 'Closed',
                    'Sunday' => 'Closed',
                ],
                'is_active' => false,
                'latitude' => 25.0777,
                'longitude' => 121.2328,
            ],
        ];

        foreach ($stores as $storeData) {
            $store = Store::create($storeData);
            
            // Create sample images for active stores
            if ($store->is_active) {
                StoreImage::create([
                    'store_id' => $store->id,
                    'image_path' => 'stores/store-' . $store->id . '-1.jpg',
                    'display_order' => 1,
                    'is_primary' => true,
                ]);
                
                StoreImage::create([
                    'store_id' => $store->id,
                    'image_path' => 'stores/store-' . $store->id . '-2.jpg',
                    'display_order' => 2,
                    'is_primary' => false,
                ]);
            }
        }
    }
}

