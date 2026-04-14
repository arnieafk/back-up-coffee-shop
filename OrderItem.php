<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $primaryKey = 'orderItemID';

    protected $fillable = [
        'orderID',
        'itemID',
        'quantity',
        'price'
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'orderID');
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'itemID');
    }

    public function getSubtotal(): float
    {
        return (float) ($this->quantity * $this->price);
    }
}
