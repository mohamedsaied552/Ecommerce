<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'amount_cents' => fake()->numberBetween(1000, 100000), // 10 EGP to 1000 EGP
            'currency' => 'EGP',
            'description' => fake()->sentence(),
            'customer_name' => fake()->name(),
            'customer_email' => fake()->email(),
            'customer_phone' => fake()->phoneNumber(),
            'status' => 'pending',
            'payment_link_token' => Invoice::generatePaymentLinkToken(),
        ];
    }
}
