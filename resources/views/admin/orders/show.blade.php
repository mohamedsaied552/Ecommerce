@extends('layouts.admin')

@section('title','Order Details')

@section('content')
  <div class="flex items-start justify-between">
    <div>
      <h1 class="text-2xl font-semibold text-gray-900">{{ $order->order_number }}</h1>
      <div class="mt-1 text-gray-600">{{ $order->created_at->format('Y-m-d H:i') }}</div>
    </div>
    <span class="inline-flex rounded-full px-3 py-1 text-sm
      @if($order->status==='paid') bg-green-50 text-green-700
      @elseif($order->status==='pending') bg-yellow-50 text-yellow-700
      @else bg-gray-100 text-gray-700 @endif
    ">
      {{ ucfirst($order->status) }}
    </span>
  </div>

  <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="rounded-2xl border bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-gray-900">Customer</h2>
      <div class="mt-3 text-gray-700">
        <div><span class="text-gray-500">Name:</span> {{ $order->customer_name }}</div>
        <div><span class="text-gray-500">Phone:</span> {{ $order->customer_phone }}</div>
        <div><span class="text-gray-500">Email:</span> {{ $order->customer_email ?? '—' }}</div>
      </div>
      @if($order->notes)
        <div class="mt-4 text-gray-700">
          <div class="text-gray-500">Notes:</div>
          <div class="mt-1">{{ $order->notes }}</div>
        </div>
      @endif
    </div>

    <div class="rounded-2xl border bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-gray-900">Payment</h2>
      <div class="mt-3 text-gray-700">
        <div><span class="text-gray-500">Total:</span> EGP {{ $order->total_egp }}</div>
        <div><span class="text-gray-500">Gateway:</span> {{ $order->gateway ?? '—' }}</div>
        <div><span class="text-gray-500">Paid at:</span> {{ $order->paid_at ? $order->paid_at->format('Y-m-d H:i') : '—' }}</div>
      </div>
    </div>
  </div>

  <div class="mt-6 rounded-2xl border bg-white p-6 shadow-sm">
    <h2 class="text-lg font-semibold text-gray-900">Items</h2>
    <div class="mt-4 overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="p-3 text-left">Item</th>
            <th class="p-3 text-left">Qty</th>
            <th class="p-3 text-left">Price</th>
            <th class="p-3 text-left">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($order->items as $it)
            <tr class="border-t">
              <td class="p-3">{{ $it->product_name }}</td>
              <td class="p-3">{{ $it->qty }}</td>
              <td class="p-3">EGP {{ number_format($it->price_cents/100,2) }}</td>
              <td class="p-3">EGP {{ number_format($it->subtotal_cents/100,2) }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection
