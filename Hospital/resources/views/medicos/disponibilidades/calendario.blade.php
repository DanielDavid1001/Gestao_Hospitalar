@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-calendar"></i> Calendário de Disponibilidades</h2>
            <p class="text-muted">Visualize seus períodos disponíveis em formato de calendário</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('medico.disponibilidades.periodo') }}" class="btn btn-primary">
                <i class="bi bi-calendar-range"></i> Adicionar Período
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Próximos Períodos Disponíveis</h6>
                </div>
                <div class="card-body">
                    @if ($disponibilidades->count() > 0)
                        <div class="row">
                            @foreach ($disponibilidades as $data => $periodos)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="border rounded p-3">
                                        <h6 class="mb-2">
                                            <i class="bi bi-calendar-event"></i>
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $data)->format('d/m/Y') }}
                                        </h6>
                                        <small class="text-muted d-block mb-2">
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d', $data)->locale('pt_BR')->dayName }}
                                        </small>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach ($periodos as $disp)
                                                <span class="badge bg-info">
                                                    {{ $disp->hora_inicio->format('H:i') }}-{{ $disp->hora_fim->format('H:i') }} 
                                                    ({{ ucfirst($disp->periodo) }})
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> Nenhuma disponibilidade cadastrada para futuras datas.
                            <a href="{{ route('medico.disponibilidades.periodo') }}" class="alert-link">Adicione um período!</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Legenda de Períodos</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><span class="badge bg-info">Manhã</span> - Período matutino</p>
                        </div>
                        <div class="col-md-4">
                            <p><span class="badge bg-info">Tarde</span> - Período vespertino</p>
                        </div>
                        <div class="col-md-4">
                            <p><span class="badge bg-info">Noite</span> - Período noturno</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('medico.disponibilidades.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-list"></i> Ver Lista Completa
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>
@endsection
