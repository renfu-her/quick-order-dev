<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Quick Order') }} - @yield('title', 'Food Ordering System')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="{{ asset('css/custom.css?v=' . time()) }}">
    <link rel="stylesheet" href="{{ asset('css/custom/toast.css?v=' . time()) }}">
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <nav>
                <a href="{{ route('home') }}" class="logo">Quick Order</a>
                
                <ul class="nav-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('home') }}#menu">Menu</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
                
                <div style="display: flex; gap: 1rem; align-items: center;">
                    @auth('member')
                        @php
                            $cart = \App\Models\Cart::where('member_id', auth('member')->id())
                                ->where('status', 'active')
                                ->with('items')
                                ->first();
                            $cartQty = $cart ? $cart->items->sum('quantity') : 0;
                        @endphp
                        <a href="{{ route('cart.index') }}" class="cart-badge cart-inline">
                            🛒 Cart
                            @if($cartQty > 0)
                                <span class="cart-count">{{ $cartQty }}</span>
                            @endif
                        </a>
                        <span style="color: #666;">👤 {{ Auth::guard('member')->user()->name }}</span>
                        <form action="{{ route('member.logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: #f77f00; cursor: pointer; font-weight: 500;">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('member.auth') }}" style="color: #666; text-decoration: none; font-weight: 500;">
                            Login / Register
                        </a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>
    
    <!-- Main Content -->
    <main>
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info">
                    {{ session('info') }}
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>About Us</h3>
                    <p>Quick Order is your go-to platform for fast and easy food ordering. We bring your favorite meals right to your doorstep.</p>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="#">Menu</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Customer Service</h3>
                    <ul>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Shipping Policy</a></li>
                        <li><a href="#">Return Policy</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact</h3>
                    <ul>
                        <li>📧 info@quickorder.com</li>
                        <li>📞 +1 (555) 123-4567</li>
                        <li>📍 123 Food Street, City, State</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Quick Order. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <!-- Toast Notification System -->
    <script src="{{ asset('js/toast.js?v=' . time()) }}"></script>
    
    @stack('scripts')
</body>
</html>

