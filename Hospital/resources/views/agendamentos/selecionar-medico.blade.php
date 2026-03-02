@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-person-health"></i> Selecione um Profissional</h2>
            <p class="text-muted">
                @isset($especialidade)
                    Médicos com especialidade em {{ $especialidade }}
                @else
                    Médicos com disponibilidade
                @endisset
            </p>
        </div>
        <div class="col-md-4 text-md-end mt-2 mt-md-0">
            <a href="{{ route('agendamentos.escolher') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
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
                    <div class="card card-outline card-primary h-100">
                        <div class="card-body p-4 d-flex flex-column h-100">
                            <h5 class="mb-4 d-flex align-items-center gap-2">
                                <i class="bi bi-person-fill"></i>
                                <span>{{ $medico->user->name }}</span>
                            </h5>
                            <p class="mb-3">
                                <strong class="me-1">Especialidade:</strong><span>{{ $medico->especialidade }}</span>
                            </p>
                            <p class="mb-3">
                                <strong class="me-1">CRM:</strong><span>{{ $medico->crm }}</span>
                            </p>
                            @if($medico->telefone)
                                <p class="mb-3">
                                    <strong class="me-1">Telefone:</strong><span>{{ $medico->telefone }}</span>
                                </p>
                            @else
                                <p class="mb-3 text-muted">
                                    <strong class="me-1">Telefone:</strong><span>Não informado</span>
                                </p>
                            @endif
                            
                            <a href="{{ route('agendamentos.disponibilidades', $medico->id) }}" class="btn btn-primary w-100 mt-auto">
                                <i class="bi bi-calendar-check"></i> Ver Disponibilidades
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
