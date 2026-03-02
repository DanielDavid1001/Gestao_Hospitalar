@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-6">
            <h1 class="mb-0">Dashboard Médico</h1>
        </div>
        <div class="col-sm-6 text-end">
            <span class="text-muted">Bem-vindo, Dr(a). {{ auth()->user()->name }}</span>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="small-box text-bg-info">
                <div class="inner text-center">
                    <h3>{{ $totalConsultasAgendadas }}</h3>
                    <p>Consultas Pendentes</p>
                </div>
                <div class="small-box-icon">
                    <i class="bi bi-calendar2-week"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary mb-4">
        <div class="card-header">
            <h3 class="card-title mb-0">Meus Dados Profissionais</h3>
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

    <div class="card card-outline card-secondary">
        <div class="card-header">
            <h3 class="card-title mb-0">Ações Disponíveis</h3>
        </div>
        <div class="card-body">
            <div class="row justify-content-center g-2">
                <div class="col-md-4">
                    <a href="{{ route('medico.disponibilidades.index') }}" class="btn btn-primary w-100">
                        <i class="bi bi-calendar-check me-1"></i> Minhas Disponibilidades
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('agendamentos.meus') }}" class="btn btn-outline-info w-100">
                        <i class="bi bi-clipboard2-check me-1"></i> Minhas Consultas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
