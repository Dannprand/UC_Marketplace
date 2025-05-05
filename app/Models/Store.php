<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'category_id',
        'name',
        'slug',
        'description',
        'logo',
        'banner',
        'is_published',
        'fundraising_target',
        'current_funds',
        'view_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'fundraising_target' => 'decimal:2',
        'current_funds' => 'decimal:2',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function analytics()
    {
        return $this->hasOne(StoreAnalytic::class);
    }
}