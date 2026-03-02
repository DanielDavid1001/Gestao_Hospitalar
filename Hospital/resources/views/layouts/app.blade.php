<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
        @auth
            <nav class="app-header navbar navbar-expand bg-body">
                <div class="container-fluid">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                                <i class="bi bi-list"></i>
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown user-menu">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                                <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                                <li class="user-footer d-flex justify-content-end p-2">
                                    <a class="btn btn-default btn-flat" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>

            <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
                <div class="sidebar-brand">
                    <a href="{{ route('dashboard') }}" class="brand-link">
                        <span class="brand-text fw-light">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                </div>

                <div class="sidebar-wrapper">
                    <nav class="mt-2">
                        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('home') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-house"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>

                            @if(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a href="{{ route('medicos.index') }}" class="nav-link {{ request()->routeIs('medicos.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-person-badge"></i>
                                        <p>Médicos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('agendamentos.meus') }}" class="nav-link {{ request()->routeIs('agendamentos.meus') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-clipboard2-check"></i>
                                        <p>Consultas</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('pacientes.index') }}" class="nav-link {{ request()->routeIs('pacientes.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-people"></i>
                                        <p>Pacientes</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admins.index') }}" class="nav-link {{ request()->routeIs('admins.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-shield-lock"></i>
                                        <p>Admins</p>
                                    </a>
                                </li>
                            @endif

                            @if(auth()->user()->isMedico())
                                <li class="nav-item">
                                    <a href="{{ route('medico.disponibilidades.index') }}" class="nav-link {{ request()->routeIs('medico.disponibilidades.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-calendar-check"></i>
                                        <p>Disponibilidades</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('agendamentos.meus') }}" class="nav-link {{ request()->routeIs('agendamentos.meus') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-clipboard2-check"></i>
                                        <p>Consultas</p>
                                    </a>
                                </li>
                            @endif

                            @if(auth()->user()->isPaciente())
                                <li class="nav-item">
                                    <a href="{{ route('agendamentos.escolher') }}" class="nav-link {{ request()->routeIs('agendamentos.escolher') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-calendar-plus"></i>
                                        <p>Agendar</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('agendamentos.meus') }}" class="nav-link {{ request()->routeIs('agendamentos.meus') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-calendar2-week"></i>
                                        <p>Meus Agendamentos</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('paciente.perfil.edit') }}" class="nav-link {{ request()->routeIs('paciente.perfil.*') ? 'active' : '' }}">
                                        <i class="nav-icon bi bi-person"></i>
                                        <p>Meu Perfil</p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </aside>

            <main class="app-main">
                <div class="app-content-header">
                    <div class="container-fluid">
                        @unless(request()->routeIs('agendamentos.meus'))
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                        @endunless
                    </div>
                </div>
                <div class="app-content">
                    @yield('content')
                </div>
            </main>
        @else
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item"><a class="nav-link" href="{{ url('/login') }}">{{ __('Login') }}</a></li>
                            <li class="nav-item"><a class="nav-link" href="{{ url('/register') }}">{{ __('Register') }}</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
            <main class="py-4">
                @yield('content')
            </main>
        @endauth

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</body>
</html>
