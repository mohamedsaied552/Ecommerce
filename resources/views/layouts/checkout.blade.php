@extends('layouts.store')

@section('title','Checkout')

@section('content')
  <h1 class="text-2xl font-semibold text-gray-900">Checkout</h1>
  <p class="mt-1 text-gray-600">Enter your details to proceed to payment.</p>

  <div class="mt-6 rounded-2xl border bg-white p-6 shadow-sm max-w-xl">
    <form method="POST" action="{{ route('store.checkout.submit') }}" class="space-y-4">
      @csrf

      <div>
        <label class="block text-sm text-gray-700">Full name</label>
        <input name="customer_name" value="{{ old('customer_name') }}" class="mt-1 w-full rounded-lg border-gray-300">
        @error('customer_name') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700">Email (optional)</label>
        <input name="customer_email" value="{{ old('customer_email') }}" class="mt-1 w-full rounded-lg border-gray-300">
        @error('customer_email') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700">Phone</label>
        <input name="customer_phone" value="{{ old('customer_phone') }}" class="mt-1 w-full rounded-lg border-gray-300" placeholder="+20...">
        @error('customer_phone') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <div>
        <label class="block text-sm text-gray-700">Notes (optional)</label>
        <textarea name="notes" class="mt-1 w-full rounded-lg border-gray-300" rows="3">{{ old('notes') }}</textarea>
        @error('notes') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
      </div>

      <button class="w-full rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
        Proceed to Paymob
      </button>

      <div class="text-xs text-gray-500">
        Need help? Contact us via email/WhatsApp (placeholder).
      </div>
    </form>
  </div>
@endsection
