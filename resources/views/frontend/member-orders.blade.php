@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container" style="max-width: 1100px; margin: 0 auto;">
    <h1 style="margin-bottom: 1rem; color: #333;">My Orders</h1>
    <div style="background:#fff; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); padding:1.5rem;">
        @if($orders->count() === 0)
            <p style="color:#666;">No orders yet.</p>
        @else
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left; padding:10px; border-bottom:1px solid #eee;">Order #</th>
                            <th style="text-align:left; padding:10px; border-bottom:1px solid #eee;">Date</th>
                            <th style="text-align:right; padding:10px; border-bottom:1px solid #eee;">Items</th>
                            <th style="text-align:right; padding:10px; border-bottom:1px solid #eee;">Total</th>
                            <th style="text-align:left; padding:10px; border-bottom:1px solid #eee;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td style="padding:12px 10px; border-bottom:1px solid #f2f2f2;">
                                    <strong>{{ $order->order_number }}</strong>
                                </td>
                                <td style="padding:12px 10px; border-bottom:1px solid #f2f2f2; color:#666;">
                                    {{ $order->created_at->format('Y-m-d H:i') }}
                                </td>
                                <td style="padding:12px 10px; border-bottom:1px solid #f2f2f2; text-align:right; color:#333;">
                                    {{ $order->items->sum('quantity') }}
                                </td>
                                <td style="padding:12px 10px; border-bottom:1px solid #f2f2f2; text-align:right; font-weight:600; color:#e63946;">
                                    ${{ number_format((float) $order->total_amount, 2) }}
                                </td>
                                <td style="padding:12px 10px; border-bottom:1px solid #f2f2f2;">
                                    <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                                </td>
                            </tr>
                            @if($order->items->count())
                                <tr>
                                    <td colspan="5" style="padding:0 0 12px 0;">
                                        <div style="background:#fafafa; border-radius:6px; padding:10px 12px; margin:0 10px 12px 10px;">
                                            @foreach($order->items as $item)
                                                <div style="display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px dashed #eee;">
                                                    <div style="color:#555;">{{ $item->product_name }}</div>
                                                    <div style="color:#555;">x {{ $item->quantity }}</div>
                                                    <div style="color:#555;">${{ number_format((float) $item->unit_price, 2) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 1rem;">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection


