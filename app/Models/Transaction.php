<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'merchant_id',
        'amount',
        'status', // 'pending', 'completed', 'failed', 'refunded'
        'payment_method',
        'transaction_reference',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}