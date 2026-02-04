@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Lista de Médicos</span>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('medicos.create') }}" class="btn btn-primary btn-sm">
                            Novo Médico
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
                                    <th>CRM</th>
                                    <th>Especialidade</th>
                                    <th>Telefone</th>
                                    <th>Ações</th>
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
                                            <a href="{{ route('medicos.show', $medico->id) }}" class="btn btn-info btn-sm">Ver</a>
                                            <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                            <form action="{{ route('medicos.destroy', $medico->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este médico?')">Excluir</button>
                                            </form>
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

                    <div class="d-flex justify-content-center">
                        {{ $medicos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
