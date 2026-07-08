<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'product_category_id', 'nombre', 'marca', 'genero', 'descripcion', 
        'precio', 'stock_actual', 'imagen_url', 'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'precio' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function scopeSearch($query, $term)
    {
        if ($term) {
            return $query->where('nombre', 'like', "%{$term}%")
                         ->orWhere('marca', 'like', "%{$term}%")
                         ->orWhereHas('category', function ($q) use ($term) {
                             $q->where('nombre', 'like', "%{$term}%");
                         });
        }
        return $query;
    }
}