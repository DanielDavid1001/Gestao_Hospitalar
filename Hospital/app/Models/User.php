<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relacionamento com Médico
     */
    public function medico()
    {
        return $this->hasOne(Medico::class);
    }

    /**
     * Relacionamento com Paciente
     */
    public function paciente()
    {
        return $this->hasOne(Paciente::class);
    }

    /**
     * Relacionamento com Admin
     */
    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Verifica se o usuário é admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Verifica se o usuário é médico
     */
    public function isMedico()
    {
        return $this->role === 'medico';
    }

    /**
     * Verifica se o usuário é paciente
     */
    public function isPaciente()
    {
        return $this->role === 'paciente';
    }
}
