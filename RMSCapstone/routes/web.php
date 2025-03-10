<?php

use App\Livewire\Admin\EventCategories\EditEventCategory;
use App\Livewire\Admin\EventCategories\ViewEventCategory;
use App\Livewire\Admin\EventHalls\EditEventHall;
use App\Livewire\Admin\EventHalls\ViewEventHall;
use App\Livewire\Admin\Maintenance\EditMaintenance;
use App\Livewire\Admin\Maintenance\ViewMaintenance;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\RoomCategories\ViewRoomCategory;
use App\Livewire\Admin\RoomCategories\EditRoomCategory;
use App\Livewire\Admin\Rooms\EditRoom;
use App\Livewire\Admin\Rooms\ViewRoom;
use App\Livewire\Admin\Activities\ViewActivity;
use App\Livewire\Admin\Activities\EditActivity;
use App\Livewire\Admin\Amenities\ViewAmenity;
use App\Livewire\Admin\Amenities\EditAmenity;
use App\Livewire\Admin\RoomRates\ViewRoomRate;
use App\Livewire\Admin\RoomRates\EditRoomRate;




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


    // Room Rates Route

    // List
    Route::get('/room-rates', function () {
        return view('admin.room-rates.view-room-rates');
    })->name('admin.room-rates');

    // Create
    Route::get('create/room-rate', function () {
        return view('admin.room-rates.create-room-rate');
    })->name('admin.create-room-rate');

    // View
    Route::get('view/room-rate/{roomRate}', ViewRoomRate::class)
        ->name('admin.view-room-rate');

    // Edit
    Route::get('edit/room-rate/{roomRate}', EditRoomRate::class)
        ->name('admin.edit-room-rate');



    // Amenities Route

    // List
    Route::get('/amenities', function () {
        return view('admin.amenities.view-amenities');
    })->name('admin.amenities');

    // Create
    Route::get('create/amenities', function () {
        return view('admin.amenities.create-amenity');
    })->name('admin.create-amenity');

    // View
    Route::get('view/amenity/{amenity}', ViewAmenity::class)
        ->name('admin.view-amenity');

    // Edit
    Route::get('edit/amenity/{amenity}', EditAmenity::class)
        ->name('admin.edit-amenity');




    // Activities Route

    //List
    Route::get('/activities', function () {
        return view('admin.activities.view-activities');
    })->name('admin.activities');

    // Create
    Route::get('create/activity', function () {
        return view('admin.activities.create-activity');
    })->name('admin.create-activity');

    // View
    Route::get('view/activity/{activity}', ViewActivity::class)
        ->name('admin.view-activity');

    // Edit
    Route::get('edit/activity/{activity}', EditActivity::class)
        ->name('admin.edit-activity');





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




    // Maintenance

    //List
    Route::get('/maintenance', function () {
        return view('admin.maintenance.view-maintenances');
    })->name('admin.maintenances');

    //Create
    Route::get('/create/create-maintenance', function () {
        return view('admin.maintenance.create-maintenance');
    })->name('admin.create-maintenance');

    // Edit
    Route::get('edit/maintenance/{maintenance}', EditMaintenance::class)
        ->name('admin.edit-maintenance');

    // View
    Route::get('view/maintenance/{maintenance}', ViewMaintenance::class)
        ->name('admin.view-maintenance');
});

    
    
    



// ----------------------------- GUEST PAGES ----------------------------------------- //


// ----------------------------- TENANT PAGES ----------------------------------------- //
