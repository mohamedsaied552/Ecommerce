<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $q = Order::query();

        if ($s = trim((string)$request->get('q'))) {
            $q->where('order_number','like',"%{$s}%")
              ->orWhere('customer_name','like',"%{$s}%")
              ->orWhere('customer_phone','like',"%{$s}%");
        }

        if ($status = $request->get('status')) {
            $q->where('status',$status);
        }

        $orders = $q->latest()->paginate(20)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('admin.orders.show', compact('order'));
    }
}
