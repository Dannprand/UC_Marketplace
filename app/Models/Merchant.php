<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'merchant_name',
        'merchant_description',
        'merchant_pfp',
        'status', // 'pending', 'active', 'suspended'
        'rating',
        'total_sales',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->hasOne(Store::class);
    }

    public function paymentReceivingMethods()
    {
        return $this->hasMany(MerchantPaymentMethod::class);
    }
}