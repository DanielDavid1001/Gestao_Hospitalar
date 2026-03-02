@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-calendar-plus"></i> Agendar Consulta</h2>
            <p class="text-muted">Escolha como deseja agendar sua consulta</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-primary h-100 shadow-sm">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <div class="mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width:72px;height:72px;">
                            <i class="bi bi-hospital text-primary" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-2">Agendar por Especialidade</h5>
                    <p class="card-text text-muted mb-4">Escolha a especialidade médica que você precisa</p>
                    <form id="especialidade-form" class="mt-3 text-start">
                        <div class="mb-3">
                            <label for="especialidade-select" class="form-label">Especialidade</label>
                            <select id="especialidade-select" name="especialidade" class="form-select">
                                <option value="">Selecione uma especialidade</option>
                                @foreach($especialidades as $especialidade)
                                    <option value="{{ $especialidade->name }}">{{ $especialidade->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="button" id="especialidade-continuar" class="btn btn-primary w-100">
                            <i class="bi bi-arrow-right"></i> Continuar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-outline card-primary h-100 shadow-sm">
                <div class="card-body text-center d-flex flex-column justify-content-center">
                    <div class="mb-3">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width:72px;height:72px;">
                            <i class="fa-solid fa-user-doctor text-primary" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                    <h5 class="card-title mb-2">Agendar com Profissional</h5>
                    <p class="card-text text-muted mb-4">Escolha diretamente o médico desejado</p>
                    <a href="{{ route('agendamentos.por-profissional') }}" class="btn btn-primary w-100">
                        <i class="bi bi-arrow-right"></i> Continuar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('especialidade-continuar').addEventListener('click', function () {
    const select = document.getElementById('especialidade-select');
    if (!select.value) {
        alert('Selecione uma especialidade para continuar.');
        return;
    }

    const especialidade = encodeURIComponent(select.value);
    const urlBase = @json(url('agendamentos/especialidade'));
    window.location.href = `${urlBase}/${especialidade}`;
});
</script>
@endsection
