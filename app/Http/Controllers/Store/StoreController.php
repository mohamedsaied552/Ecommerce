<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;

class StoreController extends Controller
{
    public function home()
    {
        $featured = Product::where('status','active')->latest()->take(6)->get();
        return view('store.home', compact('featured'));
    }
}
