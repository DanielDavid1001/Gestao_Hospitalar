@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-4">Dashboard - Administrador</h2>
            <p class="text-muted">Bem-vindo, {{ auth()->user()->name }}! Aqui você pode gerenciar todo o sistema.</p>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total de Usuários</h5>
                    <h2 class="card-text">{{ $totalUsuarios }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total de Médicos</h5>
                    <h2 class="card-text">{{ $totalMedicos }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Total de Pacientes</h5>
                    <h2 class="card-text">{{ $totalPacientes }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Médicos Recentes -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold">Médicos Recentes</span>
                    <a href="{{ route('medicos.index') }}" class="btn btn-sm btn-primary">Ver Todos</a>
                </div>
                <div class="card-body">
                    @if($medicosRecentes->isEmpty())
                        <p class="text-muted text-center mb-0">Nenhum médico cadastrado</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
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

        <!-- Pacientes Recentes -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold">Pacientes Recentes</span>
                    <a href="{{ route('pacientes.index') }}" class="btn btn-sm btn-primary">Ver Todos</a>
                </div>
                <div class="card-body">
                    @if($pacientesRecentes->isEmpty())
                        <p class="text-muted text-center mb-0">Nenhum paciente cadastrado</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
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

    <!-- Ações Rápidas -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <span class="font-weight-bold">Ações Rápidas</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('medicos.create') }}" class="btn btn-outline-primary btn-block w-100">
                                <i class="fas fa-user-md mr-2"></i> Novo Médico
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pacientes.create') }}" class="btn btn-outline-info btn-block w-100">
                                <i class="fas fa-user-injured mr-2"></i> Novo Paciente
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('medicos.index') }}" class="btn btn-outline-success btn-block w-100">
                                <i class="fas fa-list mr-2"></i> Listar Médicos
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('pacientes.index') }}" class="btn btn-outline-warning btn-block w-100">
                                <i class="fas fa-list mr-2"></i> Listar Pacientes
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
