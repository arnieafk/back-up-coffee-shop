<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'orderID';

    protected $fillable = [
        'adminID',
        'customerID',
        'staffID',
        'orderDate',
        'status',
        'totalAmount',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'orderDate' => 'datetime',
            'totalAmount' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customerID', 'customerID');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'adminID', 'adminID');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staffID', 'staffID');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'orderID', 'orderID');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'orderID', 'orderID');
    }

    public function calculateTotal(): float
    {
        $total = (float) $this->orderItems()
            ->get()
            ->sum(fn (OrderItem $item) => $item->getSubtotal());

        $this->totalAmount = $total;
        $this->save();

        return $total;
    }

    public function updateStatus(string $status): bool
    {
        $this->status = $status;

        return $this->save();
    }
}
