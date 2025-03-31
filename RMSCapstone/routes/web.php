<?php

use App\Livewire\Admin\EventCategories\EditEventCategory;
use App\Livewire\Admin\EventCategories\ViewEventCategory;
use App\Livewire\Admin\EventHalls\EditEventHall;
use App\Livewire\Admin\EventHalls\ViewEventHall;
use App\Livewire\Admin\Maintenance\EditMaintenance;
use App\Livewire\Admin\Maintenance\ViewMaintenance;
use App\Mail\ConfirmationEmail;
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
use App\Livewire\Admin\Transactions\NewTransaction\ViewTransaction;
use App\Livewire\Admin\Transactions\NewTransaction\EditTransaction;
use App\Livewire\Admin\Properties\EditProperty;
use App\Livewire\Admin\Properties\ViewProperty;
use App\Livewire\Admin\HouseCategories\EditHouseCategory;
use App\Livewire\Admin\HouseCategories\ViewHouseCategory;
use App\Livewire\Admin\Tenants\EditTenant;
use App\Livewire\Admin\Tenants\ViewTenant;
use Illuminate\Support\Facades\Mail;

// ----------------------------- ADMIN PAGES ----------------------------------------- //

// Welcome page
Route::get('/', function () {
    return redirect()->route('login'); // index file
})->name('admin.welcome');


