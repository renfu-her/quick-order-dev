@extends('layouts.app')

@section('title', 'Order Confirmation')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom/order.css?v=' . time()) }}">
@endpush

@section('content')

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
            @foreach($order->items as $item)
            <div class="order-item">
                <div class="item-info">
                    <h4>{{ $item->product_name }}</h4>
                    <p>
                        @if($item->temperature !== 'none')
                            Temperature: {{ ucfirst($item->temperature) }} •
                        @endif
                        Quantity: {{ $item->quantity }}
                    </p>
                </div>
                <div class="item-total">
                    ${{ number_format($item->subtotal, 2) }}
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="detail-section">
            <h3 class="section-title">Payment Summary</h3>
            <div class="detail-row">
                <span class="detail-label">Subtotal:</span>
                <span class="detail-value">${{ number_format($order->subtotal, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="detail-row">
                <span class="detail-label">Discount:</span>
                <span class="detail-value" style="color: #28a745;">-${{ number_format($order->discount_amount, 2) }}</span>
            </div>
            @endif
            <div class="detail-row">
                <span class="detail-label" style="font-size: 1.25rem; font-weight: bold;">Total:</span>
                <span class="detail-value" style="font-size: 1.25rem; font-weight: bold; color: #e63946;">${{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>
    
    <div class="confirmation-actions">
        <a href="{{ route('home') }}" class="btn-continue">
            🏠 Back to Home
        </a>
    </div>
</div>

@push('styles')
<style>
    .success-icon {
        text-align: center;
        font-size: 5rem;
        margin-bottom: 1rem;
    }
    
    .order-details {
        background: #fff;
        border-radius: 10px;
        padding: 2rem;
        margin-top: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .detail-section {
        margin-bottom: 2rem;
    }
    
    .detail-section:last-child {
        margin-bottom: 0;
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
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
    
    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }
</style>
@endpush

@endsection
