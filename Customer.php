<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $primaryKey = 'customerID';

    protected $fillable = [
        'customerID',
        'name',
        'phone',
    ];

    public $timestamps = false;

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customerID', 'customerID');
    }

    public function viewedByAdmins(): BelongsToMany
    {
        return $this->belongsToMany(Admin::class, 'orders', 'customerID', 'adminID', 'customerID', 'adminID')->distinct();
    }

    public function placeOrder(?int $staffID = null): Order
    {
        return Order::create([
            'customerID' => $this->customerID,
            'staffID' => $staffID,
            'orderDate' => now(),
            'status' => 'Pending',
            'totalAmount' => 0,
        ]);
    }

    public function makePayment(Order $order, string $paymentType, float $amount): Payment
    {
        $payment = Payment::create([
            'orderID' => $order->orderID,
            'paymentType' => $paymentType,
            'amount' => $amount,
            'paymentDate' => null,
        ]);

        $payment->processPayment();

        return $payment;
    }
}

