@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container mx-auto p-6">

    <h1 class="text-2xl font-bold mb-6">🛒 Your Cart</h1>

    @if(count($lines) === 0)
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
            Your cart is empty.
        </div>
    @else
        <table class="w-full border border-gray-300 mb-6">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Product</th>
                    <th class="p-3 text-center">Price</th>
                    <th class="p-3 text-center">Qty</th>
                    <th class="p-3 text-center">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lines as $line)
                    <tr class="border-t">
                        <td class="p-3">
    {{ $line['product']->name ?? 'Unknown Product' }}
</td>
                        <td class="p-3 text-center">
{{ number_format($line['product']->price_cents / 100, 2) }} EGP
                        </td>
                        <td class="p-3 text-center">{{ $line['qty'] }}</td>
                        <td class="p-3 text-center">
                            {{ number_format($line['subtotal_cents'] / 100, 2) }} EGP
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right text-xl font-semibold">
            Total: {{ number_format($total_cents / 100, 2) }} EGP
        </div>

        <div class="mt-6 text-right">
            <a href="{{ route('checkout') }}"
   class="bg-green-600 text-white px-6 py-2 rounded">
    Proceed to Checkout
</a>
        </div>
    @endif

</div>
@endsection
