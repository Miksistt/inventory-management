<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'description',
        'category_id',
        'unit_id',
        'min_stock',
        'stock_quantity',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function isLowStock(): bool
    {
        if ($this->min_stock === null) {
            return false;
        }
        return $this->stock_quantity <= $this->min_stock && $this->stock_quantity > 0;
    }

    public function isCriticalStock(): bool
    {
        return $this->stock_quantity === 0;
    }

    public function getStockStatusClass(): string
    {
        if ($this->isCriticalStock()) {
            return 'table-danger';
        }
        if ($this->isLowStock()) {
            return 'table-warning';
        }
        return 'table-success';
    }

    public function getStockStatusText(): string
    {
        if ($this->isCriticalStock()) {
            return 'Критический';
        }
        if ($this->isLowStock()) {
            return 'Ниже минимума';
        }
        return 'В норме';
    }
}
