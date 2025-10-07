@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom/store-detail.css?v=' . time()) }}">
@endpush

@section('content')
<div class="container">
    <!-- Store Header -->
    <div class="store-header">
        <div class="store-hero">
            @if($store->images->count() > 0)
            <div class="store-carousel" id="mainCarousel">
                <div class="carousel-track" id="carouselTrack">
                    @foreach($store->images as $index => $image)
                    <div class="carousel-slide {{ $index === 0 ? 'active' : '' }}">
                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                             alt="{{ $store->name }}" 
                             class="store-hero-image">
                    </div>
                    @endforeach
                </div>
                
                @if($store->images->count() > 1)
                <!-- Navigation Buttons -->
                <button class="carousel-btn carousel-prev" id="carouselPrev">
                    <span>❮</span>
                </button>
                <button class="carousel-btn carousel-next" id="carouselNext">
                    <span>❯</span>
                </button>
                
                <!-- Dots Indicator -->
                <div class="carousel-dots">
                    @foreach($store->images as $index => $image)
                    <button class="carousel-dot {{ $index === 0 ? 'active' : '' }}" 
                            data-slide="{{ $index }}"></button>
                    @endforeach
                </div>
                @endif
            </div>
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
                    <div class="product-content">
                        <h3 class="product-name">{{ $product->name }}</h3>
                        
                        @if($product->description)
                        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                        @endif
                    </div>
                    
                    <div class="product-bottom">
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
                        
                        <button class="btn btn-order" onclick="openAddToCartModal({{ $product->id }})">
                            @if(auth('member')->check())
                                🛒 Add to Cart
                            @else
                                🔐 Login to Add to Cart
                            @endif
                        </button>
                    </div>
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

<!-- Add to Cart Modal -->
<div id="addToCartModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalProductName">Add to Cart</h3>
            <span class="close" onclick="closeAddToCartModal()">&times;</span>
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
            
            <form id="addToCartForm" class="order-form">
                @csrf
                <input type="hidden" id="productId" name="product_id">
                <input type="hidden" id="storeId" name="store_id" value="{{ $store->id }}">
                
                <!-- Temperature Selection (if product has hot/cold prices) -->
                <div class="form-group" id="temperatureGroup" style="display: none;">
                    <label>Temperature</label>
                    <div class="temperature-options">
                        <label class="temperature-option">
                            <input type="radio" name="temperature" value="regular" checked>
                            <span class="temperature-label">Regular</span>
                        </label>
                        <label class="temperature-option" id="hotOption" style="display: none;">
                            <input type="radio" name="temperature" value="hot">
                            <span class="temperature-label">🔥 Hot</span>
                        </label>
                        <label class="temperature-option" id="coldOption" style="display: none;">
                            <input type="radio" name="temperature" value="cold">
                            <span class="temperature-label">❄️ Cold</span>
                        </label>
                    </div>
                </div>
                
                <!-- Ingredients Selection -->
                <div class="form-group" id="ingredientsGroup" style="display: none;">
                    <label>Ingredients (Optional)</label>
                    <div class="ingredients-list" id="ingredientsList">
                        <!-- Ingredients will be populated by JavaScript -->
                    </div>
                </div>
                
                <!-- Quantity -->
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <div class="quantity-selector">
                        <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">-</button>
                        <input type="number" id="quantity" name="quantity" min="1" value="1" readonly>
                        <button type="button" class="quantity-btn" onclick="changeQuantity(1)">+</button>
                    </div>
                </div>
                
                <!-- Special Instructions -->
                <div class="form-group">
                    <label for="special_instructions">Special Instructions</label>
                    <textarea id="special_instructions" name="special_instructions" rows="3" placeholder="Any special requests? (e.g., no onions, extra sauce)"></textarea>
                </div>
                
                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span id="subtotal">$0.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span id="total">$0.00</span>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeAddToCartModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add to Cart</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Product data for modal
const products = @json($products->keyBy('id'));

