@extends('layouts.store')

@section('title',$product->name)

@section('content')
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="rounded-2xl border bg-white p-6 shadow-sm">
      <div class="text-sm text-gray-500">EGP</div>
      <h1 class="mt-1 text-2xl font-semibold text-gray-900">{{ $product->name }}</h1>
      <div class="mt-3 text-xl text-gray-900">EGP {{ $product->price_egp }}</div>
      @if($product->description)
        <p class="mt-4 text-gray-700">{{ $product->description }}</p>
      @endif
    </div>

    <div class="rounded-2xl border bg-white p-6 shadow-sm">
      <h2 class="text-lg font-semibold text-gray-900">Buy</h2>
      <form class="mt-4 space-y-3" method="POST" action="{{ route('store.cart.add') }}">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <div>
          <label class="block text-sm text-gray-700">Quantity</label>
          <input name="qty" type="number" min="1" value="1" class="mt-1 w-32 rounded-lg border-gray-300">
        </div>
        <button class="w-full rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Add to Cart</button>
      </form>

      <div class="mt-6 text-sm text-gray-600">
        Secure checkout powered by Paymob.
      </div>
    </div>
  </div>
@endsection
