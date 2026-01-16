@extends('layouts.admin')

@section('title','Products')

@section('content')
  <div class="flex items-end justify-between gap-4">
    <div>
      <h1 class="text-2xl font-semibold text-gray-900">Products</h1>
      <p class="text-gray-600">Manage store products.</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-white">New Product</a>
  </div>

  <form class="mt-4 flex gap-2" method="GET">
    <input name="q" value="{{ request('q') }}" class="w-72 rounded-lg border-gray-300" placeholder="Search products...">
    <button class="rounded-lg bg-gray-900 px-4 py-2 text-white">Search</button>
  </form>

  <div class="mt-6 rounded-2xl border bg-white shadow-sm overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="p-4 text-left">Name</th>
          <th class="p-4 text-left">Price</th>
          <th class="p-4 text-left">Status</th>
          <th class="p-4 text-right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($products as $p)
          <tr class="border-t">
            <td class="p-4">
              <div class="font-medium text-gray-900">{{ $p->name }}</div>
              <div class="text-gray-500">{{ $p->slug }}</div>
            </td>
            <td class="p-4">EGP {{ $p->price_egp }}</td>
            <td class="p-4">
              <span class="inline-flex rounded-full px-2 py-1 text-xs {{ $p->status==='active'?'bg-green-50 text-green-700':'bg-gray-100 text-gray-700' }}">
                {{ ucfirst($p->status) }}
              </span>
            </td>
            <td class="p-4 text-right">
              <a href="{{ route('admin.products.edit',$p) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
              <form class="inline" method="POST" action="{{ route('admin.products.destroy',$p) }}" onsubmit="return confirm('Delete product?')">
                @csrf
                @method('DELETE')
                <button class="ml-3 text-red-600 hover:text-red-800">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td class="p-6 text-gray-600" colspan="4">No products yet.</td></tr>
        <td class="p-4">
  @if($p->image)
    <img src="{{ asset('storage/products/' . $p->image) }}" class="w-12 h-12 object-cover rounded mr-2 inline" alt="{{ $p->name }}">
  @endif
  <div class="font-medium text-gray-900 inline">{{ $p->name }}</div>
  <div class="text-gray-500">{{ $p->slug }}</div>
</td>
          @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">{{ $products->links() }}</div>
@endsection
