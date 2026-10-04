<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',
        'cost_price',
        'selling_price',
        'image',
        'stock_quantity',
        'add_ons',
        'ingredients',
    ];

    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'stock_quantity' => 'integer',
            'add_ons' => 'array',
            'ingredients' => 'array',
        ];
    }

    public function toCatalogArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cat' => $this->category,
            'cost' => (float) $this->cost_price,
            'sellingPrice' => (float) $this->selling_price,
            'price' => (float) $this->selling_price,
            'img' => $this->image,
            'stock' => $this->stock_quantity,
            'addOns' => $this->add_ons ?? [],
            'ingredients' => $this->ingredients ?? [],
        ];
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
