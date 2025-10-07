@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom/checkout.css?v=' . time()) }}">
@endpush

@section('content')

<div class="checkout-container">
    <h1 style="text-align: center; margin-bottom: 2rem; font-size: 2rem;">Checkout</h1>
    
    @if(empty($cartItems))
        <div style="text-align: center; padding: 4rem 2rem; background: #f8f9fa; border-radius: 10px;">
            <h2 style="color: #666; margin-bottom: 1rem;">Your cart is empty</h2>
            <a href="{{ route('home') }}" class="btn btn-primary" style="width: auto; padding: 1rem 2rem;">
                Browse Menu
            </a>
        </div>
    @else
        <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
            @csrf
            <div class="checkout-grid">
                <div>
                    <div class="checkout-section">
                        <h2 class="section-title">Customer Information</h2>
                        
                        <div class="form-group">
                            <label for="customer_name">Full Name <span style="color: #dc3545;">*</span></label>
                            <input type="text" 
                                   id="customer_name" 
                                   name="customer_name" 
                                   class="form-control @error('customer_name') error @enderror" 
                                   value="{{ old('customer_name', Auth::guard('member')->user()->name ?? '') }}" 
                                   required>
                            @error('customer_name')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_phone">Phone Number <span style="color: #dc3545;">*</span></label>
                            <input type="tel" 
                                   id="customer_phone" 
                                   name="customer_phone" 
                                   class="form-control @error('customer_phone') error @enderror" 
                                   value="{{ old('customer_phone', Auth::guard('member')->user()->phone ?? '') }}" 
                                   required>
                            @error('customer_phone')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="customer_email">Email Address</label>
                            <input type="email" 
                                   id="customer_email" 
                                   name="customer_email" 
                                   class="form-control @error('customer_email') error @enderror" 
                                   value="{{ old('customer_email', Auth::guard('member')->user()->email ?? '') }}">
                            @error('customer_email')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Order Notes</label>
                            <textarea id="notes" 
                                      name="notes" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Any special requests or delivery instructions...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="checkout-section" style="margin-top: 2rem;">
                        <h2 class="section-title">Payment Method</h2>
                        
                        <div class="payment-methods">
                            <div class="payment-option">
                                <input type="radio" id="cash" name="payment_method" value="cash" checked required>
                                <label for="cash" style="cursor: pointer; flex: 1;">💵 Cash on Delivery</label>
                            </div>
                            
                            <div class="payment-option">
                                <input type="radio" id="card" name="payment_method" value="card" required>
                                <label for="card" style="cursor: pointer; flex: 1;">💳 Credit/Debit Card</label>
                            </div>
                            
                            <div class="payment-option">
                                <input type="radio" id="mobile" name="payment_method" value="mobile_payment" required>
                                <label for="mobile" style="cursor: pointer; flex: 1;">📱 Mobile Payment</label>
                            </div>
                        </div>
                        
                        @error('payment_method')
                        <div class="error-message" style="margin-top: 1rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <div class="checkout-section order-summary">
                        <h2 class="section-title">Order Summary</h2>
                        
                        @foreach($cartItems as $item)
                        <div class="summary-item">
                            <span class="item-name">{{ $item['product']->name }}</span>
                            <span class="item-quantity">×{{ $item['quantity'] }}</span>
                            <span class="item-price">${{ number_format($item['unit_price'] * $item['quantity'], 2) }}</span>
                        </div>
                        @endforeach
                        
                        <div class="summary-row">
                            <span>Subtotal:</span>
                            <span>${{ number_format($subtotal, 2) }}</span>
                        </div>
                        
                        @if($discount > 0)
                        <div class="summary-row discount">
                            <span>Discount:</span>
                            <span>-${{ number_format($discount, 2) }}</span>
                        </div>
                        @endif
                        
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            Place Order
                        </button>
                        
                        <a href="{{ route('cart.index') }}" 
                           style="display: block; text-align: center; margin-top: 1rem; color: #666; text-decoration: none;">
                            ← Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>
@endsection
