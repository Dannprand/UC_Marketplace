<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'total_views',
        'unique_visitors',
        'conversion_rate',
        'total_sales',
        'total_revenue',
        'average_order_value',
    ];

    protected $casts = [
        'conversion_rate' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'average_order_value' => 'decimal:2',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}