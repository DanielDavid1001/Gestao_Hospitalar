@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="alert alert-warning border-0 shadow-sm" role="alert">
                <div class="text-center mb-4">
                    <i class="bi bi-calendar-x" style="font-size: 3rem; color: #ff6b6b;"></i>
                </div>
                <h4 class="alert-heading text-center mb-3">Sem Disponibilidade</h4>
                <p class="mb-3">
                    Desculpe, não há disponibilidade para a opção selecionada no momento.
                </p>
                
                @if(isset($especialidade))
                    <p class="mb-0 text-muted">
                        <small><strong>Especialidade:</strong> {{ $especialidade }}</small>
                    </p>
                @endif

                <hr>

                <p class="mb-3">
                    Sugestões:
                </p>
                <ul class="mb-3">
                    <li>Tente outra especialidade</li>
                    <li>Tente agendar com outro profissional</li>
                    <li>Verifique outros períodos de disponibilidade</li>
                </ul>

                <div class="d-grid gap-2">
                    <a href="{{ route('agendamentos.escolher') }}" class="btn btn-primary">
                        <i class="bi bi-calendar-plus"></i> Voltar e Tentar Novamente
                    </a>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body text-center">
                    <p class="mb-0 text-muted">
                        <small>
                            Se precisar de ajuda ou tem dúvidas sobre disponibilidade,
                            entre em contato com o nosso atendimento.
                        </small>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
