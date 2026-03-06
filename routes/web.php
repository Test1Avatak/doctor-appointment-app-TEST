<?php

use Illuminate\Support\Facades\Route;

// Redirect the home page to the administration panel
Route:: redirect('/', '/admin');

// Protected routes that require authentication, session, and email verification
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Path to the main dashboard of the authenticated user
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
