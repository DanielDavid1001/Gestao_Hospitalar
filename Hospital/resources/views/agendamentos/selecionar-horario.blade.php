@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-calendar-event"></i> Selecione um Horário</h2>
            <p class="text-muted">Dr(a). {{ $medico->user->name }} - {{ $medico->especialidade }}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            @if($disponibilidades->isEmpty())
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> Nenhuma disponibilidade encontrada.
                </div>
            @else
                @foreach($disponibilidades as $data => $horarios)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                {{ \Carbon\Carbon::parse($data)->format('d/m/Y') }}
                                <small class="text-muted">
                                    ({{ \Carbon\Carbon::parse($data)->locale('pt_BR')->dayName }})
                                </small>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($horarios as $disponibilidade)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <a href="{{ route('agendamentos.confirmar', [$medico->id, $data, $disponibilidade->hora_inicio->format('H:i')]) }}" 
                                           class="btn btn-outline-primary w-100">
                                            <i class="bi bi-clock"></i>
                                            {{ $disponibilidade->hora_inicio->format('H:i') }} - {{ $disponibilidade->hora_fim->format('H:i') }}
                                            <br>
                                            <small>{{ ucfirst($disponibilidade->periodo) }}</small>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Informações do Médico</h6>
                </div>
                <div class="card-body">
                    <p><strong>Nome:</strong> {{ $medico->user->name }}</p>
                    <p><strong>Especialidade:</strong> {{ $medico->especialidade }}</p>
                    <p><strong>CRM:</strong> {{ $medico->crm }}</p>
                    @if($medico->telefone)
                        <p><strong>Telefone:</strong> {{ $medico->telefone }}</p>
                    @endif
                    @if($medico->endereco)
                        <p class="mb-0"><strong>Endereço:</strong> {{ $medico->endereco }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="javascript:history.back()" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>
@endsection
