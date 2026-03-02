@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1 class="mb-0">Dashboard Admin</h1>
        </div>
        <div class="col-sm-6 text-end">
            <span class="text-muted">Bem-vindo, {{ auth()->user()->name }}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-12">
            <div class="small-box text-bg-primary">
                <div class="inner">
                    <h3>{{ $totalAdmins }}</h3>
                    <p>Total de ADM</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box text-bg-success">
                <div class="inner">
                    <h3>{{ $totalMedicos }}</h3>
                    <p>Total de Médicos</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-person-badge"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box text-bg-info">
                <div class="inner">
                    <h3>{{ $totalPacientes }}</h3>
                    <p>Total de Pacientes</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box text-bg-warning">
                <div class="inner">
                    <h3>{{ $totalConsultas }}</h3>
                    <p>Total de Consultas</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-clipboard2-pulse"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12">
            <div class="small-box text-bg-secondary">
                <div class="inner">
                    <h3>{{ $consultasPendentes }}</h3>
                    <p>Consultas Pendentes</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-7">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title mb-0">Status das Consultas</h3>
                </div>
                <div class="card-body">
                    <div class="position-relative" style="height: 280px;">
                        <canvas id="consultasStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title mb-0">Resumo de Status</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-dot text-warning"></i> Pendentes</span>
                            <span class="badge text-bg-warning">{{ $consultasPendentes }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-dot text-primary"></i> Confirmadas</span>
                            <span class="badge text-bg-primary">{{ $consultasConfirmadas }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-dot text-success"></i> Realizadas</span>
                            <span class="badge text-bg-success">{{ $consultasRealizadas }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span><i class="bi bi-dot text-danger"></i> Canceladas</span>
                            <span class="badge text-bg-danger">{{ $consultasCanceladas }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card card-outline card-primary">
                <div class="card-header position-relative d-flex align-items-center pe-2" style="min-height: 64px;">
                    <h3 class="mb-0 pe-5">Médicos Recentes</h3>
                    <a href="{{ route('medicos.index') }}" class="btn btn-sm btn-primary d-inline-flex align-items-center justify-content-center position-absolute top-50 translate-middle-y" style="right: 10px;">Ver Todos</a>
                </div>
                <div class="card-body p-0">
                    @if($medicosRecentes->isEmpty())
                        <p class="text-muted text-center py-4 mb-0">Nenhum médico cadastrado</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>CRM</th>
                                        <th>Especialidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($medicosRecentes as $medico)
                                        <tr>
                                            <td>{{ $medico->user->name }}</td>
                                            <td>{{ $medico->crm }}</td>
                                            <td>{{ $medico->especialidade }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-outline card-info">
                <div class="card-header position-relative d-flex align-items-center pe-2" style="min-height: 64px;">
                    <h3 class="mb-0 pe-6">Pacientes Recentes</h3>
                    <a href="{{ route('pacientes.index') }}" class="btn btn-sm btn-info text-white d-inline-flex align-items-center justify-content-center position-absolute top-50 translate-middle-y" style="right: 10px;">Ver Todos</a>
                </div>
                <div class="card-body p-0">
                    @if($pacientesRecentes->isEmpty())
                        <p class="text-muted text-center py-4 mb-0">Nenhum paciente cadastrado</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>CPF</th>
                                        <th>Data Cadastro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pacientesRecentes as $paciente)
                                        <tr>
                                            <td>{{ $paciente->user->name }}</td>
                                            <td>{{ $paciente->cpf }}</td>
                                            <td>{{ $paciente->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title mb-0">Ações Rápidas</h3>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-3">
                    <a href="{{ route('medicos.create') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-user-md me-1"></i> Novo Médico
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('pacientes.create') }}" class="btn btn-outline-info w-100">
                        <i class="fas fa-user-injured me-1"></i> Novo Paciente
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('medicos.index') }}" class="btn btn-outline-success w-100">
                        <i class="fas fa-list me-1"></i> Listar Médicos
                    </a>
                </div>
                <div class="col-md-3">
                    <a href="{{ route('pacientes.index') }}" class="btn btn-outline-warning w-100">
                        <i class="fas fa-list me-1"></i> Listar Pacientes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if (!window.Chart) {
        return;
    }

    const canvas = document.getElementById('consultasStatusChart');

    if (!canvas) {
        return;
    }

    const chartConfig = @json($consultasStatusChart);
    const css = getComputedStyle(document.documentElement);
    const themeColors = [
        css.getPropertyValue('--bs-warning').trim(),
        css.getPropertyValue('--bs-primary').trim(),
        css.getPropertyValue('--bs-success').trim(),
        css.getPropertyValue('--bs-danger').trim(),
    ];

    new window.Chart(canvas, {
        type: 'doughnut',
        data: {
            labels: chartConfig.labels,
            datasets: [{
                data: chartConfig.data,
                backgroundColor: themeColors,
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                },
            },
        },
    });
});
</script>
@endsection
