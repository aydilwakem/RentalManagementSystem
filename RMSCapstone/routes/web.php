<?php

use App\Http\Controllers\PDFController;
use App\Http\Controllers\PaymentController;
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
use App\Livewire\Admin\Backups\CreateBackup;
use App\Livewire\Admin\Backups\ViewBackups;
use App\Livewire\Admin\DayTourRates\CreateDayTourRate;
use App\Livewire\Admin\DayTourRates\DeletedDayTourRates;
use App\Livewire\Admin\DayTourRates\EditDayTourRate;
use App\Livewire\Admin\DayTourRates\ViewDayTourRate;
use App\Livewire\Admin\DayTourRates\ViewDayTourRates;
use App\Livewire\Admin\DayTours\CreateDayTour;
use App\Livewire\Admin\DayTours\DeletedDayTours;
use App\Livewire\Admin\DayTours\EditDayTour;
use App\Livewire\Admin\DayTours\ViewDayTour;
use App\Livewire\Admin\DayTours\ViewDayTours;
use App\Livewire\Admin\Events\ArchiveEvents;
use App\Livewire\Admin\Events\EditEvent;
use App\Livewire\Admin\Events\ViewEvent;
use App\Livewire\Admin\Features\EditFeature;
use App\Livewire\Admin\Features\ViewFeature;
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
use App\Livewire\Admin\Inclusions\EditInclusion;
use App\Livewire\Admin\Inclusions\ViewInclusion;
use App\Livewire\Admin\Properties\Leases\EditLease;
use App\Livewire\Admin\Properties\Leases\ViewLease;
use App\Livewire\Admin\Reports\ReservationReports;
use App\Livewire\Admin\Tenants\EditTenant;
use App\Livewire\Admin\Tenants\ViewTenant;
use App\Livewire\Admin\Reservations\Payments\ViewReceipt;
use App\Livewire\Admin\Reservations\ViewReservation;
use App\Livewire\Admin\Reservations\EditReservation;
use App\Livewire\Admin\Reservations\AddTransaction;
use App\Livewire\Admin\Reservations\ArchiveDayTours;
use App\Livewire\Admin\Reservations\ArchiveReservations;
use App\Livewire\Admin\Reservations\CreateDayTourReservation;
use App\Livewire\Admin\Reservations\DayTourReservationList;
use App\Livewire\Admin\Reservations\RebookReservation;
use App\Livewire\Admin\Reservations\ViewDaytourReservation;
use App\Livewire\Admin\Services\EditService;
use App\Livewire\Admin\Services\ViewService;
use App\Livewire\Admin\Settings\PromoCodes\EditPromoCode;
use App\Livewire\Admin\Settings\PromoCodes\ViewPromoCode;
use App\Livewire\Guest\Reservation\DayTourReservationForm;
use App\Livewire\Guest\Reservation\ReservationForm;
use App\Mail\DayTouReservationSubmittedMail;
use App\Mail\EventQuotesMail;
use App\Mail\PaymentUploadedMail;
use App\Mail\ReceiptRejectedMail;
use App\Mail\ReservationCompletedMail;
use App\Mail\ReservationConfirmedMail;
use App\Mail\ReservationSubmittedMail;
use App\Mail\SendOfficialReceiptMail;
use App\Models\Transaction;
use App\Models\PromoCode;
use App\Models\Receipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity as LogActivity;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;

// ----------------------------- ADMIN PAGES ----------------------------------------- //

// Welcome page
// Redirect root to guest homepage
Route::get('/', function () {
    return redirect('/guest/homepage');
});

/*
|--------------------------------------------------------------------------
| Custom Login Route
|--------------------------------------------------------------------------
*/
Route::get('/' . env('LOGIN_URI', 'l06iN-4k9v7BqP2zX'), [AuthenticatedSessionController::class, 'create'])
    ->middleware(['guest'])
    ->name('login');

