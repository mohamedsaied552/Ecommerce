<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'amount_cents',
        'currency',
        'description',
        'customer_name',
        'customer_email',
        'customer_phone',
        'status',
        'payment_link_token',
        'paid_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    /**
     * Generate a unique payment link token
     */
    public static function generatePaymentLinkToken(): string
    {
        do {
            $token = Str::random(32);
        } while (self::where('payment_link_token', $token)->exists());

        return $token;
    }

    /**
     * Generate a unique invoice number
     */
    public static function generateInvoiceNumber(): string
    {
        do {
            $number = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        } while (self::where('invoice_number', $number)->exists());

        return $number;
    }

    /**
     * Get the amount in EGP (from piastres)
     */
    public function getAmountAttribute(): float
    {
        return $this->amount_cents / 100;
    }

    /**
     * Get the payment link URL
     */
    public function getPaymentLinkAttribute(): string
    {
        return url('/i/' . $this->payment_link_token);
    }

    /**
     * Check if invoice is paid
     */
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Check if invoice can be paid
     */
    public function canBePaid(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Relationship: Invoice has many payments
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the latest payment
     */
    public function latestPayment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }
}
