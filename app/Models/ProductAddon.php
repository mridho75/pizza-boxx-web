<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Pastikan ini ada
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Pastikan ini ada

class ProductAddon extends Model
{
    use HasFactory; // Pastikan ini ada

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'is_available',
    ];

    /**
     * Relationship: A product addon belongs to one product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}