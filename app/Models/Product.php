<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','slug','price_cents','currency','description',
        'status','stock','image_path'
    ];

    protected $casts = [
        'price_cents' => 'integer',
        'stock' => 'integer',
    ];

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function getPriceEgpAttribute(): string
    {
        return number_format($this->price_cents / 100, 2);
    }
}
