@extends('layouts.store')

@section('title','Cart')

@section('content')
  <div class="flex items-end justify-between">
    <h1 class="text-2xl font-semibold text-gray-900">Cart</h1>
    <form method="POST" action="{{ route('store.cart.clear') }}">
      @csrf
      <button class="text-sm text-gray-600 hover:text-gray-900">Clear cart</button>
    </form>
  </div>

  @if(empty($lines))
    <div class="mt-6 rounded-xl border bg-white p-6 text-gray-600">Your cart is empty.</div>
    <div class="mt-4">
      <a href="{{ route('store.products.index') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-white">Browse products</a>
    </div>
  @else
    <div class="mt-6 rounded-2xl border bg-white shadow-sm overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left p-4">Item</th>
            <th class="text-left p-4">Qty</th>
            <th class="text-left p-4">Subtotal</th>
            <th class="p-4"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($lines as $line)
            <tr class="border-t">
              <td class="p-4">
                <div class="font-medium text-gray-900">{{ $line['product']->name }}</div>
                <div class="text-gray-600">EGP {{ $line['product']->price_egp }}</div>
              </td>
              <td class="p-4">
                <form class="flex items-center gap-2" method="POST" action="{{ route('store.cart.update') }}">
                  @csrf
                  <input type="hidden" name="product_id" value="{{ $line['product']->id }}">
                  <input name="qty" type="number" min="1" value="{{ $line['qty'] }}" class="w-20 rounded-lg border-gray-300">
                  <button class="rounded-lg bg-gray-900 px-3 py-2 text-white">Update</button>
                </form>
              </td>
              <td class="p-4 text-gray-900">
                EGP {{ number_format($line['subtotal_cents']/100,2) }}
              </td>
              <td class="p-4 text-right">
                <form method="POST" action="{{ route('store.cart.remove') }}">
                  @csrf
                  <input type="hidden" name="product_id" value="{{ $line['product']->id }}">
                  <button class="text-red-600 hover:text-red-800">Remove</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-6 flex items-center justify-between">
      <div class="text-lg font-semibold text-gray-900">Total: EGP {{ number_format($total_cents/100,2) }}</div>
      <a href="{{ route('store.checkout') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Checkout</a>
    </div>
  @endif
@endsection
