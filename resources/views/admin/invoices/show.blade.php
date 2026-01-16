@extends('layouts.admin')

@section('title', 'Invoice Details')

@section('content')
<div>
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Invoice Details</h1>
            <p class="mt-2 text-sm text-gray-600">Invoice #{{ $invoice->invoice_number }}</p>
        </div>
        <a href="{{ route('admin.invoices.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            <x-icon name="arrow-left" class="mr-2 h-4 w-4" />
            Back to Invoices
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Invoice Information -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Invoice Information</h2>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Invoice Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-mono">{{ $invoice->invoice_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <x-badge :variant="$invoice->status === 'paid' ? 'success' : ($invoice->status === 'pending' ? 'warning' : ($invoice->status === 'failed' ? 'danger' : 'default'))">
                                    {{ ucfirst($invoice->status) }}
                                </x-badge>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $invoice->created_at->format('M d, Y H:i:s') }}</dd>
                        </div>
                        @if($invoice->paid_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Paid At</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $invoice->paid_at->format('M d, Y H:i:s') }}</dd>
                        </div>
                        @endif
                        @if($invoice->description)
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $invoice->description }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Customer Information</h2>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $invoice->customer_name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $invoice->customer_email ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $invoice->customer_phone ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Payment Link -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Payment Link</h2>
                </div>
                <div class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <input
                            type="text"
                            id="paymentLink"
                            value="{{ $invoice->payment_link }}"
                            readonly
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-sm font-mono"
                        >
                        <button
                            onclick="copyPaymentLink()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            <x-icon name="clipboard" class="mr-2 h-4 w-4" />
                            Copy
                        </button>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Share this link with the customer to allow them to pay.</p>
                </div>
            </div>

            <!-- Payment History -->
            @if($invoice->payments->count() > 0)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Payment History</h2>
                </div>
                <div class="px-6 py-4">
                    <div class="space-y-4">
                        @foreach($invoice->payments as $payment)
                            <div class="border-l-4 border-primary-500 pl-4 py-2">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <x-badge :variant="$payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger')">
                                            {{ ucfirst($payment->status) }}
                                        </x-badge>
                                        <span class="text-sm text-gray-500">{{ $payment->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                </div>
                                @if($payment->gateway_order_id)
                                    <p class="text-sm text-gray-600"><span class="font-medium">Order ID:</span> {{ $payment->gateway_order_id }}</p>
                                @endif
                                @if($payment->gateway_transaction_id)
                                    <p class="text-sm text-gray-600"><span class="font-medium">Transaction ID:</span> {{ $payment->gateway_transaction_id }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Actions -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Actions</h2>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @if($invoice->customer_email)
                        <button
                            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'resend-email' }))"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            <x-icon name="mail" class="mr-2 h-4 w-4" />
                            Resend Email
                        </button>
                    @endif

                    @if(!$invoice->isPaid())
                        <button
                            onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'mark-expired' }))"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-yellow-300 text-sm font-medium rounded-md text-yellow-700 bg-white hover:bg-yellow-50"
                        >
                            <x-icon name="exclamation-triangle" class="mr-2 h-4 w-4" />
                            Mark as Expired
                        </button>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Timeline</h2>
                </div>
                <div class="px-6 py-4">
                    <div class="flow-root">
                        <ul class="-mb-8">
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <x-icon name="check" class="h-4 w-4 text-white" />
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">Invoice created</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                {{ $invoice->created_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @if($invoice->customer_email)
                            <li>
                                <div class="relative pb-8">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                <x-icon name="mail" class="h-4 w-4 text-white" />
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">Invoice email sent</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @if($invoice->isPaid())
                            <li>
                                <div class="relative">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                <x-icon name="check-circle" class="h-4 w-4 text-white" />
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">Payment received</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                {{ $invoice->paid_at->format('M d, Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @elseif($invoice->status === 'expired')
                            <li>
                                <div class="relative">
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                <x-icon name="x-circle" class="h-4 w-4 text-white" />
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <p class="text-sm text-gray-500">Invoice expired</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resend Email Modal -->
<x-modal name="resend-email" title="Resend Invoice Email">
    <p class="text-sm text-gray-500 mb-4">Are you sure you want to resend the invoice email to {{ $invoice->customer_email }}?</p>
    <form method="POST" action="{{ route('admin.invoices.resend-email', $invoice) }}">
        @csrf
        <div class="flex items-center justify-end gap-3">
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal'))" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </button>
            <button type="submit" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                Resend Email
            </button>
        </div>
    </form>
</x-modal>

<!-- Mark Expired Modal -->
<x-modal name="mark-expired" title="Mark Invoice as Expired">
    <p class="text-sm text-gray-500 mb-4">Are you sure you want to mark this invoice as expired? This action cannot be undone.</p>
    <form method="POST" action="{{ route('admin.invoices.mark-expired', $invoice) }}">
        @csrf
        <div class="flex items-center justify-end gap-3">
            <button type="button" onclick="window.dispatchEvent(new CustomEvent('close-modal'))" class="px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Cancel
            </button>
            <button type="submit" class="px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                Mark as Expired
            </button>
        </div>
    </form>
</x-modal>

<script>
function copyPaymentLink() {
    const link = document.getElementById('paymentLink');
    link.select();
    link.setSelectionRange(0, 99999);
    document.execCommand('copy');
    if (window.showToast) {
        window.showToast('Payment link copied to clipboard!', 'success');
    } else {
        alert('Payment link copied to clipboard!');
    }
}
</script>
@endsection
