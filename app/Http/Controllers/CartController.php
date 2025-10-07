<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductIngredient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $member = auth('member')->user();
        
        if (!$member) {
            return redirect()->route('member.auth')->with('error', 'Please login to view your cart.');
        }

        $cart = Cart::where('member_id', $member->id)
            ->where('status', 'active')
            ->with(['items.product', 'coupon'])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            $cartItems = [];
            $subtotal = 0;
            $discount = 0;
            $total = 0;
            $appliedCoupon = null;
        } else {
            $cartItems = $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product' => $item->product,
                    'product_name' => $item->product_name,
                    'quantity' => $item->quantity,
                    'temperature' => $item->temperature,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                ];
            });

            $subtotal = $cart->subtotal;
            $discount = $cart->discount_amount;
            $total = $cart->total_amount;
            $appliedCoupon = $cart->coupon;
        }

        return view('frontend.cart', compact('cartItems', 'subtotal', 'discount', 'total', 'appliedCoupon'));
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
            'temperature' => 'required|in:hot,cold,regular',
            'ingredient_ids' => 'nullable|string',
            'special_instructions' => 'nullable|string',
        ]);

        $member = auth('member')->user();
        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to add items to cart.'
            ], 401);
        }

        $product = Product::with('ingredients')->findOrFail($validated['product_id']);

        if (!$product->is_available) {
            return response()->json([
                'success' => false,
                'message' => 'This product is currently unavailable.'
            ], 400);
        }

        // Calculate price based on temperature
        $unitPrice = $this->calculatePrice($product, $validated['temperature']);

        // Calculate ingredient extras
        $ingredientExtras = 0;
        if ($validated['ingredient_ids']) {
            $ingredientIds = json_decode($validated['ingredient_ids'], true);
            if (is_array($ingredientIds)) {
                // Check ingredient limit
                $ingredientLimit = $product->ingredient_limit ?? 3;
                if ($ingredientLimit > 0 && count($ingredientIds) > $ingredientLimit) {
                    return response()->json([
                        'success' => false,
                        'message' => "Maximum {$ingredientLimit} ingredients allowed for this product."
                    ], 422);
                }

                $ingredientExtras = (float) ProductIngredient::whereIn('id', $ingredientIds)
                    ->sum('extra_price');
            }
        }

        $subtotal = ($unitPrice + $ingredientExtras) * $validated['quantity'];

        // Get or create cart
        $cart = Cart::where('member_id', $member->id)
            ->where('status', 'active')
            ->first();

        if (!$cart) {
            $cart = Cart::create([
                'member_id' => $member->id,
                'session_id' => session()->getId(),
                'subtotal' => 0,
                'discount_amount' => 0,
                'total_amount' => 0,
                'status' => 'active',
            ]);
        }

        // Check if same product with same temperature already exists
        $existingItem = $cart->items()
            ->where('product_id', $product->id)
            ->where('temperature', $validated['temperature'])
            ->first();

        if ($existingItem) {
            // Update existing item
            $existingItem->update([
                'quantity' => $existingItem->quantity + $validated['quantity'],
                'subtotal' => ($unitPrice + $ingredientExtras) * ($existingItem->quantity + $validated['quantity']),
            ]);
        } else {
            // Create new cart item
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $validated['quantity'],
                'temperature' => $validated['temperature'],
                'unit_price' => $unitPrice + $ingredientExtras,
                'subtotal' => $subtotal,
            ]);
        }

        // Update cart totals
        $this->updateCartTotals($cart);

        // Get updated cart count
        $cartCount = $cart->items()->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart successfully!',
            'cart_count' => $cartCount
        ]);
    }

    private function calculatePrice(Product $product, string $temperature): float
    {
        $base = match ($temperature) {
            'hot' => $product->hot_price ?? $product->price,
            'cold' => $product->cold_price ?? $product->price,
            default => $product->special_price ?? $product->price,
        };
        return (float) ($base ?? 0);
    }

    private function updateCartTotals(Cart $cart): void
    {
        $subtotal = $cart->items->sum('subtotal');
        $discount = $cart->coupon ? $cart->coupon->calculateDiscount($subtotal) : 0;
        $total = max(0, $subtotal - $discount);

        $cart->update([
            'subtotal' => $subtotal,
            'discount_amount' => $discount,
            'total_amount' => $total,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $member = auth('member')->user();
        if (!$member) {
            return back()->with('error', 'Please login to update cart.');
        }

        $cartItem = CartItem::whereHas('cart', function ($query) use ($member) {
            $query->where('member_id', $member->id);
        })->findOrFail($validated['item_id']);

        $cartItem->update([
            'quantity' => $validated['quantity'],
            'subtotal' => $cartItem->unit_price * $validated['quantity'],
        ]);

        $this->updateCartTotals($cartItem->cart);

        return back()->with('success', 'Cart updated successfully!');
    }

    public function remove(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:cart_items,id',
        ]);

        $member = auth('member')->user();
        if (!$member) {
            return back()->with('error', 'Please login to remove items from cart.');
        }

        $cartItem = CartItem::whereHas('cart', function ($query) use ($member) {
            $query->where('member_id', $member->id);
        })->findOrFail($validated['item_id']);

        $cart = $cartItem->cart;
        $cartItem->delete();

        $this->updateCartTotals($cart);

        return back()->with('success', 'Item removed from cart.');
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $member = auth('member')->user();
        if (!$member) {
            return back()->with('error', 'Please login to apply coupon.');
        }

        $cart = Cart::where('member_id', $member->id)
            ->where('status', 'active')
            ->with('items')
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        $coupon = Coupon::where('code', strtoupper($validated['coupon_code']))->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }

        if (!$coupon->isValid($cart->subtotal)) {
            return back()->with('error', 'This coupon is not valid or has expired.');
        }

        $cart->update(['coupon_id' => $coupon->id]);
        $this->updateCartTotals($cart);

        return back()->with('success', "Coupon '{$coupon->code}' applied successfully!");
    }
}