Route::post('/' . env('LOGIN_URI', 'l06iN-4k9v7BqP2zX'), [AuthenticatedSessionController::class, 'store'])->middleware(['guest']);

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware(['auth'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Custom Sign Up Route
|--------------------------------------------------------------------------
*/
Route::get('/' . env('REGISTER_URI', 'R3g1st3r9xV2'), [RegisteredUserController::class, 'create'])
    ->middleware(['guest'])
    ->name('register');

Route::post('/' . env('REGISTER_URI', 'R3g1st3r9xV2'), [RegisteredUserController::class, 'store'])->middleware(['guest']);

/*
|--------------------------------------------------------------------------
| Custom Two-Factor Authentication Route
|--------------------------------------------------------------------------
*/
Route::get('/' . env('TWO_FACTOR_URI', 't2FaC-8mX2zV4r9L1k'), [TwoFactorAuthenticatedSessionController::class, 'create'])
    ->middleware(['guest'])
    ->name('two-factor.login');

Route::post('/' . env('TWO_FACTOR_URI', 't2FaC-8mX2zV4r9L1k'), [TwoFactorAuthenticatedSessionController::class, 'store'])->middleware(['guest']);

/*
|--------------------------------------------------------------------------
| Custom Forgot Password + Reset PAssword Routes
|--------------------------------------------------------------------------
*/
Route::get('/' . env('FORGOT_PASSWORD_URI', 'f0rG07-2xV7k2P6zQ3m'), [PasswordResetLinkController::class, 'create'])
    ->middleware(['guest'])
    ->name('password.request');

Route::post('/' . env('FORGOT_PASSWORD_URI', 'f0rG07-2xV7k2P6zQ3m'), [PasswordResetLinkController::class, 'store'])
    ->middleware(['guest'])
    ->name('password.email');

Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
    ->middleware(['guest'])
    ->name('password.reset');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware(['guest'])
    ->name('password.update');

// Admin welcome page (kept for other references)
Route::get('/admin', function () {
    return redirect()->route('login'); // index file
})->name('admin.welcome');

// Authentication Middleware Group
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(
    callback: function () {
        // Dashboard Route
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::get('/no-access', function () {
            return view('admin.no-access');
        })->name('no-access');

        // Users Route

        // List
        Route::get('/manage-users', function () {
            return view('admin.users.view-users');
        })
            ->name('admin.manage-users')
            ->middleware('can:user-list');

        // Create
        Route::get('create/user', function () {
            return view('admin.users.create-user');
        })
            ->name('admin.create-user')
            ->middleware('can:user-create');

        // View
        Route::get('view/user/{user}', ViewUser::class)->name('admin.view-user')->middleware('can:user-view');

        // View
        Route::get('edit/user/{user}', EditUser::class)->name('admin.edit-user')->middleware('can:user-edit');

        // Deleted Users (Soft Deletes)
        Route::get('deleted-users', function () {
            return view('admin.users.deleted-users');
        })->name('admin.deleted-users');

        // Roles Route

        // Create
        Route::get('create/role', function () {
            return view('admin.roles.create-role');
        })
            ->name('admin.create-role')
            ->middleware('can:role-create');

        // View
        Route::get('view/role/{role}', ViewRole::class)->name('admin.view-role')->middleware('can:role-view');

        // Edit
        Route::get('edit/role/{role}', EditRole::class)->name('admin.edit-role')->middleware('can:role-edit');

        // Rooms Route

        // List
        Route::get('/rooms', function () {
            return view('admin.rooms.view-rooms');
        })
            ->name('admin.rooms')
            ->middleware('can:room-list');

        // Create
        Route::get('create/room', function () {
            return view('admin.rooms.create-room');
        })
            ->name('admin.create-room')
            ->middleware('can:room-create');

        // View
        Route::get('view/room/{room}', ViewRoom::class)->name('admin.view-room');

        // Edit
        Route::get('edit/room/{room}', EditRoom::class)->name('admin.edit-room')->middleware('can:room-edit');

        // Deleted Rooms (Soft Deletes)
        Route::get('deleted-rooms', function () {
            return view('admin.rooms.deleted-rooms');
        })
            ->name('admin.deleted-rooms')
            ->middleware('can:room-soft-delete');

        //Room Categories
        // List
        Route::get('/room-categories', function () {
            return view('admin.room-categories.view-room-categories');
        })
            ->name('admin.room-categories')
            ->middleware('can:room-category-list');

        // Create
        Route::get('create/room-category', function () {
            return view('admin.room-categories.create-room-category');
        })
            ->name('admin.create-room-category')
            ->middleware('can:room-category-create');

        // View
        Route::get('view/room-category/{roomCategory}', ViewRoomCategory::class)->name('admin.view-room-category')->middleware('can:room-category-view');

        // Edit
        Route::get('edit/room-category/{roomCategory}', EditRoomCategory::class)->name('admin.edit-room-category')->middleware('can:room-category-edit');

        // Deleted Categories (Soft Deletes)
        Route::get('deleted-room-categories', function () {
            return view('admin.room-categories.deleted-room-categories');
        })
            ->name('admin.deleted-room-categories')
            ->middleware('can:room-category-soft-delete');

        // Room Rates Route

        // List
        Route::get('/room-rates', function () {
            return view('admin.room-rates.view-room-rates');
        })
            ->name('admin.room-rates')
            ->middleware('can:room-rate-list');

        // Create
        Route::get('create/room-rate', function () {
            return view('admin.room-rates.create-room-rate');
        })
            ->name('admin.create-room-rate')
            ->middleware('can:room-rate-create');

        // View
        Route::get('view/room-rate/{roomRate}', ViewRoomRate::class)->name('admin.view-room-rate')->middleware('can:room-rate-view');

        // Edit
        Route::get('edit/room-rate/{roomRate}', EditRoomRate::class)->name('admin.edit-room-rate')->middleware('can:room-rate-edit');

        // Deleted Room Rate (Soft Deletes)
        Route::get('deleted-room-rates', function () {
            return view('admin.room-rates.deleted-room-rates');
        })
            ->name('admin.deleted-room-rates')
            ->middleware('can:room-rate-soft-delete');

        // Individual Room Rates Route

        // Create
        Route::get('/create/individual-room-rate/{roomId}', CreateIndividualRate::class)->name('admin.create-individual-rate')->middleware('can:individual-room-rate-create');

        // Edit
        Route::get('edit/individual-room-rate/{roomRate}', EditIndividualRate::class)->name('admin.edit-individual-rate')->middleware('can:individual-room-rate-edit');

        // Amenities Route

        // List
        Route::get('/amenities', function () {
            return view('admin.amenities.view-amenities');
        })
            ->name('admin.amenities')
            ->middleware('can:amenity-list');

        // Create
        Route::get('create/amenities', function () {
            return view('admin.amenities.create-amenity');
        })
            ->name('admin.create-amenity')
            ->middleware('can:amenity-create');

        // View
        Route::get('view/amenity/{amenity}', ViewAmenity::class)->name('admin.view-amenity')->middleware('can:amenity-view');

        // Edit
        Route::get('edit/amenity/{amenity}', EditAmenity::class)->name('admin.edit-amenity')->middleware('can:amenity-edit');

        // Deleted Rooms (Soft Deletes)
        Route::get('deleted-amenitites', function () {
            return view('admin.amenities.deleted-amenities');
        })
            ->name('admin.deleted-amenities')
            ->middleware('can:amenity-soft-delete');

        // Activities Route

        //List
        Route::get('/activities', function () {
            return view('admin.activities.view-activities');
        })
            ->name('admin.activities')
            ->middleware('can:activity-list');

        // Create
        Route::get('create/activity', function () {
            return view('admin.activities.create-activity');
        })
            ->name('admin.create-activity')
            ->middleware('can:activity-create');

        // View
        Route::get('view/activity/{activity}', ViewActivity::class)->name('admin.view-activity')->middleware('can:activity-view');

        // Edit
        Route::get('edit/activity/{activity}', EditActivity::class)->name('admin.edit-activity')->middleware('can:activity-edit');

        // Deleted Activities (Soft Deletes)
        Route::get('deleted-activities', function () {
            return view('admin.activities.deleted-activities');
        })
            ->name('admin.deleted-activities')
            ->middleware('can:activity-soft-delete');

        // ----------------------------- Services ------------------------------ //
        Route::get('/services', function () {
            return view('admin.services.view-services');
        })
            ->name('admin.services')
            ->middleware('can:service-list');

        // Create
        Route::get('create/create-service', function () {
            return view('admin.services.create-service');
        })->name('admin.create-service');

        //Edit
        Route::get('edit/service/{service}', EditService::class)->name('admin.edit-service');

        // View
        Route::get('view/service/{service}', ViewService::class)->name('admin.view-service');

        // Deleted Services (Soft Deletes)
        Route::get('deleted-services', function () {
            return view('admin.services.deleted-services');
        })->name('admin.deleted-services');

        // ----------------------------- Events ------------------------------ //

        // List
        Route::get('/events', function () {
            return view('admin.events.view-events');
        })
            ->name('admin.events')
            ->middleware('can:event-list');

        // Create
        Route::get('create/create-event', function () {
            return view('admin.events.create-event');
        })
            ->name('admin.create-event')
            ->middleware('can:event-create');

        // View
        Route::get('view/event/{event}', ViewEvent::class)->name('admin.view-event')->middleware('can:event-view');

        // Edit
        Route::get('edit/event/{event}', EditEvent::class)->name('admin.edit-event')->middleware('can:event-edit');

        // Deleted Events (Soft Deletes)
        Route::get('deleted-events', function () {
            return view('admin.events.deleted-events');
        })
            ->name('admin.deleted-events')
            ->middleware('can:event-soft-delete');


        Route::get('/events/archives', ArchiveEvents::class)
        ->name('admin.events-archives')
        ->middleware(['auth', 'verified']);


        // Events Summary
        Route::get('/event-reports', function () {
            return view('admin.reports.event-reports');
        })
            ->name('admin.event-reports')
            ->middleware('can:event-reports');

        //--------------------------RESERVATION REPORTS PAGE------------------ //
        Route::get('/reservation-reports', function () {
            return view('admin.reports.reservation-reports');
        })
            ->name('admin.reservation-reports')
            ->middleware('can:reservation-reports');

        // Event Categories Route

        //List
        Route::get('/event-categories', function () {
            return view('admin.event-categories.view-event-categories');
        })
            ->name('admin.event-categories')
            ->middleware('can:event-category-list');

        // Create
        Route::get('create/create-event-category', function () {
            return view('admin.event-categories.create-event-category');
        })
            ->name('admin.create-event-category')
            ->middleware('can:event-category-create');

        // View
        Route::get('view/event-category/{eventCategory}', ViewEventCategory::class)->name('admin.view-event-category')->middleware('can:event-category-view');

        // Edit
        Route::get('edit/event-category/{eventCategory}', EditEventCategory::class)->name('admin.edit-event-category')->middleware('can:event-category-edit');

        // Deleted Event Categories (Soft Deletes)
        Route::get('deleted-event-categories', function () {
            return view('admin.event-categories.deleted-event-categories');
        })
            ->name('admin.deleted-event-categories')
            ->middleware('can:event-category-soft-delete');

        // Event Halls

        //List
        Route::get('/event-halls', function () {
            return view('admin.event-halls.view-event-halls');
        })
            ->name('admin.event-halls')
            ->middleware('can:event-hall-list');

        //Create
        Route::get('/create-event-hall', function () {
            return view('admin.event-halls.create-event-hall');
        })
            ->name('admin.create-event-hall')
            ->middleware('can:event-hall-create');

        // Edit
        Route::get('edit/event-hall/{eventHall}', EditEventHall::class)->name('admin.edit-event-hall')->middleware('can:event-hall-edit');

        // View
        Route::get('view/event-hall/{eventHall}', ViewEventHall::class)->name('admin.view-event-hall')->middleware('can:event-hall-view');

        // Deleted Event Halls (Soft Deletes)
        Route::get('deleted-event-halls', function () {
            return view('admin.event-halls.deleted-event-halls');
        })
            ->name('admin.deleted-event-halls')
            ->middleware('can:event-hall-soft-delete');

        // ------------------ Event Hall Inclusions

        //List Inclusions
        Route::get('/inclusions', function () {
            return view('admin.inclusions.view-inclusions');
        })
            ->name('admin.inclusions')
            ->middleware('can:event-inclusions-list');

        // Create Inclusion
        Route::get('create/inclusions', function () {
            return view('admin.inclusions.create-inclusion');
        })
            ->name('admin.create-inclusion')
            ->middleware('can:event-inclusions-create');

        // View Inclusion
        Route::get('view/inclusion/{inclusion}', ViewInclusion::class)->name('admin.view-inclusion')->middleware('can:event-inclusions-view');

        //Edit Inclusions
        Route::get('edit/inclusion/{inclusion}', EditInclusion::class)->name('admin.edit-inclusion')->middleware('can:event-inclusions-edit');

        // Deleted Inclusions (Soft Deletes)
        Route::get('deleted-inclusions', function () {
            return view('admin.inclusions.deleted-inclusions');
        })
            ->name('admin.deleted-inclusions')
            ->middleware('can:event-inclusions-soft-delete');

        // --------------------- Day Tours ---------------------------------------
        // Route::get('/day-tours', ViewDayTours::class)->name('admin.day-tours')->middleware('can:daytour-list');
        Route::get('/day-tours', function () {
            return view('admin.day-tour.view-day-tours');
        })
            ->name('admin.day-tours')
            ->middleware('can:daytour-list');

        Route::get('create/day-tour', CreateDayTour::class)->name('admin.create-day-tour')->middleware('can:daytour-create');

        Route::get('view/day-tour/{dayTour}', ViewDayTour::class)->name('admin.view-day-tour')->middleware('can:daytour-view');

        Route::get('edit/day-tour/{dayTour}', EditDayTour::class)->name('admin.edit-day-tour')->middleware('can:daytour-edit');

        Route::get('deleted-day-tours', DeletedDayTours::class)->name('admin.deleted-day-tours')->middleware('can:daytour-soft-delete');

        // --------------------- Day Tour Rates ---------------------------------------
        // Route::get('/day-tour-rates', ViewDayTourRates::class)->name('admin.day-tour-rates')->middleware('can:daytourrate-list');
        Route::get('/day-tour-rates', function () {
            return view('admin.day-tour.view-daytour-rates');
        })
            ->name('admin.day-tour-rates')
            ->middleware('can:daytourrate-list');


        Route::get('create/day-tour-rate', CreateDayTourRate::class)->name('admin.create-day-tour-rate')->middleware('can:daytourrate-create');

        Route::get('view/day-tour-rate/{dayTourRate}', ViewDayTourRate::class)->name('admin.view-day-tour-rate')->middleware('can:daytourrate-view');

        Route::get('edit/day-tour-rate/{dayTourRate}', EditDayTourRate::class)->name('admin.edit-day-tour-rate')->middleware('can:daytourrate-edit');

        Route::get('deleted-day-tour-rates', DeletedDayTourRates::class)->name('admin.deleted-day-tour-rates')->middleware('can:daytourrate-soft-delete');


        //Daytour Reports
        Route::get('/daytour-reports', function () {
            return view('admin.reports.daytour-reports');
        })->name('admin.daytour-reports');

        // --------------------- Maintenance ---------------------------------------

        // New Maintenance

        //List
        Route::get('/maintenance', function () {
            return view('admin.maintenance.view-maintenances');
        })
            ->name('admin.maintenances')
            ->middleware('can:maintenance-list');

        //Create
        Route::get('/create/create-maintenance', function () {
            return view('admin.maintenance.create-maintenance');
        })
            ->name('admin.create-maintenance')
            ->middleware('can:maintenance-create');

        // Edit
        Route::get('edit/maintenance/{maintenance}', EditMaintenance::class)->name('admin.edit-maintenance')->middleware('can:maintenance-edit');

        // View
        Route::get('view/maintenance/{maintenance}', ViewMaintenance::class)->name('admin.view-maintenance')->middleware('can:maintenance-view');

        // Deleted Maintenances (Soft Deletes)
        Route::get('deleted-maintenances', function () {
            return view('admin.maintenance.deleted-maintenances');
        })
            ->name('admin.deleted-maintenances')
            ->middleware('can:maintenance-soft-delete');

        // Old Maintenance
        Route::get('/old-maintenances', function () {
            return view('admin.maintenance.old-maintenances');
        })->name('admin.old-maintenances');

        // Settings

        //Appearance
        Route::get('/settings/appearance', function () {
            return view('admin.settings.appearance.view-appearance');
        })->name('admin.appearance');

        // Branding
        Route::get('/settings/branding', function () {
            return view('admin.settings.branding.view-branding');
        })
            ->name('admin.branding')
            ->middleware('can:branding-view');

        //Payment Methods

        //List
        Route::get('/settings/payment-methods', function () {
            return view('admin.settings.payments.view-payments');
        })
            ->name('admin.payments')
            ->middleware('can:payment-method-list');

        //Create
        Route::get('/settings/create/payment-method', function () {
            return view('admin.settings.payments.create-payment');
        })
            ->name('admin.create-payment')
            ->middleware('can:payment-method-create');

        // Edit
        Route::get('settings/edit/payment-method/{paymentMethod}', EditPayment::class)->name('admin.edit-payment')->middleware('can:payment-method-edit');

        // View
        Route::get('settings/view/payment-method/{paymentMethod}', ViewPayment::class)->name('admin.view-payment')->middleware('can:payment-method-view');

        // Deleted Payments (Soft Deletes)
        Route::get('deleted-payments', function () {
            return view('admin.settings.payments.deleted-payments');
        })
            ->name('admin.deleted-payments')
            ->middleware('can:payment-method-soft-delete');

        //Promo Codes
        //List
        Route::get('/reservations/promo-codes', function () {
            return view('admin.settings.promo-codes.view-promo-codes');
        })
            ->name('admin.view-promo-codes')
            ->middleware('can:promo-code-list');

        //Create
        Route::get('/reservations/create/promo-code', function () {
            return view('admin.settings.promo-codes.create-promo-code');
        })
            ->name('admin.create-promo-code')
            ->can('promo-code-create');

        //View
        Route::get('reservations/view/promo-code/{promoCode}', ViewPromoCode::class)->name('admin.view-promo-code')->middleware('can:promo-code-view');

        //Edit
        Route::get('reservations/edit/promo-code/{promoCode}', EditPromoCode::class)->name('admin.edit-promo-code')->middleware('can:promo-code-edit');

        //Soft Deletes
        Route::get('deleted-promo-codes', function () {
            return view('admin.settings.promo-codes.deleted-promo-codes');
        })
            ->name('admin.deleted-promo-codes')
            ->middleware('can:promo-code-soft-delete');

        /**
         * Reservations
         */

        // Reservation Lists

        Route::get('/reservations-list', function () {
            return view('admin.reservations.reservations-list');
        })->name('admin.reservations-list');

        //Create Transaction
        Route::get('create/new-reservation', function () {
            return view('admin.reservations.create-reservation');
        })->name('admin.create-reservation');

        // Completed Reservations
        Route::get('completed-reservations', function () {
            return view('admin.reservations.completed-reservation');
        })->name('admin.completed-reservations');

        // View Reservation
        Route::get('view/reservation/{transaction}', ViewReservation::class)->name('admin.view-reservation');

        // Edit Reservation
        Route::get('edit/reservation/{transaction}', EditReservation::class)->name('admin.edit-reservation');

        // Add Transaction
        Route::get('add/transaction/{transaction}', AddTransaction::class)->name('admin.add-transaction');

        // Rebook Reservation
        Route::get('rebook/reservation/{transaction}', RebookReservation::class)->name('admin.rebook-reservation');

        /**
         * Payments
         */

        // View Payments List
        Route::get('/payments-list', function () {
            return view('admin.reservations.payments.payment-list');
        })
            ->name('admin.payments-list')
            ->middleware('can:payments-list');

        // View Payment Receipt
        Route::get('view/payment-receipt/{payment}', ViewReceipt::class)->name('admin.view-payment-receipt');

        // Payments Summary
        Route::get('/payment-reports', function () {
            return view('admin.reports.payment-reports');
        })->name('admin.payment-reports');

        /**
         * Invoice
         */

        Route::get('/invoice-list', function () {
            return view('admin.reservations.invoices.invoice-list');
        })
            ->name('admin.invoice-list')
            ->middleware('can:invoices-list');

        // Invoice Summary
        Route::get('/invoice-reports', function () {
            return view('admin.reports.invoice-reports');
        })->name('admin.invoice-reports');

        /**
         * Feedback
         */

        Route::get('/feedback', function () {
            return view('admin.feedback');
        })
            ->name('admin.feedback')
            ->middleware('can:feedback');

        // View
        Route::get('view/new-reservation/{transaction}', ViewTransaction::class)->name('admin.view-new-transaction')->middleware('can:new-reservation-view');

        // Edit
        Route::get('edit/new-reservation/{transaction}', EditTransaction::class)->name('admin.edit-new-transaction')->middleware('can:new-reservation-edit');

        // Deleted New Transactions (Soft Deletes)
        Route::get('/deleted-new-reservations', function () {
            return view('admin.transactions.new.deleted-new-transactions');
        })
            ->name('admin.deleted-new-transactions')
            ->middleware('can:new-reservation-soft-delete');

        // Confirmed Reservations
        Route::get('/confirmed-reservations', function () {
            return view('admin.transactions.confirmed.view-transactions');
        })
            ->name('admin.view-confirmed-transactions')
            ->middleware('can:confirmed-reservation-list');

        // On-going Bookings
        Route::get('/on-going-bookings', function () {
            return view('admin.transactions.ongoing.view-transactions');
        })
            ->name('admin.view-ongoing-transactions')
            ->middleware('can:on-going-booking-list');

        // Old bookings
        Route::get('/old-bookings', function () {
            return view('admin.transactions.old.view-transactions');
        })
            ->name('admin.view-old-transactions')
            ->middleware('can:old-booking-list');

        // Day Tour Routes
        Route::get('/daytour-reservations', function () {
            return view('admin.reservations.daytour-reservations-list');
        })
            ->name('admin.daytour-reservations-list')
            ->middleware(['auth', 'can:daytour-reservation-list']);

        Route::get('/day-tour-reservations/create', CreateDayTourReservation::class)
            ->name('admin.create-day-tour-reservation')
            ->middleware(['auth', 'can:daytour-reservation-create']);

        Route::get('/daytour-reservations/{transaction}', ViewDaytourReservation::class)
            ->name('admin.view-daytour-reservation')
            ->middleware(['auth', 'can:daytour-reservation-view']);

        Route::get('/reservations/archives', ArchiveReservations::class)
            ->name('admin.reservations-archives')
            ->middleware(['auth', 'verified']);

        Route::get('/daytours/archives', ArchiveDayTours::class)
            ->name('admin.daytours-archives')
            ->middleware(['auth', 'verified']);

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
        })
            ->name('admin.properties')
            ->middleware('can:house-list');

        // Create
        Route::get('create/property', function () {
            return view('admin.rentals.properties.create-property');
        })
            ->name('admin.create-property')
            ->middleware('can:house-create');

        // View
        Route::get('view/property/{property}', ViewProperty::class)->name('admin.view-property')->middleware('can:house-view');

        // Edit
        Route::get('edit/property/{property}', EditProperty::class)->name('admin.edit-property')->middleware('can:house-edit');

        // Deleted Houses (Soft Deletes)
        Route::get('deleted-properties', function () {
            return view('admin.rentals.properties.deleted-properties');
        })
            ->name('admin.deleted-properties')
            ->middleware('can:house-soft-delete');

        // ------------------ House Features

        //List Features
        Route::get('/features', function () {
            return view('admin.features.view-features');
        })
            ->name('admin.features')
            ->middleware('can:house-features-list');

        // Create Feature
        Route::get('create/features', function () {
            return view('admin.features.create-feature');
        })
            ->name('admin.create-feature')
            ->middleware('can:house-features-create');

        // View Feature
        Route::get('view/feature/{feature}', ViewFeature::class)->name('admin.view-feature')->middleware('can:house-features-view');

        //Edit Features
        Route::get('edit/feature/{feature}', EditFeature::class)->name('admin.edit-feature')->middleware('can:house-features-edit');

        // Deleted Features (Soft Deletes)
        Route::get('deleted-features', function () {
            return view('admin.features.deleted-features');
        })
            ->name('admin.deleted-features')
            ->middleware('can:house-features-soft-delete');

        // ------------------ Leases
        //List
        Route::get('/leases', function () {
            return view('admin.rentals.leases.view-leases');
        })
            ->name('admin.leases')
            ->middleware('can:leases-list');

        // Create
        Route::get('create/lease', function () {
            return view('admin.rentals.leases.create-lease');
        })
            ->name('admin.create-lease')
            ->middleware('can:leases-create');

        //View
        Route::get('view/lease/{transaction}', ViewLease::class)->name('admin.view-lease')->middleware('can:leases-view');

        //Edit
        Route::get('edit/lease/{transaction}', EditLease::class)->name('admin.edit-lease')->middleware('can:leases-edit');

        // Deleted Leases (Soft Deletes)
        Route::get('deleted-leases', function () {
            return view('admin.rentals.leases.deleted-leases');
        })
            ->name('admin.deleted-leases')
            ->middleware('can:leases-soft-delete');


        Route::get('/leases/archives', \App\Livewire\Admin\Properties\Leases\ArchiveLeases::class)
        ->name('admin.leases-archives')
        ->middleware(['auth', 'verified']);

        //Lease Summary
        Route::get('/lease-reports', function () {
            return view('admin.reports.lease-reports');
        })
            ->name('admin.lease-reports')
            ->middleware('can:lease-reports');

        // ------------------ House Categories

        // List
        Route::get('/house-categories', function () {
            return view('admin.rentals.house-categories.view-house-categories');
        })
            ->name('admin.house-categories')
            ->middleware('can:house-category-list');

        // Create
        Route::get('create/house-category', function () {
            return view('admin.rentals.house-categories.create-house-category');
        })
            ->name('admin.create-house-category')
            ->middleware('can:house-category-create');

        // View
        Route::get('view/house-category/{houseCategory}', ViewHouseCategory::class)->name('admin.view-house-category')->middleware('can:house-category-view');

        // Edit
        Route::get('edit/house-category/{houseCategory}', EditHouseCategory::class)->name('admin.edit-house-category')->middleware('can:house-category-edit');

        // Deleted House Categories (Soft Deletes)
        Route::get('deleted-house-categories', function () {
            return view('admin.rentals.house-categories.deleted-house-categories');
        })
            ->name('admin.deleted-house-categories')
            ->middleware('can:house-category-soft-delete');

        // ------------------ Tenants

        // List
        Route::get('/tenants', function () {
            return view('admin.rentals.tenants.view-tenants');
        })
            ->name('admin.tenants')
            ->middleware('can:tenant-list');

        // Create
        Route::get('create/tenant', function () {
            return view('admin.rentals.tenants.create-tenant');
        })
            ->name('admin.create-tenant')
            ->middleware('can:tenant-create');

        // View
        Route::get('view/tenant/{tenant}', ViewTenant::class)->name('admin.view-tenant')->middleware('can:tenant-view');

        // Edit
        Route::get('edit/tenant/{tenant}', EditTenant::class)->name('admin.edit-tenant')->middleware('can:tenant-edit');

        // Deleted Tenants (Soft Deletes)
        Route::get('deleted-tenants', function () {
            return view('admin.rentals.tenants.deleted-tenants');
        })
            ->name('admin.deleted-tenants')
            ->middleware('can:tenant-soft-delete');

        // Backup routes
        Route::get('view/backups', ViewBackups::class)->name('admin.view-backups')->middleware('can:backup-view');

        Route::get('create/backup', CreateBackup::class)->name('admin.create-backup')->middleware('can:backup-create');

        // ----------------- Activity Logs
        Route::get('/audit-trail', function () {
            return view('admin.activity-logs.view-activity-logs');
        })
            ->name('admin.activity-logs')
            ->middleware('can:activity-logs-view');
    },
);

