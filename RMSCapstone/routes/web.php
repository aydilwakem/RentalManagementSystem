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
use App\Livewire\Admin\Events\EditEvent;
use App\Livewire\Admin\Events\ViewEvent;
use App\Livewire\Admin\RoomRates\ViewRoomRate;
use App\Livewire\Admin\RoomRates\EditRoomRate;
use App\Livewire\Admin\Settings\Payments\EditPayment;
use App\Livewire\Admin\Settings\Payments\ViewPayment;
use App\Livewire\Admin\Users\ViewUser;
use App\Livewire\Admin\Users\EditUser;
use App\Livewire\Admin\Roles\ViewRole;
use App\Livewire\Admin\Roles\EditRole;
use App\Livewire\Admin\RoomRates\CreateIndividualRate;
use App\Livewire\Admin\RoomRates\EditIndividualRate;

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


    // Users Route

    // List
    Route::get('/manage-users', function () {
        return view('admin.users.view-users');
    })->name('admin.manage-users')->middleware('can:user-list');

    // Create
    Route::get('create/user', function () {
        return view('admin.users.create-user');
    })->name('admin.create-user')->middleware('can:user-create');

    // View
    Route::get('view/user/{user}', ViewUser::class)
        ->name('admin.view-user')->middleware('can:user-view');

    // Edit
    Route::get('edit/user/{user}', EditUser::class)
        ->name('admin.edit-user')->middleware('can:user-edit');



    // Roles Route

    // Create
    Route::get('create/role', function () {
        return view('admin.roles.create-role');
    })->name('admin.create-role')->middleware('can:role-create');

    // View
    Route::get('view/role/{role}', ViewRole::class)
        ->name('admin.view-role')->middleware('can:role-view');

    // Edit
    Route::get('edit/role/{role}', EditRole::class)
        ->name('admin.edit-role')->middleware('can:role-edit');




    // Rooms Route

    // List
    Route::get('/rooms', function () {
        return view('admin.rooms.view-rooms');
    })->name('admin.rooms')->middleware('can:room-list');

    // Create
    Route::get('create/room', function () {
        return view('admin.rooms.create-room');
    })->name('admin.create-room')->middleware('can:room-create');

    // View
    Route::get('view/room/{room}', ViewRoom::class)
        ->name('admin.view-room')->middleware('can:room-view');

    // Edit
    Route::get('edit/room/{room}', EditRoom::class)
        ->name('admin.edit-room')->middleware('can:room-edit');


    // Deleted Rooms (Soft Deletes)
    Route::get('deleted-rooms', function () {
        return view('admin.rooms.deleted-rooms');
    })->name('admin.deleted-rooms');



    // Room Categories Route

    // List
    Route::get('/room-categories', function () {
        return view('admin.room-categories.view-room-categories');
    })->name('admin.room-categories')->middleware('can:room-category-list');

    // Create
    Route::get('create/room-category', function () {
        return view('admin.room-categories.create-room-category');
    })->name('admin.create-room-category')->middleware('can:room-category-create');

    // View
    Route::get('view/room-category/{roomCategory}', ViewRoomCategory::class)
        ->name('admin.view-room-category')->middleware('can:room-category-view');

    // Edit
    Route::get('edit/room-category/{roomCategory}', EditRoomCategory::class)
        ->name('admin.edit-room-category')->middleware('can:room-category-edit');

    // Deleted Categories (Soft Deletes)
    Route::get('deleted-room-categories', function () {
        return view('admin.room-categories.deleted-room-categories');
    })->name('admin.deleted-room-categories');


    // Room Rates Route

    // List
    Route::get('/room-rates', function () {
        return view('admin.room-rates.view-room-rates');
    })->name('admin.room-rates')->middleware('can:room-rate-list');

    // Create
    Route::get('create/room-rate', function () {
        return view('admin.room-rates.create-room-rate');
    })->name('admin.create-room-rate')->middleware('can:room-rate-create');

    // View
    Route::get('view/room-rate/{roomRate}', ViewRoomRate::class)
        ->name('admin.view-room-rate')->middleware('can:room-rate-view');

    // Edit
    Route::get('edit/room-rate/{roomRate}', EditRoomRate::class)
        ->name('admin.edit-room-rate')->middleware('can:room-rate-edit');

     // Deleted Room Rate (Soft Deletes)
     Route::get('deleted-room-rates', function () {
        return view('admin.room-rates.deleted-room-rates');
    })->name('admin.deleted-room-rates');

    // Individual Room Rates Route

    // Create
    Route::get('/create/individual-room-rate/{roomId}', CreateIndividualRate::class)
        ->name('admin.create-individual-rate');

    // Edit
    Route::get('edit/individual-room-rate/{roomRate}', EditIndividualRate::class)
        ->name('admin.edit-individual-rate');





    // Amenities Route

    // List
    Route::get('/amenities', function () {
        return view('admin.amenities.view-amenities');
    })->name('admin.amenities')->middleware('can:amenity-list');

    // Create
    Route::get('create/amenities', function () {
        return view('admin.amenities.create-amenity');
    })->name('admin.create-amenity')->middleware('can:amenity-create');

    // View
    Route::get('view/amenity/{amenity}', ViewAmenity::class)
        ->name('admin.view-amenity')->middleware('can:amenity-view');

    // Edit
    Route::get('edit/amenity/{amenity}', EditAmenity::class)
        ->name('admin.edit-amenity')->middleware('can:amenity-edit');

    // Deleted Rooms (Soft Deletes)
    Route::get('deleted-amenitites', function () {
        return view('admin.amenities.deleted-amenities');
    })->name('admin.deleted-amenities');


    // Activities Route

    //List
    Route::get('/activities', function () {
        return view('admin.activities.view-activities');
    })->name('admin.activities')->middleware('can:activity-list');

    // Create
    Route::get('create/activity', function () {
        return view('admin.activities.create-activity');
    })->name('admin.create-activity')->middleware('can:activity-create');

    // View
    Route::get('view/activity/{activity}', ViewActivity::class)
        ->name('admin.view-activity')->middleware('can:activity-view');

    // Edit
    Route::get('edit/activity/{activity}', EditActivity::class)
        ->name('admin.edit-activity')->middleware('can:activity-edit');



    //Events

    // List
    Route::get('/events', function () {
        return view('admin.events.view-events');
    })->name('admin.events')->middleware('can:event-list');

    // Create
    Route::get('create/create-event', function () {
        return view('admin.events.create-event');
    })->name('admin.create-event')->middleware('can:event-create');

    // View
    Route::get('view/event/{event}', ViewEvent::class)
        ->name('admin.view-event')->middleware('can:event-view');

    // Edit
    Route::get('edit/event/{event}', EditEvent::class)
        ->name('admin.edit-event')->middleware('can:event-edit');



    // Event Categories Route

    //List
    Route::get('/event-categories', function () {
        return view('admin.event-categories.view-event-categories');
    })->name('admin.event-categories')->middleware('can:event-category-list');

    // Create
    Route::get('create/create-event-category', function () {
        return view('admin.event-categories.create-event-category');
    })->name('admin.create-event-category')->middleware('can:event-category-create');

    // View
    Route::get('view/event-category/{eventCategory}', ViewEventCategory::class)
        ->name('admin.view-event-category')->middleware('can:event-category-view');

    // Edit
    Route::get('edit/event-category/{eventCategory}', EditEventCategory::class)
        ->name('admin.edit-event-category')->middleware('can:event-category-edit');





    // Event Halls

    //List
    Route::get('/event-halls', function () {
        return view('admin.event-halls.view-event-halls');
    })->name('admin.event-halls')->middleware('can:event-hall-list');

    //Create
    Route::get('/create/create-event-hall', function () {
        return view('admin.event-halls.create-event-hall');
    })->name('admin.create-event-hall')->middleware('can:event-hall-create');

    // Edit
    Route::get('edit/event-hall/{eventHall}', EditEventHall::class)
        ->name('admin.edit-event-hall')->middleware('can:event-hall-edit');

    // View
    Route::get('view/event-hall/{eventHall}', ViewEventHall::class)
        ->name('admin.view-event-hall')->middleware('can:event-hall-view');




    // Maintenance

    //List
    Route::get('/maintenance', function () {
        return view('admin.maintenance.view-maintenances');
    })->name('admin.maintenances')->middleware('can:maintenance-list');

    //Create
    Route::get('/create/create-maintenance', function () {
        return view('admin.maintenance.create-maintenance');
    })->name('admin.create-maintenance')->middleware('can:maintenance-create');

    // Edit
    Route::get('edit/maintenance/{maintenance}', EditMaintenance::class)
        ->name('admin.edit-maintenance')->middleware('can:maintenance-edit');

    // View
    Route::get('view/maintenance/{maintenance}', ViewMaintenance::class)
        ->name('admin.view-maintenance')->middleware('can:maintenance-view');


    // Settings

    //Payment Methods

    //List
    Route::get('/settings/payment-methods', function () {
        return view('admin.settings.payments.view-payments');
    })->name('admin.payments')->middleware('can:payment-method-list');

    //Create
    Route::get('/settings/create/payment-method', function () {
        return view('admin.settings.payments.create-payment');
    })->name('admin.create-payment')->middleware('can:payment-method-create');

    // Edit
    Route::get('settings/edit/payment-method/{paymentMethod}', EditPayment::class)
        ->name('admin.edit-payment')->middleware('can:payment-method-edit');

    // View
    Route::get('settings/view/payment-method/{paymentMethod}', ViewPayment::class)
        ->name('admin.view-payment')->middleware('can:payment-method-view');
});










// ----------------------------- GUEST PAGES ----------------------------------------- //


// ----------------------------- TENANT PAGES ----------------------------------------- //
