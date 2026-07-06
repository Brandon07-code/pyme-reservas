<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'primer_nombre', 'segundo_nombre', 'primer_apellido', 
        'segundo_apellido', 'telefono', 'email', 'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}