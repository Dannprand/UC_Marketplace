<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type', // e.g., 'bank_transfer', 'e-wallet'
        'provider', // e.g.,  'BCA', 'Gopay','UC Coin'
        'account_name',
        'account_number',
        'expiry_date',
        'is_default',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}