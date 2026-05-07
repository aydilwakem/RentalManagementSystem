<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Reports\EventReports;
use App\Livewire\Admin\Reports\LeaseReports;
use App\Livewire\Admin\Reports\ReservationReports;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ReportTest extends TestCase
{
    // -------------------- TESTS RESERVATION, EVENT, AND LEASE REPORTS PDF GENERATION ---------------//
    
    //--------------------- RESERVATION REPORTS ---------------------------------- //
    
    //--------------------------- TEST TO SEE IF VISIBLE WITHOUT PERMISSION ---------------//
    //List View
    public function test_reservation_report_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('reports');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.reservation-reports'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(ReservationReports::class);
    }

    //------------------------ TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION -----------------//
    //List View
    public function test_reservation_report_view_component_is_visible()
    {
        Permission::findOrCreate('reports');

        $user = User::factory()->create();
        $user->givePermissionTo('reports');

        $this->actingAs($user)
            ->get(route('admin.reservation-reports'))
            ->assertSeeLivewire(ReservationReports::class);
    }

    //---------------------------- PDF GENERATION -----------------------------//
    public function test_export_reservation_summary_is_generated(): void
    {
         // Define May 1–31, 2025
        $mayStart = Carbon::create(2025, 5, 1);
        $mayEnd = Carbon::create(2025, 5, 31);

        $transactions = Transaction::factory()
        ->count(10)
        ->create(function () use ($mayStart) {
            $start = $mayStart->copy()->addDays(rand(0, 20));
            return [
                'reservation_type_id' => 2,
                'start_datetime' => $start,
                'end_datetime' => $start->copy()->addDays(rand(1, 3)),
                'pax' => rand(1, 5),
                'total_amount' => rand(1000, 5000),
            ];
        });

    // Link each transaction to a property with property_type_id = 2
    foreach ($transactions as $transaction) {
        $property = Property::factory()->create([
            'property_type_id' => 2,
        ]);

         // Attach the property using the belongsToMany pivot
        $transaction->properties()->attach($property->id, [
            'adults' => rand(1, 3),
            'kids' => rand(0, 2),
            'extra_guest' => 0,
            'extra_charge' => 0,
            'amount' => 1000,
            'total_amount' => 1000,
            'days' => 2,
        ]);
    }

        // Call the Livewire component method
        Livewire::test(ReservationReports::class)
            ->set('start_date', '2025-05-01')
            ->set('end_date', '2025-05-31')
            ->call('exportReservationSummary')
            ->assertSuccessful(); // Ensures no exceptions or validation errors
    }




    //--------------------- LEASE REPORTS ---------------------------------- //
    //--------------------------- TEST TO SEE IF VISIBLE WITHOUT PERMISSION ---------------//
    //List View
    public function test_lease_report_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('lease-reports');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.lease-reports'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(LeaseReports::class);
    }

    //------------------------ TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION -----------------//
    //List View
    public function test_lease_report_view_component_is_visible()
    {
        Permission::findOrCreate('lease-reports');

        $user = User::factory()->create();
        $user->givePermissionTo('lease-reports');

        $this->actingAs($user)
            ->get(route('admin.lease-reports'))
            ->assertSeeLivewire(LeaseReports::class);
    }

    //--------------------- PDF GENERATION --------------------------------- //
    public function test_export_lease_summary_is_generated(): void
    {
        //within a year
        $mayStart = Carbon::create(2025, 5, 1);
        $mayEnd = Carbon::create(2026, 5, 31);

    $transactions = Transaction::factory()
        ->count(10)
        ->create(function () use ($mayStart) {
            // Random lease start date in May 2025
            $start = $mayStart->copy()->addDays(rand(0, 30));

            // Lease length between 3–12 months
            $end = $start->copy()->addMonths(rand(3, 12));

            return [
                'reservation_type_id' => 1, // for lease
                'start_datetime' => $start,
                'end_datetime' => $end,
                'pax' => rand(1, 5),
                'total_amount' => rand(10000, 20000),
            ];
        });

        foreach ($transactions as $transaction) {
        $property = Property::factory()->create([
            'property_type_id' => 1, //for houses
        ]);

        $transaction->properties()->attach($property->id, [
            'adults' => rand(1, 3),
            'kids' => rand(0, 2),
            'extra_guest' => 0,
            'extra_charge' => 0,
            'amount' => 10000,
            'total_amount' => 10000,
            'days' => $transaction->start_datetime->diffInDays($transaction->end_datetime),
        ]);
    }

    Livewire::test(LeaseReports::class)
        ->set('start_date', '2025-05-01')
        ->set('end_date', '2026-05-31')
        ->call('exportLeaseSummary')
        ->assertSuccessful(); // PDF export was triggered without error
    }



    //--------------------- EVENT REPORTS ---------------------------------- //
    //--------------------------- TEST TO SEE IF VISIBLE WITHOUT PERMISSION ---------------//
    //List View
    public function test_event_report_view_component_is_not_visible_without_permission()
    {
        Permission::findOrCreate('event-reports');

        //user without permission
        $user = User::factory()->create();

        // Act as the user and visit the route
        $response = $this->actingAs($user)
            ->get(route('admin.event-reports'));

        $response->assertForbidden(); 

        $response->assertDontSeeLivewire(EventReports::class);
    }

    //------------------------ TESTS TO SEE IF ROUTES ARE VISIBLE WITH PERMISSION -----------------//
    //List View
    public function test_event_report_view_component_is_visible()
    {
        Permission::findOrCreate('event-reports');

        $user = User::factory()->create();
        $user->givePermissionTo('event-reports');

        $this->actingAs($user)
            ->get(route('admin.event-reports'))
            ->assertSeeLivewire(EventReports::class);
    }

    //--------------------- PDF GENERATION -------------------------------- //
    public function test_export_event_summary_is_generated(): void
    {
         // Define May 1–31, 2025
        $mayStart = Carbon::create(2025, 5, 1);
        $mayEnd = Carbon::create(2025, 5, 31);

        $transactions = Transaction::factory()
        ->count(10)
        ->create(function () use ($mayStart) {
            $start = $mayStart->copy()->addDays(rand(0, 20));
            return [
                'reservation_type_id' => 3,
                'start_datetime' => $start,
                'end_datetime' => $start->copy()->addDays(rand(1, 3)),
                'pax' => rand(1, 5),
                'total_amount' => rand(1000, 5000),
            ];
        });

    // Link each transaction to a property with property_type_id = 2
    foreach ($transactions as $transaction) {
        $property = Property::factory()->create([
            'property_type_id' => 3, //Event Halls
        ]);

         // Attach the property using the belongsToMany pivot
        $transaction->properties()->attach($property->id, [
            'adults' => rand(1, 3),
            'kids' => rand(0, 2),
            'extra_guest' => 0,
            'extra_charge' => 0,
            'amount' => 1000,
            'total_amount' => 1000,
            'days' => 2,
        ]);
    }

        // Call the Livewire component method
        Livewire::test(EventReports::class)
            ->set('start_date', '2025-05-01')
            ->set('end_date', '2025-05-31')
            ->call('exportEventSummary')
            ->assertSuccessful(); // Ensures no exceptions or validation errors
    }
}
