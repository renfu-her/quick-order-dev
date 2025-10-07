@extends('layouts.app')

@section('title', 'Home')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom/index.css?v=' . time()) }}">
@endpush

@section('content')
<!-- Hero Section -->
<div class="hero">
    <h1>Welcome to Quick Order</h1>
    <p>Order your favorite food quickly and easily</p>
</div>

@if($stores->count() > 0)
<!-- Stores Section -->
<section class="stores-section">
    <div class="section-header">
        <h2>Our Locations</h2>
        <p>Visit us at any of our convenient locations</p>
    </div>
    <div class="stores-grid">
        @foreach($stores as $store)
        <div class="store-card">
            @if($store->getPrimaryImage())
            <img src="{{ asset('storage/' . $store->getPrimaryImage()->image_path) }}" 
                 alt="{{ $store->name }}" 
                 class="store-image">
            @else
            <div class="store-image" style="background: linear-gradient(135deg, #e63946 0%, #f77f00 100%);"></div>
            @endif
            
            <div class="store-card-content">
                <h3>{{ $store->name }}</h3>
                
                @if($store->description)
                <p class="store-description">{{ Str::limit($store->description, 100) }}</p>
                @endif
                
                <div class="store-info">
                    @if($store->address)
                    <div class="info-item">
                        <span class="info-icon">📍</span>
                        <span>{{ $store->address }}</span>
                    </div>
                    @endif
                    
                    @if($store->phone)
                    <div class="info-item">
                        <span class="info-icon">📞</span>
                        <a href="tel:{{ $store->phone }}" style="color: inherit; text-decoration: none;">
                            {{ $store->phone }}
                        </a>
                    </div>
                    @endif
                    
                    @if($store->hours)
                    <div class="info-item">
                        <span class="info-icon">🕐</span>
                        <span>
                            @php
                                $today = now()->format('l');
                                $todayHours = $store->hours[$today] ?? 'Closed';
                            @endphp
                            Today: {{ $todayHours }}
                        </span>
                    </div>
                    @endif
                </div>
                
                @if($store->latitude && $store->longitude)
                <a href="https://www.google.com/maps?q={{ $store->latitude }},{{ $store->longitude }}" 
                   target="_blank" 
                   class="btn btn-map">
                    🗺️ View on Map
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

@if($ads->count() > 0)
<!-- Ads Section -->
<section class="ads-section">
    <div class="section-header">
        <h2>Special Offers</h2>
    </div>
    <div class="ads-grid">
        @foreach($ads as $ad)
        <div class="ad-card">
            @if($ad->image)
            <img src="{{ asset('storage/' . $ad->image) }}" alt="{{ $ad->title }}">
            @endif
            <div class="ad-card-content">
                <h3>{{ $ad->title }}</h3>
                @if($ad->description)
                <p>{{ $ad->description }}</p>
                @endif
                @if($ad->product)
                <a href="{{ route('product.show', $ad->product) }}" class="btn btn-primary">View Product</a>
                @elseif($ad->link)
                <a href="{{ $ad->link }}" class="btn btn-primary">Learn More</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- Products Section -->
<section class="products-section" id="menu">
    <div class="section-header">
        <h2>Our Menu</h2>
        <p>Browse our delicious selection</p>
    </div>
    
    <div class="products-grid">
        @forelse($products as $product)
        <div class="product-card {{ !$product->is_available ? 'unavailable' : '' }}">
            @if($product->getPrimaryImage())
            <img src="{{ asset('storage/' . $product->getPrimaryImage()->image_path) }}" 
                 alt="{{ $product->name }}" 
                 class="product-image">
            @else
            <div class="product-image"></div>
            @endif
            
            <div class="product-info">
                @if(!$product->is_available)
                <span class="unavailable-badge">Unavailable</span>
                @endif
                
                <h3 class="product-name">{{ $product->name }}</h3>
                
                @if($product->description)
                <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                @endif
                
                <div class="product-price">
                    @if($product->special_price)
                    <span class="special-price">${{ number_format($product->special_price, 2) }}</span>
                    <span class="original-price">${{ number_format($product->price, 2) }}</span>
                    @else
                    <span>${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
                
                @if($product->is_available)
                <a href="{{ route('product.show', $product) }}" class="btn btn-primary">
                    View Details
                </a>
                @else
                <button class="btn btn-primary" disabled>Currently Unavailable</button>
                @endif
            </div>
        </div>
        @empty
        <div style="grid-column: 1/-1; text-align: center; padding: 3rem;">
            <p style="font-size: 1.2rem; color: #666;">No products available at the moment.</p>
        </div>
        @endforelse
    </div>
    
    @if($products->hasPages())
    <div style="margin-top: 2rem;">
        {{ $products->links() }}
    </div>
    @endif
</section>
@endsection

