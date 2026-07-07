<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = [
        'service_category_id', 'nombre', 'descripcion', 
        'precio', 'duracion_minutos', 'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
        'precio' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class)->withTimestamps();
    }

    public function reservations(): BelongsToMany
    {
        return $this->belongsToMany(Reservation::class)
                    ->withPivot('precio_historico', 'duracion_historica', 'observaciones')
                    ->withTimestamps();
    }
    public function scopeSearch($query, $term)
    {
        if ($term) {
            return $query->where('nombre', 'like', "%{$term}%")
                         ->orWhereHas('category', function ($q) use ($term) {
                             $q->where('nombre', 'like', "%{$term}%");
                         });
        }
        return $query;
    }
}