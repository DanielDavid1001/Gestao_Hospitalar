<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MedicalSpecialtyController;
use App\Http\Controllers\MedicoAvailabilityController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Dashboard - Redireciona para dashboard específico após login
Route::get('/home', [DashboardController::class, 'index'])->name('home')->middleware('auth');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Routes protected by authentication
Route::middleware(['auth'])->group(function () {
    // Routes for Doctors
    // CREATE e STORE - apenas Admin pode cadastrar novos médicos
    Route::middleware(['role:admin'])->group(function () {
        Route::get('medicos/create', [MedicoController::class, 'create'])->name('medicos.create');
        Route::post('medicos', [MedicoController::class, 'store'])->name('medicos.store');
    });
    
    // INDEX, SHOW, EDIT, UPDATE, DELETE - apenas Admin pode acessar
    Route::middleware(['role:admin'])->group(function () {
        Route::get('medicos', [MedicoController::class, 'index'])->name('medicos.index');
        Route::get('medicos/{medico}', [MedicoController::class, 'show'])->name('medicos.show');
        Route::get('medicos/{medico}/edit', [MedicoController::class, 'edit'])->name('medicos.edit');
        Route::put('medicos/{medico}', [MedicoController::class, 'update'])->name('medicos.update');
        Route::delete('medicos/{medico}', [MedicoController::class, 'destroy'])->name('medicos.destroy');
    });
    
    // Routes for Patients
    // CREATE e STORE - apenas Admin pode cadastrar novos pacientes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('pacientes/create', [PacienteController::class, 'create'])->name('pacientes.create');
        Route::post('pacientes', [PacienteController::class, 'store'])->name('pacientes.store');
        Route::get('pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
        Route::get('pacientes/{paciente}', [PacienteController::class, 'show'])->name('pacientes.show');
        Route::get('pacientes/{paciente}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit');
        Route::put('pacientes/{paciente}', [PacienteController::class, 'update'])->name('pacientes.update');
        Route::delete('pacientes/{paciente}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');
    });
    
    // Routes for Admins - Apenas Admin pode gerenciar outros admins
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('admins', AdminController::class);
        Route::get('especialidades', [MedicalSpecialtyController::class, 'index'])->name('especialidades.index');
        Route::post('especialidades', [MedicalSpecialtyController::class, 'store'])->name('especialidades.store');
        Route::delete('especialidades/{medicalSpecialty}', [MedicalSpecialtyController::class, 'destroy'])->name('especialidades.destroy');
    });

    // Routes for Medico Availabilities - Apenas Médicos podem gerenciar suas disponibilidades
    Route::middleware(['role:medico,admin'])->group(function () {
        Route::get('disponibilidades', [MedicoAvailabilityController::class, 'calendario'])->name('medico.disponibilidades.index');
        Route::get('disponibilidades/lista', [MedicoAvailabilityController::class, 'index'])->name('medico.disponibilidades.lista');
        Route::get('disponibilidades/periodo', [MedicoAvailabilityController::class, 'createPeriodo'])->name('medico.disponibilidades.periodo');
        Route::post('disponibilidades/periodo', [MedicoAvailabilityController::class, 'storePeriodo'])->name('medico.disponibilidades.periodo.store');
        Route::get('disponibilidades/calendario', [MedicoAvailabilityController::class, 'calendario'])->name('medico.disponibilidades.calendario');
        Route::delete('disponibilidades/{id}', [MedicoAvailabilityController::class, 'destroy'])->name('medico.disponibilidades.destroy');
    });

    // Routes for Agendamentos - Apenas Pacientes podem agendar consultas
    Route::middleware(['role:paciente,medico,admin'])->group(function () {
        Route::get('meus-agendamentos', [AgendamentoController::class, 'meus'])->name('agendamentos.meus');
    });

    Route::middleware(['role:medico'])->group(function () {
        Route::patch('agendamentos/{id}/validar', [AgendamentoController::class, 'validar'])->name('agendamentos.validar');
        Route::patch('agendamentos/{id}/finalizar', [AgendamentoController::class, 'finalizar'])->name('agendamentos.finalizar');
        Route::patch('agendamentos/{id}/nao-realizada', [AgendamentoController::class, 'naoRealizada'])->name('agendamentos.nao-realizada');
    });

    Route::middleware(['role:admin'])->group(function () {
        Route::delete('agendamentos/{id}', [AgendamentoController::class, 'destroy'])->name('agendamentos.destroy');
    });

    // Rotas exclusivas para Pacientes (agendamento e cancelamento)
    Route::middleware(['role:paciente'])->group(function () {
        Route::get('meu-perfil', [PacienteController::class, 'editPerfil'])->name('paciente.perfil.edit');
        Route::put('meu-perfil', [PacienteController::class, 'updatePerfil'])->name('paciente.perfil.update');
        Route::get('agendamentos', [AgendamentoController::class, 'escolher'])->name('agendamentos.escolher');
        Route::get('agendamentos/especialidade/{especialidade}', [AgendamentoController::class, 'porEspecialidade'])->name('agendamentos.por-especialidade');
        Route::get('agendamentos/profissional', [AgendamentoController::class, 'porProfissional'])->name('agendamentos.por-profissional');
        Route::get('agendamentos/disponibilidades/{medico_id}', [AgendamentoController::class, 'disponibilidades'])->name('agendamentos.disponibilidades');
        Route::get('agendamentos/confirmar/{medico_id}/{data}/{hora_inicio?}', [AgendamentoController::class, 'confirmar'])->name('agendamentos.confirmar');
        Route::post('agendamentos', [AgendamentoController::class, 'store'])->name('agendamentos.store');
    });
});

