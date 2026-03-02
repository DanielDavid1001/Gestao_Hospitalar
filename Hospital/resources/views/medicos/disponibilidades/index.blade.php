@extends('layouts.app')

@section('content')
<div class="container py-4">
    @if(auth()->user()->isAdmin())
        <div class="row mb-3">
            <div class="col-md-6">
                <form method="GET" action="{{ route('medico.disponibilidades.lista') }}" class="d-flex gap-2 align-items-center">
                    <label for="medico_id" class="form-label mb-0">Médico:</label>
                    <select id="medico_id" name="medico_id" class="form-select" onchange="this.form.submit()">
                        @foreach(($medicosAdmin ?? []) as $medicoItem)
                            <option value="{{ $medicoItem->id }}" @selected(($medicoSelecionadoId ?? null) == $medicoItem->id)>
                                {{ $medicoItem->nome ?? ($medicoItem->user->name ?? ('Médico #' . $medicoItem->id)) }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-calendar-check"></i> Minhas Disponibilidades</h2>
            <p class="text-muted">Gerencie seus períodos de atendimento</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('medico.disponibilidades.periodo', auth()->user()->isAdmin() ? ['medico_id' => $medicoSelecionadoId] : []) }}" class="btn btn-primary">
                <i class="bi bi-calendar-range"></i> Adicionar Período
            </a>
            <a href="{{ route('medico.disponibilidades.calendario', auth()->user()->isAdmin() ? ['medico_id' => $medicoSelecionadoId] : []) }}" class="btn btn-outline-secondary">
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

    <div class="mb-3">
        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>

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
                            <th class="text-end">Ações</th>
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
                                <td class="text-end">
                                    <form action="{{ route('medico.disponibilidades.destroy', $disp->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        @if(auth()->user()->isAdmin())
                                            <input type="hidden" name="medico_id" value="{{ $medicoSelecionadoId }}">
                                        @endif
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja excluir esta disponibilidade?')">
                                            <i class="bi bi-trash"></i> Excluir
                                        </button>
                                    </form>
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

</div>
@endsection
