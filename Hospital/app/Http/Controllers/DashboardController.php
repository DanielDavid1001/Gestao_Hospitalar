<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Exibe o dashboard baseado no role do usuário
     * Shows The Dashboard Based On User Role
     */
    public function index()
    {
        $user = auth()->user();

        // Redireciona para o dashboard específico de cada role
        if ($user->isAdmin()) {
            return $this->dashboardAdmin();
        } elseif ($user->isMedico()) {
            return $this->dashboardMedico();
        } elseif ($user->isPaciente()) {
            return $this->dashboardPaciente();
        }

        return redirect()->route('login');
    }

    /**
     * Dashboard do Administrador
     * Admin Dashboard
     */
    private function dashboardAdmin()
    {
        $data = [
            'totalUsuarios' => User::count(),
            'totalMedicos' => Medico::count(),
            'totalPacientes' => Paciente::count(),
            'medicosRecentes' => Medico::with('user')->latest()->take(5)->get(),
            'pacientesRecentes' => Paciente::with('user')->latest()->take(5)->get(),
        ];

        return view('dashboard.admin', $data);
    } 

    /**
     * Dashboard do Médico
     * Medical Dashboard
     */
    private function dashboardMedico()
    {
        $user = auth()->user();
        $medico = $user->medico;

        if (!$medico) {
            return view('dashboard.perfil-incompleto', [
                'role' => 'medico',
                'mensagem' => 'Perfil de médico não encontrado. Complete seu cadastro para continuar.',
                'acaoUrl' => url('/medicos/create'),
                'acaoLabel' => 'Completar cadastro de médico',
            ]);
        }

        $data = [
            'medico' => $medico,
            'totalPacientes' => Paciente::count(),
        ];

        return view('dashboard.medico', $data);
    }

    /**
     * Dashboard do Paciente
     * Patient Dashboard
     */
    private function dashboardPaciente()
    {
        $user = auth()->user();
        $paciente = $user->paciente;

        if (!$paciente) {
            return view('dashboard.perfil-incompleto', [
                'role' => 'paciente',
                'mensagem' => 'Perfil de paciente não encontrado. Complete seu cadastro para continuar.',
                'acaoUrl' => url('/pacientes/create'),
                'acaoLabel' => 'Completar cadastro de paciente',
            ]);
        }

        $data = [
            'paciente' => $paciente,
        ];

        return view('dashboard.paciente', $data);
    }
}
