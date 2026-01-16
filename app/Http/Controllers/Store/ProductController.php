<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = Product::query()->where('status','active');

        if ($search = trim((string)$request->get('q'))) {
            $q->where(function($qq) use ($search) {
                $qq->where('name','like',"%{$search}%")
                   ->orWhere('description','like',"%{$search}%");
            });
        }

        $sort = $request->get('sort','new');
        if ($sort === 'price_asc') $q->orderBy('price_cents','asc');
        elseif ($sort === 'price_desc') $q->orderBy('price_cents','desc');
        else $q->latest();

        $products = $q->paginate(12)->withQueryString();

        return view('store.products.index', compact('products','sort'));
    }

    public function show(string $slug)
    {
        $product = Product::where('slug',$slug)->where('status','active')->firstOrFail();
        return view('store.products.show', compact('product'));
    }
}
