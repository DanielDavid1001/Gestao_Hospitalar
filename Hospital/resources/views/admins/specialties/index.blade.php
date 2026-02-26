@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Especialidades Medicas</span>
                    <a href="{{ route('medicos.index') }}" class="btn btn-secondary btn-sm">Voltar</a>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Especialidade</th>
                                    <th class="text-end">Acoes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($especialidades as $especialidade)
                                    <tr>
                                        <td>
                                            {{ $especialidade->name }}
                                            @if($especialidade->name === $defaultSpecialty)
                                                <span class="badge bg-info text-dark ms-2">Padrao</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <form action="{{ route('especialidades.destroy', $especialidade->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Excluir esta especialidade? Medicos com ela serao atualizados para Clinico Geral.');">
                                                    Excluir
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Nenhuma especialidade encontrada.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3 text-muted">
                        Ao excluir uma especialidade, todos os medicos que a possuem sao atualizados para Clinico Geral.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
