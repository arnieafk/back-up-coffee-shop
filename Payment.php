<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $primaryKey = 'paymentID';

    protected $fillable = [
        'orderID',
        'paymentType',
        'amount',
        'paymentDate'
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paymentDate' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'orderID');
    }

    public function processPayment(): bool
    {
        $this->paymentDate = now();

        $saved = $this->save();

        $order = $this->order()->first();
        if (! $order) {
            return $saved;
        }

        $order->updateStatus('Paid');

        return $saved;
    }
}
