@extends('layouts.public')

@section('title', 'Payment Success')

@section('content')
<div class="max-w-md w-full">
    <div class="bg-white shadow-xl rounded-lg overflow-hidden text-center">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-12">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-white bg-opacity-20 mb-4">
                <x-icon name="check-circle" class="h-10 w-10 text-white" />
            </div>
            <h1 class="text-3xl font-bold text-white">Payment Successful!</h1>
        </div>

        <div class="px-6 py-8">
            <p class="text-gray-600 mb-6">Your payment has been processed successfully.</p>
            
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 text-left">
                <div class="flex">
                    <x-icon name="information-circle" class="h-5 w-5 text-blue-400 mr-3 flex-shrink-0" />
                    <div class="text-sm text-blue-700">
                        <p class="font-medium">Payment Confirmation</p>
                        <p class="mt-1">Payment confirmation will be sent via email. The webhook is the authoritative source for payment status.</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Next Steps</h2>
                <p class="text-gray-600">Please contact us via chat to receive your digital code. Our team will assist you shortly.</p>
            </div>

            <!-- Help Section -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Need Help?</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-center text-gray-600">
                        <x-icon name="mail" class="mr-3 h-5 w-5 text-gray-400" />
                        <span>Email: support@example.com</span>
                    </div>
                    <div class="flex items-center justify-center text-gray-600">
                        <x-icon name="phone" class="mr-3 h-5 w-5 text-gray-400" />
                        <span>WhatsApp: +20 123 456 7890</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