// --------------------- TEST ROUTES FOR PAYMENT INTEGRATION ----------------------------------- //

Route::get('/payment-success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment-failed', [PaymentController::class, 'failed'])->name('payment.failed');

// ----------------------------- TEST ROUTE FOR EMAILS ----------------------------------------- //

Route::get('/event-email', function () {
    $quoteData = [
        'company_name' => 'ABC Corp',
        'contact_person' => 'Jane Doe',
        'email' => 'jane@example.com',
        'contact_number' => '09171234567',
        'selected_hall' => (object) ['name_number' => 'Hall A'],
        'event_start' => Carbon::now()->addDays(7),
        'event_end' => Carbon::now()->addDays(7)->addHours(4),
        'event_type' => 'Corporate Meeting',
        'other_event_type' => null,
        'additional_requests' => 'Projector, Sound system',
    ];

    return view('guest.emails.event-quotes', compact('quoteData'));
});

Route::get('/guest-event', function () {
    $quoteData = [
        'company_name' => 'Green Events Co.',
        'contact_person' => 'Alex Cruz',
        'email' => 'alex@example.com',
        'contact_number' => '09171234567',
        'selected_hall' => (object) ['name_number' => 'Hall B - Garden View'],
        'event_start' => now()->addDays(7)->setTime(15, 0),
        'event_end' => now()->addDays(7)->setTime(21, 0),
        'event_type' => 'Corporate Event',
        'other_event_type' => null,
        'additional_requests' => 'Stage setup and catering.',
    ];

    return view('guest.emails.request-quote', ['quoteData' => $quoteData]);
});

