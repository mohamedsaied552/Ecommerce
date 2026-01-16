<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublicInvoiceController extends Controller
{
    /**
     * Display public invoice page
     */
    public function show(string $token)
    {
        $invoice = Invoice::where('payment_link_token', $token)->firstOrFail();

        return view('public.invoice', compact('invoice'));
    }

    /**
     * Initiate payment - redirect to Paymob checkout
     */
    public function pay(Request $request, string $token)
    {
        $invoice = Invoice::where('payment_link_token', $token)->firstOrFail();

        // Check if invoice can be paid
        if (!$invoice->canBePaid()) {
            return redirect()->route('invoice.show', $token)
                ->with('error', 'This invoice cannot be paid. It may have already been paid or expired.');
        }

        // Prevent duplicate payment attempts (idempotency)
        if ($invoice->payments()->where('status', 'initiated')->exists()) {
            return redirect()->route('invoice.show', $token)
                ->with('error', 'A payment is already in progress for this invoice.');
        }

        try {
            $paymobService = new PaymobService();

            // Prepare billing data
            $billingData = [
                'amount_cents' => $invoice->amount_cents,
                'apartment' => 'NA',
                'email' => $invoice->customer_email ?? 'customer@example.com',
                'floor' => 'NA',
                'first_name' => $invoice->customer_name ?? 'Customer',
                'street' => 'NA',
                'building' => 'NA',
                'phone_number' => $invoice->customer_phone ?? '00000000000',
                'shipping_method' => 'NA',
                'postal_code' => 'NA',
                'city' => 'NA',
                'country' => 'EG',
                'last_name' => 'NA',
                'state' => 'NA',
            ];

            // Get checkout URL
            $checkoutUrl = $paymobService->getCheckoutUrlForInvoice(
                $invoice->amount_cents,
                $invoice->invoice_number,
                $billingData
            );

            // Create payment record
            $payment = $invoice->payments()->create([
                'gateway' => 'paymob',
                'status' => 'initiated',
            ]);

            Log::info('Payment initiated', [
                'invoice_id' => $invoice->id,
                'payment_id' => $payment->id,
                'checkout_url' => $checkoutUrl,
            ]);

            // Redirect to Paymob checkout
            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            Log::error('Payment initiation failed', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('invoice.show', $token)
                ->with('error', 'Failed to initiate payment. Please try again later.');
        }
    }

    /**
     * Payment success page (not authoritative - webhook is source of truth)
     */
    public function success()
    {
        return view('public.success');
    }

    /**
     * Payment cancel page
     */
    public function cancel()
    {
        return view('public.cancel');
    }
}
