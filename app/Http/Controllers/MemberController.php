<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password as PasswordRule;

class MemberController extends Controller
{
    public function profile(): View
    {
        return view('frontend.member-profile');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $member = Auth::guard('member')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        $member->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $member = Auth::guard('member')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password:member'],
            'password' => ['required', 'confirmed', PasswordRule::defaults()],
        ]);

        $member->password = $validated['password'];
        $member->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function orders(Request $request): View
    {
        $member = Auth::guard('member')->user();

        $orders = Order::with(['items'])
            ->where('member_id', $member->id)
            ->latest()
            ->paginate(10);

        return view('frontend.member-orders', [
            'orders' => $orders,
        ]);
    }
}


