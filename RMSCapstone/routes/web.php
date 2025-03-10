<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\RoomCategories\ViewRoomCategory;
use App\Livewire\Admin\RoomCategories\EditRoomCategory;




// ----------------------------- ADMIN PAGES ----------------------------------------- //

// Welcome page
Route::get('/', function () {
    return view('admin.welcome');
})->name('admin.welcome');


// Authentication Middleware Group
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard Route
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Room Categories Route

    // List
    Route::get('/room-categories', function () {
        return view('admin.room-categories.view-room-categories');
    })->name('admin.room-categories');

    // Create
    Route::get('create/room-category', function () {
        return view('admin.room-categories.create-room-category');
    })->name('admin.create-room-category');

    // View
    Route::get('view/room-category/{roomCategory}', ViewRoomCategory::class)
        ->name('admin.view-room-category');

    // Edit
    Route::get('edit/room-category/{roomCategory}', EditRoomCategory::class)
        ->name('admin.edit-room-category');
});







// ----------------------------- GUEST PAGES ----------------------------------------- //


// ----------------------------- TENANT PAGES ----------------------------------------- //
