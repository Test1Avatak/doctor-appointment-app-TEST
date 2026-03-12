<?php

use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\SupportTicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

// Página principal del panel de administración
Route::get('/', function(){
    return view ('admin.dashboard');
})->name('dashboard');

// Gestión de Roles
Route::resource('roles', RoleController::class);

// Gestión de Usuarios
Route::resource('users', UserController::class);

// Gestión de pacientes
Route::resource('patients', PatientController::class);

// Gestión de doctores
Route::resource('doctors', DoctorController::class);

// Ruta para crear doctor desde usuario
Route::get('users/{user}/doctor/create', [DoctorController::class, 'createFromUser'])
    ->name('doctors.createFromUser');

// Gestión de tickets de soporte
Route::resource('support-tickets', SupportTicketController::class);



// Gestión de citas médicas
Route::resource('appointments', \App\Http\Controllers\Admin\AppointmentController::class);

// Gestión de horarios médicos
Route::resource('doctors.schedules', \App\Http\Controllers\Admin\ScheduleController::class)
    ->only(['index', 'store']);

// Módulo de Consulta (Manager)
Route::get('consultations/{appointment}', \App\Livewire\Admin\ConsultationManager::class)
    ->name('consultations.show');
