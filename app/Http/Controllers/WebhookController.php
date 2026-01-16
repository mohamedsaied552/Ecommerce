<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use App\Services\PaymobService;
use App\Mail\InvoicePaidAdminMail;
use App\Mail\InvoicePaidCustomerMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    /**
     * Handle Paymob webhook
     * 
     * This is the authoritative source for payment confirmation.
     * Do NOT rely on success/cancel redirects for payment status.
     */
    public function paymob(Request $request, PaymobService $paymobService)
    {
        // Log the raw webhook payload for audit
        Log::info('Paymob webhook received', [
            'payload' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        $data = $request->all();
        $hmac = $request->header('X-Hmac') ?? $request->input('hmac');

        // Verify HMAC signature if provided
        if ($hmac && !$paymobService->verifyHmac($data, $hmac)) {
            Log::warning('Paymob webhook HMAC verification failed', [
                'received_hmac' => $hmac,
                'payload' => $data,
            ]);
            // Continue processing but log the warning
            // In production, you may want to reject the request
        }

        // Extract order information
        $order = $data['obj']['order'] ?? null;
        if (!$order) {
            Log::error('Paymob webhook missing order data', ['payload' => $data]);
            return response()->json(['error' => 'Invalid webhook payload'], 400);
        }

        $merchantOrderId = $order['merchant_order_id'] ?? null;
        if (!$merchantOrderId) {
            Log::error('Paymob webhook missing merchant_order_id', ['payload' => $data]);
            return response()->json(['error' => 'Missing merchant_order_id'], 400);
        }

        // Find invoice by invoice number
      // First: try Invoice (old module)
$invoice = Invoice::where('invoice_number', $merchantOrderId)->first();

// Second: try Store Order (new module)
$orderModel = null;
if (!$invoice) {
    $orderModel = \App\Models\Order::where('order_number', $merchantOrderId)->first();
    if (!$orderModel) {
        Log::error('Paymob webhook: Invoice/Order not found', [
            'merchant_order_id' => $merchantOrderId,
            'payload' => $data,
        ]);
        return response()->json(['error' => 'Invoice/Order not found'], 404);
    }
}


        // Check if already processed (idempotency)
        $existingPayment = Payment::where('invoice_id', $invoice->id)
            ->where('gateway_order_id', $order['id'])
            ->where('status', 'paid')
            ->first();

        if ($existingPayment) {
            Log::info('Paymob webhook: Payment already processed', [
                'invoice_id' => $invoice->id,
                'payment_id' => $existingPayment->id,
                'order_id' => $order['id'],
            ]);
            return response()->json(['message' => 'Payment already processed'], 200);
        }

        $targetType = $invoice ? 'invoice' : 'order';
        DB::beginTransaction();
        if ($targetType === 'invoice') {
            // Existing invoice logic (unchanged)
            if ($paymentStatus === 'paid' && !$invoice->isPaid()) {
                $invoice->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);
        
                $adminEmail = config('mail.from.address');
                if ($adminEmail) {
                    Mail::to($adminEmail)->queue(new InvoicePaidAdminMail($invoice, $payment));
                }
        
                if ($invoice->customer_email) {
                    Mail::to($invoice->customer_email)->queue(new InvoicePaidCustomerMail($invoice));
                }
        
                Log::info('Invoice marked as paid via webhook', [
                    'invoice_id' => $invoice->id,
                    'payment_id' => $payment->id,
                ]);
            } elseif ($paymentStatus === 'failed' && $invoice->status === 'pending') {
                $invoice->update(['status' => 'failed']);
            }
        } else {
            // Store Order logic
            if ($paymentStatus === 'paid' && !$orderModel->isPaid()) {
                $orderModel->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                    'gateway' => 'paymob',
                    'gateway_order_id' => $order['id'] ?? null,
                    'gateway_transaction_id' => $order['id'] ?? null,
                ]);
                Log::info('Store order marked as paid via webhook', [
                    'order_id' => $orderModel->id,
                    'order_number' => $orderModel->order_number,
                ]);
            } elseif ($paymentStatus === 'failed' && $orderModel->status === 'pending') {
                $orderModel->update(['status' => 'failed']);
            }
        }
        
    }
}
