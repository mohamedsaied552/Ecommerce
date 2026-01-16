<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = Product::query();

        if ($s = trim((string)$request->get('q'))) {
            $q->where('name','like',"%{$s}%")->orWhere('slug','like',"%{$s}%");
        }

        $products = $q->latest()->paginate(15)->withQueryString();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:200'],
            'slug' => ['nullable','string','max:220','unique:products,slug'],
            'price_cents' => ['required','integer','min:1'],
            'description' => ['nullable','string'],
            'status' => ['required','in:active,inactive'],
            'stock' => ['nullable','integer','min:0'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $data['currency'] = 'EGP';

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success','Product created.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => ['required','string','max:200'],
            'slug' => ['nullable','string','max:220','unique:products,slug,'.$product->id],
            'price_cents' => ['required','integer','min:1'],
            'description' => ['nullable','string'],
            'status' => ['required','in:active,inactive'],
            'stock' => ['nullable','integer','min:0'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        $product->update($data);

        return redirect()->route('admin.products.index')->with('success','Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success','Product deleted.');
    }
}
