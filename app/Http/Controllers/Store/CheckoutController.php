<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $products = collect(\App\Models\Product::whereIn('id', array_keys($cart))->get())
            ->keyBy('id');

        $lines = [];
        $total = 0;

        foreach ($cart as $pid => $qty) {
            $p = $products->get((int)$pid);
            if (!$p) continue;

            $subtotal = $p->price_cents * (int)$qty;
            $total += $subtotal;

            $lines[] = [
                'product' => $p,
                'qty' => $qty,
                'subtotal_cents' => $subtotal,
            ];
        }

        return view('store.checkout', [
            'lines' => $lines,
            'total_cents' => $total,
        ]);
    }

    public function place()
    {
        session()->forget('cart');

        return redirect()->route('cart.index')
            ->with('success', 'Order placed successfully!');
    }
}
