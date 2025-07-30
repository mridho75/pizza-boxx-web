<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Pastikan ini ada
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Untuk relasi jika ada products yang menggunakan category ini

class Category extends Model
{
    use HasFactory; // Pastikan ini ada

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', // Izinkan kolom 'name' untuk diisi melalui mass assignment
    ];

    /**
     * Relationship: A category can have many products.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}