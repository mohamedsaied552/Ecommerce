<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class WebhookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test webhook updates invoice status to paid
     */
    public function test_webhook_updates_invoice_to_paid(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_number' => 'INV-20240101-ABC123',
            'status' => 'pending',
        ]);

        $webhookData = [
            'obj' => [
                'order' => [
                    'id' => 12345,
                    'merchant_order_id' => $invoice->invoice_number,
                    'amount_cents' => $invoice->amount_cents,
                    'currency' => 'EGP',
                    'is_paid' => true,
                    'is_canceled' => false,
                    'is_refunded' => false,
                    'created_at' => now()->toIso8601String(),
                ],
            ],
        ];

        $response = $this->postJson('/webhooks/paymob', $webhookData);

        $response->assertStatus(200);
        $invoice->refresh();
        $this->assertEquals('paid', $invoice->status);
        $this->assertNotNull($invoice->paid_at);
    }

    /**
     * Test webhook creates payment record
     */
    public function test_webhook_creates_payment_record(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_number' => 'INV-20240101-ABC123',
        ]);

        $webhookData = [
            'obj' => [
                'order' => [
                    'id' => 12345,
                    'merchant_order_id' => $invoice->invoice_number,
                    'amount_cents' => $invoice->amount_cents,
                    'currency' => 'EGP',
                    'is_paid' => true,
                    'is_canceled' => false,
                    'is_refunded' => false,
                    'created_at' => now()->toIso8601String(),
                ],
            ],
        ];

        $this->postJson('/webhooks/paymob', $webhookData);

        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'gateway_order_id' => '12345',
            'status' => 'paid',
        ]);
    }

    /**
     * Test webhook is idempotent
     */
    public function test_webhook_is_idempotent(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_number' => 'INV-20240101-ABC123',
            'status' => 'paid',
        ]);

        $payment = Payment::factory()->create([
            'invoice_id' => $invoice->id,
            'gateway_order_id' => '12345',
            'status' => 'paid',
        ]);

        $webhookData = [
            'obj' => [
                'order' => [
                    'id' => 12345,
                    'merchant_order_id' => $invoice->invoice_number,
                    'amount_cents' => $invoice->amount_cents,
                    'currency' => 'EGP',
                    'is_paid' => true,
                    'is_canceled' => false,
                    'is_refunded' => false,
                    'created_at' => now()->toIso8601String(),
                ],
            ],
        ];

        $response = $this->postJson('/webhooks/paymob', $webhookData);

        $response->assertStatus(200);
        $this->assertEquals(1, Payment::where('gateway_order_id', '12345')->count());
    }

    /**
     * Test webhook handles missing invoice
     */
    public function test_webhook_handles_missing_invoice(): void
    {
        $webhookData = [
            'obj' => [
                'order' => [
                    'id' => 12345,
                    'merchant_order_id' => 'INV-NONEXISTENT',
                    'amount_cents' => 10000,
                    'currency' => 'EGP',
                    'is_paid' => true,
                ],
            ],
        ];

        $response = $this->postJson('/webhooks/paymob', $webhookData);

        $response->assertStatus(404);
    }

    /**
     * Test webhook handles failed payment
     */
    public function test_webhook_handles_failed_payment(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_number' => 'INV-20240101-ABC123',
            'status' => 'pending',
        ]);

        $webhookData = [
            'obj' => [
                'order' => [
                    'id' => 12345,
                    'merchant_order_id' => $invoice->invoice_number,
                    'amount_cents' => $invoice->amount_cents,
                    'currency' => 'EGP',
                    'is_paid' => false,
                    'is_canceled' => true,
                    'is_refunded' => false,
                ],
            ],
        ];

        $this->postJson('/webhooks/paymob', $webhookData);

        $invoice->refresh();
        $this->assertEquals('failed', $invoice->status);
    }
}