// Route::get('/reservation-submitted', function () {
//     $fakeData = [
//         'name' => 'Juan Dela Cruz',
//         'transaction_number' => 'TXN123456789',
//         'email' => 'juan@example.com',
//         'invoice_number' => 'INV-2025-001',
//         'check_in' => '2025-06-01',
//         'check_out' => '2025-06-03',
//         'total_amount' => 5000.0,
//         'deposit' => 1000.0,
//         'expirationHours' => 48,
//     ];

//     return new ReservationSubmittedMail($fakeData, $pdfContent);
// });

Route::get('/payment-email', function () {
    $paymentDetails = [
        'full_name' => 'Juan Dela Cruz',
        'email' => 'juan@example.com',
        'payment_method_id' => 1,
        'check_in' => '2025-06-01',
        'check_out' => '2025-06-03',
        'total_amount' => 3500.0,
        'deposit' => 1500.0,
        'expirationHours' => 24,
    ];

    return new PaymentUploadedMail($paymentDetails);
});

Route::get('/payment-rejected', function () {
    $paymentDetails = [
        'first_name' => 'Maria',
        'last_name' => 'Reyes',
        'user_email' => 'maria.reyes@example.com',
        'rejection_reason' => 'Unclear image or invalid reference number',
    ];

    return new ReceiptRejectedMail($paymentDetails);
});

