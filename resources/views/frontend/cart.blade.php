@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<style>
    .cart-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    
    .cart-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .cart-header h1 {
        font-size: 2rem;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .cart-empty {
        text-align: center;
        padding: 4rem 2rem;
        background: #f8f9fa;
        border-radius: 10px;
    }
    
    .cart-empty h2 {
        color: #666;
        margin-bottom: 1rem;
    }
    
    .cart-items {
        background: #fff;
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .cart-item {
        display: grid;
        grid-template-columns: 100px 1fr auto;
        gap: 1.5rem;
        padding: 1.5rem 0;
        border-bottom: 1px solid #eee;
    }
    
    .cart-item:last-child {
        border-bottom: none;
    }
    
    .item-image {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        background: #f8f9fa;
    }
    
    .item-details h3 {
        font-size: 1.25rem;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .item-meta {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    
    .item-price {
        font-size: 1.1rem;
        font-weight: 600;
        color: #e63946;
    }
    
    .item-actions {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: flex-end;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .quantity-btn {
        background: #e63946;
        color: white;
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
    }
    
    .quantity-input {
        width: 60px;
        text-align: center;
        padding: 0.5rem;
        border: 2px solid #ddd;
        border-radius: 5px;
    }
    
    .remove-btn {
        background: #dc3545;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
    }
    
    .cart-summary {
        background: #fff;
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .coupon-form {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #eee;
    }
    
    .coupon-form h3 {
        margin-bottom: 1rem;
        color: #333;
    }
    
    .coupon-input-group {
        display: flex;
        gap: 0.5rem;
    }
    
    .coupon-input {
        flex: 1;
        padding: 0.75rem;
        border: 2px solid #ddd;
        border-radius: 5px;
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
    }
    
    .btn-primary {
        background: #e63946;
        color: white;
        width: 100%;
        font-size: 1.1rem;
    }
    
    .btn-primary:hover {
        background: #d62839;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 1rem;
    }
    
    .summary-row.total {
        font-size: 1.5rem;
        font-weight: bold;
        padding-top: 1rem;
        border-top: 2px solid #eee;
        color: #e63946;
    }
    
    .summary-row.discount {
        color: #28a745;
    }
    
    @media (max-width: 768px) {
        .cart-item {
            grid-template-columns: 80px 1fr;
        }
        
        .item-actions {
            grid-column: 1 / -1;
            flex-direction: row;
            align-items: center;
            margin-top: 1rem;
        }
    }
</style>

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

