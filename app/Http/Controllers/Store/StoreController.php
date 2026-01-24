<?php


namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function home(Request $request)
    {
        $q = Product::where('status', 'active');

        if ($request->filled('q')) {
            $q->where('name', 'like', '%' . $request->q . '%');
        }

        match ($request->get('sort', 'new')) {
            'price_asc'  => $q->orderBy('price_cents', 'asc'),
            'price_desc' => $q->orderBy('price_cents', 'desc'),
            default      => $q->orderBy('created_at', 'desc'),
        };

        $products = $q->paginate(9);

        return view('store.home', compact('products'));
    }
}
