<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reservation extends Model
{
    protected $fillable = [
        'client_id', 'employee_id', 'fecha', 
        'hora_inicio', 'hora_fin', 'estado', 'total'
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'fecha' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)
                    ->withPivot('precio_historico', 'duracion_historica', 'observaciones')
                    ->withTimestamps();
    }
      public function scopeSearch($query, $term)
    {
        if ($term) {
            return $query->whereHas('client', function ($q) use ($term) {
                $q->where('primer_nombre', 'like', "%{$term}%")
                  ->orWhere('primer_apellido', 'like', "%{$term}%");
            })->orWhereHas('employee.user', function ($q) use ($term) {
                $q->where('primer_nombre', 'like', "%{$term}%")
                  ->orWhere('primer_apellido', 'like', "%{$term}%");
            });
        }
        return $query;
    }
    public function scopeCompletadas($query)
    {
        return $query->where('estado', 'completada');
    }

    public function scopeDelMes($query, $mes, $anio)
    {
        return $query->whereMonth('fecha', $mes)
                     ->whereYear('fecha', $anio);
    }

    public function scopeActivas($query)
    {
        return $query->whereIn('estado', ['pendiente', 'confirmada']);
    }
}