Route::get('/reservation-completed', function () {
    $reservationData = [
        'name' => 'Juan Dela Cruz',
        'email' => 'juan.delacruz@example.com',
        'contact_number' => '09123456789',
        'transaction_number' => 'TXN-123456',
        'invoice_number' => 'INV-987654',
        'check_in' => now()->addDays(2)->toDateString(),
        'check_out' => now()->addDays(4)->toDateString(),
        'total_amount' => 5000,
        'deposit' => 2000,
        'amount_paid' => 2000,
        'balance_due' => 3000,
        'properties' => collect([
            (object) [
                'name_number' => 'Room A1',
                'pivot' => (object) [
                    'adults' => 2,
                    'kids' => 1,
                    'days' => 2,
                    'extra_charge' => 500,
                    'total_amount' => 2500,
                ],
            ],
        ]),
        'activities' => collect([
            (object) [
                'name' => 'ATV Ride',
                'amount' => 500,
                'pivot' => (object) [
                    'quantity' => 2,
                ],
            ],
        ]),
    ];

    //return new ReservationCompletedMail($reservationData);
});

Route::get('/contact-email', function () {
    $contactData = [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'contact_number' => '09171234567',
        'message' => '
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis vitae nibh imperdiet, aliquet diam a, aliquam urna. Donec mi sapien, mollis laoreet nisl tempus, sollicitudin facilisis risus. Integer congue, ante nec scelerisque sodales, neque nunc bibendum mi, at cursus enim libero in dui. Fusce condimentum nunc vitae arcu ullamcorper mollis. Sed metus sem, posuere non leo ac, eleifend fermentum felis. Integer ullamcorper odio nec enim laoreet efficitur. Donec sed sapien vel lectus dapibus interdum.',
    ];

    return view('guest.emails.contact-message', ['contactData' => $contactData]);
});

