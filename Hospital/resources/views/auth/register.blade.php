@extends('layouts.app')

@section('content')
<style>
    body.auth-page,
    body.auth-page .app-wrapper {
        width: 100vw !important;
        min-height: 100vh !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .auth-screen {
        width: 100vw !important;
        min-height: 100vh !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        background-size: cover !important;
        background-position: center center !important;
        background-repeat: no-repeat !important;
        padding: 24px !important;
    }

    .auth-box {
        margin: 0 auto !important;
    }

    .auth-screen::before,
    .auth-screen::after {
        display: none !important;
        content: none !important;
    }
</style>
<div class="auth-screen" style="width: 100vw; min-height: 100vh; display: flex; align-items: center; justify-content: center; background-image: linear-gradient(rgba(240, 246, 250, 0.42), rgba(240, 246, 250, 0.42)), url('{{ asset('images/auth-bg.svg') }}');">
    <div class="auth-box">
        <div class="auth-logo">Gerenciamento de Consultas</div>

        <div class="auth-panel">
            <div class="auth-panel-logo">
                <img src="{{ asset('images/tf-medic-logo.png') }}" alt="TF Medic" class="auth-panel-logo-image" onerror="this.style.display='none';">
            </div>
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="input-group auth-input-group mb-2">
                    <input id="name" type="text" class="form-control auth-input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Nome" required autocomplete="name" autofocus>
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                </div>
                @error('name')
                    <div class="text-danger text-start small mb-2">{{ $message }}</div>
                @enderror

                <div class="input-group auth-input-group mb-2">
                    <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="E-mail" required autocomplete="email">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                </div>
                @error('email')
                    <div class="text-danger text-start small mb-2">{{ $message }}</div>
                @enderror

                <div class="input-group auth-input-group mb-2">
                    <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" placeholder="Senha" required autocomplete="new-password">
                    <button class="input-group-text auth-password-toggle" type="button" data-toggle-password="password" aria-label="Mostrar senha">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger text-start small mb-2">{{ $message }}</div>
                @enderror

                <div class="input-group auth-input-group mb-2">
                    <input id="password-confirm" type="password" class="form-control auth-input" name="password_confirmation" placeholder="Confirmar senha" required autocomplete="new-password">
                    <button class="input-group-text auth-password-toggle" type="button" data-toggle-password="password-confirm" aria-label="Mostrar senha">
                        <i class="bi bi-eye-slash"></i>
                    </button>
                </div>

                <button type="submit" class="auth-submit">CADASTRAR</button>

                <div class="auth-links text-end">
                    <a href="{{ route('login') }}">Já tenho conta</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('[data-toggle-password]');

    toggles.forEach(function (toggleButton) {
        const targetId = toggleButton.getAttribute('data-toggle-password');
        const input = document.getElementById(targetId);
        const icon = toggleButton.querySelector('i');

        if (!input || !icon) {
            return;
        }

        toggleButton.addEventListener('click', function () {
            const isPassword = input.getAttribute('type') === 'password';

            input.setAttribute('type', isPassword ? 'text' : 'password');
            icon.classList.toggle('bi-eye', isPassword);
            icon.classList.toggle('bi-eye-slash', !isPassword);
            toggleButton.setAttribute('aria-label', isPassword ? 'Ocultar senha' : 'Mostrar senha');
        });
    });
});
</script>
@endsection
