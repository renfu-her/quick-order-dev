@extends('layouts.app')

@section('title', 'Login / Register')

@section('content')
<style>
    .auth-container {
        max-width: 500px;
        margin: 3rem auto;
    }
    
    .auth-tabs {
        display: flex;
        background: #f8f9fa;
        border-radius: 10px 10px 0 0;
        overflow: hidden;
        margin-bottom: 0;
    }
    
    .auth-tab {
        flex: 1;
        padding: 1rem;
        text-align: center;
        background: #f8f9fa;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        font-weight: 600;
        color: #666;
        transition: all 0.3s;
    }
    
    .auth-tab.active {
        background: #fff;
        color: #e63946;
        border-bottom: 3px solid #e63946;
    }
    
    .auth-tab:hover {
        background: #fff;
    }
    
    .auth-content {
        background: #fff;
        border-radius: 0 0 10px 10px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    
    .auth-panel {
        display: none;
    }
    
    .auth-panel.active {
        display: block;
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
    
    .form-control.error {
        border-color: #dc3545;
    }
    
    .error-message {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
    
    .btn {
        display: block;
        width: 100%;
        padding: 1rem;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.3s;
        text-align: center;
        text-decoration: none;
    }
    
    .btn-primary {
        background: #e63946;
        color: white;
    }
    
    .btn-primary:hover {
        background: #d62839;
    }
    
    .divider {
        text-align: center;
        margin: 1.5rem 0;
        position: relative;
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
        background: #fff;
        padding: 0 1rem;
        position: relative;
        color: #666;
    }
    
    .text-center {
        text-align: center;
    }
    
    .text-link {
        color: #e63946;
        text-decoration: none;
        font-weight: 500;
    }
    
    .text-link:hover {
        text-decoration: underline;
    }
</style>

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

@push('scripts')
<script>
    function switchTab(tab) {
        // Update tabs
        document.querySelectorAll('.auth-tab').forEach(t => {
            t.classList.remove('active');
        });
        event.target.classList.add('active');
        
        // Update panels
        document.querySelectorAll('.auth-panel').forEach(p => {
            p.classList.remove('active');
        });
        document.getElementById(tab + '-panel').classList.add('active');
    }
    
    // Check if there are errors for register form
    @if($errors->has('name') || $errors->has('phone') || ($errors->has('password') && old('name')))
        switchTab('register');
        document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.auth-tab')[1].classList.add('active');
    @endif
</script>
@endpush
@endsection

