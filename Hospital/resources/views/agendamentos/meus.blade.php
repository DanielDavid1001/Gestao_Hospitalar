@extends('layouts.app')

@section('content')
<div class="container py-4">
    @php
        $isPaciente = auth()->user()->isPaciente();
        $isMedico = auth()->user()->isMedico();
        $isAdmin = auth()->user()->isAdmin();
    @endphp

    <div class="row mb-4">
        <div class="col-md-6">
            <h2><i class="bi bi-calendar-check"></i> {{ $isMedico ? 'Minhas Consultas' : ($isAdmin ? 'Consultas do Sistema' : 'Meus Agendamentos') }}</h2>
            <p class="text-muted">Listagem de todas as {{ $isPaciente ? 'consultas agendadas' : 'consultas' }}</p>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
            @if($isPaciente)
                <a href="{{ route('agendamentos.escolher') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Agendar Nova Consulta
                </a>
            @endif
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
                        <th>{{ $isPaciente ? 'Profissional' : 'Paciente' }}</th>
                        <th>{{ $isPaciente ? 'Especialidade' : 'Médico' }}</th>
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
                                @if(!$isPaciente)
                                    {{ $agendamento->paciente->user->name ?? $agendamento->nome_paciente }}
                                    <br>
                                    <small class="text-muted">CPF: {{ $agendamento->paciente->cpf ?? '-' }}</small>
                                @else
                                    {{ $agendamento->medico->user->name }}
                                    <br>
                                    <small class="text-muted">CRM: {{ $agendamento->medico->crm }}</small>
                                @endif
                            </td>
                            <td>
                                @if($isPaciente)
                                    {{ $agendamento->medico->especialidade }}
                                @else
                                    {{ $agendamento->medico->user->name ?? '-' }}
                                @endif
                            </td>
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
                                            <i class="bi bi-check-all"></i> Finalizada
                                        </span>
                                        @break
                                    @case('nao_realizada')
                                        <span class="badge bg-dark">
                                            <i class="bi bi-x-octagon"></i> Não Realizada
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
                                @if($isMedico || $isAdmin)
                                    <button type="button"
                                            class="btn btn-sm btn-outline-info"
                                            data-bs-toggle="modal"
                                            data-bs-target="#pacienteDadosModal"
                                            data-paciente-nome="{{ $agendamento->paciente->user->name ?? $agendamento->nome_paciente }}"
                                            data-paciente-cpf="{{ $agendamento->paciente->cpf ?? '-' }}"
                                            data-paciente-email="{{ $agendamento->paciente->user->email ?? '-' }}"
                                            data-paciente-nascimento="{{ $agendamento->paciente?->data_nascimento ? $agendamento->paciente->data_nascimento->format('d/m/Y') : ($agendamento->data_nascimento ? $agendamento->data_nascimento->format('d/m/Y') : '-') }}"
                                            data-paciente-telefone="{{ $agendamento->paciente->telefone ?? '-' }}"
                                            data-paciente-sexo="{{ $agendamento->paciente->sexo ?? '-' }}"
                                            data-paciente-sangue="{{ $agendamento->paciente->tipo_sanguineo ?? '-' }}"
                                            data-paciente-responsavel="{{ $agendamento->nome_responsavel ?? '-' }}"
                                            data-paciente-alergias="{{ $agendamento->paciente->alergias ?? '-' }}"
                                            data-paciente-endereco="{{ $agendamento->paciente->endereco ?? '-' }}">
                                        <i class="bi bi-person-vcard"></i> Dados do Paciente
                                    </button>
                                @endif

                                @if($isMedico && $agendamento->status === 'pendente' && $agendamento->data_hora > now())
                                    <form action="{{ route('agendamentos.validar', $agendamento->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-success"
                                                onclick="return confirm('Confirmar esta consulta?')">
                                            <i class="bi bi-check2-square"></i> Confirmar
                                        </button>
                                    </form>
                                @endif

                                @if($isMedico && $agendamento->status === 'confirmada')
                                    <form action="{{ route('agendamentos.finalizar', $agendamento->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-primary"
                                                onclick="return confirm('Finalizar esta consulta como realizada?')">
                                            <i class="bi bi-check-all"></i> Finalizar
                                        </button>
                                    </form>

                                    <form action="{{ route('agendamentos.nao-realizada', $agendamento->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-outline-dark"
                                                onclick="return confirm('Marcar esta consulta como não realizada?')">
                                            <i class="bi bi-x-octagon"></i> Não Realizada
                                        </button>
                                    </form>
                                @endif

                                @if($isAdmin && $agendamento->data_hora > now() && in_array($agendamento->status, ['pendente', 'confirmada']))
                                        <form action="{{ route('agendamentos.destroy', $agendamento->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Tem certeza que deseja cancelar esta consulta?')">
                                                <i class="bi bi-trash"></i> Cancelar
                                            </button>
                                        </form>
                                @endif

                                @if(!(($isMedico && $agendamento->status === 'pendente' && $agendamento->data_hora > now()))
                                    && !(($isMedico && $agendamento->status === 'confirmada'))
                                    && !(($isAdmin && $agendamento->data_hora > now() && in_array($agendamento->status, ['pendente', 'confirmada'])))
                                    && !($isMedico || $isAdmin))
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
                <p class="text-muted mb-3">{{ $isMedico ? 'Nenhuma consulta encontrada.' : 'Você ainda não agendou nenhuma consulta.' }}</p>
                @if($isPaciente)
                    <a href="{{ route('agendamentos.escolher') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Agendar Consulta Agora
                    </a>
                @endif
            </div>
        </div>
    @endif
