@extends('layouts.store')

@section('title', $product->name)

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="rounded-2xl border bg-white overflow-hidden">
      <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center">
        <span class="text-gray-400 text-sm">No image</span>
      </div>
    </div>

    <div>
      <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
      <div class="mt-2 text-2xl font-extrabold">
        {{ number_format($product->price_cents / 100, 2) }} {{ $product->currency ?? 'EGP' }}
      </div>

      @if($product->description)
        <p class="mt-4 text-gray-700 leading-relaxed">{{ $product->description }}</p>
      @endif

      <form method="POST" action="{{ route('store.cart.add') }}" class="mt-6 flex items-center gap-3">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <input
          type="number"
          name="qty"
          min="1"
          value="1"
          class="w-24 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
        />

        <button class="flex-1 rounded-lg bg-indigo-600 text-white py-3 font-semibold hover:bg-indigo-700">
          Add to cart
        </button>
      </form>

      @if ($errors->any())
        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700 text-sm">
          <ul class="list-disc pl-5">
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @if(session('success'))
        <div class="mt-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700 text-sm">
          {{ session('success') }}
        </div>
        
      @endif
    </div>
  </div>
</div>
@endsection
