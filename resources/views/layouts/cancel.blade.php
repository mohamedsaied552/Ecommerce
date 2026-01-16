@extends('layouts.store')

@section('title','Payment Canceled')

@section('content')
  <div class="rounded-2xl border bg-white p-8 shadow-sm text-center">
    <h1 class="text-2xl font-semibold text-gray-900">Payment canceled</h1>
    <p class="mt-2 text-gray-600">You can retry checkout anytime.</p>
    <a class="mt-6 inline-flex rounded-lg bg-gray-900 px-4 py-2 text-white" href="{{ route('store.cart') }}">Back to cart</a>
  </div>
@endsection
