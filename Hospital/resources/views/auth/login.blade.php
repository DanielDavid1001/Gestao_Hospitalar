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
                <svg viewBox="0 0 200 200" class="auth-panel-logo-image" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background circle -->
                    <circle cx="100" cy="100" r="95" fill="#f8f9fa" stroke="#e9ecef" stroke-width="2"/>
                    <!-- Stethoscope -->
                    <g transform="translate(100, 100)">
                        <!-- Earpieces -->
                        <circle cx="-25" cy="-35" r="12" fill="none" stroke="#0d5a7d" stroke-width="8"/>
                        <circle cx="25" cy="-35" r="12" fill="none" stroke="#0d5a7d" stroke-width="8"/>
                        <!-- Tubes connecting earpieces -->
                        <path d="M -25 -23 Q -25 0 -10 15" fill="none" stroke="#0d5a7d" stroke-width="8" stroke-linecap="round"/>
                        <path d="M 25 -23 Q 25 0 10 15" fill="none" stroke="#0d5a7d" stroke-width="8" stroke-linecap="round"/>
                        <!-- Main tube -->
                        <path d="M -10 15 Q 0 35 0 50" fill="none" stroke="#0d5a7d" stroke-width="8" stroke-linecap="round"/>
                        <path d="M 10 15 Q 0 35 0 50" fill="none" stroke="#0d5a7d" stroke-width="8" stroke-linecap="round"/>
                        <!-- Diaphragm -->
                        <circle cx="0" cy="70" r="20" fill="none" stroke="#0d5a7d" stroke-width="8"/>
                        <circle cx="0" cy="70" r="12" fill="#0d9488"/>
                        <!-- Plus sign inside diaphragm -->
                        <line x1="0" y1="62" x2="0" y2="78" stroke="white" stroke-width="3" stroke-linecap="round"/>
                        <line x1="-8" y1="70" x2="8" y2="70" stroke="white" stroke-width="3" stroke-linecap="round"/>
                    </g>
                </svg>
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="input-group auth-input-group mb-2">
                    <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Usuário" required autocomplete="email" autofocus>
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                </div>
                @error('email')
                    <div class="text-danger text-start small mb-2">{{ $message }}</div>
                @enderror

                <div class="input-group auth-input-group mb-2">
                    <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" placeholder="Senha" required autocomplete="current-password">
                    <button class="input-group-text auth-password-toggle" type="button" id="togglePassword" aria-label="Mostrar senha">
                        <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                    </button>
                </div>
                @error('password')
                    <div class="text-danger text-start small mb-2">{{ $message }}</div>
                @enderror

                <button type="submit" class="auth-submit">ENTRAR</button>

                <div class="auth-links d-flex justify-content-between align-items-center">
                    <input type="hidden" name="remember" value="0">
                    <label class="form-check m-0 d-flex align-items-center" for="remember">
                        <input id="remember" class="form-check-input" type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                        <span class="form-check-label">Lembrar de mim</span>
                    </label>
                    <a href="{{ route('register') }}">Criar conta</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('togglePassword');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (!passwordInput || !toggleButton || !toggleIcon) {
        return;
    }

    toggleButton.addEventListener('click', function () {
        const isPassword = passwordInput.getAttribute('type') === 'password';

        passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
        toggleIcon.classList.toggle('bi-eye', isPassword);
        toggleIcon.classList.toggle('bi-eye-slash', !isPassword);
        toggleButton.setAttribute('aria-label', isPassword ? 'Ocultar senha' : 'Mostrar senha');
    });
});
</script>
@endsection
