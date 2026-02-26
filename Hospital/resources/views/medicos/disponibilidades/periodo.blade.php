@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2><i class="bi bi-calendar-range"></i> Adicionar Periodo de Disponibilidade</h2>
            <p class="text-muted">Defina um intervalo e exclua dias especificos se necessario</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Erros de validacao:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('medico.disponibilidades.periodo.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="data_inicio" class="form-label">Data de Inicio <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('data_inicio') is-invalid @enderror"
                                           id="data_inicio" name="data_inicio" value="{{ old('data_inicio') }}" required>
                                    @error('data_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="data_fim" class="form-label">Data de Fim <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('data_fim') is-invalid @enderror"
                                           id="data_fim" name="data_fim" value="{{ old('data_fim') }}" required>
                                    @error('data_fim')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hora_inicio" class="form-label">Hora de Inicio <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('hora_inicio') is-invalid @enderror"
                                           id="hora_inicio" name="hora_inicio" value="{{ old('hora_inicio') }}" required>
                                    @error('hora_inicio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="hora_fim" class="form-label">Hora de Fim <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('hora_fim') is-invalid @enderror"
                                           id="hora_fim" name="hora_fim" value="{{ old('hora_fim') }}" required>
                                    @error('hora_fim')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="periodo" class="form-label">Periodo <span class="text-danger">*</span></label>
                            <select class="form-select @error('periodo') is-invalid @enderror"
                                    id="periodo" name="periodo" required>
                                <option value="">-- Selecione um periodo --</option>
                                @foreach ($periodos as $periodo)
                                    <option value="{{ $periodo }}" @selected(old('periodo') === $periodo)>
                                        {{ ucfirst($periodo) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('periodo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Excluir dias especificos (opcional)</label>
                            <div class="d-flex gap-2">
                                <input type="date" class="form-control" id="excluir_data">
                                <button type="button" class="btn btn-outline-secondary" id="add_exclusao">Adicionar</button>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">Datas excluidas:</small>
                                <div id="lista_exclusoes" class="mt-1"></div>
                            </div>
                            <input type="hidden" id="datas_excluidas" name="datas_excluidas" value="{{ old('datas_excluidas') }}">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Salvar Periodo
                            </button>
                            <a href="{{ route('medico.disponibilidades.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Como funciona</h6>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <li>Escolha um intervalo de datas continuo</li>
                        <li>Defina o horario fixo para todos os dias do periodo</li>
                        <li>Se precisar, exclua dias especificos do intervalo</li>
                        <li>O sistema cria uma disponibilidade por dia valido</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Informacoes</h6>
                </div>
                <div class="card-body">
                    <p><strong>Medico:</strong> {{ $medico->nome }}</p>
                    <p><strong>Especialidade:</strong> {{ $medico->especialidade }}</p>
                    <p class="mb-0 text-muted">Os dias excluidos nao serao cadastrados.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addButton = document.getElementById('add_exclusao');
    const excluirInput = document.getElementById('excluir_data');
    const lista = document.getElementById('lista_exclusoes');
    const hidden = document.getElementById('datas_excluidas');

    const exclusions = new Set();

    const renderList = () => {
        lista.innerHTML = '';
        const values = Array.from(exclusions);
        hidden.value = values.join(',');

        values.forEach((date) => {
            const badge = document.createElement('span');
            badge.className = 'badge bg-secondary me-2 mb-2';
            badge.textContent = date;
            badge.style.cursor = 'pointer';
            badge.title = 'Clique para remover';
            badge.addEventListener('click', () => {
                exclusions.delete(date);
                renderList();
            });
            lista.appendChild(badge);
        });
    };

    addButton.addEventListener('click', function() {
        const value = excluirInput.value;
        if (!value) {
            return;
        }
        exclusions.add(value);
        excluirInput.value = '';
        renderList();
    });

    // Precarregar exclusoes do old()
    const oldValue = hidden.value;
    if (oldValue) {
        oldValue.split(',').map(v => v.trim()).filter(Boolean).forEach(v => exclusions.add(v));
        renderList();
    }

    // Impedir datas passadas
    const hoje = new Date().toISOString().split('T')[0];
    document.getElementById('data_inicio').setAttribute('min', hoje);
    document.getElementById('data_fim').setAttribute('min', hoje);
    excluirInput.setAttribute('min', hoje);
});
</script>
@endsection
