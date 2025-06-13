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
    'status',
    'rating',
    'total_sales',  
    'merchant_password', 
    'bank_name',         
    'account_number',
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