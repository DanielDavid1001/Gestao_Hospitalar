@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-4">Dashboard - Médico</h2>
            <p class="text-muted">Bem-vindo, Dr(a). {{ auth()->user()->name }}!</p>
        </div>
    </div>

    <!-- Informações do Médico -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <span class="font-weight-bold">Meus Dados Profissionais</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nome:</strong> {{ $medico->user->name }}</p>
                            <p><strong>Email:</strong> {{ $medico->user->email }}</p>
                            <p><strong>CRM:</strong> {{ $medico->crm }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Especialidade:</strong> {{ $medico->especialidade }}</p>
                            <p><strong>Telefone:</strong> {{ $medico->telefone ?? '-' }}</p>
                            <p><strong>Endereço:</strong> {{ $medico->endereco ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Total de Pacientes</h5>
                    <h2 class="card-text">{{ $totalPacientes }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Consultas (A Implementar)</h5>
                    <h2 class="card-text">0</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Rápidas -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <span class="font-weight-bold">Ações Disponíveis</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('pacientes.index') }}" class="btn btn-outline-primary btn-block w-100">
                                <i class="fas fa-users mr-2"></i> Visualizar Pacientes
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-outline-success btn-block w-100" disabled>
                                <i class="fas fa-calendar mr-2"></i> Meus Agendamentos
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-outline-info btn-block w-100" disabled>
                                <i class="fas fa-file-medical mr-2"></i> Prontuários
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aviso de Funcionalidades em Desenvolvimento -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="alert alert-info" role="alert">
                <strong>ℹ️ Informação:</strong> As funcionalidades de agendamento e prontuários estão em desenvolvimento e estarão disponíveis em breve.
            </div>
        </div>
    </div>
</div>
@endsection
