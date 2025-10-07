@extends('layouts.app')

@section('title', $product->name)

@section('content')
<style>
    .product-detail {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        margin-bottom: 3rem;
    }
    
    @media (max-width: 768px) {
        .product-detail {
            grid-template-columns: 1fr;
        }
    }
    
    .product-images {
        position: sticky;
        top: 100px;
        height: fit-content;
    }
    
    .main-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 1rem;
        background: #f8f9fa;
    }
    
    .image-thumbnails {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 0.5rem;
    }
    
    .thumbnail {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: border 0.3s;
    }
    
    .thumbnail:hover, .thumbnail.active {
        border-color: #e63946;
    }
    
    .product-details h1 {
        font-size: 2rem;
        margin-bottom: 1rem;
        color: #333;
    }
    
    .product-price {
        font-size: 2rem;
        font-weight: bold;
        color: #e63946;
        margin-bottom: 1.5rem;
    }
    
    .product-price .special-price {
        color: #e63946;
    }
    
    .product-price .original-price {
        font-size: 1.5rem;
        color: #999;
        text-decoration: line-through;
        margin-left: 0.5rem;
    }
    
    .product-description {
        color: #666;
        line-height: 1.8;
        margin-bottom: 2rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        transition: border 0.3s;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #e63946;
    }
    
    .radio-group {
        display: flex;
        gap: 1rem;
    }
    
    .radio-option {
        flex: 1;
    }
    
    .radio-option input[type="radio"] {
        display: none;
    }
    
    .radio-option label {
        display: block;
        padding: 0.75rem;
        border: 2px solid #ddd;
        border-radius: 5px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .radio-option input[type="radio"]:checked + label {
        border-color: #e63946;
        background: #fff5f5;
        color: #e63946;
        font-weight: 600;
    }
    
    .price-badge {
        display: inline-block;
        background: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 3px;
        font-size: 0.85rem;
        margin-left: 0.5rem;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .quantity-btn {
        background: #e63946;
        color: white;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1.2rem;
        transition: background 0.3s;
    }
    
    .quantity-btn:hover {
        background: #d62839;
    }
    
    .quantity-input {
        width: 80px;
        text-align: center;
        padding: 0.75rem;
        border: 2px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
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
        width: 100%;
    }
    
    .btn-primary:hover {
        background: #d62839;
    }
    
    .btn-primary:disabled {
        background: #ccc;
        cursor: not-allowed;
    }
    
    .ingredients-section {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #ddd;
    }
    
    .ingredients-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    
    .ingredient-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 5px;
        border: 2px solid transparent;
    }
    
    .ingredient-item.available {
        border-color: #28a745;
    }
    
    .ingredient-item.unavailable {
        opacity: 0.6;
    }
    
    .unavailable-badge {
        background: #dc3545;
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        display: inline-block;
        margin-bottom: 1rem;
    }
</style>

<div class="product-detail">
    <div class="product-images">
        @if($product->images->count() > 0)
            <img src="{{ asset('storage/' . $product->getPrimaryImage()->image_path) }}" 
                 alt="{{ $product->name }}" 
                 class="main-image" 
                 id="mainImage">
            
            @if($product->images->count() > 1)
            <div class="image-thumbnails">
                @foreach($product->images as $image)
                <img src="{{ asset('storage/' . $image->image_path) }}" 
                     alt="{{ $product->name }}" 
                     class="thumbnail {{ $loop->first ? 'active' : '' }}"
                     onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}', this)">
                @endforeach
            </div>
            @endif
        @else
            <div class="main-image"></div>
        @endif
    </div>
    
    <div class="product-details">
        @if(!$product->is_available)
            <div class="unavailable-badge">Currently Unavailable</div>
        @endif
        
        <h1>{{ $product->name }}</h1>
        
        <div class="product-price">
            @if($product->special_price)
                <span class="special-price">${{ number_format($product->special_price, 2) }}</span>
                <span class="original-price">${{ number_format($product->price, 2) }}</span>
            @else
                <span>${{ number_format($product->price, 2) }}</span>
            @endif
        </div>
        
        @if($product->description)
        <div class="product-description">
            <p>{{ $product->description }}</p>
        </div>
        @endif
        
        @if($product->is_available)
        <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            @if($product->hot_price || $product->cold_price)
            <div class="form-group">
                <label>Temperature</label>
                <div class="radio-group">
                    @if($product->hot_price)
                    <div class="radio-option">
                        <input type="radio" id="hot" name="temperature" value="hot" 
                               data-price="{{ $product->hot_price }}" required>
                        <label for="hot">
                            Hot 🔥
                            <span class="price-badge">${{ number_format($product->hot_price, 2) }}</span>
                        </label>
                    </div>
                    @endif
                    
                    @if($product->cold_price)
                    <div class="radio-option">
                        <input type="radio" id="cold" name="temperature" value="cold" 
                               data-price="{{ $product->cold_price }}" required>
                        <label for="cold">
                            Cold ❄️
                            <span class="price-badge">${{ number_format($product->cold_price, 2) }}</span>
                        </label>
                    </div>
                    @endif
                    
                    <div class="radio-option">
                        <input type="radio" id="none" name="temperature" value="none" 
                               data-price="{{ $product->getEffectivePrice() }}" checked required>
                        <label for="none">
                            Regular
                            <span class="price-badge">${{ number_format($product->getEffectivePrice(), 2) }}</span>
                        </label>
                    </div>
                </div>
            </div>
            @else
            <input type="hidden" name="temperature" value="none">
            @endif
            
            <div class="form-group">
                <label>Quantity</label>
                <div class="quantity-control">
                    <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">−</button>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="99" class="quantity-input" required>
                    <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">
                🛒 Add to Cart
            </button>
        </form>
        @else
        <button class="btn btn-primary" disabled>Currently Unavailable</button>
        @endif
        
        @if($product->ingredients->count() > 0)
        <div class="ingredients-section">
            <h2>Ingredients</h2>
            <div class="ingredients-grid">
                @foreach($product->ingredients as $ingredient)
                <div class="ingredient-item {{ $ingredient->is_available ? 'available' : 'unavailable' }}">
                    <strong>{{ $ingredient->ingredient_name }}</strong>
                    @if($ingredient->extra_price > 0)
                    <span class="price-badge">+${{ number_format($ingredient->extra_price, 2) }}</span>
                    @endif
                    @if(!$ingredient->is_available)
                    <br><small style="color: #dc3545;">Currently unavailable</small>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function changeMainImage(src, element) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        element.classList.add('active');
    }
    
    function changeQuantity(delta) {
        const input = document.getElementById('quantity');
        let value = parseInt(input.value) + delta;
        value = Math.max(1, Math.min(99, value));
        input.value = value;
    }
</script>
@endpush
@endsection

