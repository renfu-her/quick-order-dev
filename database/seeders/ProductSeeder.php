<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductIngredient;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Classic Burger',
                'description' => 'Juicy beef patty with fresh lettuce, tomatoes, onions, and our special sauce',
                'price' => 12.99,
                'special_price' => null,
                'cold_price' => null,
                'hot_price' => null,
                'category' => 'Burgers',
                'is_available' => true,
                'ingredients' => ['Beef Patty', 'Lettuce', 'Tomato', 'Onion', 'Special Sauce', 'Cheese'],
            ],
            [
                'name' => 'Chicken Wings',
                'description' => 'Crispy chicken wings with your choice of sauce',
                'price' => 9.99,
                'special_price' => 7.99,
                'cold_price' => null,
                'hot_price' => null,
                'category' => 'Appetizers',
                'is_available' => true,
                'ingredients' => ['Chicken Wings', 'BBQ Sauce', 'Buffalo Sauce', 'Ranch Dressing'],
            ],
            [
                'name' => 'Iced Coffee',
                'description' => 'Refreshing cold brew coffee with ice',
                'price' => 4.99,
                'special_price' => null,
                'cold_price' => 4.99,
                'hot_price' => 4.49,
                'category' => 'Beverages',
                'is_available' => true,
                'ingredients' => ['Coffee', 'Ice', 'Milk', 'Sugar'],
            ],
            [
                'name' => 'Caesar Salad',
                'description' => 'Fresh romaine lettuce with Caesar dressing, croutons, and parmesan',
                'price' => 8.99,
                'special_price' => null,
                'cold_price' => null,
                'hot_price' => null,
                'category' => 'Salads',
                'is_available' => true,
                'ingredients' => ['Romaine Lettuce', 'Caesar Dressing', 'Croutons', 'Parmesan Cheese'],
            ],
            [
                'name' => 'Margherita Pizza',
                'description' => 'Classic pizza with tomato sauce, mozzarella, and fresh basil',
                'price' => 14.99,
                'special_price' => 12.99,
                'cold_price' => null,
                'hot_price' => null,
                'category' => 'Pizza',
                'is_available' => true,
                'ingredients' => ['Pizza Dough', 'Tomato Sauce', 'Mozzarella', 'Basil', 'Olive Oil'],
            ],
            [
                'name' => 'Green Tea',
                'description' => 'Premium green tea served hot or iced',
                'price' => 3.99,
                'special_price' => null,
                'cold_price' => 3.99,
                'hot_price' => 3.49,
                'category' => 'Beverages',
                'is_available' => true,
                'ingredients' => ['Green Tea Leaves', 'Water', 'Honey'],
            ],
            [
                'name' => 'Fish and Chips',
                'description' => 'Crispy battered fish with golden fries',
                'price' => 13.99,
                'special_price' => null,
                'cold_price' => null,
                'hot_price' => null,
                'category' => 'Main Course',
                'is_available' => true,
                'ingredients' => ['Fish Fillet', 'Batter', 'Potatoes', 'Tartar Sauce'],
            ],
            [
                'name' => 'Chocolate Cake',
                'description' => 'Rich chocolate cake with chocolate frosting',
                'price' => 6.99,
                'special_price' => null,
                'cold_price' => null,
                'hot_price' => null,
                'category' => 'Desserts',
                'is_available' => true,
                'ingredients' => ['Chocolate', 'Flour', 'Sugar', 'Eggs', 'Butter'],
            ],
        ];

        foreach ($products as $productData) {
            $ingredients = $productData['ingredients'];
            unset($productData['ingredients']);

            $product = Product::create($productData);

            // Add ingredients
            foreach ($ingredients as $ingredientName) {
                ProductIngredient::create([
                    'product_id' => $product->id,
                    'ingredient_name' => $ingredientName,
                    'is_available' => true,
                    'extra_price' => rand(0, 2) > 1 ? rand(50, 200) / 100 : 0,
                ]);
            }
        }
    }
}

