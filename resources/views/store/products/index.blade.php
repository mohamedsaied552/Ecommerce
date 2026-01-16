@extends('layouts.store')

@section('title','Products')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
  <div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold">Products</h1>
  </div>

  <form method="GET" action="{{ route('store.products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-6">
    <div class="md:col-span-2">
      <input
        type="text"
        name="q"
        value="{{ request('q') }}"
        placeholder="Search products..."
        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
      />
    </div>

    <div>
      <select name="sort" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        <option value="new" {{ request('sort','new')==='new' ? 'selected' : '' }}>Newest</option>
        <option value="price_asc" {{ request('sort')==='price_asc' ? 'selected' : '' }}>Price: Low to High</option>
        <option value="price_desc" {{ request('sort')==='price_desc' ? 'selected' : '' }}>Price: High to Low</option>
      </select>
    </div>

    <div>
      <button class="w-full rounded-lg bg-indigo-600 text-white py-2 font-semibold hover:bg-indigo-700">
        Apply
      </button>
    </div>
  </form>

  @if($products->count() === 0)
    <div class="rounded-xl border bg-white p-8 text-center">
      <p class="text-gray-600">No products found.</p>
    </div>
  @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($products as $p)
        <a href="{{ route('store.products.show', $p->slug) }}" class="group rounded-xl border bg-white overflow-hidden hover:shadow-lg transition">
          <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center">
            <span class="text-gray-400 text-sm">No image</span>
          </div>
          <div class="p-4">
            <div class="flex items-start justify-between gap-3">
              <h3 class="font-semibold group-hover:text-indigo-700">{{ $p->name }}</h3>
              <div class="font-bold whitespace-nowrap">
                {{ number_format($p->price_cents / 100, 2) }} {{ $p->currency ?? 'EGP' }}
              </div>
            </div>
            <p class="mt-2 text-sm text-gray-600 line-clamp-2">
              {{ $p->description ?? '' }}
            </p>
            <div class="mt-4">
              <span class="inline-flex items-center rounded-lg bg-indigo-50 text-indigo-700 px-3 py-1 text-sm font-medium">
                View details
              </span>
            </div>
          </div>
        </a>
      @endforeach
    </div>

    <div class="mt-8">
      {{ $products->withQueryString()->links() }}
    </div>
  @endif
</div>
@endsection
