<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário admin
        $user = User::create([
            'name' => 'Administrador',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Criar perfil admin
        Admin::create([
            'user_id' => $user->id,
            'cargo' => 'Administrador Geral',
            'setor' => 'Gestão',
            'telefone' => '(11) 99999-9999',
            'observacoes' => 'Administrador principal do sistema',
        ]);
    }
}

