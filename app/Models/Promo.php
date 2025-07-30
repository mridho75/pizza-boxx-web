<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Pastikan ini ada
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;   // Pastikan ini ada (untuk relasi ke Order)

class Promo extends Model
{
    use HasFactory; // Pastikan ini ada

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_order_amount',
        'start_date',
        'end_date',
        'usage_limit',
        'uses',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Relationship: A promo can be used by many orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}