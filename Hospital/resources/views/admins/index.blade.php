@extends('layouts.app')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Administradores</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h3 class="card-title mb-0">Lista de Administradores</h3>
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admins.create') }}" class="btn btn-primary btn-sm ms-auto">
                        <i class="bi bi-plus-lg me-1"></i> Novo Administrador
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
                                <th>Cargo</th>
                                <th>Setor</th>
                                <th>Telefone</th>
                                <th style="width: 170px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins as $admin)
                                <tr>
                                    <td>{{ $admin->id }}</td>
                                    <td>{{ $admin->user->name }}</td>
                                    <td>{{ $admin->user->email }}</td>
                                    <td>{{ $admin->cargo ?? '-' }}</td>
                                    <td>{{ $admin->setor ?? '-' }}</td>
                                    <td>{{ $admin->telefone ?? '-' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admins.show', $admin->id) }}"
                                               class="btn btn-info" title="Visualizar">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admins.edit', $admin->id) }}"
                                               class="btn btn-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('admins.destroy', $admin->id) }}"
                                                  method="POST" style="display: inline;"
                                                  onsubmit="return confirm('Tem certeza que deseja excluir este administrador?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" title="Excluir">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum administrador cadastrado</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $admins->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
