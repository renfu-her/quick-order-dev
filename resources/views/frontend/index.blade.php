@extends('layouts.app')

@section('title', 'Home')

@section('content')
<style>
    /* Hero Section */
    .hero {
        background: linear-gradient(135deg, #e63946 0%, #f77f00 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
        border-radius: 10px;
        margin-bottom: 3rem;
    }
    
    /* Stores Section */
    .stores-section {
        margin-bottom: 3rem;
    }
    
    .stores-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }
    
    .store-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    
    .store-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    
    .store-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f8f9fa;
    }
    
    .store-card-content {
        padding: 1.5rem;
    }
    
    .store-card h3 {
        margin-bottom: 0.75rem;
        color: #333;
        font-size: 1.25rem;
    }
    
    .store-description {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        line-height: 1.5;
    }
    
    .store-info {
        margin: 1rem 0;
    }
    
    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        color: #555;
        font-size: 0.9rem;
    }
    
    .info-icon {
        font-size: 1rem;
        flex-shrink: 0;
    }
    
    .btn-map {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: #f8f9fa;
        color: #333;
        text-decoration: none;
        border-radius: 5px;
        font-size: 0.9rem;
        border: 2px solid #ddd;
        transition: all 0.3s;
        margin-top: 0.5rem;
    }
    
    .btn-map:hover {
        background: #e63946;
        color: white;
        border-color: #e63946;
    }
    
    .hero h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .hero p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
    }
    
    /* Ads Section */
    .ads-section {
        margin-bottom: 3rem;
    }
    
    .ads-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }
    
    .ad-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    
    .ad-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    
    .ad-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }
    
    .ad-card-content {
        padding: 1.5rem;
    }
    
    .ad-card h3 {
        margin-bottom: 0.5rem;
        color: #e63946;
    }
    
    /* Products Section */
    .products-section {
        margin-top: 3rem;
    }
    
    .section-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .section-header h2 {
        font-size: 2rem;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }
    
    .product-card {
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }
    
    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background: #f8f9fa;
    }
    
    .product-info {
        padding: 1.5rem;
    }
    
    .product-name {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .product-description {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        line-height: 1.5;
    }
    
    .product-price {
        font-size: 1.5rem;
        font-weight: bold;
        color: #e63946;
        margin-bottom: 1rem;
    }
    
    .product-price .special-price {
        color: #e63946;
    }
    
    .product-price .original-price {
        font-size: 1rem;
        color: #999;
        text-decoration: line-through;
        margin-left: 0.5rem;
    }
    
    .btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.3s;
        font-weight: 500;
        text-align: center;
    }
    
    .btn-primary {
        background: #e63946;
        color: white;
        width: 100%;
    }
    
    .btn-primary:hover {
        background: #d62839;
    }
    
    .unavailable {
        opacity: 0.6;
    }
    
    .unavailable-badge {
        background: #6c757d;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 15px;
        font-size: 0.85rem;
        display: inline-block;
        margin-bottom: 0.5rem;
    }
</style>

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

