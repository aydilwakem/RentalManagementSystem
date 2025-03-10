<?php

use App\Livewire\Admin\EventCategories\EditEventCategory;
use App\Livewire\Admin\EventCategories\ViewEventCategory;
use App\Livewire\Admin\EventHalls\EditEventHall;
use App\Livewire\Admin\EventHalls\ViewEventHall;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\RoomCategories\ViewRoomCategory;
use App\Livewire\Admin\RoomCategories\EditRoomCategory;
use App\Livewire\Admin\Rooms\EditRoom;
use App\Livewire\Admin\Rooms\ViewRoom;




// ----------------------------- ADMIN PAGES ----------------------------------------- //

// Welcome page
Route::get('/', function () {
    return view('admin.welcome');
});


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

    // Rooms Route

    // List
    Route::get('/rooms', function () {
        return view('admin.rooms.view-rooms');
    })->name('admin.rooms');

    // Create
    Route::get('create/room', function () {
        return view('admin.rooms.create-room');
    })->name('admin.create-room');

    // View
    Route::get('view/room/{room}', ViewRoom::class)
        ->name('admin.view-room');

    // Edit
    Route::get('edit/room/{room}', EditRoom::class)
        ->name('admin.edit-room');





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


    // Event Categories Route

    //List
    Route::get('/event-categories', function () {
        return view('admin.event-categories.view-event-categories');
    })->name('admin.event-categories');

    // Create
    Route::get('create/create-event-category', function () {
        return view('admin.event-categories.create-event-category');
    })->name('admin.create-event-category');

    // View
    Route::get('view/event-category/{eventCategory}', ViewEventCategory::class)
        ->name('admin.view-event-category');

    // Edit
    Route::get('edit/event-category/{eventCategory}', EditEventCategory::class)
        ->name('admin.edit-event-category');


    // Event Halls

    //List
    Route::get('/event-halls', function () {
        return view('admin.event-halls.view-event-halls');
    })->name('admin.event-halls');

    //Create
    Route::get('/create/create-event-hall', function () {
        return view('admin.event-halls.create-event-hall');
    })->name('admin.create-event-hall');

    // Edit
    Route::get('edit/event-hall/{eventHall}', EditEventHall::class)
        ->name('admin.edit-event-hall');

    // View
    Route::get('view/event-hall/{eventHall}', ViewEventHall::class)
        ->name('admin.view-event-hall');
});

    
    
    



// ----------------------------- GUEST PAGES ----------------------------------------- //


// ----------------------------- TENANT PAGES ----------------------------------------- //
