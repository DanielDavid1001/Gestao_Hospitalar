<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicoAvailability extends Model
{
    use HasFactory;

    protected $table = 'medico_availabilities';

    protected $fillable = [
        'medico_id',
        'data',
        'hora_inicio',
        'hora_fim',
        'periodo',
    ];

    protected $casts = [
        'data' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fim' => 'datetime:H:i',
    ];

    /**
     * Relacionamento com Medico
     */
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    /**
     * Scope para obter disponibilidades de um médico em uma data específica
     */
    public function scopeByMedicoAndData($query, $medico_id, $data)
    {
        return $query->where('medico_id', $medico_id)
            ->whereDate('data', $data);
    }

    /**
     * Scope para obter disponibilidades de um médico em um período
     */
    public function scopeByPeriodo($query, $periodo)
    {
        return $query->where('periodo', $periodo);
    }

    /**
     * Scope para obter disponibilidades futuras
     */
    public function scopeFuturos($query)
    {
        return $query->where('data', '>=', now()->toDateString());
    }
};
