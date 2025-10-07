@extends('layouts.app')

@section('title', 'Login / Register')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/custom/auth.css?v=' . time()) }}">
@endpush

@section('content')

<div class="auth-container">
    <div class="auth-tabs">
        <button class="auth-tab active" onclick="switchTab('login')">Login</button>
        <button class="auth-tab" onclick="switchTab('register')">Register</button>
    </div>
    
    <div class="auth-content">
        <!-- Login Form -->
        <div id="login-panel" class="auth-panel active">
            <h2 style="margin-bottom: 1.5rem; color: #333;">Welcome Back!</h2>
            
            <form action="{{ route('member.login') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="login-email">Email Address</label>
                    <input type="email" 
                           id="login-email" 
                           name="email" 
                           class="form-control @error('email') error @enderror" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus>
                    @error('email')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="login-password">Password</label>
                    <input type="password" 
                           id="login-password" 
                           name="password" 
                           class="form-control @error('password') error @enderror" 
                           required>
                    @error('password')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem; font-weight: normal;">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember Me
                    </label>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    🔐 Login
                </button>
            </form>
            
            <div class="divider">
                <span>or</span>
            </div>
            
            <div class="text-center">
                <p style="color: #666;">
                    Don't have an account? 
                    <a href="#" class="text-link" onclick="switchTab('register'); return false;">
                        Register now
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Register Form -->
        <div id="register-panel" class="auth-panel">
            <h2 style="margin-bottom: 1.5rem; color: #333;">Create Account</h2>
            
            <form action="{{ route('member.register') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="register-name">Full Name</label>
                    <input type="text" 
                           id="register-name" 
                           name="name" 
                           class="form-control @error('name') error @enderror" 
                           value="{{ old('name') }}" 
                           required>
                    @error('name')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="register-email">Email Address</label>
                    <input type="email" 
                           id="register-email" 
                           name="email" 
                           class="form-control @error('email') error @enderror" 
                           value="{{ old('email') }}" 
                           required>
                    @error('email')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="register-phone">Phone Number</label>
                    <input type="tel" 
                           id="register-phone" 
                           name="phone" 
                           class="form-control @error('phone') error @enderror" 
                           value="{{ old('phone') }}">
                    @error('phone')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="register-password">Password</label>
                    <input type="password" 
                           id="register-password" 
                           name="password" 
                           class="form-control @error('password') error @enderror" 
                           required>
                    @error('password')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                    <small style="color: #666; font-size: 0.85rem;">Minimum 8 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="register-password-confirmation">Confirm Password</label>
                    <input type="password" 
                           id="register-password-confirmation" 
                           name="password_confirmation" 
                           class="form-control" 
                           required>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    ✨ Create Account
                </button>
            </form>
            
            <div class="divider">
                <span>or</span>
            </div>
            
            <div class="text-center">
                <p style="color: #666;">
                    Already have an account? 
                    <a href="#" class="text-link" onclick="switchTab('login'); return false;">
                        Login here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .divider {
        position: relative;
        text-align: center;
        margin: 2rem 0;
    }
    
    .divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #ddd;
    }
    
    .divider span {
        position: relative;
        background: #fff;
        padding: 0 1rem;
        color: #999;
    }
    
    .text-center {
        text-align: center;
    }
    
    .text-link {
        color: #e63946;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s;
    }
    
    .text-link:hover {
        color: #d62839;
        text-decoration: underline;
    }
</style>
@endpush

@push('scripts')
<script>
    function switchTab(tab) {
        // Hide all panels
        document.querySelectorAll('.auth-panel').forEach(panel => {
            panel.classList.remove('active');
        });
        
        // Deactivate all tabs
        document.querySelectorAll('.auth-tab').forEach(button => {
            button.classList.remove('active');
        });
        
        // Show selected panel and activate tab
        if (tab === 'login') {
            document.getElementById('login-panel').classList.add('active');
            document.querySelectorAll('.auth-tab')[0].classList.add('active');
        } else {
            document.getElementById('register-panel').classList.add('active');
            document.querySelectorAll('.auth-tab')[1].classList.add('active');
        }
    }
</script>
@endpush

@endsection
