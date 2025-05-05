<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'quantity',
        'sold_amount',
        'images',
        'is_discounted',
        'discount_percentage',
        'is_featured',
        'rating',
        'review_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_discounted' => 'boolean',
        'discount_percentage' => 'decimal:2',
        'is_featured' => 'boolean',
        'rating' => 'decimal:1',
        'images' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}