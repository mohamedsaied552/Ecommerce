@extends('layouts.admin')

@section('title','Orders')

@section('content')
  <h1 class="text-2xl font-semibold text-gray-900">Orders</h1>

  <form class="mt-4 flex flex-wrap gap-2" method="GET">
    <input name="q" value="{{ request('q') }}" class="w-72 rounded-lg border-gray-300" placeholder="Search order/customer...">
    <select name="status" class="rounded-lg border-gray-300">
      <option value="">All</option>
      <option value="pending" @selected(request('status')==='pending')>Pending</option>
      <option value="paid" @selected(request('status')==='paid')>Paid</option>
      <option value="failed" @selected(request('status')==='failed')>Failed</option>
      <option value="canceled" @selected(request('status')==='canceled')>Canceled</option>
    </select>
    <button class="rounded-lg bg-gray-900 px-4 py-2 text-white">Filter</button>
  </form>

  <div class="mt-6 rounded-2xl border bg-white shadow-sm overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-gray-50">
        <tr>
          <th class="p-4 text-left">Order</th>
          <th class="p-4 text-left">Customer</th>
          <th class="p-4 text-left">Total</th>
          <th class="p-4 text-left">Status</th>
          <th class="p-4 text-right">View</th>
        </tr>
      </thead>
      <tbody>
        @forelse($orders as $o)
          <tr class="border-t">
            <td class="p-4 font-medium text-gray-900">{{ $o->order_number }}</td>
            <td class="p-4 text-gray-700">
              <div>{{ $o->customer_name }}</div>
              <div class="text-gray-500">{{ $o->customer_phone }}</div>
            </td>
            <td class="p-4">EGP {{ $o->total_egp }}</td>
            <td class="p-4">
              <span class="inline-flex rounded-full px-2 py-1 text-xs
                @if($o->status==='paid') bg-green-50 text-green-700
                @elseif($o->status==='pending') bg-yellow-50 text-yellow-700
                @else bg-gray-100 text-gray-700 @endif
              ">
                {{ ucfirst($o->status) }}
              </span>
            </td>
            <td class="p-4 text-right">
              <a href="{{ route('admin.orders.show',$o) }}" class="text-blue-600 hover:text-blue-800">Details</a>
            </td>
          </tr>
        @empty
          <tr><td class="p-6 text-gray-600" colspan="5">No orders yet.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-6">{{ $orders->links() }}</div>
@endsection
