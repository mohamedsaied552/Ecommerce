@extends('layouts.store')

@section('title','Products')

@section('content')
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    <div>
      <h1 class="text-2xl font-semibold text-gray-900">Products</h1>
      <p class="text-gray-600">Choose what you need and checkout.</p>
    </div>

    <form class="flex gap-2" method="GET" action="{{ route('store.products.index') }}">
      <input name="q" value="{{ request('q') }}" class="w-56 rounded-lg border-gray-300" placeholder="Search...">
      <select name="sort" class="rounded-lg border-gray-300">
        <option value="new" @selected($sort==='new')>Newest</option>
        <option value="price_asc" @selected($sort==='price_asc')>Price: Low</option>
        <option value="price_desc" @selected($sort==='price_desc')>Price: High</option>
      </select>
      <button class="rounded-lg bg-gray-900 px-4 py-2 text-white">Apply</button>
    </form>
  </div>

  @if($products->isEmpty())
    <div class="mt-6 rounded-xl border bg-white p-6 text-gray-600">No products found.</div>
  @else
    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($products as $p)
        <div class="rounded-2xl border bg-white p-5 shadow-sm">
          <a href="{{ route('store.products.show',$p->slug) }}" class="block">
            <div class="text-lg font-semibold text-gray-900">{{ $p->name }}</div>
            <div class="mt-2 text-gray-700">EGP {{ $p->price_egp }}</div>
            <div class="mt-2 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit((string)$p->description, 120) }}</div>
          </a>
          <form class="mt-4" method="POST" action="{{ route('store.cart.add') }}">
            @csrf
            <input type="hidden" name="product_id" value="{{ $p->id }}">
            <button class="w-full rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Add to Cart</button>
          </form>
        </div>
      @endforeach
    </div>

    <div class="mt-6">{{ $products->links() }}</div>
  @endif
@endsection
