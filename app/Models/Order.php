<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    protected $fillable = [
        'client_id', 'estado', 'total'
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // Relación con el cliente que hizo el pedido
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Relación N:M con los productos (La canasta)
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
                    ->withPivot('cantidad', 'precio_historico')
                    ->withTimestamps();
    }
}