<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $primaryKey = 'inventoryID';

    protected $fillable = [
        'adminID',
        'itemID',
        'stockQuantity',
        'reorderLevel',
        'lastUpdated',
    ];

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'itemID', 'itemID');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'adminID', 'adminID');
    }

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'stockQuantity' => 'integer',
            'reorderLevel' => 'integer',
            'lastUpdated' => 'datetime',
        ];
    }

    public function updateStock(int $quantityChange): bool
    {
        $newQuantity = $this->stockQuantity + $quantityChange;

        if ($newQuantity < 0) {
            return false;
        }

        $this->stockQuantity = $newQuantity;
        $this->lastUpdated = now();

        return $this->save();
    }

    public function checkLowStock(): bool
    {
        return $this->stockQuantity <= $this->reorderLevel;
    }
}
