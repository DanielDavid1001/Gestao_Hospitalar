@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-person-health"></i> Selecione um Profissional</h2>
            <p class="text-muted">
                @isset($especialidade)
                    Médicos com especialidade em {{ $especialidade }}
                @else
                    Médicos com disponibilidade
                @endisset
            </p>
        </div>
    </div>

    @if($medicos->isEmpty())
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle"></i> Nenhum médico com disponibilidade encontrado.
        </div>
    @else
        <div class="row">
            @foreach($medicos as $medico)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bi bi-person-fill"></i> {{ $medico->user->name }}
                            </h5>
                            <p class="mb-2">
                                <strong>Especialidade:</strong> {{ $medico->especialidade }}
                            </p>
                            <p class="mb-2">
                                <strong>CRM:</strong> {{ $medico->crm }}
                            </p>
                            @if($medico->telefone)
                                <p class="mb-3">
                                    <strong>Telefone:</strong> {{ $medico->telefone }}
                                </p>
                            @endif
                            
                            <a href="{{ route('agendamentos.disponibilidades', $medico->id) }}" class="btn btn-primary w-100">
                                <i class="bi bi-calendar-check"></i> Ver Disponibilidades
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('agendamentos.escolher') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>
@endsection
