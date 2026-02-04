<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $fillable = [
        'user_id',
        'cargo',
        'setor',
        'telefone',
        'observacoes',
    ];

    /**
     * Relacionamento com User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