// Carousel functionality
document.addEventListener('DOMContentLoaded', function() {
    const carouselTrack = document.getElementById('carouselTrack');
    const carouselPrev = document.getElementById('carouselPrev');
    const carouselNext = document.getElementById('carouselNext');
    const carouselDots = document.querySelectorAll('.carousel-dot');
    
    console.log('Carousel elements found:', {
        track: !!carouselTrack,
        prev: !!carouselPrev,
        next: !!carouselNext,
        dots: carouselDots.length
    });
    
    if (!carouselTrack) {
        console.log('No carousel track found, exiting');
        return; // Exit if no carousel
    }
    
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-slide');
    const totalSlides = slides.length;
    
    function showSlide(index) {
        // Remove active class from all slides and dots
        slides.forEach(slide => slide.classList.remove('active'));
        carouselDots.forEach(dot => dot.classList.remove('active'));
        
        // Add active class to current slide and dot
        slides[index].classList.add('active');
        if (carouselDots[index]) {
            carouselDots[index].classList.add('active');
        }
        
        // Update track position
        carouselTrack.style.transform = `translateX(-${index * 100}%)`;
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }
    
    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(currentSlide);
    }
    
    // Event listeners
    if (carouselNext) {
        carouselNext.addEventListener('click', nextSlide);
    }
    
    if (carouselPrev) {
        carouselPrev.addEventListener('click', prevSlide);
    }
    
    // Dot navigation
    carouselDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });
    
    // Auto-play (optional)
    setInterval(nextSlide, 5000); // Change slide every 5 seconds
});

function openAddToCartModal(productId) {
    const product = products[productId];
    if (!product) return;
    
    // Check if user is logged in
    const isLoggedIn = {{ auth('member')->check() ? 'true' : 'false' }};
    
    if (!isLoggedIn) {
        // Show login prompt instead of add to cart modal
        if (confirm('Please login to add items to cart. Would you like to go to the login page?')) {
            window.location.href = '{{ route("member.auth") }}';
        }
        return;
    }
    
    // Update modal content
    document.getElementById('modalProductName').textContent = 'Add to Cart: ' + product.name;
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
    
    // Setup temperature options
    setupTemperatureOptions(product);
    
    // Setup ingredients
    setupIngredients(product);
    
    // Reset form
    document.getElementById('addToCartForm').reset();
    document.getElementById('quantity').value = 1;
    
    // Update pricing
    updatePricing();
    
    // Show modal
    document.getElementById('addToCartModal').style.display = 'block';
}

function setupTemperatureOptions(product) {
    const temperatureGroup = document.getElementById('temperatureGroup');
    const hotOption = document.getElementById('hotOption');
    const coldOption = document.getElementById('coldOption');
    
    // Check if product has hot/cold prices
    const hasHotPrice = product.hot_price && parseFloat(product.hot_price) > 0;
    const hasColdPrice = product.cold_price && parseFloat(product.cold_price) > 0;
    
    if (hasHotPrice || hasColdPrice) {
        temperatureGroup.style.display = 'block';
        hotOption.style.display = hasHotPrice ? 'block' : 'none';
        coldOption.style.display = hasColdPrice ? 'block' : 'none';
        
        // Set default temperature
        document.querySelector('input[name="temperature"][value="regular"]').checked = true;
    } else {
        temperatureGroup.style.display = 'none';
    }
}

function setupIngredients(product) {
    const ingredientsGroup = document.getElementById('ingredientsGroup');
    const ingredientsList = document.getElementById('ingredientsList');
    
    if (product.ingredients && product.ingredients.length > 0) {
        ingredientsGroup.style.display = 'block';
        ingredientsList.innerHTML = '';
        
        // Add ingredient limit info
        const limitInfo = document.createElement('div');
        limitInfo.className = 'ingredient-limit-info';
        const limit = parseInt(product.ingredient_limit) || 3;
        const limitText = limit === 0 ? 'Unlimited ingredients allowed' : `Maximum ${limit} ingredients allowed`;
        limitInfo.innerHTML = `<small class="text-muted">${limitText}</small>`;
        ingredientsList.appendChild(limitInfo);
        
        product.ingredients.forEach(ingredient => {
            const ingredientItem = document.createElement('div');
            ingredientItem.className = 'ingredient-item';
            ingredientItem.innerHTML = `
                <label class="ingredient-option">
                    <input type="checkbox" name="ingredients[]" value="${ingredient.id}" data-price="${ingredient.extra_price || 0}">
                    <span class="ingredient-label">
                        ${ingredient.ingredient_name}
                        ${ingredient.extra_price > 0 ? ` (+$${parseFloat(ingredient.extra_price).toFixed(2)})` : ''}
                    </span>
                </label>
            `;
            ingredientsList.appendChild(ingredientItem);
        });
        
        // Add event listeners for ingredient limit checking (only if limit > 0)
        if (limit > 0) {
            setupIngredientLimitHandling(product);
        }
    } else {
        ingredientsGroup.style.display = 'none';
    }
}

