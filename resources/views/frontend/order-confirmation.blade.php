@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<style>
    .confirmation-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .success-icon {
        text-align: center;
        font-size: 4rem;
        margin-bottom: 1rem;
    }
    
    .confirmation-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .confirmation-header h1 {
        font-size: 2rem;
        color: #28a745;
        margin-bottom: 0.5rem;
    }
    
    .confirmation-header p {
        color: #666;
        font-size: 1.1rem;
    }
    
    .order-details {
        background: #fff;
        border-radius: 10px;
        padding: 2rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 2rem;
    }
    
    .detail-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid #eee;
    }
    
    .detail-section:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .section-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #333;
    }
    
    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
    }
    
    .detail-label {
        color: #666;
        font-weight: 500;
    }
    
    .detail-value {
        color: #333;
        font-weight: 600;
    }
    
    .order-number {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 5px;
        text-align: center;
        margin-bottom: 1.5rem;
        border: 2px dashed #e63946;
    }
    
    .order-number strong {
        font-size: 1.5rem;
        color: #e63946;
    }
    
    .order-items {
        margin-top: 1rem;
    }
    
    .order-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .order-item:last-child {
        border-bottom: none;
    }
    
    .item-name {
        flex: 1;
    }
    
    .item-quantity {
        color: #666;
        margin: 0 1rem;
    }
    
    .item-price {
        font-weight: 600;
        color: #e63946;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin: 0.75rem 0;
        font-size: 1rem;
    }
    
    .summary-row.total {
        font-size: 1.5rem;
        font-weight: bold;
        padding-top: 1rem;
        margin-top: 1rem;
        border-top: 2px solid #eee;
        color: #e63946;
    }
    
    .summary-row.discount {
        color: #28a745;
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .status-pending {
        background: #fff3cd;
        color: #856404;
    }
    
    .status-confirmed {
        background: #d1ecf1;
        color: #0c5460;
    }
    
    .status-completed {
        background: #d4edda;
        color: #155724;
    }
    
    .btn {
        display: inline-block;
        padding: 1rem 2rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        text-align: center;
    }
    
    .btn-primary {
        background: #e63946;
        color: white;
    }
    
    .btn-primary:hover {
        background: #d62839;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }
</style>

<div class="confirmation-container">
    <div class="success-icon">✅</div>
    
    <div class="confirmation-header">
        <h1>Order Placed Successfully!</h1>
        <p>Thank you for your order. We'll get it ready for you soon.</p>
    </div>
    
    <div class="order-details">
        <div class="order-number">
            Order Number: <strong>{{ $order->order_number }}</strong>
        </div>
        
        <div class="detail-section">
            <h3 class="section-title">Order Status</h3>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Method:</span>
                <span class="detail-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Status:</span>
                <span class="detail-value">{{ ucfirst($order->payment_status) }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Order Date:</span>
                <span class="detail-value">{{ $order->created_at->format('M d, Y h:i A') }}</span>
            </div>
        </div>
        
        <div class="detail-section">
            <h3 class="section-title">Customer Information</h3>
            <div class="detail-row">
                <span class="detail-label">Name:</span>
                <span class="detail-value">{{ $order->customer_name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phone:</span>
                <span class="detail-value">{{ $order->customer_phone }}</span>
            </div>
            @if($order->customer_email)
            <div class="detail-row">
                <span class="detail-label">Email:</span>
                <span class="detail-value">{{ $order->customer_email }}</span>
            </div>
            @endif
            @if($order->notes)
            <div class="detail-row">
                <span class="detail-label">Notes:</span>
                <span class="detail-value">{{ $order->notes }}</span>
            </div>
            @endif
        </div>
        
        <div class="detail-section">
            <h3 class="section-title">Order Items</h3>
            <div class="order-items">
                @foreach($order->items as $item)
                <div class="order-item">
                    <span class="item-name">
                        {{ $item->product_name }}
                        @if($item->temperature !== 'none')
                            <span style="color: #666; font-size: 0.9rem;">({{ ucfirst($item->temperature) }})</span>
                        @endif
                    </span>
                    <span class="item-quantity">×{{ $item->quantity }}</span>
                    <span class="item-price">${{ number_format($item->subtotal, 2) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="detail-section">
            <h3 class="section-title">Order Summary</h3>
            
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>${{ number_format($order->subtotal, 2) }}</span>
            </div>
            
            @if($order->discount_amount > 0)
            <div class="summary-row discount">
                <span>Discount:</span>
                <span>-${{ number_format($order->discount_amount, 2) }}</span>
            </div>
            @endif
            
            <div class="summary-row total">
                <span>Total:</span>
                <span>${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>
    
    <div class="action-buttons">
        <a href="{{ route('home') }}" class="btn btn-primary">
            Continue Shopping
        </a>
    </div>
    
    <div style="text-align: center; margin-top: 2rem; color: #666;">
        <p>We've sent the order details to your phone.</p>
        <p>If you have any questions, please contact us at <strong>+1 (555) 123-4567</strong></p>
    </div>
</div>
@endsection

