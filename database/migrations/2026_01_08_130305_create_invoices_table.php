<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->bigInteger('amount_cents'); // EGP in piastres
            $table->string('currency')->default('EGP');
            $table->text('description')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->enum('status', ['draft', 'pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->string('payment_link_token')->unique();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('payment_link_token');
            $table->index('customer_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
