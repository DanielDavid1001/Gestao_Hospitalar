@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="bi bi-calendar-check"></i> Meus Agendamentos</h2>
            <p class="text-muted">Listagem de todas as suas consultas</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('agendamentos.escolher') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Agendar Nova Consulta
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($agendamentos->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Data e Hora</th>
                        <th>Profissional</th>
                        <th>Especialidade</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agendamentos as $agendamento)
                        <tr>
                            <td>
                                <strong>{{ $agendamento->data_hora->format('d/m/Y') }}</strong>
                                <br>
                                <small class="text-muted">{{ $agendamento->data_hora->format('H:i') }}</small>
                            </td>
                            <td>
                                {{ $agendamento->medico->user->name }}
                                <br>
                                <small class="text-muted">CRM: {{ $agendamento->medico->crm }}</small>
                            </td>
                            <td>{{ $agendamento->medico->especialidade }}</td>
                            <td>
                                @switch($agendamento->status)
                                    @case('pendente')
                                        <span class="badge bg-warning text-dark">
                                            <i class="bi bi-clock"></i> Pendente
                                        </span>
                                        @break
                                    @case('confirmada')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle"></i> Confirmada
                                        </span>
                                        @break
                                    @case('realizada')
                                        <span class="badge bg-info">
                                            <i class="bi bi-check-all"></i> Realizada
                                        </span>
                                        @break
                                    @case('cancelada')
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle"></i> Cancelada
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ ucfirst($agendamento->status) }}</span>
                                @endswitch
                            </td>
                            <td>
                                @if($agendamento->data_hora > now() && in_array($agendamento->status, ['pendente', 'confirmada']))
                                    <form action="{{ route('agendamentos.destroy', $agendamento->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Tem certeza que deseja cancelar?')">
                                            <i class="bi bi-trash"></i> Cancelar
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-lock"></i> -
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        @if($agendamentos->hasPages())
            <nav aria-label="Page navigation" class="mt-4">
                {{ $agendamentos->links('pagination::bootstrap-4') }}
            </nav>
        @endif
    @else
        <div class="alert alert-info">
            <div class="text-center py-5">
                <i class="bi bi-calendar-x" style="font-size: 3rem; opacity: 0.5;"></i>
                <h5 class="mt-3 mb-1">Nenhum agendamento registrado</h5>
                <p class="text-muted mb-3">Você ainda não agendou nenhuma consulta</p>
                <a href="{{ route('agendamentos.escolher') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Agendar Consulta Agora
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
