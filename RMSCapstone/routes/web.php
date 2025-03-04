<?php

use Illuminate\Support\Facades\Route;


// ----------------------------- ADMIN PAGES ----------------------------------------- //

// Welcome page
Route::get('/', function () {
    return view('admin.welcome');
});

// Authentication
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',

    // Dashboard
])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

Route::get('/tite', function () {
    return view('admin.dashboard');
});

// Reservation 

// Rooms

// Activities

// Events

// Housekeeping


// ----------------------------- GUEST PAGES ----------------------------------------- //


// ----------------------------- TENANT PAGES ----------------------------------------- //