<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
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
        $member = auth('member')->user();
        
        if (!$member) {
            return redirect()->route('member.auth')->with('error', 'Please login to checkout.');
        }

        $cart = Cart::where('member_id', $member->id)
            ->where('status', 'active')
            ->with(['items.product', 'coupon'])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

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

        $member = auth('member')->user();
        if (!$member) {
            return redirect()->route('member.auth')->with('error', 'Please login to checkout.');
        }

        $cart = Cart::where('member_id', $member->id)
            ->where('status', 'active')
            ->with(['items', 'coupon'])
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            // Create order from cart
            $order = Order::create([
                'member_id' => $member->id,
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'],
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'],
                'subtotal' => $cart->subtotal,
                'discount_amount' => $cart->discount_amount,
                'total_amount' => $cart->total_amount,
                'coupon_id' => $cart->coupon_id,
                'status' => 'pending',
                'payment_status' => $validated['payment_method'] === 'cash' ? 'pending' : 'pending',
            ]);

            // Create order items from cart items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product_name,
                    'quantity' => $cartItem->quantity,
                    'temperature' => $cartItem->temperature,
                    'unit_price' => $cartItem->unit_price,
                    'subtotal' => $cartItem->subtotal,
                ]);
            }

            // Increment coupon usage if applied
            if ($cart->coupon) {
                $cart->coupon->increment('used_count');
            }

            // Mark cart as converted
            $cart->update(['status' => 'converted']);

            DB::commit();

            return redirect()->route('order.confirmation', $order)->with('success', 'Order placed successfully!');

        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Checkout failed', [
                'member_id' => $member->id ?? null,
                'cart_id' => $cart->id ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    public function confirmation(Order $order): View
    {
        $order->load(['items', 'coupon']);

        return view('frontend.order-confirmation', compact('order'));
    }
}

