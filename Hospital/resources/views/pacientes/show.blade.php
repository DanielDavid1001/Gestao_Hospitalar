@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Detalhes do Paciente</span>
                    <div>
                        <a href="{{ route('pacientes.edit', $paciente->id) }}" class="btn btn-warning btn-sm">Editar</a>
                        <a href="{{ route('pacientes.index') }}" class="btn btn-secondary btn-sm">Voltar</a>
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="mb-3">Dados Pessoais</h5>
                    
                    <div class="row mb-3">
                        <div class="col-md-4"><strong>ID:</strong></div>
                        <div class="col-md-8">{{ $paciente->id }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Nome:</strong></div>
                        <div class="col-md-8">{{ $paciente->user->name }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Email:</strong></div>
                        <div class="col-md-8">{{ $paciente->user->email }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>CPF:</strong></div>
                        <div class="col-md-8">{{ $paciente->cpf ?? '-' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Data de Nascimento:</strong></div>
                        <div class="col-md-8">{{ $paciente->data_nascimento ? $paciente->data_nascimento->format('d/m/Y') : '-' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Idade:</strong></div>
                        <div class="col-md-8">{{ $paciente->data_nascimento ? $paciente->data_nascimento->age . ' anos' : '-' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Sexo:</strong></div>
                        <div class="col-md-8">
                            @if($paciente->sexo == 'M') Masculino
                            @elseif($paciente->sexo == 'F') Feminino
                            @elseif($paciente->sexo) {{ $paciente->sexo }}
                            @else - @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Telefone:</strong></div>
                        <div class="col-md-8">{{ $paciente->telefone ?? '-' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Endereço:</strong></div>
                        <div class="col-md-8">{{ $paciente->endereco ?? '-' }}</div>
                    </div>

                    <hr class="my-4">

                    <h5 class="mb-3">Informações Médicas</h5>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Tipo Sanguíneo:</strong></div>
                        <div class="col-md-8">{{ $paciente->tipo_sanguineo ?? '-' }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Alergias:</strong></div>
                        <div class="col-md-8">{{ $paciente->alergias ?? '-' }}</div>
                    </div>

                    <hr class="my-4">

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Cadastrado em:</strong></div>
                        <div class="col-md-8">{{ $paciente->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><strong>Última atualização:</strong></div>
                        <div class="col-md-8">{{ $paciente->updated_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <hr>

                    <form action="{{ route('pacientes.destroy', $paciente->id) }}" method="POST" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este paciente?')">
                            Excluir Paciente
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
