@extends('layouts.admin')

@section('title', 'Create Invoice')

@section('content')
<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Invoice</h1>
            <p class="mt-2 text-sm text-gray-600">Create a new invoice for your customer</p>
        </div>
        <a href="{{ route('admin.invoices.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <x-icon name="arrow-left" class="mr-2 h-4 w-4" />
            Back to Invoices
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <form method="POST" action="{{ route('admin.invoices.store') }}" class="bg-white shadow rounded-lg p-6">
                @csrf

                <div class="mb-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Invoice Details</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <x-input
                            name="amount"
                            label="Amount (EGP)"
                            type="number"
                            step="0.01"
                            min="0.01"
                            required
                            value="{{ old('amount') }}"
                        />
                        <x-input
                            name="currency"
                            label="Currency"
                            value="{{ old('currency', 'EGP') }}"
                            maxlength="3"
                        />
                    </div>
                    <x-textarea
                        name="description"
                        label="Description"
                        rows="3"
                        placeholder="Invoice description..."
                    >{{ old('description') }}</x-textarea>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Customer Information (Optional)</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <x-input
                            name="customer_name"
                            label="Customer Name"
                            value="{{ old('customer_name') }}"
                        />
                        <x-input
                            name="customer_email"
                            label="Customer Email"
                            type="email"
                            value="{{ old('customer_email') }}"
                        />
                        <x-input
                            name="customer_phone"
                            label="Customer Phone"
                            value="{{ old('customer_phone') }}"
                        />
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                        Create Invoice
                    </button>
                </div>
            </form>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Information</h3>
                <div class="space-y-3 text-sm text-gray-600">
                    <p>• Invoice number will be generated automatically</p>
                    <p>• A unique payment link will be created</p>
                    <p>• Customer will receive an email if provided</p>
                    <p>• Amount is stored in EGP (Egyptian Pounds)</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
