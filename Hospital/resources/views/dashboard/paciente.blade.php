@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1 class="mb-0">Dashboard Paciente</h1>
        </div>
        <div class="col-sm-6 text-end">
            <span class="text-muted">Bem-vindo, {{ auth()->user()->name }}</span>
        </div>
    </div>

    <div class="row mb-4 justify-content-center">
        <div class="col-md-12">
            <a href="{{ route('agendamentos.meus') }}" class="text-decoration-none">
                <div class="small-box text-bg-info">
                    <div class="inner text-center">
                        <h3>{{ $agendamentosCount ?? 0 }}</h3>
                        <p>Consultas Pendentes</p>
                    </div>
                    <div class="small-box-icon">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title mb-0">Meus Dados Pessoais</h3>
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

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title mb-0">Informações Médicas</h3>
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

    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title mb-0">Ações Disponíveis</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-center g-2">
                <div class="col-md-5">
                    <a href="{{ route('paciente.perfil.edit') }}" class="btn btn-outline-primary w-100">
                        <i class="fas fa-edit me-1"></i> Editar Meus Dados
                    </a>
                </div>
                <div class="col-md-5">
                    <a href="{{ route('agendamentos.escolher') }}" class="btn btn-outline-success w-100">
                        <i class="fas fa-calendar me-1"></i> Agendar Consulta
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
