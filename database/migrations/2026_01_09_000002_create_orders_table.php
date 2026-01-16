<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->enum('status', ['pending','paid','failed','canceled'])->default('pending');
            $table->unsignedInteger('total_cents');
            $table->string('currency', 3)->default('EGP');

            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone');

            $table->text('notes')->nullable();

            $table->timestamp('paid_at')->nullable();

            // Paymob tracking
            $table->string('gateway')->nullable();
            $table->string('gateway_order_id')->nullable();
            $table->string('gateway_transaction_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
