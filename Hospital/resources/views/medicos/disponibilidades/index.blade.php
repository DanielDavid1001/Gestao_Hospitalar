@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header com Seletor de Médico (Admin) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="bi bi-calendar-check me-1"></i> Minhas Disponibilidades
                    </h3>
                </div>
                <div class="card-body">
                    @if(auth()->user()->isAdmin())
                        <div class="mb-3">
                            <form method="GET" action="{{ route('medico.disponibilidades.lista') }}" class="d-flex gap-2 align-items-center">
                                <label for="medico_id" class="form-label mb-0"><strong>Selecione o Médico:</strong></label>
                                <select id="medico_id" name="medico_id" class="form-select" onchange="this.form.submit()" style="max-width: 400px;">
                                    @foreach(($medicosAdmin ?? []) as $medicoItem)
                                        <option value="{{ $medicoItem->id }}" @selected(($medicoSelecionadoId ?? null) == $medicoItem->id)>
                                            {{ $medicoItem->nome ?? ($medicoItem->user->name ?? ('Médico #' . $medicoItem->id)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <hr class="my-2">
                    @endif
                    <p class="text-muted mb-0">Gerencie seus períodos de atendimento</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="bi bi-exclamation-circle" style="font-size: 1.25rem;"></i> Erro!</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Ações -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
            <a href="{{ route('medico.disponibilidades.periodo', auth()->user()->isAdmin() ? ['medico_id' => $medicoSelecionadoId] : []) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-calendar-range me-1"></i> Adicionar Período
            </a>
            <a href="{{ route('medico.disponibilidades.calendario', auth()->user()->isAdmin() ? ['medico_id' => $medicoSelecionadoId] : []) }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-calendar me-1"></i> Calendário
            </a>
        </div>
    </div>

    <!-- Tabela de Disponibilidades -->
    @if ($disponibilidades->count() > 0)
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="bi bi-list me-1"></i> Disponibilidades Cadastradas
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 15%;">Data</th>
                                <th class="text-center" style="width: 20%;">Horário</th>
                                <th class="text-center" style="width: 15%;">Período</th>
                                <th class="text-end" style="width: 15%;"><i class="bi bi-gear"></i> Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($disponibilidades as $disp)
                                <tr>
                                    <td class="text-center">
                                        <strong>{{ $disp->data->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $disp->data->locale('pt_BR')->dayName }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <i class="bi bi-clock text-info"></i> 
                                        {{ $disp->hora_inicio->format('H:i') }} - {{ $disp->hora_fim->format('H:i') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">
                                            {{ ucfirst($disp->periodo) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <form action="{{ route('medico.disponibilidades.destroy', $disp->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            @if(auth()->user()->isAdmin())
                                                <input type="hidden" name="medico_id" value="{{ $medicoSelecionadoId }}">
                                            @endif
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja excluir esta disponibilidade?')">
                                                <i class="bi bi-trash me-1"></i> Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                {{ $disponibilidades->links() }}
            </div>
        </div>
    @else
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="bi bi-info-circle" style="font-size: 1.25rem;"></i> 
            <strong>Nenhuma disponibilidade cadastrada</strong>
            <br>
            Você ainda não possui disponibilidades cadastradas.
            <a href="{{ route('medico.disponibilidades.periodo') }}" class="alert-link">Adicione um período agora!</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

</div>
@endsection

