@extends('layouts.admin')

@section('title','Edit Product')

@section('content')
  <h1 class="text-2xl font-semibold text-gray-900">Edit Product</h1>

  <div class="mt-6 rounded-2xl border bg-white p-6 shadow-sm max-w-2xl">
    <form method="POST" action="{{ route('admin.products.update',$product) }}" class="space-y-4">
      @csrf
      @method('PUT')

      <div>
        <label class="block text-sm text-gray-700">Name</label>
        <input name="name" value="{{ old('name',$product->name) }}" class="mt-1 w-full rounded-lg border-gray-300">
        @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700">Slug</label>
        <input name="slug" value="{{ old('slug',$product->slug) }}" class="mt-1 w-full rounded-lg border-gray-300">
        @error('slug') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700">Price (cents)</label>
        <input name="price_cents" value="{{ old('price_cents',$product->price_cents) }}" class="mt-1 w-full rounded-lg border-gray-300">
        @error('price_cents') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700">Status</label>
        <select name="status" class="mt-1 w-full rounded-lg border-gray-300">
          <option value="active" @selected(old('status',$product->status)==='active')>Active</option>
          <option value="inactive" @selected(old('status',$product->status)==='inactive')>Inactive</option>
        </select>
      </div>

      <div>
        <label class="block text-sm text-gray-700">Stock (optional)</label>
        <input name="stock" value="{{ old('stock',$product->stock) }}" class="mt-1 w-full rounded-lg border-gray-300">
      </div>

      <div>
        <label class="block text-sm text-gray-700">Description</label>
        <textarea name="description" class="mt-1 w-full rounded-lg border-gray-300" rows="4">{{ old('description',$product->description) }}</textarea>
      </div>

      <button class="rounded-lg bg-blue-600 px-4 py-2 text-white">Save</button>
      <a href="{{ route('admin.products.index') }}" class="ml-3 text-gray-600 hover:text-gray-900">Back</a>
    </form>
  </div>
@endsection
