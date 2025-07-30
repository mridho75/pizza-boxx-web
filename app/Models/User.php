<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <--- TAMBAHKAN INI
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'location_id', // <--- TAMBAHKAN INI
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Filament User Access Control
     * Only users with 'admin' or 'employee' role can access Filament panels.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['admin', 'employee']);
    }

    /**
     * Relationship: A user (employee) belongs to one location.
     */
    public function location(): BelongsTo // <--- TAMBAHKAN FUNGSI INI
    {
        return $this->belongsTo(Location::class);
    }

    /**
     * Relationship: An admin/employee can have many orders as a delivery employee.
     */
    public function ordersAsDeliveryEmployee(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_employee_id');
    }

    /**
     * Relationship: A user (customer) can have many orders.
     */
    public function ordersAsCustomer(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }


}
