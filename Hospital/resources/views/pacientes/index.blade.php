@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Lista de Pacientes</span>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('pacientes.create') }}" class="btn btn-primary btn-sm">
                            Novo Paciente
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
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>CPF</th>
                                    <th>Data Nascimento</th>
                                    <th>Telefone</th>
                                    <th>Ações</th>
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
                                            <a href="{{ route('pacientes.show', $paciente->id) }}" class="btn btn-info btn-sm">Ver</a>
                                            <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                            <form action="{{ route('pacientes.destroy', $paciente->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este paciente?')">Excluir</button>
                                            </form>
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

                    <div class="d-flex justify-content-center">
                        {{ $pacientes->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
