@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Perfil incompleto</div>

                <div class="card-body">
                    <div class="alert alert-warning" role="alert">
                        {{ $mensagem }}
                    </div>

                    <p class="mb-4">
                        Para acessar seu dashboard, finalize o cadastro do perfil.
                    </p>

                    <div class="d-flex gap-2">
                        <a href="{{ $acaoUrl }}" class="btn btn-primary">
                            {{ $acaoLabel }}
                        </a>
                        <a href="{{ url('/logout') }}"
                           class="btn btn-outline-secondary"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Sair
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
