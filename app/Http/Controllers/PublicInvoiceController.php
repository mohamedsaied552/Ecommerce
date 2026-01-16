<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Services\PaymobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PublicInvoiceController extends Controller
{
    /**
     * Show public invoice page
     */
    public function show(string $token)
    {
        $invoice = Invoice::where('payment_link_token', $token)->firstOrFail();
        return view('public.invoice', compact('invoice'));
    }

    /**
     * Redirect user to Paymob payment page
     */
    public function pay(Request $request, string $token)
    {
        $invoice = Invoice::where('payment_link_token', $token)->firstOrFail();

        // Prevent paying invalid invoice
        if (!$invoice->canBePaid()) {
            return redirect()
                ->route('invoice.show', $token)
                ->with('error', 'Invoice cannot be paid.');
        }

        // Prevent duplicate payments
        if ($invoice->payments()->where('status', 'initiated')->exists()) {
            return redirect()
                ->route('invoice.show', $token)
                ->with('error', 'Payment already in progress.');
        }

        try {
            $paymob = new PaymobService();

            // Required billing data for Paymob
            $billingData = [
                'first_name'     => $invoice->customer_name ?? 'Customer',
                'last_name'      => 'NA',
                'email'          => $invoice->customer_email ?? 'customer@example.com',
                'phone_number'   => $invoice->customer_phone ?? '01000000000',
                'apartment'      => 'NA',
                'floor'          => 'NA',
                'street'         => 'NA',
                'building'       => 'NA',
                'shipping_method'=> 'NA',
                'postal_code'    => '00000',
                'city'           => 'Cairo',
                'state'          => 'Cairo',
                'country'        => 'EG',
            ];

            // Generate Paymob checkout URL
            $checkoutUrl = $paymob->getCheckoutUrlForInvoice(
                $invoice->amount_cents,
                $invoice->invoice_number,
                $billingData
            );

            // Save initiated payment
            $payment = $invoice->payments()->create([
                'gateway' => 'paymob',
                'status'  => 'initiated',
            ]);

            Log::info('Paymob payment started', [
                'invoice_id' => $invoice->id,
                'payment_id' => $payment->id,
                'url'        => $checkoutUrl,
            ]);

            // IMPORTANT: redirect OUTSIDE Laravel
            return redirect()->away($checkoutUrl);

        } catch (\Throwable $e) {
            Log::error('Paymob error', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('invoice.show', $token)
                ->with('error', 'Payment failed. Try again.');
        }
    }

    /**
     * Success page (webhook is source of truth)
     */
    public function success()
    {
        return view('public.success');
    }

    /**
     * Cancel page
     */
    public function cancel()
    {
        return view('public.cancel');
    }
}
