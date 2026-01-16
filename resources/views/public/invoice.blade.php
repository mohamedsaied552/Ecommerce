@extends('layouts.public')

@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')
<div class="max-w-2xl w-full">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-6 py-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Invoice</h1>
                    <p class="mt-1 text-primary-100">{{ $invoice->invoice_number }}</p>
                </div>
                <div class="text-right">
                    <x-badge variant="info" size="lg" class="bg-white text-primary-700">
                        {{ ucfirst($invoice->status) }}
                    </x-badge>
                </div>
            </div>
        </div>

        <div class="px-6 py-8">
            @if($invoice->isPaid())
                <!-- Paid State -->
                <div class="mb-6">
                    <x-alert type="success" dismissible>
                        <div class="flex items-center">
                            <x-icon name="check-circle" class="mr-3 h-6 w-6" />
                            <div>
                                <h3 class="font-semibold">Payment Received</h3>
                                <p class="mt-1">Thank you for your payment! Your invoice has been successfully paid.</p>
                            </div>
                        </div>
                    </x-alert>
                </div>

                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Next Steps</h2>
                    <p class="text-gray-600 mb-4">Please contact us via chat to receive your digital code. Our team will assist you shortly.</p>
                    <div class="flex items-center text-sm text-gray-500">
                        <x-icon name="clock" class="mr-2 h-4 w-4" />
                        Payment completed on: {{ $invoice->paid_at->format('M d, Y H:i') }}
                    </div>
                </div>

                <!-- Help Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
                    <div class="space-y-3">
                        <div class="flex items-center text-gray-600">
                            <x-icon name="mail" class="mr-3 h-5 w-5 text-gray-400" />
                            <span>Email: support@example.com</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <x-icon name="phone" class="mr-3 h-5 w-5 text-gray-400" />
                            <span>WhatsApp: +20 123 456 7890</span>
                        </div>
                    </div>
                </div>

            @elseif($invoice->status === 'expired')
                <!-- Expired State -->
                <div class="mb-6">
                    <x-alert type="warning" dismissible>
                        <div class="flex items-center">
                            <x-icon name="exclamation-triangle" class="mr-3 h-6 w-6" />
                            <div>
                                <h3 class="font-semibold">Invoice Expired</h3>
                                <p class="mt-1">This invoice has expired. Please contact us for assistance.</p>
                            </div>
                        </div>
                    </x-alert>
                </div>

                <!-- Help Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
                    <div class="space-y-3">
                        <div class="flex items-center text-gray-600">
                            <x-icon name="mail" class="mr-3 h-5 w-5 text-gray-400" />
                            <span>Email: support@example.com</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <x-icon name="phone" class="mr-3 h-5 w-5 text-gray-400" />
                            <span>WhatsApp: +20 123 456 7890</span>
                        </div>
                    </div>
                </div>

            @elseif($invoice->status === 'failed')
                <!-- Failed State -->
                <div class="mb-6">
                    <x-alert type="error" dismissible>
                        <div class="flex items-center">
                            <x-icon name="x-circle" class="mr-3 h-6 w-6" />
                            <div>
                                <h3 class="font-semibold">Payment Failed</h3>
                                <p class="mt-1">The payment for this invoice has failed. Please try again or contact us for assistance.</p>
                            </div>
                        </div>
                    </x-alert>
                </div>

                <!-- Help Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
                    <div class="space-y-3">
                        <div class="flex items-center text-gray-600">
                            <x-icon name="mail" class="mr-3 h-5 w-5 text-gray-400" />
                            <span>Email: support@example.com</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <x-icon name="phone" class="mr-3 h-5 w-5 text-gray-400" />
                            <span>WhatsApp: +20 123 456 7890</span>
                        </div>
                    </div>
                </div>

            @else
                <!-- Pending State - Show Payment Form -->
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-medium text-gray-500">Amount Due</span>
                            <span class="text-3xl font-bold text-gray-900">{{ number_format($invoice->amount, 2) }} {{ $invoice->currency }}</span>
                        </div>
                        @if($invoice->description)
                            <div class="border-t border-gray-200 pt-4">
                                <p class="text-sm text-gray-600">{{ $invoice->description }}</p>
                            </div>
                        @endif
                    </div>

                    @if($invoice->customer_name)
                        <div class="mb-4">
                            <p class="text-sm text-gray-500">Customer</p>
                            <p class="text-base font-medium text-gray-900">{{ $invoice->customer_name }}</p>
                        </div>
                    @endif

                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <x-icon name="information-circle" class="h-5 w-5 text-blue-400 mr-3" />
                            <div class="text-sm text-blue-700">
                                <p class="font-medium">Secure Payment</p>
                                <p class="mt-1">You will be redirected to a secure payment page to complete your transaction.</p>
                            </div>
                        </div>
                    </div>

        <form method="POST" action="{{ route('invoice.pay', $invoice->payment_link_token) }}">
    @csrf
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
        Pay Now
    </button>
                </form>
                    <p class="mt-4 text-center text-sm text-gray-500">
                        By clicking "Pay Now", you agree to proceed with the payment
                    </p>
                </div>

                <!-- Help Section -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
                    <div class="space-y-3">
                        <div class="flex items-center text-gray-600">
                            <x-icon name="mail" class="mr-3 h-5 w-5 text-gray-400" />
                            <span>Email: support@example.com</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <x-icon name="phone" class="mr-3 h-5 w-5 text-gray-400" />
                            <span>WhatsApp: +20 123 456 7890</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
