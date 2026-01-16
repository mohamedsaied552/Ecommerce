@extends('layouts.store')

@section('title','Payment Success')

@section('content')
  <div class="rounded-2xl border bg-white p-8 shadow-sm text-center">
    <h1 class="text-2xl font-semibold text-gray-900">Payment initiated</h1>
    <p class="mt-2 text-gray-600">If your payment is successful, your order will be marked as paid via webhook.</p>
    <a class="mt-6 inline-flex rounded-lg bg-blue-600 px-4 py-2 text-white" href="{{ route('store.home') }}">Back to store</a>
  </div>
@endsection
