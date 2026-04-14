<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;

class Staff extends Model
{
    use HasFactory;

    protected $table = 'staff';

    protected $primaryKey = 'staffID';

    protected $fillable = [
        'adminID',
        'name',
        'username',
        'password',
        'role',
    ];

    public $timestamps = false;

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'adminID', 'adminID');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'staffID', 'staffID');
    }

    public function login(string $username, string $password): bool
    {
        if ($this->username !== $username) {
            return false;
        }

        return Hash::check($password, (string) $this->password);
    }

    public function createOrder(int $customerID): Order
    {
        return Order::create([
            'customerID' => $customerID,
            'staffID' => $this->staffID,
            'orderDate' => now(),
            'status' => 'Pending',
            'totalAmount' => 0,
        ]);
    }

    public function prepareOrder(Order $order): bool
    {
        if ($order->staffID !== $this->staffID) {
            return false;
        }

        return $order->updateStatus('Preparing');
    }

    public function updateOrderStatus(Order $order, string $status): bool
    {
        if ($order->staffID !== $this->staffID) {
            return false;
        }

        return $order->updateStatus($status);
    }

    public function processPayment(Payment $payment): bool
    {
        return $payment->processPayment();
    }
}
