@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-check-circle"></i> Confirmar Agendamento</h2>
            <p class="text-muted">Preencha os dados para confirmar a consulta</p>
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
            <div class="card">
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

                        <div class="mb-3">
                            <label for="nome_paciente" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nome_paciente') is-invalid @enderror"
                                   id="nome_paciente" name="nome_paciente" value="{{ old('nome_paciente') }}" required>
                            @error('nome_paciente')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="data_nascimento" class="form-label">Data de Nascimento <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('data_nascimento') is-invalid @enderror"
                                   id="data_nascimento" name="data_nascimento" placeholder="dd/mm/yyyy"
                                   value="{{ old('data_nascimento') }}" maxlength="10" required>
                            @error('data_nascimento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Formato: dd/mm/yyyy</small>
                        </div>

                        <div class="mb-3">
                            <label for="nome_responsavel" class="form-label">Nome do Pai/Mãe ou Responsável</label>
                            <input type="text" class="form-control @error('nome_responsavel') is-invalid @enderror"
                                   id="nome_responsavel" name="nome_responsavel" value="{{ old('nome_responsavel') }}">
                            @error('nome_responsavel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Opcional - deixe em branco se maior de idade</small>
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
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-calendar-check"></i> Resumo do Agendamento</h6>
                </div>
                <div class="card-body">
                    <p><strong>Médico:</strong><br>{{ $medico->user->name }}</p>
                    <p><strong>Especialidade:</strong><br>{{ $medico->especialidade }}</p>
                    <p><strong>Data:</strong><br>{{ \Carbon\Carbon::parse($disponibilidade->data)->format('d/m/Y') }}</p>
                    <p class="mb-0"><strong>Horário:</strong><br>{{ $disponibilidade->hora_inicio->format('H:i') }} - {{ $disponibilidade->hora_fim->format('H:i') }}</p>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Atenção</h6>
                </div>
                <div class="card-body">
                    <small>Após confirmar, seu agendamento será registrado como <strong>pendente</strong> e precisará ser confirmado pelo consultório.</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-formatar data
document.getElementById('data_nascimento').addEventListener('keyup', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2);
    }
    if (value.length >= 5) {
        value = value.substring(0, 5) + '/' + value.substring(5, 9);
    }
    e.target.value = value;
});
</script>
@endsection