Route::get('/guest-contact', function () {
    $contactData = [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'contact_number' => '09181234567',
        'message' => 'I am interested in visiting the farm this weekend.',
    ];

    return view('guest.emails.contact-us', ['contactData' => $contactData]);
});

Route::get('/reservation-confirmed', function () {
    $reservationData = [
        'name' => 'Juan Dela Cruz',
        'email' => 'juan.delacruz@example.com',
        'contact_number' => '09123456789',
        'transaction_number' => 'TXN-123456',
        'invoice_number' => 'INV-987654',
        'check_in' => now()->addDays(2)->toDateString(),
        'check_out' => now()->addDays(4)->toDateString(),
        'total_amount' => 5000,
        'deposit' => 2000,
        'amount_paid' => 2000,
        'balance_due' => 3000,
        'properties' => collect([
            (object) [
                'name_number' => 'Room A1',
                'pivot' => (object) [
                    'adults' => 2,
                    'kids' => 1,
                    'days' => 2,
                    'extra_charge' => 500,
                    'total_amount' => 2500,
                ],
            ],
        ]),
        'activities' => collect([
            (object) [
                'name' => 'ATV Ride',
                'amount' => 500,
                'pivot' => (object) [
                    'quantity' => 2,
                ],
            ],
        ]),
    ];

    return new ReservationConfirmedMail($reservationData);
});

Route::get('/daytour-submitted', function () {
    $reservationData = [
        'name' => 'Juan Dela Cruz',
        'transaction_number' => 'DT-ABC12345',
        'email' => 'juan@example.com',
        'invoice_number' => 'INV-DT-987654',
        'tour_date' => '2025-10-25',
        'tour_name' => 'Premium Day Tour',
        'rate_name' => 'Summer Promo Rate',
        'adult_count' => 2,
        'kid_count' => 1,
        'adult_rate' => 1200,
        'kid_rate' => 850,
        'subtotal' => 3250,
        'convenience_fee' => 97.5,
        'total_amount' => 3347.5,
        'payment_link' => 'https://paymongo.link/example',

        'branding_company_name' => 'Sunscape Resorts',
        'logo_path' => asset('images/logo.png'),
        'branding_company_email' => 'info@sunscape.com',
        'branding_company_contact' => '+63 912 345 6789',
        'company_address' => '123 Beachside Rd, Batangas',
        'facebook_link' => 'https://facebook.com/sunscape',
        'instagram_link' => 'https://instagram.com/sunscape',
    ];

    $pdfContent = 'Sample PDF content for preview';

    return view('guest.emails.daytour-submitted', $reservationData);
});

Route::get('/daytour-confirmed', function () {
    $dayTourData = [
        'name' => 'Juan Dela Cruz',
        'email' => 'juan@example.com',
        'contact_number' => '+63 912 345 6789',

        'transaction_number' => 'DTX-20251018-001',
        'tour_date' => '2025-10-25',
        'adult_count' => 2,
        'kid_count' => 1,
        'total_guests' => 3,
        'subtotal' => 3500,
        'convenience_fee' => 105,
        'total_amount' => 3605,
        'amount_paid' => 2000,
        'balance_due' => 1605,
        'requests' => 'We’d like a vegetarian meal option, please.',
        'request_reply' => 'Noted! We’ll prepare a vegetarian meal for your group.',

        'invoice_number' => 'INV-DT-20251018-001',
        'invoice_basesubtotal' => 3500,
        'invoice_total_discount' => 0,
        'invoice_subtotal' => 3500,

        'guest_details' => ['Juan Dela Cruz', 'Maria Santos', 'Pedro Reyes'],

        'branding_company_name' => 'Canopy Farm PH',
        'logo_path' => asset('images/canopy-logo.png'),
        'branding_company_email' => 'info@canopyfarmph.com',
        'branding_company_contact' => '+63 900 123 4567',
        'company_address' => ' Brgy. Buna Cerca, Indang, Philippines',
        'facebook_link' => 'https://facebook.com/canopyfarmph',
        'instagram_link' => 'https://instagram.com/canopyfarmph',
    ];

    return view('guest.emails.daytour-confirmed', $dayTourData);
});

