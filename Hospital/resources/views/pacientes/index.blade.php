@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pacientes</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="card-title mb-0">Lista de Pacientes</h3>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('pacientes.create') }}" class="btn btn-primary btn-sm ms-auto">
                        <i class="bi bi-plus-lg me-1"></i> Novo Paciente
                    </a>
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
                                <th>CPF</th>
                                <th>Data Nascimento</th>
                                <th>Telefone</th>
                                <th style="width: 170px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pacientes as $paciente)
                                <tr>
                                    <td>{{ $paciente->id }}</td>
                                    <td>{{ $paciente->user->name }}</td>
                                    <td>{{ $paciente->user->email }}</td>
                                    <td>{{ $paciente->cpf ?? '-' }}</td>
                                    <td>{{ $paciente->data_nascimento ? $paciente->data_nascimento->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $paciente->telefone ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-info" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('pacientes.destroy', $paciente->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir este paciente?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum paciente cadastrado.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $pacientes->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
