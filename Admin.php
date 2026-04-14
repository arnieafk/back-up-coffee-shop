<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Admin extends Model
{
    protected $primaryKey = 'adminID';

    protected $fillable = [
        'name',
        'username',
        'password',
    ];

    public $timestamps = false;

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class, 'adminID', 'adminID');
    }

    public function inventories(): HasMany
    {
        return $this->hasMany(Inventory::class, 'adminID', 'adminID');
    }

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'adminID', 'adminID');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'adminID', 'adminID');
    }

    public function viewedCustomers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'orders', 'adminID', 'customerID', 'adminID', 'customerID')->distinct();
    }

    public function login(string $username, string $password): bool
    {
        if ($this->username !== $username) {
            return false;
        }

        return Hash::check($password, (string) $this->password);
    }

    public function manageMenu(): bool
    {
        return true;
    }

    public function manageInventory(): bool
    {
        return true;
    }

    public function manageStaff(): bool
    {
        return true;
    }

    public function viewReports(): bool
    {
        return true;
    }
}

