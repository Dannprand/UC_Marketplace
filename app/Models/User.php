<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable //implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'phone_number',
        'email',
        'password',
        'pfp',
        'is_merchant',
        'merchant_password',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'merchant_password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_merchant' => 'boolean',
    ];

    // Relationships
    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }

    public function interests()
    {
        return $this->belongsToMany(Category::class, 'user_interests');
    }

    public function merchant()
    {
        return $this->hasOne(Merchant::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}