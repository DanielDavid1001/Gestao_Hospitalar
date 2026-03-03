@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Médicos</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="card-title mb-0">Lista de Médicos</h3>
                @if(auth()->user()->isAdmin())
                    <div class="d-flex gap-2 ms-auto">
                        <a href="{{ route('especialidades.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-gear me-1"></i> Gerenciar Especialidade
                        </a>
                        <a href="{{ route('medico.disponibilidades.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-calendar-check me-1"></i> Gerenciar Horários
                        </a>
                        <a href="{{ route('medicos.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg me-1"></i> Novo Médico
                        </a>
                    </div>
                @endif
            </div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered align-middle mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>CRM</th>
                                <th>Especialidade</th>
                                <th>Telefone</th>
                                <th style="width: 170px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($medicos as $medico)
                                <tr>
                                    <td>{{ $medico->id }}</td>
                                    <td>{{ $medico->user->name }}</td>
                                    <td>{{ $medico->user->email }}</td>
                                    <td>{{ $medico->crm }}</td>
                                    <td>{{ $medico->especialidade }}</td>
                                    <td>{{ $medico->telefone ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('medicos.show', $medico->id) }}" class="btn btn-info" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('medicos.destroy', $medico->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este médico?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum médico cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $medicos->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
