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
        // Criar/atualizar usuário admin principal
        $user = User::firstOrCreate(
            ['email' => 'admin@hospital.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $user->update([
            'role' => 'admin',
        ]);

        // Criar/atualizar perfil admin
        Admin::updateOrCreate(
            ['user_id' => $user->id],
            [
            'user_id' => $user->id,
            'cargo' => 'Administrador Geral',
            'setor' => 'Gestão',
            'telefone' => '(11) 99999-9999',
            'observacoes' => 'Administrador principal do sistema',
            ]
        );
    }
}

