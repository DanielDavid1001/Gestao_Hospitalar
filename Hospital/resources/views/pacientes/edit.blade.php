@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Editar Paciente</div>

                <div class="card-body">
                    <form action="{{ route('pacientes.update', $paciente->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $paciente->user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $paciente->user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nova Senha (deixe em branco para manter a atual)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control @error('cpf') is-invalid @enderror" 
                                       id="cpf" name="cpf" value="{{ old('cpf', $paciente->cpf) }}" required>
                                @error('cpf')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                <input type="date" class="form-control @error('data_nascimento') is-invalid @enderror" 
                                       id="data_nascimento" name="data_nascimento" value="{{ old('data_nascimento', $paciente->data_nascimento ? $paciente->data_nascimento->format('Y-m-d') : '') }}" required>
                                @error('data_nascimento')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                       id="telefone" name="telefone" value="{{ old('telefone', $paciente->telefone) }}">
                                @error('telefone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="sexo" class="form-label">Sexo</label>
                                <select class="form-select @error('sexo') is-invalid @enderror" 
                                        id="sexo" name="sexo">
                                    <option value="">Selecione...</option>
                                    <option value="M" {{ old('sexo', $paciente->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                    <option value="F" {{ old('sexo', $paciente->sexo) == 'F' ? 'selected' : '' }}>Feminino</option>
                                    <option value="Outro" {{ old('sexo', $paciente->sexo) == 'Outro' ? 'selected' : '' }}>Outro</option>
                                </select>
                                @error('sexo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tipo_sanguineo" class="form-label">Tipo Sanguíneo</label>
                            <select class="form-select @error('tipo_sanguineo') is-invalid @enderror" 
                                    id="tipo_sanguineo" name="tipo_sanguineo">
                                <option value="">Selecione...</option>
                                <option value="A+" {{ old('tipo_sanguineo', $paciente->tipo_sanguineo) == 'A+' ? 'selected' : '' }}>A+</option>
                                <option value="A-" {{ old('tipo_sanguineo', $paciente->tipo_sanguineo) == 'A-' ? 'selected' : '' }}>A-</option>
                                <option value="B+" {{ old('tipo_sanguineo', $paciente->tipo_sanguineo) == 'B+' ? 'selected' : '' }}>B+</option>
                                <option value="B-" {{ old('tipo_sanguineo', $paciente->tipo_sanguineo) == 'B-' ? 'selected' : '' }}>B-</option>
                                <option value="AB+" {{ old('tipo_sanguineo', $paciente->tipo_sanguineo) == 'AB+' ? 'selected' : '' }}>AB+</option>
                                <option value="AB-" {{ old('tipo_sanguineo', $paciente->tipo_sanguineo) == 'AB-' ? 'selected' : '' }}>AB-</option>
                                <option value="O+" {{ old('tipo_sanguineo', $paciente->tipo_sanguineo) == 'O+' ? 'selected' : '' }}>O+</option>
                                <option value="O-" {{ old('tipo_sanguineo', $paciente->tipo_sanguineo) == 'O-' ? 'selected' : '' }}>O-</option>
                            </select>
                            @error('tipo_sanguineo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço</label>
                            <textarea class="form-control @error('endereco') is-invalid @enderror" 
                                      id="endereco" name="endereco" rows="2">{{ old('endereco', $paciente->endereco) }}</textarea>
                            @error('endereco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="alergias" class="form-label">Alergias</label>
                            <textarea class="form-control @error('alergias') is-invalid @enderror" 
                                      id="alergias" name="alergias" rows="3">{{ old('alergias', $paciente->alergias) }}</textarea>
                            @error('alergias')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pacientes.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
