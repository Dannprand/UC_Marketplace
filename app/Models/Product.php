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
    public function getDiscountedPriceAttribute()
{
    // Jika produk diskon dan persentase diskon valid
        if ($this->is_discounted && $this->discount_percentage > 0) {
            return $this->price * (1 - ($this->discount_percentage / 100));
        }
        
        // Jika tidak diskon, kembalikan harga normal
        return $this->price;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    public function updateRating()
    {
        $this->review_count = $this->reviews()->count();
        $this->average_rating = $this->reviews()->avg('rating') ?? 0;
        $this->save();
    }
}