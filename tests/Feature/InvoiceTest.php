<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin can create an invoice
     */
    public function test_admin_can_create_invoice(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/admin/invoices', [
            'amount' => 100.50,
            'currency' => 'EGP',
            'description' => 'Test invoice',
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '01234567890',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('invoices', [
            'amount_cents' => 10050,
            'currency' => 'EGP',
            'customer_email' => 'john@example.com',
        ]);
    }

    /**
     * Test invoice number is generated automatically
     */
    public function test_invoice_number_is_generated(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/admin/invoices', [
            'amount' => 50.00,
        ]);

        $invoice = Invoice::latest()->first();
        $this->assertNotNull($invoice->invoice_number);
        $this->assertStringStartsWith('INV-', $invoice->invoice_number);
    }

    /**
     * Test payment link token is generated
     */
    public function test_payment_link_token_is_generated(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/admin/invoices', [
            'amount' => 50.00,
        ]);

        $invoice = Invoice::latest()->first();
        $this->assertNotNull($invoice->payment_link_token);
        $this->assertEquals(32, strlen($invoice->payment_link_token));
    }

    /**
     * Test invoice validation
     */
    public function test_invoice_requires_amount(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->post('/admin/invoices', [
            'amount' => '',
        ]);

        $response->assertSessionHasErrors('amount');
    }

    /**
     * Test invoice amount is stored in cents
     */
    public function test_invoice_amount_stored_in_cents(): void
    {
        $user = User::factory()->create();
        
        $this->actingAs($user)->post('/admin/invoices', [
            'amount' => 99.99,
        ]);

        $invoice = Invoice::latest()->first();
        $this->assertEquals(9999, $invoice->amount_cents);
    }

    /**
     * Test public invoice page is accessible
     */
    public function test_public_invoice_page_is_accessible(): void
    {
        $invoice = Invoice::factory()->create([
            'payment_link_token' => 'test-token-123',
        ]);

        $response = $this->get('/i/test-token-123');

        $response->assertStatus(200);
        $response->assertSee($invoice->invoice_number);
    }
}
