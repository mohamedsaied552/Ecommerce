<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function cart(): array
    {
        return session()->get('cart', []);
    }

    private function saveCart(array $cart): void
    {
        session()->put('cart', $cart);
    }

    public function index()
    {
        $cart = $this->cart();
        $ids = array_keys($cart);
        $products = Product::whereIn('id',$ids)->get()->keyBy('id');

        $lines = [];
        $total = 0;

        foreach ($cart as $pid => $qty) {
            $p = $products->get((int)$pid);
            if (!$p) continue;
            $subtotal = $p->price_cents * (int)$qty;
            $total += $subtotal;

            $lines[] = [
                'product' => $p,
                'qty' => (int)$qty,
                'subtotal_cents' => $subtotal,
            ];
        }

        return view('store.cart', [
            'lines' => $lines,
            'total_cents' => $total,
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer','exists:products,id'],
            'qty' => ['nullable','integer','min:1','max:99'],
        ]);

        $qty = (int)($data['qty'] ?? 1);
        $cart = $this->cart();
        $pid = (string)$data['product_id'];

        $cart[$pid] = ($cart[$pid] ?? 0) + $qty;
        $this->saveCart($cart);

        return back()->with('success','Added to cart.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer'],
            'qty' => ['required','integer','min:1','max:99'],
        ]);

        $cart = $this->cart();
        $pid = (string)$data['product_id'];

        if (isset($cart[$pid])) {
            $cart[$pid] = (int)$data['qty'];
            $this->saveCart($cart);
        }

        return back()->with('success','Cart updated.');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required','integer'],
        ]);

        $cart = $this->cart();
        $pid = (string)$data['product_id'];

        unset($cart[$pid]);
        $this->saveCart($cart);

        return back()->with('success','Removed from cart.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('store.cart')->with('success','Cart cleared.');
    }
}
