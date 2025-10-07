<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    /**
     * Display store detail page with products
     */
    public function show(string $id)
    {
        $store = Store::with(['images', 'products.images'])
            ->where('is_active', true)
            ->findOrFail($id);

        $products = $store->products()
            ->where('is_available', true)
            ->with(['images', 'ingredients' => function ($query) {
                $query->where('is_available', true);
            }])
            ->orderBy('name')
            ->get();

        // Add primary_image attribute to products for modal
        $products->each(function ($product) {
            $product->primary_image = $product->getPrimaryImage() 
                ? asset('storage/' . $product->getPrimaryImage()->image_path) 
                : null;
        });

        return view('frontend.store-detail', compact('store', 'products'));
    }

    /**
     * Handle quick order from modal
     */
    public function quickOrder(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'store_id' => 'required|exists:stores,id',
            'quantity' => 'required|integer|min:1|max:10',
            'temperature' => 'required|in:regular,hot,cold',
            'ingredient_ids' => 'nullable|string',
            'special_instructions' => 'nullable|string|max:500',
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $store = Store::findOrFail($request->store_id);

            // Calculate price based on temperature
            $price = $this->calculatePrice($product, $request->temperature);
            
            // Calculate ingredient extras and validate limit
            $ingredientExtras = 0;
            if ($request->ingredient_ids) {
                $ingredientIds = json_decode($request->ingredient_ids, true);
                if (is_array($ingredientIds)) {
                    // Check ingredient limit
                    $ingredientLimit = $product->ingredient_limit ?? 3;
                    if ($ingredientLimit > 0 && count($ingredientIds) > $ingredientLimit) {
                        return response()->json([
                            'success' => false,
                            'message' => "Maximum {$ingredientLimit} ingredients allowed for this product.",
                        ], 422);
                    }
                    
                    $ingredients = $product->ingredients()->whereIn('id', $ingredientIds)->get();
                    $ingredientExtras = $ingredients->sum('extra_price');
                }
            }
            
            $totalAmount = ($price + $ingredientExtras) * $request->quantity;

            // Get customer information from authenticated member or use defaults
            $member = auth('member')->user();
            $customerName = $member ? $member->name : 'Guest Customer';
            $customerPhone = $member ? $member->phone : 'N/A';
            $customerEmail = $member ? $member->email : null;

            // Create order
            $order = $store->orders()->create([
                'order_number' => 'QO-' . time() . '-' . rand(1000, 9999),
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'customer_email' => $customerEmail,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'order_type' => 'quick_order',
                'special_instructions' => $request->special_instructions,
                'member_id' => $member ? $member->id : null,
            ]);

            // Create order item
            $orderItem = $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'unit_price' => $price,
                'total_price' => $totalAmount,
                'variant' => $request->temperature,
                'special_instructions' => $request->special_instructions,
            ]);
            
            // Attach selected ingredients to order item
            if ($request->ingredient_ids) {
                $ingredientIds = json_decode($request->ingredient_ids, true);
                if (is_array($ingredientIds)) {
                    $orderItem->ingredients()->attach($ingredientIds);
                }
            }

            return response()->json([
                'success' => true,
                'order_id' => $order->order_number,
                'message' => 'Order placed successfully!',
                'total_amount' => $totalAmount,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Calculate price based on product variant
     */
    private function calculatePrice(Product $product, string $temperature): float
    {
        return match ($temperature) {
            'hot' => $product->hot_price ?? $product->price,
            'cold' => $product->cold_price ?? $product->price,
            default => $product->special_price ?? $product->price,
        };
    }
}
