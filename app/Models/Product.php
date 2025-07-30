<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany; // <--- TAMBAHKAN INI

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'base_price',
        'image_path',
        'is_available',
    ];

    /**
     * Relationship: A product belongs to one category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship: A product can have many options (e.g., sizes, crust types).
     */
    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    /**
     * Relationship: A product can have many addons (e.g., extra toppings, sauces).
     */
    public function addons(): HasMany
    {
        return $this->hasMany(ProductAddon::class);
    }

    /**
     * Relationship: A product can be available in many locations.
     */
    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(Location::class)->withPivot('is_available')->withTimestamps();
    }
}