</div>

@if($isMedico || $isAdmin)
    <div class="modal fade" id="pacienteDadosModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-vcard"></i> Dados do Paciente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><strong>Nome:</strong> <span id="modal-paciente-nome">-</span></div>
                        <div class="col-md-6"><strong>CPF:</strong> <span id="modal-paciente-cpf">-</span></div>
                        <div class="col-md-6"><strong>Email:</strong> <span id="modal-paciente-email">-</span></div>
                        <div class="col-md-6"><strong>Data de Nascimento:</strong> <span id="modal-paciente-nascimento">-</span></div>
                        <div class="col-md-6"><strong>Telefone:</strong> <span id="modal-paciente-telefone">-</span></div>
                        <div class="col-md-6"><strong>Sexo:</strong> <span id="modal-paciente-sexo">-</span></div>
                        <div class="col-md-6"><strong>Tipo Sanguíneo:</strong> <span id="modal-paciente-sangue">-</span></div>
                        <div class="col-md-6"><strong>Responsável:</strong> <span id="modal-paciente-responsavel">-</span></div>
                        <div class="col-12"><strong>Alergias:</strong> <span id="modal-paciente-alergias">-</span></div>
                        <div class="col-12"><strong>Endereço:</strong> <span id="modal-paciente-endereco">-</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const pacienteDadosModal = document.getElementById('pacienteDadosModal');

        if (!pacienteDadosModal) {
            return;
        }

        pacienteDadosModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            const getValue = (field) => button.getAttribute('data-paciente-' + field) || '-';

            document.getElementById('modal-paciente-nome').textContent = getValue('nome');
            document.getElementById('modal-paciente-cpf').textContent = getValue('cpf');
            document.getElementById('modal-paciente-email').textContent = getValue('email');
            document.getElementById('modal-paciente-nascimento').textContent = getValue('nascimento');
            document.getElementById('modal-paciente-telefone').textContent = getValue('telefone');
            document.getElementById('modal-paciente-sexo').textContent = getValue('sexo');
            document.getElementById('modal-paciente-sangue').textContent = getValue('sangue');
            document.getElementById('modal-paciente-responsavel').textContent = getValue('responsavel');
            document.getElementById('modal-paciente-alergias').textContent = getValue('alergias');
            document.getElementById('modal-paciente-endereco').textContent = getValue('endereco');
        });
    });
    </script>
@endif
@endsection