Route::get('/daytour-completed', function () {
    $dayTourData = [
        'name' => 'Juan Dela Cruz',
        'email' => 'juan@example.com',
        'contact_number' => '+63 912 345 6789',

        'transaction_number' => 'DTX-20251018-002',
        'tour_date' => '2025-10-25',
        'completion_date' => '2025-10-25',
        'adult_count' => 2,
        'kid_count' => 1,
        'total_guests' => 3,
        'subtotal' => 3500,
        'convenience_fee' => 105,
        'total_amount' => 3605,
        'amount_paid' => 3605,
        'balance_due' => 0,

        'invoice_number' => 'INV-DT-20251018-002',
        'invoice_basesubtotal' => 3500,
        'invoice_total_discount' => 0,
        'invoice_subtotal' => 3500,

        'guest_details' => [(object) ['name' => 'Juan Dela Cruz'], (object) ['name' => 'Maria Santos'], (object) ['name' => 'Pedro Reyes']],

        'branding_company_name' => 'Canopy Farm PH',
        'logo_path' => asset('images/canopy-logo.png'),
        'branding_company_email' => 'info@canopyfarmph.com',
        'branding_company_contact' => '+63 900 123 4567',
        'company_address' => ' Brgy. Buna Cerca, Indang, Philippines',
        'facebook_link' => 'https://facebook.com/canopyfarmph',
        'instagram_link' => 'https://instagram.com/canopyfarmph',
    ];

    return view('guest.emails.daytour-completed', $dayTourData);
});

Route::get('/new-daytour', function () {
    $reservationData = [
        'name' => 'Juan Dela Cruz',
        'email' => 'juan@example.com',
        'transaction_number' => 'DTX-20251020-001',
        'invoice_number' => 'INV-20251020-001',

        'tour_date' => '2025-10-28',
        'tour_name' => 'Canopy Farm Day Tour Experience',
        'rate_name' => 'Family Package (With Room)',

        'adult_count' => 2,
        'kid_count' => 1,
        'adult_rate' => 1200,
        'kid_rate' => 800,
        'subtotal' => 3200,
        'convenience_fee' => 96,
        'total_amount' => 3296,
        'payment_link' => 'https://canopyfarmph.com/payments/checkout',

        'branding_company_name' => 'Canopy Farm PH',
        'logo_path' => asset('images/logo.png'),
        'branding_company_email' => 'info@canopyfarmph.com',
        'branding_company_contact' => '+63 912 345 6789',
        'company_address' => ' Brgy. Buna Cerca, Indang, Philippines',
        'facebook_link' => 'https://facebook.com/canopyfarmph',
        'instagram_link' => 'https://instagram.com/canopyfarmph',
    ];

    return view('guest.emails.new-daytour-reservation', $reservationData);
});

// ----------------------------- TEST ROUTE FOR PDFs ----------------------------------------- //

Route::get('/event-summary-preview', function () {
    $transactions = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
        ->with(['transactionUser', 'properties'])
        ->where('reservation_type_id', 3) // 3 = event
        ->get();

    $start_date = now()->startOfMonth()->format('Y-m-d');
    $end_date = now()->endOfMonth()->format('Y-m-d');

    $totalEvents = $transactions->count();
    $totalGuests = $transactions->sum('pax');
    $totalAmountEarned = $transactions->sum('total_amount');

    $pdf = Pdf::loadView('livewire.admin.reports.events-report-summary', [
        'transactions' => $transactions,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'totalEvents' => $totalEvents,
        'totalGuests' => $totalGuests,
        'totalAmountEarned' => $totalAmountEarned,
    ]);

    return $pdf->stream('event-summary-preview.pdf');
});

Route::get('/lease-summary-preview', function () {
    $transactions = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
        ->with(['transactionUser', 'properties'])
        ->where('reservation_type_id', 1) // 1 = lease
        ->get();

    $start_date = now()->startOfMonth()->format('Y-m-d');
    $end_date = now()->endOfMonth()->format('Y-m-d');

    $totalLeases = $transactions->count();

    if ($totalLeases > 0) {
        $totalMonths = $transactions->sum(function ($transaction) {
            $start = Carbon::parse($transaction->start_datetime);
            $end = Carbon::parse($transaction->end_datetime);
            return $start->diffInMonths($end);
        });

        $averageLength = $totalMonths / $totalLeases;
    } else {
        $averageLength = 0;
    }

    $totalTenants = $transactions->sum('pax');
    $totalAmountEarned = $transactions->sum('total_amount');

    $pdf = Pdf::loadView('livewire.admin.reports.leases-report-summary', [
        'transactions' => $transactions,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'totalLeases' => $totalLeases,
        'averageLength' => round($averageLength, 2),
        'totalTenants' => $totalTenants,
        'totalAmountEarned' => $totalAmountEarned,
    ]);

    return $pdf->stream('lease-summary-preview.pdf');
});

Route::get('/reservation-summary-preview', function () {
    $transactions = Transaction::query()
        ->select('trn_transactions.*')
        ->join('transaction_properties', 'trn_transactions.id', '=', 'transaction_properties.transaction_id')
        ->with(['transactionUser', 'properties'])
        ->where('reservation_type_id', 2) // 2 = reservation
        ->get();

    $start_date = now()->startOfMonth()->format('Y-m-d');
    $end_date = now()->endOfMonth()->format('Y-m-d');

    $totalReservations = $transactions->count();

    if ($totalReservations > 0) {
        $totalDays = $transactions->sum(function ($transaction) {
            $start = Carbon::parse($transaction->start_datetime);
            $end = Carbon::parse($transaction->end_datetime);
            return $start->diffInDays($end);
        });

        $averageLength = $totalDays / $totalReservations;
    } else {
        $averageLength = 0;
    }

    $totalGuests = $transactions->sum('pax');
    $totalAmountEarned = $transactions->sum('total_amount');

    $pdf = Pdf::loadView('livewire.admin.reports.reservations-report-summary', [
        'transactions' => $transactions,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'totalReservations' => $totalReservations,
        'averageLength' => round($averageLength, 2),
        'totalGuests' => $totalGuests,
        'totalAmountEarned' => $totalAmountEarned,
    ]);

    return $pdf->stream('reservation-summary-preview.pdf');
});

Route::get('/reservation-details-preview/{transaction}', function ($transactionId) {
    $transaction = Transaction::with([
        'invoice.payments',
        'transactionUser',
        'guestDetails',
        'properties',
        'activities' => function ($query) {
            $query->withPivot('quantity', 'amount', 'activity_datetime', 'status');
        },
    ])->findOrFail($transactionId);

    $pdf = Pdf::loadView('livewire.admin.reservations.reservation-details', [
        'transaction' => $transaction,
        'guestDetails' => $transaction->guestDetails,
        'invoice' => $transaction->invoice,
        'activities' => $transaction->activities,
        'properties' => $transaction->properties,
        'payments' => $transaction->invoice?->payments ?? [],
        'totalRooms' => $transaction->totalRooms,
        'totalAddons' => $transaction->totalAddons,
    ]);

    return $pdf->stream('reservation-details.pdf' . $transaction->start_datetime . '.pdf');
});

