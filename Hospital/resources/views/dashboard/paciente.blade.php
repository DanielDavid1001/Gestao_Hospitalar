@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-4">Dashboard - Paciente</h2>
            <p class="text-muted">Bem-vindo, {{ auth()->user()->name }}! Aqui você pode acompanhar sua saúde.</p>
        </div>
    </div>

    <!-- Cartão com Dados do Paciente -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <span class="font-weight-bold">Meus Dados Pessoais</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nome:</strong> {{ $paciente->user->name }}</p>
                            <p><strong>Email:</strong> {{ $paciente->user->email }}</p>
                            <p><strong>CPF:</strong> {{ $paciente->cpf ?? 'Não informado' }}</p>
                            <p><strong>Data de Nascimento:</strong> {{ $paciente->data_nascimento ? $paciente->data_nascimento->format('d/m/Y') : 'Não informada' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Sexo:</strong> 
                                @if($paciente->sexo == 'M') Masculino
                                @elseif($paciente->sexo == 'F') Feminino
                                @elseif($paciente->sexo) {{ $paciente->sexo }}
                                @else - @endif
                            </p>
                            <p><strong>Tipo Sanguíneo:</strong> {{ $paciente->tipo_sanguineo ?? '-' }}</p>
                            <p><strong>Telefone:</strong> {{ $paciente->telefone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informações Médicas -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <span class="font-weight-bold">Informações Médicas</span>
                </div>
                <div class="card-body">
                    <p><strong>Alergias:</strong></p>
                    <p>{{ $paciente->alergias ?? 'Nenhuma alergia registrada' }}</p>
                    
                    <p class="mt-3"><strong>Endereço:</strong></p>
                    <p>{{ $paciente->endereco ?? 'Não informado' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Ações -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Consultas Agendadas</h5>
                    <h2 class="card-text">0</h2>
                    <small>(A implementar)</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Prontuário</h5>
                    <h2 class="card-text">-</h2>
                    <small>(A implementar)</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Ações Disponíveis -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <span class="font-weight-bold">Ações Disponíveis</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-outline-primary btn-block w-100">
                                <i class="fas fa-edit mr-2"></i> Editar Meus Dados
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-outline-success btn-block w-100" disabled>
                                <i class="fas fa-calendar mr-2"></i> Agendar Consulta
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-outline-info btn-block w-100" disabled>
                                <i class="fas fa-file-medical mr-2"></i> Ver Prontuário
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
                <strong>ℹ️ Informação:</strong> As funcionalidades de agendamento e prontuário eletrônico estão em desenvolvimento e estarão disponíveis em breve.
            </div>
        </div>
    </div>
</div>
@endsection
