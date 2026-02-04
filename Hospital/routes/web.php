<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\AdminController;
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
    
    // INDEX, SHOW, EDIT, UPDATE, DELETE - Admin e Medico podem acessar
    Route::middleware(['role:admin,medico'])->group(function () {
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
    });
    
    // INDEX, SHOW, EDIT, UPDATE, DELETE - Admin e Paciente podem acessar
    Route::middleware(['role:admin,paciente'])->group(function () {
        Route::get('pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
        Route::get('pacientes/{paciente}', [PacienteController::class, 'show'])->name('pacientes.show');
        Route::get('pacientes/{paciente}/edit', [PacienteController::class, 'edit'])->name('pacientes.edit');
        Route::put('pacientes/{paciente}', [PacienteController::class, 'update'])->name('pacientes.update');
        Route::delete('pacientes/{paciente}', [PacienteController::class, 'destroy'])->name('pacientes.destroy');
    });
    
    // Routes for Admins - Apenas Admin pode gerenciar outros admins
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('admins', AdminController::class);
    });
});