Route::get('/event-details-preview/{event}', function ($eventId) {
    $event = Transaction::with(['transactionUser', 'properties', 'invoice.transaction', 'event_type'])->findOrFail($eventId);

    $pdf = Pdf::loadView('livewire.admin.events.event-details', [
        'event' => $event,
    ]);

    return $pdf->stream('event-details-' . $event->start_datetime . '.pdf');
});

Route::get('/preview-receipt-email', function () {
    // Mock data
    $receipt = (object) [
        'receipt_number' => '15',
        'created_at' => now(),
        'total_amount' => 2500.0,
    ];

    $transaction = (object) [
        'id' => 1,
        'payment_method' => 'GCash',
        'reference_number' => 'GC123456789',
    ];

    $transactionUser = (object) [
        'name' => 'Juan Dela Cruz',
        'email' => 'juan@example.com',
    ];

    $invoice = (object) [
        'invoice_number' => 'INV-2025-0001',
        'billing_period' => 'July 2025',
    ];

    $properties = collect([(object) ['name' => 'Cabin A', 'rate' => 1200], (object) ['name' => 'Cabin B', 'rate' => 1300]]);

    $activities = collect([(object) ['name' => 'Zipline', 'amount' => 500], (object) ['name' => 'Bonfire', 'amount' => 300]]);

    $data = [
        'receipt' => $receipt,
        'transaction' => $transaction,
        'invoice' => $invoice,
        'transactionUser' => $transactionUser,
        'properties' => $properties,
        'activities' => $activities,
        'branding_company_name' => 'Canopy Farm PH',
        'branding_company_contact' => '0917-123-4567',
        'company_address' => 'Brgy. Example Address, Tanay, Rizal',
        'facebook_link' => 'https://facebook.com/canopyfarmph',
        'instagram_link' => 'https://instagram.com/canopyfarmph',
        'logo_path' => 'images/canopy-logo.png',
    ];

    $pdf = Pdf::loadView('guest.emails.official-receipt', $data);
    $pdfContent = $pdf->output();

    $email = new SendOfficialReceiptMail($pdfContent, $receipt->receipt_number, $transactionUser, $data);

    return $email->render(); // Show email preview in browser
})->name('preview.receipt.email');

Route::get('/receipt-preview', function () {
    $receipt = (object) [
        'receipt_number' => 'RCPT-00123',
        'created_at' => now(),
    ];

    $transaction = (object) [
        'payment_method' => 'GCash',
        'total_amount' => 3500,
    ];

    $transactionUser = (object) [
        'name' => 'Juan Dela Cruz',
        'email' => 'juan@example.com',
    ];

    $invoice = (object) [
        'invoice_number' => 'INV-00001',
        'billing_period' => 'July 2025',
    ];

    $properties = collect([(object) ['name' => 'Unit A101', 'monthly_rent' => 3500]]);

    $activities = collect([(object) ['description' => 'Rent Payment', 'amount' => 3500]]);

    $data = [
        'receipt' => $receipt,
        'transaction' => $transaction,
        'invoice' => $invoice,
        'transactionUser' => $transactionUser,
        'properties' => $properties,
        'activities' => $activities,
        'branding_company_name' => 'Canopy Farm PH',
        'branding_company_contact' => '0917-123-4567',
        'company_address' => 'Brgy. Example Address, Tanay, Rizal',
        'facebook_link' => 'https://facebook.com/canopyfarmph',
        'instagram_link' => 'https://instagram.com/canopyfarmph',
        'logo_path' => 'images/canopy-logo.png',
    ];

    // We won't use a real PDF here, just fake string (not attached)
    $dummyPdf = 'dummy-binary';

    $email = new SendOfficialReceiptMail($dummyPdf, $receipt->receipt_number, $transactionUser, $data);

    return $email->render();
})->name('receipt.preview');

//Preview the available payment methods pdf:
Route::get('/reports/payment-methods/preview', [ReservationForm::class, 'printAvailablePaymentMethods'])->name('reports.payment-methods.preview');

// ----------------------------- GUEST PAGES ----------------------------------------- //

Route::prefix('guest')->group(function () {
    Route::get('/homepage', function () {
        return view('guest.homepage');
    })->name('guest.homepage');

    Route::get('/activities', function () {
        return view('guest.activities');
    })->name('guest.activities');

    Route::get('/rooms', function () {
        return view('guest.rooms');
    })->name('guest.rooms');

    Route::get('/houses', function () {
        return view('guest.houses');
    })->name('guest.houses');

    Route::get('/event-halls', function () {
        return view('guest.event-halls');
    })->name('guest.event-halls');

    Route::get('/request-a-quote', function () {
        return view('guest.request-a-quote');
    })->name('guest.request-a-quote');

    // Route::get('/reservation-form', function () {
    //     return view('guest.reservation-form');
    // })->name('guest.reservation-form');

    Route::get('/reservation-form', function () {
        return view('guest.reservation.reservation-form');
    })->name('guest.reservation-form');

    // Day Tour Reservation Form (Livewire)
    Route::get('/day-tour-reservation', DayTourReservationForm::class)->name('guest.day-tour-reservation');

    Route::get('/proof-of-payment-page', function () {
        return view('guest.proof-of-payment-page');
    })->name('guest.proof-of-payment-page');

    Route::get('/thank-you-page', function () {
        return view('guest.thank-you-page');
    })->name('guest.thank-you-page');

    Route::get('/feedback-form', function () {
        return view('guest.feedback.feedback-form');
    })->name('guest.feedback-form');

    Route::get('/about-us', function () {
        return view('guest.about-us');
    })->name('guest.about-us');
});

// ------------------------------- WEBHOOK ----------------------------------------- //

Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// ------------------------------- VIEW LOGS ----------------------------------------- //

Route::get('/view-logs', function () {
    $logs = LogActivity::latest()->get();

    $propertiesOnly = $logs->map(function ($log) {
        return $log->properties;
    });

    return response()->json($propertiesOnly);
})->name('admin.view-logs');

Route::get('/promo-codes', function () {
    $promoCodes = PromoCode::all();
    // I dont have view. just display
    return $promoCodes
        ->map(function ($promoCode) {
            return [
                'id' => $promoCode->id,
                'code' => $promoCode->code,
                'discount' => $promoCode->discount,
                'start_date' => $promoCode->start_date,
                'end_date' => $promoCode->end_date,
                'status' => $promoCode->status ? 'Active' : 'Inactive',
            ];
        })
        ->toArray();
})->name('admin.promo-codes');
