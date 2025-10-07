<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Product;
use App\Models\Store;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function index(): View
    {
        $stores = Store::where('is_active', true)
            ->with(['images'])
            ->orderBy('name')
            ->get();
            
        $ads = Ad::active()->with('product')->get();
        
        $products = Product::where('is_available', true)
            ->with(['images'])
            ->latest()
            ->paginate(12);

        return view('frontend.index', compact('stores', 'ads', 'products'));
    }
}

