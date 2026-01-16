@extends('layouts.admin')

@section('title','New Product')

@section('content')
  <h1 class="text-2xl font-semibold text-gray-900">Create Product</h1>

  <div class="mt-6 rounded-2xl border bg-white p-6 shadow-sm max-w-2xl">
    <form method="POST" action="{{ route('admin.products.store') }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-sm text-gray-700">Name</label>
        <input name="name" value="{{ old('name') }}" class="mt-1 w-full rounded-lg border-gray-300">
        @error('name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700">Slug (optional)</label>
        <input name="slug" value="{{ old('slug') }}" class="mt-1 w-full rounded-lg border-gray-300">
        @error('slug') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700">Price (cents) — EGP * 100</label>
        <input name="price_cents" value="{{ old('price_cents') }}" class="mt-1 w-full rounded-lg border-gray-300" placeholder="e.g. 5000 for 50.00 EGP">
        @error('price_cents') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700">Status</label>
        <select name="status" class="mt-1 w-full rounded-lg border-gray-300">
          <option value="active">Active</option>
          <option value="inactive">Inactive</option>
        </select>
        @error('status') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700">Stock (optional)</label>
        <input name="stock" value="{{ old('stock') }}" class="mt-1 w-full rounded-lg border-gray-300">
        @error('stock') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700">Description (optional)</label>
        <textarea name="description" class="mt-1 w-full rounded-lg border-gray-300" rows="4">{{ old('description') }}</textarea>
        @error('description') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <button class="rounded-lg bg-blue-600 px-4 py-2 text-white">Create</button>
      <a href="{{ route('admin.products.index') }}" class="ml-3 text-gray-600 hover:text-gray-900">Cancel</a>
    </form>
  </div>
@endsection
