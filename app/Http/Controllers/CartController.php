<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session('cart', []);
        $appliedCouponCode = session('applied_coupon');
        $appliedCoupon = null;

        $cartItems = array_map(function ($item) {
            $product = Product::find($item['product_id']);
            return [
                'product' => $product,
                'quantity' => $item['quantity'],
                'temperature' => $item['temperature'],
                'unit_price' => $item['unit_price'],
            ];
        }, $cart);

        $subtotal = collect($cartItems)->sum(fn($item) => $item['unit_price'] * $item['quantity']);
        $discount = 0;

        if ($appliedCouponCode) {
            $appliedCoupon = Coupon::where('code', $appliedCouponCode)->first();
            if ($appliedCoupon && $appliedCoupon->isValid($subtotal)) {
                $discount = $appliedCoupon->calculateDiscount($subtotal);
            } else {
                session()->forget('applied_coupon');
                $appliedCoupon = null;
            }
        }

        $total = max(0, $subtotal - $discount);

        return view('frontend.cart', compact('cartItems', 'subtotal', 'discount', 'total', 'appliedCoupon'));
    }

    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:99',
            'temperature' => 'required|in:hot,cold,none',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if (!$product->is_available) {
            return back()->with('error', 'This product is currently unavailable.');
        }

        $temperature = $validated['temperature'];
        $unitPrice = $product->getPriceForTemperature($temperature);

        if ($product->special_price) {
            $unitPrice = (float) $product->special_price;
        }

        $cart = session('cart', []);
        
        // Check if product with same temperature already exists
        $existingIndex = null;
        foreach ($cart as $index => $item) {
            if ($item['product_id'] === $product->id && $item['temperature'] === $temperature) {
                $existingIndex = $index;
                break;
            }
        }

        if ($existingIndex !== null) {
            $cart[$existingIndex]['quantity'] += $validated['quantity'];
        } else {
            $cart[] = [
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'temperature' => $temperature,
                'unit_price' => $unitPrice,
            ];
        }

        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'index' => 'required|integer',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart = session('cart', []);

        if (isset($cart[$validated['index']])) {
            $cart[$validated['index']]['quantity'] = $validated['quantity'];
            session(['cart' => $cart]);
            return back()->with('success', 'Cart updated successfully!');
        }

        return back()->with('error', 'Item not found in cart.');
    }

    public function remove(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'index' => 'required|integer',
        ]);

        $cart = session('cart', []);

        if (isset($cart[$validated['index']])) {
            unset($cart[$validated['index']]);
            $cart = array_values($cart); // Re-index array
            session(['cart' => $cart]);
            return back()->with('success', 'Item removed from cart.');
        }

        return back()->with('error', 'Item not found in cart.');
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', strtoupper($validated['coupon_code']))->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }

        $cartItems = array_map(function ($item) {
            $product = Product::find($item['product_id']);
            return [
                'product' => $product,
                'quantity' => $item['quantity'],
                'temperature' => $item['temperature'],
                'unit_price' => $item['unit_price'],
            ];
        }, $cart);

        $subtotal = collect($cartItems)->sum(fn($item) => $item['unit_price'] * $item['quantity']);

        if (!$coupon->isValid($subtotal)) {
            return back()->with('error', 'This coupon is not valid or has expired.');
        }

        session(['applied_coupon' => $coupon->code]);

        return back()->with('success', "Coupon '{$coupon->code}' applied successfully!");
    }
}

