@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-check-circle"></i> Confirmar Agendamento</h2>
            <p class="text-muted">Confira os dados cadastrados e confirme se estão corretos</p>
        </div>
        <div class="col-md-4 text-md-end mt-2 mt-md-0">
            <a href="{{ route('agendamentos.disponibilidades', $medico->id) }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erros:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card card-outline card-primary">
                <div class="card-body">
                    <form action="{{ route('agendamentos.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label"><strong>Profissional:</strong></label>
                            <p>{{ $medico->user->name }} - {{ $medico->especialidade }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Data e Hora:</strong></label>
                            <p>
                                {{ \Carbon\Carbon::parse($disponibilidade->data)->format('d/m/Y') }}
                                às {{ $disponibilidade->hora_inicio->format('H:i') }}
                            </p>
                        </div>

                        <hr>

                        <h5 class="mb-3">Dados do Paciente</h5>

                        <div class="row g-3 mb-2">
                            <div class="col-md-6">
                                <strong>Nome:</strong>
                                <div>{{ $paciente->user->name }}</div>
                            </div>
                            <div class="col-md-6">
                                <strong>Email:</strong>
                                <div>{{ $paciente->user->email }}</div>
                            </div>
                            <div class="col-md-6">
                                <strong>CPF:</strong>
                                <div>{{ $paciente->cpf ?? '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <strong>Data de Nascimento:</strong>
                                <div>{{ $paciente->data_nascimento ? $paciente->data_nascimento->format('d/m/Y') : '-' }}</div>
                            </div>
                            <div class="col-md-6">
                                <strong>Telefone:</strong>
                                <div>{{ $paciente->telefone ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="form-check mb-3 mt-3">
                            <input class="form-check-input @error('confirmar_dados') is-invalid @enderror" type="checkbox" id="confirmar_dados" name="confirmar_dados" value="1" required>
                            <label class="form-check-label" for="confirmar_dados">
                                Os dados do paciente estão corretos
                            </label>
                            @error('confirmar_dados')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="medico_id" value="{{ $medico->id }}">
                        <input type="hidden" id="data_hidden" name="data" value="{{ \Carbon\Carbon::parse($disponibilidade->data)->format('Y-m-d') }}">
                        <input type="hidden" id="hora_hidden" name="hora_inicio" value="{{ $disponibilidade->hora_inicio->format('H:i') }}">

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Confirmar Agendamento
                            </button>
                            <a href="{{ route('agendamentos.disponibilidades', $medico->id) }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-calendar-check"></i> Resumo do Agendamento</h6>
                </div>
                <div class="card-body">
                    <p><strong>Médico:</strong><br>{{ $medico->user->name }}</p>
                    <p><strong>Especialidade:</strong><br>{{ $medico->especialidade }}</p>
                    <p><strong>Data:</strong><br>{{ \Carbon\Carbon::parse($disponibilidade->data)->format('d/m/Y') }}</p>
                    <p class="mb-0"><strong>Horário:</strong><br>{{ $disponibilidade->hora_inicio->format('H:i') }} - {{ $disponibilidade->hora_fim->format('H:i') }}</p>
                </div>
            </div>

            <div class="card card-outline card-primary mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Atenção</h6>
                </div>
                <div class="card-body">
                    <small>Após confirmar, seu agendamento será registrado como <strong>pendente</strong> e precisará ser confirmado pelo consultório.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
