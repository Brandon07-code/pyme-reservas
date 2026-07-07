<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    protected $fillable = ['user_id', 'telefono', 'direccion', 'especialidad', 'estado'];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)->withTimestamps();
    }
    public function scopeSearch($query, $term)
    {
        if ($term) {
            return $query->whereHas('user', function ($q) use ($term) {
                $q->where('primer_nombre', 'like', "%{$term}%")
                  ->orWhere('primer_apellido', 'like', "%{$term}%");
            })->orWhere('especialidad', 'like', "%{$term}%");
        }
        return $query;
    }
}