function setupIngredientLimitHandling(product) {
    const ingredientCheckboxes = document.querySelectorAll('input[name="ingredients[]"]');
    const limit = parseInt(product.ingredient_limit) || 3;
    
    if (limit === 0) return; // No limit - unlimited ingredients allowed
    
    ingredientCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedBoxes = document.querySelectorAll('input[name="ingredients[]"]:checked');
            
            if (checkedBoxes.length > limit) {
                // Uncheck the last checked box
                this.checked = false;
                showIngredientLimitMessage(limit);
            }
            
            updatePricing();
        });
    });
}

function showIngredientLimitMessage(limit) {
    // Create or update limit message
    let messageElement = document.getElementById('ingredientLimitMessage');
    if (!messageElement) {
        messageElement = document.createElement('div');
        messageElement.id = 'ingredientLimitMessage';
        messageElement.className = 'ingredient-limit-message';
        document.getElementById('ingredientsList').appendChild(messageElement);
    }
    
    messageElement.innerHTML = `<small class="text-danger">⚠️ Maximum ${limit} ingredients allowed</small>`;
    
    // Hide message after 3 seconds
    setTimeout(() => {
        if (messageElement) {
            messageElement.style.display = 'none';
        }
    }, 3000);
}

function changeQuantity(delta) {
    const quantityInput = document.getElementById('quantity');
    const currentQuantity = parseInt(quantityInput.value);
    const newQuantity = Math.max(1, currentQuantity + delta);
    quantityInput.value = newQuantity;
    updatePricing();
}

function updatePricing() {
    const product = products[document.getElementById('productId').value];
    if (!product) return;
    
    const quantity = parseInt(document.getElementById('quantity').value);
    const temperature = document.querySelector('input[name="temperature"]:checked')?.value || 'regular';
    
    // Get base price based on temperature
    let basePrice = parseFloat(product.price);
    if (temperature === 'hot' && product.hot_price) {
        basePrice = parseFloat(product.hot_price);
    } else if (temperature === 'cold' && product.cold_price) {
        basePrice = parseFloat(product.cold_price);
    }
    
    // Calculate ingredient extras
    let ingredientExtras = 0;
    const selectedIngredients = document.querySelectorAll('input[name="ingredients[]"]:checked');
    selectedIngredients.forEach(ingredient => {
        ingredientExtras += parseFloat(ingredient.dataset.price || 0);
    });
    
    const subtotal = (basePrice + ingredientExtras) * quantity;
    
    document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('total').textContent = '$' + subtotal.toFixed(2);
}

function closeAddToCartModal() {
    document.getElementById('addToCartModal').style.display = 'none';
    document.getElementById('addToCartForm').reset();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('addToCartModal');
    if (event.target === modal) {
        closeAddToCartModal();
    }
}

// Add event listeners for dynamic pricing updates
document.addEventListener('DOMContentLoaded', function() {
    // Temperature change listener
    document.addEventListener('change', function(e) {
        if (e.target.name === 'temperature') {
            updatePricing();
        }
    });
    
    // Ingredients change listener
    document.addEventListener('change', function(e) {
        if (e.target.name === 'ingredients[]') {
            updatePricing();
        }
    });
});

// Handle form submission
document.getElementById('addToCartForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Add selected ingredients to form data
    const selectedIngredients = document.querySelectorAll('input[name="ingredients[]"]:checked');
    const ingredientIds = Array.from(selectedIngredients).map(ingredient => ingredient.value);
    formData.append('ingredient_ids', JSON.stringify(ingredientIds));
    
    // Add temperature to form data
    const temperature = document.querySelector('input[name="temperature"]:checked')?.value || 'regular';
    formData.append('temperature', temperature);
    
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            successToast('Item added to cart successfully!');
            closeAddToCartModal();
            
            // Update cart count if element exists
            const cartBadge = document.querySelector('.cart-count');
            if (cartBadge && data.cart_count) {
                cartBadge.textContent = data.cart_count;
            }
            
            // Optionally redirect to cart after 1 second
            setTimeout(() => {
                if (confirm('Would you like to view your cart?')) {
                    window.location.href = '{{ route("cart.index") }}';
                }
            }, 500);
        } else {
            errorToast(data.message || 'Failed to add item to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        errorToast('An error occurred while adding item to cart');
    });
});
</script>
@endsection
