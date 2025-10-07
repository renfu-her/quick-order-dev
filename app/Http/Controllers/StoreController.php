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
            ->with(['images'])
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
            'variant' => 'required|in:regular,hot,cold',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'special_instructions' => 'nullable|string|max:500',
        ]);

        try {
            $product = Product::findOrFail($request->product_id);
            $store = Store::findOrFail($request->store_id);

            // Calculate price based on variant
            $price = $this->calculatePrice($product, $request->variant);
            $totalAmount = $price * $request->quantity;

            // Create order
            $order = $store->orders()->create([
                'order_number' => 'QO-' . time() . '-' . rand(1000, 9999),
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'order_type' => 'quick_order',
                'special_instructions' => $request->special_instructions,
                'member_id' => auth('member')->id(), // If member is logged in
            ]);

            // Create order item
            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'unit_price' => $price,
                'total_price' => $totalAmount,
                'variant' => $request->variant,
                'special_instructions' => $request->special_instructions,
            ]);

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
    private function calculatePrice(Product $product, string $variant): float
    {
        return match ($variant) {
            'hot' => $product->hot_price ?? $product->price,
            'cold' => $product->cold_price ?? $product->price,
            default => $product->special_price ?? $product->price,
        };
    }
}
