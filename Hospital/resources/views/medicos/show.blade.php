@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detalhes do Médico</span>
                    <div>
                        <a href="{{ route('medicos.edit', $medico->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <a href="{{ route('medicos.index') }}" class="btn btn-secondary btn-sm">Voltar</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>ID:</strong></div>
                        <div class="col-md-8">{{ $medico->id }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Nome:</strong></div>
                        <div class="col-md-8">{{ $medico->user->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Email:</strong></div>
                        <div class="col-md-8">{{ $medico->user->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>CRM:</strong></div>
                        <div class="col-md-8">{{ $medico->crm }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Especialidade:</strong></div>
                        <div class="col-md-8">{{ $medico->especialidade }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Telefone:</strong></div>
                        <div class="col-md-8">{{ $medico->telefone ?? '-' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Endereço:</strong></div>
                        <div class="col-md-8">{{ $medico->endereco ?? '-' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Cadastrado em:</strong></div>
                        <div class="col-md-8">{{ $medico->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Última atualização:</strong></div>
                        <div class="col-md-8">{{ $medico->updated_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <hr>

                    <form action="{{ route('medicos.destroy', $medico->id) }}" method="POST" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este médico?')">
                            Excluir Médico
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
