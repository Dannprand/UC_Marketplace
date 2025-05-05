<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'type',
        'provider',
        'account_name',
        'account_number',
        'instructions',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}