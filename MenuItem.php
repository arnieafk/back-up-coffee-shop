<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MenuItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'itemID';

    protected $fillable = [
        'adminID',
        'itemName',
        'category',
        'price',
    ];

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class, 'itemID', 'itemID');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'itemID', 'itemID');
    }

    public function updatePrice(float $price): bool
    {
        $this->price = $price;

        return $this->save();
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'adminID', 'adminID');
    }
}
