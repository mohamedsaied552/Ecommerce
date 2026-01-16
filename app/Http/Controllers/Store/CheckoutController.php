<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    private function cart(): array
    {
        return session()->get('cart', []);
    }

    public function show()
    {
        $cart = $this->cart();
        if (empty($cart)) {
            return redirect()->route('store.cart')->with('error','Your cart is empty.');
        }

        return view('store.checkout');
    }

    public function submit(Request $request, PaymobService $paymob)
    {
        $data = $request->validate([
            'customer_name' => ['required','string','max:120'],
            'customer_email' => ['nullable','email','max:190'],
            'customer_phone' => ['required','string','max:30'],
            'notes' => ['nullable','string','max:1000'],
        ]);

        $cart = $this->cart();
        if (empty($cart)) {
            return redirect()->route('store.cart')->with('error','Your cart is empty.');
        }

        $ids = array_map('intval', array_keys($cart));
        $products = Product::whereIn('id',$ids)->where('status','active')->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($cart as $pid => $qty) {
            $p = $products->get((int)$pid);
            if (!$p) continue;

            $qty = (int)$qty;
            $subtotal = $p->price_cents * $qty;
            $total += $subtotal;

            $items[] = [
                'name' => $p->name,
                'amount_cents' => $p->price_cents,
                'description' => Str::limit((string)$p->description, 120),
                'quantity' => $qty,
            ];
        }

        if ($total <= 0 || empty($items)) {
            return redirect()->route('store.cart')->with('error','No valid items in cart.');
        }

        $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        $order = Order::create([
            'order_number' => $orderNumber,
            'status' => 'pending',
            'total_cents' => $total,
            'currency' => 'EGP',
            'customer_name' => $data['customer_name'],
            'customer_email' => $data['customer_email'] ?? null,
            'customer_phone' => $data['customer_phone'],
            'notes' => $data['notes'] ?? null,
        ]);

        foreach ($items as $it) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => null,
                'product_name' => $it['name'],
                'qty' => (int)$it['quantity'],
                'price_cents' => (int)$it['amount_cents'],
                'subtotal_cents' => (int)$it['amount_cents'] * (int)$it['quantity'],
                'currency' => 'EGP',
            ]);
        }

        // Billing data expected by Paymob
        $billing = [
            'first_name' => $data['customer_name'],
            'last_name' => 'NA',
            'email' => $data['customer_email'] ?? 'na@example.com',
            'phone_number' => $data['customer_phone'],
            'city' => 'Cairo',
            'country' => 'EG',
            'state' => 'Cairo',
            'street' => 'NA',
            'building' => 'NA',
            'floor' => 'NA',
            'apartment' => 'NA',
            'postal_code' => '00000',
            'shipping_method' => 'NA',
        ];

        try {
            $checkoutUrl = $paymob->getCheckoutUrlForStoreOrder(
                $order->total_cents,
                $order->order_number,
                $billing,
                $items
            );

            // store gateway marker (optional)
            $order->update([
                'gateway' => 'paymob',
            ]);

            // clear cart
            session()->forget('cart');

            return redirect()->away($checkoutUrl);
        } catch (\Exception $e) {
            return redirect()->route('store.checkout')->with('error', 'Failed to initiate payment. ' . $e->getMessage());
        }
    }

    public function success()
    {
        return view('store.success');
    }

    public function cancel()
    {
        return view('store.cancel');
    }
}
