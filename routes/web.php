<?php

use Illuminate\Support\Facades\Route;

// Redirigir la página de inicio al panel de administración
Route:: redirect('/', '/admin');

// Rutas protegidas que requieren autenticación, sesión y verificación de correo
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Ruta del dashboard principal del usuario autenticado
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
