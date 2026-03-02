<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agendamento extends Model
{
    use HasFactory;

    protected $table = 'agendamentos';

    protected $fillable = [
        'paciente_id',
        'medico_id',
        'data_hora',
        'nome_paciente',
        'data_nascimento',
        'nome_responsavel',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'data_hora' => 'datetime',
        'data_nascimento' => 'date',
    ];

    /**
     * Relacionamento com Paciente
     */
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    /**
     * Relacionamento com Medico
     */
    public function medico()
    {
        return $this->belongsTo(Medico::class, 'medico_id');
    }

    /**
     * Scope para agendamentos activos
     */
    public function scopeAtivos($query)
    {
        return $query->whereIn('status', ['pendente', 'confirmada']);
    }

    /**
     * Scope para agendamentos de um médico em uma data
     */
    public function scopeByMedicoData($query, $medico_id, $data)
    {
        return $query->where('medico_id', $medico_id)
            ->whereDate('data_hora', $data);
    }

    /**
     * Scope para agendamentos futuros
     */
    public function scopeFuturos($query)
    {
        return $query->where('data_hora', '>=', now());
    }
}
