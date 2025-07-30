<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Pastikan ini ada
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Pastikan ini ada

class OrderItem extends Model
{
    use HasFactory; // Pastikan ini ada

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'unit_price',
        'options', // Pastikan kolom ini di-fillable
        'addons',  // Pastikan kolom ini di-fillable
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'options' => 'array', // Cast kolom 'options' ke array
        'addons' => 'array',  // Cast kolom 'addons' ke array
    ];

    /**
     * Relationship: An order item belongs to one order.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relationship: An order item belongs to one product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}