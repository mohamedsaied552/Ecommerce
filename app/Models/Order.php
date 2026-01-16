<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number','status','total_cents','currency',
        'customer_name','customer_email','customer_phone','notes',
        'paid_at','gateway','gateway_order_id','gateway_transaction_id'
    ];

    protected $casts = [
        'total_cents' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function getTotalEgpAttribute(): string
    {
        return number_format($this->total_cents / 100, 2);
    }
}
