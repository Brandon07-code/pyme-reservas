<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'primer_nombre', 'segundo_nombre', 'primer_apellido', 
        'segundo_apellido', 'telefono', 'email', 'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function scopeSearch($query, $term)
    {
        if ($term) {
            return $query->where('primer_nombre', 'like', "%{$term}%")
                         ->orWhere('primer_apellido', 'like', "%{$term}%")
                         ->orWhere('telefono', 'like', "%{$term}%")
                         ->orWhere('email', 'like', "%{$term}%");
        }
        return $query;
    }
    // NUEVO: Un cliente puede tener muchos pedidos
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}