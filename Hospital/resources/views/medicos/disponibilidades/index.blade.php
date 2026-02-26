@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-calendar-check"></i> Minhas Disponibilidades</h2>
            <p class="text-muted">Gerencie seus períodos de atendimento</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('medico.disponibilidades.periodo') }}" class="btn btn-primary">
                <i class="bi bi-calendar-range"></i> Adicionar Período
            </a>
            <a href="{{ route('medico.disponibilidades.calendario') }}" class="btn btn-outline-secondary">
                <i class="bi bi-calendar"></i> Calendário
            </a>
        </div>
    </div>

    <!-- Alertas -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erro!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabela de Disponibilidades -->
    @if ($disponibilidades->count() > 0)
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Data</th>
                            <th>Horário</th>
                            <th>Período</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($disponibilidades as $disp)
                            <tr>
                                <td>
                                    <strong>{{ $disp->data->format('d/m/Y') }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $disp->data->locale('pt_BR')->dayName }}
                                    </small>
                                </td>
                                <td>
                                    <i class="bi bi-clock"></i> 
                                    {{ $disp->hora_inicio->format('H:i') }} - {{ $disp->hora_fim->format('H:i') }}
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ ucfirst($disp->periodo) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Paginação -->
        <div class="mt-4">
            {{ $disponibilidades->links() }}
        </div>
    @else
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle"></i> Você ainda não possui disponibilidades cadastradas.
            <a href="{{ route('medico.disponibilidades.periodo') }}" class="alert-link">Adicione um período agora!</a>
        </div>
    @endif

    <div class="mt-4">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>
@endsection
