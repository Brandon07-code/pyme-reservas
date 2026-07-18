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
    public function scopeBuscar($query, $term)
    {
        if (empty($term)) return $query;
        return $query->whereHas('client', function ($q) use ($term) {
            $q->where('primer_nombre', 'like', "%{$term}%")
              ->orWhere('primer_apellido', 'like', "%{$term}%");
        })->orWhereHas('employee.user', function ($q) use ($term) {
            $q->where('primer_nombre', 'like', "%{$term}%")
              ->orWhere('primer_apellido', 'like', "%{$term}%");
        });
    }

    public function scopeFiltrarEstado($query, $estado)
    {
        if (empty($estado)) return $query;
        return $query->where('estado', $estado);
    }

    public function scopeFechas($query, $fechaInicio, $fechaFin)
    {
        if (!empty($fechaInicio)) {
            $query->whereDate('fecha', '>=', $fechaInicio);
        }
        if (!empty($fechaFin)) {
            $query->whereDate('fecha', '<=', $fechaFin);
        }
        return $query;
    }

    // Scopes originales que ya usábamos para los KPIs
    public function scopeSearch($query, $term)
    {
        return $this->scopeBuscar($query, $term);
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