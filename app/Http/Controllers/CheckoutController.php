<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(): View
    {
        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index');
        }

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
            }
        }

        $total = max(0, $subtotal - $discount);

        return view('frontend.checkout', compact('cartItems', 'subtotal', 'discount', 'total', 'appliedCoupon'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'payment_method' => 'required|string|in:cash,card,mobile_payment',
            'notes' => 'nullable|string|max:1000',
        ]);

        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $appliedCouponCode = session('applied_coupon');

        try {
            DB::beginTransaction();

            // Calculate order totals
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
            $couponId = null;

            if ($appliedCouponCode) {
                $coupon = Coupon::where('code', $appliedCouponCode)->first();
                if ($coupon && $coupon->isValid($subtotal)) {
                    $discount = $coupon->calculateDiscount($subtotal);
                    $couponId = $coupon->id;
                    
                    // Increment usage count
                    $coupon->increment('used_count');
                }
            }

            $total = max(0, $subtotal - $discount);

            // Create order
            $order = Order::create([
                'member_id' => Auth::guard('member')->id(),
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'],
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'total_amount' => $total,
                'coupon_id' => $couponId,
                'status' => 'pending',
                'payment_status' => $validated['payment_method'] === 'cash' ? 'pending' : 'pending',
            ]);

            // Create order items
            foreach ($cartItems as $item) {
                $order->items()->create([
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'quantity' => $item['quantity'],
                    'temperature' => $item['temperature'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['unit_price'] * $item['quantity'],
                ]);
            }

            DB::commit();

            // Clear cart
            session()->forget(['cart', 'applied_coupon']);

            return redirect()->route('order.confirmation', $order)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    public function confirmation(Order $order): View
    {
        $order->load(['items', 'coupon']);

        return view('frontend.order-confirmation', compact('order'));
    }
}

