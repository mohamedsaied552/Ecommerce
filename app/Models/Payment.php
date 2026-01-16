<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'gateway',
        'gateway_order_id',
        'gateway_transaction_id',
        'status',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    /**
     * Relationship: Payment belongs to an invoice
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Check if payment is successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === 'paid';
    }
}
