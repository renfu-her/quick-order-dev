@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container" style="max-width: 900px; margin: 0 auto;">
    <h1 style="margin-bottom: 1rem; color: #333;">Profile</h1>

    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <!-- Profile Info & Edit -->
        <div style="background:#fff; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); padding:1.5rem; display:flex; flex-direction:column;">
            <h2 style="margin-bottom: 1rem; color:#333;">General</h2>
            <form action="{{ route('member.profile.update') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
                @csrf
                <div class="form-group">
                    <label>Email (read only)</label>
                    <input class="form-control" type="email" value="{{ Auth::guard('member')->user()->email }}" readonly>
                </div>
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" name="name" value="{{ old('name', Auth::guard('member')->user()->name) }}" required>
                    @error('name')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input class="form-control" name="phone" value="{{ old('phone', Auth::guard('member')->user()->phone) }}">
                    @error('phone')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input class="form-control" name="address" value="{{ old('address', Auth::guard('member')->user()->address) }}">
                    @error('address')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div style="margin-top:auto;">
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                </div>
            </form>
        </div>

        <!-- Change Password -->
        <div style="background:#fff; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); padding:1.5rem; display:flex; flex-direction:column;">
            <h2 style="margin-bottom: 1rem; color:#333;">Change Password</h2>
            <form action="{{ route('member.password.update') }}" method="POST" style="display:flex; flex-direction:column; height:100%;">
                @csrf
                <div class="form-group">
                    <label>Current Password</label>
                    <input class="form-control" type="password" name="current_password" required>
                    @error('current_password')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input class="form-control" type="password" name="password" required>
                    @error('password')<div class="error-message">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input class="form-control" type="password" name="password_confirmation" required>
                </div>
                <div style="margin-top:auto;">
                    <button class="btn btn-primary" type="submit">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


