<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $fillable = [
        'user_id',
        'cpf',
        'data_nascimento',
        'telefone',
        'endereco',
        'sexo',
        'tipo_sanguineo',
        'alergias',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    /**
     * Relacionamento com User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
