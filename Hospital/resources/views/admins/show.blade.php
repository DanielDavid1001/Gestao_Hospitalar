@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detalhes do Administrador</span>
                    <div>
                        <a href="{{ route('admins.edit', $admin->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                        <a href="{{ route('admins.index') }}" class="btn btn-secondary btn-sm">
                            Voltar
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Nome:</strong>
                            <p>{{ $admin->user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <p>{{ $admin->user->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Cargo:</strong>
                            <p>{{ $admin->cargo ?? 'Não informado' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Setor:</strong>
                            <p>{{ $admin->setor ?? 'Não informado' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Telefone:</strong>
                            <p>{{ $admin->telefone ?? 'Não informado' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Cadastrado em:</strong>
                            <p>{{ $admin->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    @if($admin->observacoes)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Observações:</strong>
                                <p>{{ $admin->observacoes }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card-footer">
                    <form action="{{ route('admins.destroy', $admin->id) }}" method="POST" 
                          onsubmit="return confirm('Tem certeza que deseja excluir este administrador?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Excluir Administrador
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
