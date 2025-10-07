<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Member;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'name' => 'Test Member',
                'email' => 'member@test.com',
                'phone' => '+1 (555) 123-4567',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '+1 (555) 234-5678',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '+1 (555) 345-6789',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'Michael Johnson',
                'email' => 'michael@example.com',
                'phone' => '+1 (555) 456-7890',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Williams',
                'email' => 'sarah@example.com',
                'phone' => '+1 (555) 567-8901',
                'password' => Hash::make('password'),
                'is_active' => true,
            ],
        ];

        foreach ($members as $memberData) {
            $member = Member::create($memberData);
            
            // Create sample orders for some members
            if (in_array($member->email, ['member@test.com', 'john@example.com'])) {
                $this->createSampleOrderForMember($member);
            }
            
            // Create active cart for one member
            if ($member->email === 'jane@example.com') {
                $this->createSampleCartForMember($member);
            }
        }
    }

    private function createSampleOrderForMember(Member $member): void
    {
        $products = Product::limit(3)->get();
        
        if ($products->isEmpty()) {
            return;
        }

        $subtotal = 0;
        
        $order = Order::create([
            'member_id' => $member->id,
            'order_number' => Order::generateOrderNumber(),
            'customer_name' => $member->name,
            'customer_phone' => $member->phone,
            'customer_email' => $member->email,
            'subtotal' => 0, // Will update after items
            'discount_amount' => 0,
            'total_amount' => 0, // Will update after items
            'status' => ['pending', 'confirmed', 'completed'][rand(0, 2)],
            'payment_method' => ['cash', 'card', 'mobile_payment'][rand(0, 2)],
            'payment_status' => 'paid',
            'notes' => 'Sample order created during seeding',
        ]);

        foreach ($products as $product) {
            $quantity = rand(1, 3);
            $unitPrice = $product->getEffectivePrice();
            $itemSubtotal = $unitPrice * $quantity;
            $subtotal += $itemSubtotal;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $quantity,
                'temperature' => ['hot', 'cold', 'none'][rand(0, 2)],
                'unit_price' => $unitPrice,
                'subtotal' => $itemSubtotal,
            ]);
        }

        $order->update([
            'subtotal' => $subtotal,
            'total_amount' => $subtotal,
        ]);
    }

    private function createSampleCartForMember(Member $member): void
    {
        $products = Product::where('is_available', true)->limit(2)->get();
        
        if ($products->isEmpty()) {
            return;
        }

        $cart = Cart::create([
            'member_id' => $member->id,
            'session_id' => null,
        ]);

        foreach ($products as $product) {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => rand(1, 2),
                'temperature' => ['hot', 'cold', 'none'][rand(0, 2)],
                'unit_price' => $product->getEffectivePrice(),
            ]);
        }
    }
}


