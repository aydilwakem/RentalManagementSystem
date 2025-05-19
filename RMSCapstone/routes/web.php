<?php

use App\Http\Controllers\PDFController;
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
use App\Mail\EventQuotesMail;
use App\Mail\PaymentUploadedMail;
use App\Mail\ReceiptRejectedMail;
use App\Mail\ReservationCompletedMail;
use App\Mail\ReservationConfirmedMail;
use App\Mail\ReservationSubmittedMail;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

// ----------------------------- ADMIN PAGES ----------------------------------------- //

// Welcome page
Route::get('/', function () {
    return redirect()->route('login'); // index file
})->name('admin.welcome');

// Authentication Middleware Group
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

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

    //Events

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

    // Events Summary
    Route::get('/event-reports', function () {
        return view('admin.reports.event-reports');
    })->name('admin.event-reports');

    //--------------------------RESERVATION REPORTS PAGE------------------ //
    Route::get('/reservation-reports', function () {
        return view('admin.reports.reservation-reports');
    })->name('admin.reservation-reports');

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
    Route::get('/create/create-event-hall', function () {
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
    })->name('admin.inclusions');

    // Create Inclusion
    Route::get('create/inclusions', function () {
        return view('admin.inclusions.create-inclusion');
    })->name('admin.create-inclusion');

    // View Inclusion
    Route::get('view/inclusion/{inclusion}', ViewInclusion::class)->name('admin.view-inclusion');

    //Edit Inclusions
    Route::get('edit/inclusion/{inclusion}', EditInclusion::class)->name('admin.edit-inclusion');

    // Deleted Inclusions (Soft Deletes)
    Route::get('deleted-inclusions', function () {
        return view('admin.inclusions.deleted-inclusions');
    })->name('admin.deleted-inclusions');

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

    // View Reservation
    Route::get('view/reservation/{transaction}', ViewReservation::class)->name('admin.view-reservation');

    // Edit Reservation
    Route::get('edit/reservation/{transaction}', EditReservation::class)->name('admin.edit-reservation');

    // Add Transaction
    Route::get('add/transaction/{transaction}', AddTransaction::class)->name('admin.add-transaction');

    /**
     * Payments
     */

    // View Payments List
    Route::get('/payments-list', function () {
        return view('admin.reservations.payments.payment-list');
    })->name('admin.payments-list');

    // View Payment Receipt
    Route::get('view/payment-receipt/{payment}', ViewReceipt::class)->name('admin.view-payment-receipt');

    /**
     * Invoice
     */

    Route::get('/invoice-list', function () {
        return view('admin.reservations.invoices.invoice-list');
    })->name('admin.invoice-list');

    /**
     * Feedback
     */

    Route::get('/feedback', function () {
        return view('admin.feedback');
    })->name('admin.feedback');


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
    Route::get('/houses', function () {
        return view('admin.rentals.properties.view-properties');
    })
        ->name('admin.properties')
        ->middleware('can:house-list');

    // Create
    Route::get('create/house', function () {
        return view('admin.rentals.properties.create-property');
    })
        ->name('admin.create-property')
        ->middleware('can:house-create');

    // View
    Route::get('view/house/{property}', ViewProperty::class)->name('admin.view-property')->middleware('can:house-view');

    // Edit
    Route::get('edit/house/{property}', EditProperty::class)->name('admin.edit-property')->middleware('can:house-edit');

    // Deleted Houses (Soft Deletes)
    Route::get('deleted-houses', function () {
        return view('admin.rentals.properties.deleted-properties');
    })
        ->name('admin.deleted-properties')
        ->middleware('can:house-soft-delete');

    // ------------------ House Features

    //List Features
    Route::get('/features', function () {
        return view('admin.features.view-features');
    })->name('admin.features');

    // Create Feature
    Route::get('create/features', function () {
        return view('admin.features.create-feature');
    })->name('admin.create-feature');

    // View Feature
    Route::get('view/feature/{feature}', ViewFeature::class)->name('admin.view-feature');

    //Edit Features
    Route::get('edit/feature/{feature}', EditFeature::class)->name('admin.edit-feature');

    // Deleted Features (Soft Deletes)
    Route::get('deleted-features', function () {
        return view('admin.features.deleted-features');
    })->name('admin.deleted-features');

    // ------------------ Leases
    //List
    Route::get('/leases', function () {
        return view('admin.rentals.leases.view-leases');
    })->name('admin.leases');

    // Create
    Route::get('create/lease', function () {
        return view('admin.rentals.leases.create-lease');
    })->name('admin.create-lease');

    //View
    Route::get('view/lease/{transaction}', ViewLease::class)->name('admin.view-lease');

    //Edit
    Route::get('edit/lease/{transaction}', EditLease::class)->name('admin.edit-lease');

    // Deleted Leases (Soft Deletes)
    Route::get('deleted-leases', function () {
        return view('admin.rentals.leases.deleted-leases');
    })->name('admin.deleted-leases');

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
});

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

Route::get('/reservation-submitted-email', function () {
    $fakeData = [
        'name' => 'Juan Dela Cruz',
        'transaction_number' => 'TXN123456789',
        'email' => 'juan@example.com',
        'invoice_number' => 'INV-2025-001',
        'check_in' => '2025-06-01',
        'check_out' => '2025-06-03',
        'total_amount' => 5000.0,
        'deposit' => 1000.0,
        'expirationHours' => 48,
    ];

    return new ReservationSubmittedMail($fakeData);
});

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
            (object)[
                'name_number' => 'Room A1',
                'pivot' => (object)[
                    'adults' => 2,
                    'kids' => 1,
                    'days' => 2,
                    'extra_charge' => 500,
                    'total_amount' => 2500,
                ]
            ]
        ]),
        'activities' => collect([
            (object)[
                'name' => 'ATV Ride',
                'amount' => 500,
                'pivot' => (object)[
                    'quantity' => 2
                ]
            ]
        ]),
    ];

    return new ReservationCompletedMail($reservationData);
});

// ----------------------------- TEST ROUTE FOR PDFs ----------------------------------------- //




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
            (object)[
                'name_number' => 'Room A1',
                'pivot' => (object)[
                    'adults' => 2,
                    'kids' => 1,
                    'days' => 2,
                    'extra_charge' => 500,
                    'total_amount' => 2500,
                ]
            ]
        ]),
        'activities' => collect([
            (object)[
                'name' => 'ATV Ride',
                'amount' => 500,
                'pivot' => (object)[
                    'quantity' => 2
                ]
            ]
        ]),
    ];

    return new ReservationConfirmedMail($reservationData);
});


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

    Route::get('/proof-of-payment-page', function () {
        return view('guest.proof-of-payment-page');
    })->name('guest.proof-of-payment-page');

    Route::get('/thank-you-page', function () {
        return view('guest.thank-you-page');
    })->name('guest.thank-you-page');

    Route::get('/feedback-form', function () {
        return view('guest.feedback.feedback-form');
    })->name('guest.feedback-form');
});

// ----------------------------- TENANT PAGES ----------------------------------------- //
