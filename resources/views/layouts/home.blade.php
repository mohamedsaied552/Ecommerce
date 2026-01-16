@extends('layouts.store')

@section('title','Store Home')

@section('content')
  <div class="rounded-2xl bg-white p-6 shadow-sm border">
    <h1 class="text-2xl font-semibold text-gray-900">Welcome</h1>
    <p class="mt-2 text-gray-600">Browse products and checkout securely.</p>
    <div class="mt-4">
      <a href="{{ route('store.products.index') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
        Browse Products
      </a>
    </div>
  </div>

  <h2 class="mt-10 text-lg font-semibold text-gray-900">Featured</h2>
  @if($featured->isEmpty())
    <div class="mt-4 rounded-xl border bg-white p-6 text-gray-600">No products yet. Admin can add products from dashboard.</div>
  @else
    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($featured as $p)
        <a href="{{ route('store.products.show',$p->slug) }}" class="rounded-2xl border bg-white p-5 shadow-sm hover:shadow">
          <div class="text-sm text-gray-500">EGP</div>
          <div class="mt-1 text-lg font-semibold text-gray-900">{{ $p->name }}</div>
          <div class="mt-2 text-gray-700">EGP {{ $p->price_egp }}</div>
          <div class="mt-3 text-sm text-blue-600">View</div>
        </a>
      @endforeach
    </div>
  @endif
@endsection
