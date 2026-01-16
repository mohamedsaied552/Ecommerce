<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'gateway' => 'paymob',
            'gateway_order_id' => (string) fake()->numberBetween(10000, 99999),
            'gateway_transaction_id' => (string) fake()->numberBetween(10000, 99999),
            'status' => 'pending',
            'payload' => [],
        ];
    }
}
