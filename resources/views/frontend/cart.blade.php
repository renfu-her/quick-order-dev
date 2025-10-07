@extends('layouts.app')

@section('title', 'Shopping Cart')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom/cart.css?v=' . time()) }}">
@endpush

@section('content')

<div class="cart-container">
    <div class="cart-header">
        <h1>🛒 Shopping Cart</h1>
    </div>
    
    @if(empty($cartItems))
        <div class="cart-empty">
            <h2>Your cart is empty</h2>
            <p>Add some delicious items to get started!</p>
            <br>
            <a href="{{ route('home') }}" class="btn btn-primary" style="width: auto; padding: 1rem 2rem;">
                Browse Menu
            </a>
        </div>
    @else
        <div class="cart-items">
            @foreach($cartItems as $index => $item)
            <div class="cart-item">
                @if($item['product']->getPrimaryImage())
                <img src="{{ asset('storage/' . $item['product']->getPrimaryImage()->image_path) }}" 
                     alt="{{ $item['product']->name }}" 
                     class="item-image">
                @else
                <div class="item-image"></div>
                @endif
                
                <div class="item-details">
                    <h3>{{ $item['product']->name }}</h3>
                    <div class="item-meta">
                        @if($item['temperature'] !== 'none')
                            Temperature: <strong>{{ ucfirst($item['temperature']) }}</strong><br>
                        @endif
                        Unit Price: ${{ number_format($item['unit_price'], 2) }}
                    </div>
                    <div class="item-price">
                        Subtotal: ${{ number_format($item['unit_price'] * $item['quantity'], 2) }}
                    </div>
                </div>
                
                <div class="item-actions">
                    <form action="{{ route('cart.update') }}" method="POST" class="quantity-control">
                        @csrf
                        <input type="hidden" name="index" value="{{ $index }}">
                        <button type="button" class="quantity-btn" onclick="updateQuantity(this.form, -1)">−</button>
                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" 
                               min="1" max="99" class="quantity-input" 
                               onchange="this.form.submit()">
                        <button type="button" class="quantity-btn" onclick="updateQuantity(this.form, 1)">+</button>
                    </form>
                    
                    <form action="{{ route('cart.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="index" value="{{ $index }}">
                        <button type="submit" class="remove-btn" onclick="return confirm('Remove this item from cart?')">
                            🗑️ Remove
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="cart-summary">
            <form action="{{ route('cart.apply-coupon') }}" method="POST" class="coupon-form">
                @csrf
                <h3>Have a coupon code?</h3>
                <div class="coupon-input-group">
                    <input type="text" name="coupon_code" class="coupon-input" 
                           placeholder="Enter coupon code" 
                           value="{{ $appliedCoupon ? $appliedCoupon->code : '' }}">
                    <button type="submit" class="btn btn-secondary">Apply</button>
                </div>
                @if($appliedCoupon)
                <div style="margin-top: 0.5rem; color: #28a745;">
                    ✓ Coupon "{{ $appliedCoupon->code }}" applied
                </div>
                @endif
            </form>
            
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
            
            <a href="{{ route('checkout.index') }}" class="btn btn-primary">
                Proceed to Checkout
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function updateQuantity(form, delta) {
        const input = form.querySelector('input[name="quantity"]');
        let value = parseInt(input.value) + delta;
        value = Math.max(1, Math.min(99, value));
        input.value = value;
        form.submit();
    }
</script>
@endpush
@endsection
