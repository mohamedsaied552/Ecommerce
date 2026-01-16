@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container mx-auto p-6 max-w-3xl">

    <h1 class="text-2xl font-bold mb-6">Checkout</h1>

    @if(count($lines) === 0)
        <div class="bg-yellow-100 text-yellow-800 p-4 rounded">
            Your cart is empty.
        </div>
    @else
        <table class="w-full border mb-6">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Product</th>
                    <th class="p-3 text-center">Qty</th>
                    <th class="p-3 text-center">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lines as $line)
                    <tr class="border-t">
                        <td class="p-3">{{ $line['product']->name }}</td>
                        <td class="p-3 text-center">{{ $line['qty'] }}</td>
                        <td class="p-3 text-center">
                            {{ number_format($line['subtotal_cents'] / 100, 2) }} EGP
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right text-xl font-semibold mb-6">
            Total: {{ number_format($total_cents / 100, 2) }} EGP
        </div>

        <form method="POST" action="{{ route('checkout.place') }}">
            @csrf
            <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Place Order
            </button>
        </form>
        <form method="POST" action="{{ route('paymob.pay') }}">
    @csrf
    <input type="hidden" name="total_cents" value="{{ $total_cents }}">
    <button class="bg-indigo-600 text-white px-4 py-2 rounded">
        Pay with Card
    </button>
</form>

    @endif

</div>
@endsection
