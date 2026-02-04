@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Editar Médico</div>

                <div class="card-body">
                    <form action="{{ route('medicos.update', $medico->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $medico->user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $medico->user->email) }}" required>
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

                        <div class="mb-3">
                            <label for="crm" class="form-label">CRM</label>
                            <input type="text" class="form-control @error('crm') is-invalid @enderror" 
                                   id="crm" name="crm" value="{{ old('crm', $medico->crm) }}" required>
                            @error('crm')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="especialidade" class="form-label">Especialidade</label>
                            <input type="text" class="form-control @error('especialidade') is-invalid @enderror" 
                                   id="especialidade" name="especialidade" value="{{ old('especialidade', $medico->especialidade) }}" required>
                            @error('especialidade')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                   id="telefone" name="telefone" value="{{ old('telefone', $medico->telefone) }}">
                            @error('telefone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço</label>
                            <textarea class="form-control @error('endereco') is-invalid @enderror" 
                                      id="endereco" name="endereco" rows="3">{{ old('endereco', $medico->endereco) }}</textarea>
                            @error('endereco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('medicos.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
