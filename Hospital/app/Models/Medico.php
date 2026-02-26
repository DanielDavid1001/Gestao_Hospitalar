<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medico extends Model
{
    protected $fillable = [
        'user_id',
        'crm',
        'especialidade',
        'telefone',
        'endereco',
    ];

    /**
     * Relacionamento com User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com MedicoAvailability
     */
    public function availabilities()
    {
        return $this->hasMany(MedicoAvailability::class, 'medico_id');
    }
}
