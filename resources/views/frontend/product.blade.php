@extends('layouts.app')

@section('title', $product->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom/product.css?v=' . time()) }}">
@endpush

@section('content')

<div class="product-detail">
    <!-- Product Images -->
    <div class="product-images">
        <img src="{{ $product->getPrimaryImage() ? asset('storage/' . $product->getPrimaryImage()->image_path) : 'https://via.placeholder.com/400' }}" 
             alt="{{ $product->name }}" 
             class="main-image"
             id="mainImage">
        
        @if($product->images->count() > 1)
        <div class="image-thumbnails">
            @foreach($product->images as $image)
            <img src="{{ asset('storage/' . $image->image_path) }}" 
                 alt="{{ $product->name }}" 
                 class="thumbnail {{ $loop->first ? 'active' : '' }}"
                 onclick="changeMainImage(this)">
            @endforeach
        </div>
        @endif
    </div>
    
    <!-- Product Details -->
    <div class="product-details">
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
            {{ $product->description }}
        </div>
        @endif
        
        <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            
            <!-- Temperature Selection -->
            @if($product->hasTemperatureOptions())
            <div class="temperature-options">
                <h3>Select Temperature</h3>
                <div class="temperature-radio">
                    @if($product->hot_price)
                    <div class="temperature-option">
                        <input type="radio" id="hot" name="temperature" value="hot" 
                               data-price="{{ $product->hot_price }}" required>
                        <label for="hot">
                            🔥 Hot
                            <br>
                            <small>${{ number_format($product->hot_price, 2) }}</small>
                        </label>
                    </div>
                    @endif
                    
                    @if($product->cold_price)
                    <div class="temperature-option">
                        <input type="radio" id="cold" name="temperature" value="cold" 
                               data-price="{{ $product->cold_price }}" required>
                        <label for="cold">
                            ❄️ Cold
                            <br>
                            <small>${{ number_format($product->cold_price, 2) }}</small>
                        </label>
                    </div>
                    @endif
                    
                    @if(!$product->hot_price && !$product->cold_price)
                    <input type="hidden" name="temperature" value="none">
                    @endif
                </div>
            </div>
            @else
            <input type="hidden" name="temperature" value="none">
            @endif
            
            <!-- Ingredients -->
            @if($product->ingredients->count() > 0)
            <div class="ingredients-section">
                <h3>Customize Your Order</h3>
                @foreach($product->ingredients as $ingredient)
                <div class="ingredient-item">
                    <input type="checkbox" 
                           id="ingredient_{{ $ingredient->id }}" 
                           name="ingredients[]" 
                           value="{{ $ingredient->id }}"
                           {{ $ingredient->is_default ? 'checked' : '' }}
                           data-price="{{ $ingredient->extra_price ?? 0 }}">
                    <div class="ingredient-info">
                        <label for="ingredient_{{ $ingredient->id }}">
                            {{ $ingredient->name }}
                        </label>
                    </div>
                    @if($ingredient->extra_price > 0)
                    <span class="ingredient-price">+${{ number_format($ingredient->extra_price, 2) }}</span>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
            
            <!-- Quantity and Add to Cart -->
            <div class="add-to-cart-section">
                <div class="quantity-selector">
                    <button type="button" class="qty-btn" onclick="changeQuantity(-1)">−</button>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="99" class="qty-value" readonly>
                    <button type="button" class="qty-btn" onclick="changeQuantity(1)">+</button>
                </div>
                
                <button type="submit" class="add-to-cart-btn">
                    🛒 Add to Cart
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function changeMainImage(thumbnail) {
        const mainImage = document.getElementById('mainImage');
        mainImage.src = thumbnail.src;
        
        // Update active thumbnail
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        thumbnail.classList.add('active');
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
