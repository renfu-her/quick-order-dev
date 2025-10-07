@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom/store-detail.css?v=' . time()) }}">
@endpush

@section('content')
<div class="container">
    <!-- Store Header -->
    <div class="store-header">
        <div class="store-hero">
            @if($store->getPrimaryImage())
            <img src="{{ asset('storage/' . $store->getPrimaryImage()->image_path) }}" 
                 alt="{{ $store->name }}" 
                 class="store-hero-image">
            @else
            <div class="store-hero-placeholder">
                <span class="placeholder-icon">🏪</span>
            </div>
            @endif
            
            <div class="store-hero-overlay">
                <div class="store-info">
                    <h1 class="store-title">{{ $store->name }}</h1>
                    @if($store->description)
                    <p class="store-description">{{ $store->description }}</p>
                    @endif
                    
                    <div class="store-meta">
                        @if($store->address)
                        <div class="meta-item">
                            <span class="meta-icon">📍</span>
                            <span>{{ $store->address }}</span>
                        </div>
                        @endif
                        
                        @if($store->phone)
                        <div class="meta-item">
                            <span class="meta-icon">📞</span>
                            <a href="tel:{{ $store->phone }}" class="meta-link">{{ $store->phone }}</a>
                        </div>
                        @endif
                        
                        @if($store->email)
                        <div class="meta-item">
                            <span class="meta-icon">✉️</span>
                            <a href="mailto:{{ $store->email }}" class="meta-link">{{ $store->email }}</a>
                        </div>
                        @endif
                        
                        @if($store->hours)
                        <div class="meta-item">
                            <span class="meta-icon">🕐</span>
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
        </div>
    </div>

    <!-- Store Images Gallery -->
    @if($store->images->count() > 1)
    <div class="store-gallery">
        <h3>Store Gallery</h3>
        <div class="gallery-grid">
            @foreach($store->images as $image)
            <div class="gallery-item">
                <img src="{{ asset('storage/' . $image->image_path) }}" 
                     alt="{{ $store->name }}" 
                     class="gallery-image">
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Products Section -->
    <div class="products-section">
        <div class="section-header">
            <h2>Our Products</h2>
            <p>Order from {{ $store->name }}</p>
        </div>
        
        @if($products->count() > 0)
        <div class="products-grid">
            @foreach($products as $product)
            <div class="product-card" data-product-id="{{ $product->id }}">
                @if($product->getPrimaryImage())
                <img src="{{ asset('storage/' . $product->getPrimaryImage()->image_path) }}" 
                     alt="{{ $product->name }}" 
                     class="product-image">
                @else
                <div class="product-image-placeholder">
                    <span class="placeholder-icon">🍽️</span>
                </div>
                @endif
                
                <div class="product-info">
                    <h3 class="product-name">{{ $product->name }}</h3>
                    
                    @if($product->description)
                    <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                    @endif
                    
                    <div class="product-pricing">
                        @if($product->special_price && $product->special_price < $product->price)
                        <div class="price-row">
                            <span class="price-original">${{ number_format($product->price, 2) }}</span>
                            <span class="price-special">${{ number_format($product->special_price, 2) }}</span>
                        </div>
                        @else
                        <div class="price-row">
                            <span class="price-regular">${{ number_format($product->price, 2) }}</span>
                        </div>
                        @endif
                        
                        @if($product->hot_price || $product->cold_price)
                        <div class="variant-prices">
                            @if($product->hot_price)
                            <span class="variant-price">🔥 Hot: ${{ number_format($product->hot_price, 2) }}</span>
                            @endif
                            @if($product->cold_price)
                            <span class="variant-price">❄️ Cold: ${{ number_format($product->cold_price, 2) }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                    
                    <button class="btn btn-order" onclick="openOrderModal({{ $product->id }})">
                        🛒 Quick Order
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-icon">🍽️</div>
            <h3>No Products Available</h3>
            <p>This store doesn't have any products available at the moment.</p>
        </div>
        @endif
    </div>
</div>

<!-- Product Order Modal -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalProductName">Product Name</h3>
            <span class="close" onclick="closeOrderModal()">&times;</span>
        </div>
        
        <div class="modal-body">
            <div class="product-details">
                <img id="modalProductImage" src="" alt="" class="modal-product-image">
                <div class="product-info">
                    <p id="modalProductDescription"></p>
                    <div class="product-pricing">
                        <span id="modalProductPrice" class="price"></span>
                    </div>
                </div>
            </div>
            
            <form id="orderForm" class="order-form">
                @csrf
                <input type="hidden" id="productId" name="product_id">
                <input type="hidden" id="storeId" name="store_id" value="{{ $store->id }}">
                
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" min="1" value="1" required>
                </div>
                
                <div class="form-group">
                    <label for="variant">Variant</label>
                    <select id="variant" name="variant">
                        <option value="regular">Regular</option>
                        <option value="hot">Hot</option>
                        <option value="cold">Cold</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="special_instructions">Special Instructions</label>
                    <textarea id="special_instructions" name="special_instructions" rows="3" placeholder="Any special requests?"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="customer_name">Your Name</label>
                    <input type="text" id="customer_name" name="customer_name" required>
                </div>
                
                <div class="form-group">
                    <label for="customer_phone">Phone Number</label>
                    <input type="tel" id="customer_phone" name="customer_phone" required>
                </div>
                
                <div class="form-group">
                    <label for="customer_email">Email (Optional)</label>
                    <input type="email" id="customer_email" name="customer_email">
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeOrderModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Place Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Product data for modal
const products = @json($products->keyBy('id'));

function openOrderModal(productId) {
    const product = products[productId];
    if (!product) return;
    
    // Update modal content
    document.getElementById('modalProductName').textContent = product.name;
    document.getElementById('modalProductDescription').textContent = product.description || '';
    document.getElementById('modalProductPrice').textContent = '$' + parseFloat(product.price).toFixed(2);
    document.getElementById('productId').value = productId;
    
    // Update product image
    const imageElement = document.getElementById('modalProductImage');
    if (product.primary_image) {
        imageElement.src = product.primary_image;
        imageElement.style.display = 'block';
    } else {
        imageElement.style.display = 'none';
    }
    
    // Show modal
    document.getElementById('orderModal').style.display = 'block';
}

function closeOrderModal() {
    document.getElementById('orderModal').style.display = 'none';
    document.getElementById('orderForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('orderModal');
    if (event.target === modal) {
        closeOrderModal();
    }
}

// Handle form submission
document.getElementById('orderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route("store.quick-order") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Order placed successfully! Order ID: ' + data.order_id);
            closeOrderModal();
        } else {
            alert('Error: ' + (data.message || 'Failed to place order'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while placing the order');
    });
});
</script>
@endsection
