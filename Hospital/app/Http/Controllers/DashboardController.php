<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Medico;
use App\Models\Paciente;
use App\Models\Agendamento;
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
        $consultasPorStatus = Agendamento::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $consultasPendentes = (int) ($consultasPorStatus['pendente'] ?? 0);
        $consultasConfirmadas = (int) ($consultasPorStatus['confirmada'] ?? 0);
        $consultasRealizadas = (int) ($consultasPorStatus['realizada'] ?? 0);
        $consultasCanceladas = (int) ($consultasPorStatus['cancelada'] ?? 0);

        $data = [
            'totalAdmins' => User::where('role', 'admin')->count(),
            'totalMedicos' => Medico::count(),
            'totalPacientes' => Paciente::count(),
            'totalConsultas' => $consultasPendentes + $consultasConfirmadas + $consultasRealizadas + $consultasCanceladas,
            'consultasPendentes' => $consultasPendentes,
            'consultasConfirmadas' => $consultasConfirmadas,
            'consultasRealizadas' => $consultasRealizadas,
            'consultasCanceladas' => $consultasCanceladas,
            'consultasStatusChart' => [
                'labels' => ['Pendentes', 'Confirmadas', 'Realizadas', 'Canceladas'],
                'data' => [$consultasPendentes, $consultasConfirmadas, $consultasRealizadas, $consultasCanceladas],
            ],
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

        $consultasAgendadasQuery = Agendamento::where('medico_id', $medico->id)
            ->where('status', 'pendente');

        $totalPacientesAgendados = (clone $consultasAgendadasQuery)
            ->distinct('paciente_id')
            ->count('paciente_id');

        $totalConsultasAgendadas = (clone $consultasAgendadasQuery)
            ->count();

        $data = [
            'medico' => $medico,
            'totalPacientesAgendados' => $totalPacientesAgendados,
            'totalConsultasAgendadas' => $totalConsultasAgendadas,
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

        $agendamentosCount = Agendamento::where('paciente_id', $paciente->id)
            ->where('status', 'pendente')
            ->count();

        $data = [
            'paciente' => $paciente,
            'agendamentosCount' => $agendamentosCount,
        ];

        return view('dashboard.paciente', $data);
    }
}