// Authentication Middleware Group
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard Route
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/no-access', function () {
        return view('admin.no-access');
    })->name('no-access');

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

    // Deleted Activities (Soft Deletes)
    Route::get('deleted-activities', function () {
        return view('admin.activities.deleted-activities');
    })->name('admin.deleted-activities');


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

    // Deleted Events (Soft Deletes)
    Route::get('deleted-events', function () {
        return view('admin.events.deleted-events');
    })->name('admin.deleted-events');


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

    // Deleted Event Categories (Soft Deletes)
    Route::get('deleted-event-categories', function () {
        return view('admin.event-categories.deleted-event-categories');
    })->name('admin.deleted-event-categories');



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

    // Deleted Event Halls (Soft Deletes)
    Route::get('deleted-event-halls', function () {
        return view('admin.event-halls.deleted-event-halls');
    })->name('admin.deleted-event-halls');



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

    // Deleted Maintenances (Soft Deletes)
    Route::get('deleted-maintenances', function () {
        return view('admin.maintenance.deleted-maintenances');
    })->name('admin.deleted-maintenances');


    // Settings

    //Appearance
    Route::get('/settings/appearance', function () {
        return view('admin.settings.appearance.view-appearance');
    })->name('admin.appearance');

    // Branding
    Route::get('/settings/branding', function () {
        return view('admin.settings.branding.view-branding');
    })->name('admin.branding');

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

    // Deleted Payments (Soft Deletes)
    Route::get('deleted-payments', function () {
        return view('admin.settings.payments.deleted-payments');
    })->name('admin.deleted-payments');

    /**
     * Reservations
     */

    // New Reservations

    Route::get('/new-reservations', function () {
        return view('admin.transactions.new.view-transactions');
    })->name('admin.view-new-transactions')->middleware('can:new-reservation-list');

    //Create
    Route::get('create/new-reservation', function () {
        return view('admin.transactions.new.create-transaction');
    })->name('admin.create-new-transaction')->middleware('can:new-reservation-create');

    // View
    Route::get('view/new-reservation/{transaction}', ViewTransaction::class)
        ->name('admin.view-new-transaction')->middleware('can:new-reservation-view');

    // Edit
    Route::get('edit/new-reservation/{transaction}', EditTransaction::class)
        ->name('admin.edit-new-transaction')->middleware('can:new-reservation-edit');

    // Deleted New Transactions (Soft Deletes)
    Route::get('/deleted-new-reservations', function () {
        return view('admin.transactions.new.deleted-new-transactions');
    })->name('admin.deleted-new-transactions')->middleware('can:new-reservation-soft-delete');


    // Confirmed Reservations
    Route::get('/confirmed-reservations', function () {
        return view('admin.transactions.confirmed.view-transactions');
    })->name('admin.view-confirmed-transactions')->middleware('can:confirmed-reservation-list');

    // On-going Bookings
    Route::get('/on-going-bookings', function () {
        return view('admin.transactions.ongoing.view-transactions');
    })->name('admin.view-ongoing-transactions')->middleware('can:on-going-booking-list');

    // Old bookings
    Route::get('/old-bookings', function () {
        return view('admin.transactions.old.view-transactions');
    })->name('admin.view-old-transactions')->middleware('can:old-booking-list');



    /***
     * These routes are for Long-Term Rentals.
     *
     * Route list:
     * - Houses
     * - Tenants
     * - House Categories
     * - Payments
     * - Invoices
     */


    // ----------------- Houses

    // List
    Route::get('/properties', function () {
        return view('admin.rentals.properties.view-properties');
    })->name('admin.properties')->middleware('can:house-list');

    // Create
    Route::get('create/property', function () {
        return view('admin.rentals.properties.create-property');
    })->name('admin.create-property')->middleware('can:house-create');

    // View
    Route::get('view/property/{property}', ViewProperty::class)
        ->name('admin.view-property')->middleware('can:house-view');

    // Edit
    Route::get('edit/property/{property}', EditProperty::class)
        ->name('admin.edit-property')->middleware('can:house-edit');

    // Deleted Houses (Soft Deletes)
    Route::get('deleted-houses', function () {
        return view('admin.rentals.properties.deleted-properties');
    })->name('admin.deleted-properties')->middleware('can:house-soft-delete');




    // ------------------ House Categories

    // List
    Route::get('/house-categories', function () {
        return view('admin.rentals.house-categories.view-house-categories');
    })->name('admin.house-categories')->middleware('can:house-category-list');

    // Create
    Route::get('create/house-category', function () {
        return view('admin.rentals.house-categories.create-house-category');
    })->name('admin.create-house-category')->middleware('can:house-category-create');

    // View
    Route::get('view/house-category/{houseCategory}', ViewHouseCategory::class)
        ->name('admin.view-house-category')->middleware('can:house-category-view');

    // Edit
    Route::get('edit/house-category/{houseCategory}', EditHouseCategory::class)
        ->name('admin.edit-house-category')->middleware('can:house-category-edit');

    // Deleted House Categories (Soft Deletes)
    Route::get('deleted-house-categories', function () {
        return view('admin.rentals.house-categories.deleted-house-categories');
    })->name('admin.deleted-house-categories')->middleware('can:house-category-soft-delete');



    // ------------------ Tenants

    // List
    Route::get('/tenants', function () {
        return view('admin.rentals.tenants.view-tenants');
    })->name('admin.tenants')->middleware('can:tenant-list');

    // Create
    Route::get('create/tenant', function () {
        return view('admin.rentals.tenants.create-tenant');
    })->name('admin.create-tenant')->middleware('can:tenant-create');

    // View
    Route::get('view/tenant/{tenant}', ViewTenant::class)
        ->name('admin.view-tenant')->middleware('can:tenant-view');

    // Edit
    Route::get('edit/tenant/{tenant}', EditTenant::class)
        ->name('admin.edit-tenant')->middleware('can:tenant-edit');

    // Deleted Tenants (Soft Deletes)
    Route::get('deleted-tenants', function () {
        return view('admin.rentals.tenants.deleted-tenants');
    })->name('admin.deleted-tenants')->middleware('can:tenant-soft-delete');
});


Route::get('email', function(){
    Mail::to('arasdump@gmail.com')->send(new ConfirmationEmail());
});






// ----------------------------- GUEST PAGES ----------------------------------------- //


// ----------------------------- TENANT PAGES ----------------------------------------- //
