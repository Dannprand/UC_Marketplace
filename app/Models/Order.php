<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_id',
        'order_number',
        'status', // 'pending', 'processing', 'shipped', 'delivered', 'cancelled'
        'total_amount',
        'shipping_address_id',
        'billing_address_id',
        'payment_method_id',
        'tracking_number',
        'shipping_provider',       
        'shipped_at',              
        'delivered_at',            
        'estimated_delivery', 
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'estimated_delivery' => 'datetime',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function items()
{
    return $this->hasMany(OrderItem::class); // atau OrderDetails, tergantung nama tabelnya
}

public function merchant()
{
    return $this->belongsTo(Merchant::class); // jika ingin tampilkan merchant
}


    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}