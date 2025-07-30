<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // <--- TAMBAHKAN INI

class Location extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'address',
        'phone',
        'opening_hours',
        'delivery_area_geojson',
        'latitude',
        'longitude',
        'delivery_radius_km',
    ];

    /**
     * Relationship: A location can have many orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relationship: A location can have many products.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('is_available')->withTimestamps();
    }
}