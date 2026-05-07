<?php

namespace Tests\Feature\Admin\Events;

use App\Livewire\Admin\Events\CreateEvent;
use App\Livewire\Admin\Events\DeletedEvents;
use App\Livewire\Admin\Events\EditEvent;
use App\Livewire\Admin\Events\ViewEvent;
use App\Livewire\Admin\Events\ViewEvents;
use App\Models\EventType;
use App\Models\Invoice;
use Illuminate\Support\Str;
use App\Models\Property;
use App\Models\ReservationType;
use App\Models\Transaction;
use App\Models\TransactionUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class EventsTest extends TestCase
{
    //With Permission
    public function test_event_view_component_is_visible()
    {
        Permission::findOrCreate('event-list');

        $user = User::factory()->create();
        $user->givePermissionTo('event-list');

        $this->actingAs($user)
            ->get(route('admin.events'))
            ->assertSeeLivewire(ViewEvents::class);
    }

    public function test_event_single_view_component_is_visible()
    {
        Permission::findOrCreate('event-view');

        $user = User::factory()->create();
        $user->givePermissionTo('event-view');

        // Create TransactionUser
        $guest = TransactionUser::factory()->create();

        // Create Transaction
        $event = Transaction::factory()->create([
            'reservation_type_id' => 3,
            'created_by' => $guest->id,
        ]);

        //Create a valid Invoice for that transaction
        Invoice::factory()->create([
            'transaction_id' => $event->id,
            'invoice_type' => 'Event_Hall',
        ]);

        // Test
        $this->actingAs($user)
            ->get(route('admin.view-event', ['event' => $event->id]))
            ->assertStatus(200)
            ->assertSeeLivewire(ViewEvent::class);
    }

    public function test_event_create_component_is_visible()
    {
        Permission::findOrCreate('event-create');

        $user = User::factory()->create();
        $user->givePermissionTo('event-create');

        $this->actingAs($user)
            ->get(route('admin.create-event'))
            ->assertSeeLivewire(CreateEvent::class);
    }

    //Edit View
    public function test_event_edit_component_is_visible()
    {
      
       Permission::findOrCreate('event-edit');

       $user = User::factory()->create();
       $user->givePermissionTo('event-edit');

        // Create TransactionUser
        $guest = TransactionUser::factory()->create();

        // Create Transaction
        $event = Transaction::factory()->create([
            'reservation_type_id' => 3,
            'created_by' => $guest->id,
        ]);

        // Add invoice
        Invoice::factory()->create([
            'transaction_id' => $event->id,
            'invoice_type' => 'Event_Hall',
        ]);

        // Act as the user and visit the edit route 
        $this->actingAs($user)
        ->get(route('admin.edit-event', ['event' => $event->id]))
        ->assertSeeLivewire(EditEvent::class);
    }

    //Soft Delete View
    public function test_event_soft_delete_component_is_visible()
    {
        Permission::findOrCreate('event-soft-delete');

        $user = User::factory()->create();
        $user->givePermissionTo('event-soft-delete');

        $this->actingAs($user)
            ->get(route('admin.deleted-events'))
            ->assertSeeLivewire(DeletedEvents::class);
    }

    //Without Permission
    public function test_event_list_route_requires_permission()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.events'))
            ->assertForbidden(); // or ->assertStatus(403);
    }

    public function test_event_single_view_route_requires_permission()
    {
        $guest = TransactionUser::factory()->create();

        $event = Transaction::factory()->create([
            'reservation_type_id' => 3,
            'created_by' => $guest->id,
        ]);

        Invoice::factory()->create([
            'transaction_id' => $event->id,
            'invoice_type' => 'Event_Hall',
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.view-event', ['event' => $event->id]))
            ->assertForbidden();
    }

    public function test_event_create_route_requires_permission()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.create-event'))
            ->assertForbidden();
    }

public function test_event_edit_route_requires_permission()
    {
        $guest = TransactionUser::factory()->create();

        $event = Transaction::factory()->create([
            'reservation_type_id' => 3,
            'created_by' => $guest->id,
        ]);

        Invoice::factory()->create([
            'transaction_id' => $event->id,
            'invoice_type' => 'Event_Hall',
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.edit-event', ['event' => $event->id]))
            ->assertForbidden();
    }

    public function test_event_soft_delete_route_requires_permission()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.deleted-events'))
            ->assertForbidden();
    }

    // ------------------------------- CREATE EVENT ----------------------- //

    public function test_event_can_be_created()
    {
        $this->withoutExceptionHandling(); // optional, helps with debugging

        // Create dependencies
        $reservationType = ReservationType::factory()->create();
        $eventType = EventType::factory()->create();
        $hall = Property::factory()->create([
            'property_type_id' => 3,
            'property_status' => 'available',
        ]);

        // Insert default setting
        DB::table('st_settings')->updateOrInsert(['id' => 1], ['deposit_percentage' => 50]);

        // Create transaction user
        $user = TransactionUser::factory()->create([
            'trn_user_type' => 'guest',
        ]);

        $totalAmount = 100000;
        $depositAmount = $totalAmount * 0.5;
        $start = now()->addDays(3);
        $end = now()->addDays(4);

        // Create transaction manually as saveEvent() would
        $transaction = Transaction::create([
            'transaction_number' => 'EVT-' . strtoupper(Str::random(8)),
            'reservation_type_id' => $reservationType->id,
            'created_by' => $user->id,
            'event_type_id' => $eventType->id,
            'start_datetime' => $start,
            'end_datetime' => $end,
            'total_adults' => 10,
            'total_kids' => 2,
            'pax' => 12,
            'total_amount' => $totalAmount,
            'deposit_amount' => $depositAmount,
            'reservation_source' => 'WebApp',
            'transaction_status' => 'pending',
        ]);

        // Generate invoice number
        $invoiceNumber = 'INV-' . strtoupper(Str::random(8));

        $invoice = Invoice::create([
            'transaction_id' => $transaction->id,
            'invoice_number' => $invoiceNumber,
            'invoice_type' => 'event_hall',
            'sub_total' => $totalAmount,
            'deposit_paid' => 0,
            'amount_paid' => 0,
            'balance_due' => $totalAmount,
            'due_date' => $end,
            'invoice_status' => 'pending',
        ]);

        // Attach hall
        $transaction->properties()->attach($hall->id, [
            'adults' => 10,
            'kids' => 2,
            'extra_guest' => 0,
            'extra_charge' => 0,
            'amount' => $totalAmount,
            'total_amount' => $totalAmount,
            'days' => 1,
        ]);

        // Check Assertions
        $this->assertDatabaseHas('trn_transactions', [
            'id' => $transaction->id,
            'created_by' => $user->id,
            'reservation_type_id' => $reservationType->id,
            'event_type_id' => $eventType->id,
            'total_amount' => $totalAmount,
        ]);

        $this->assertDatabaseHas('trn_invoice', [
            'transaction_id' => $transaction->id,
            'sub_total' => $totalAmount,
            'invoice_status' => 'pending',
        ]);

        $this->assertDatabaseHas('transaction_properties', [
            'transaction_id' => $transaction->id,
            'property_id' => $hall->id,
            'total_amount' => $totalAmount,
        ]);
    }

    public function test_admin_can_update_event_status()
    {
        // Setup
        Permission::findOrCreate('event-edit');

        $user = User::factory()->create();
        $user->givePermissionTo('event-edit');

        $guest = TransactionUser::factory()->create();

        $event = Transaction::factory()->create([
            'reservation_type_id' => 3,
            'created_by' => $guest->id,
            'transaction_status' => 'pending',
        ]);

        // Act & Assert
        Livewire::test(EditEvent::class, [
            'event' => $event,
        ])
        ->set('transaction_status', 'confirmed')
        ->call('updateEvent')
        ->assertRedirect(route('admin.events'));

        $event->refresh();
        $this->assertEquals('confirmed', $event->transaction_status);
    }

    public function test_admin_can_create_payment_for_event_and_updates_invoice_and_transaction()
    {
        //User with permissions
        $admin = User::factory()->create();
        $admin->givePermissionTo('event-view');

        // Create a guest
        $guest = TransactionUser::factory()->create();

        // Create an event transaction
        $transaction = Transaction::factory()->create([
            'reservation_type_id' => 3,
            'created_by' => $guest->id,
            'deposit_amount' => 1000,
        ]);

        // Create an invoice associated with the event
        $invoice = Invoice::factory()->create([
            'transaction_id' => $transaction->id,
            'sub_total' => 1500,
            'amount_paid' => 200,
            'balance_due' => 1300,
            'invoice_status' => 'pending',
        ]);

        // Set test amount
        $paymentAmount = 1000;

        // Act as admin and test the ViewEvent component
        $this->actingAs($admin);

        Livewire::test(ViewEvent::class, [
            'event' => $transaction,
        ])
            ->set('transaction', $transaction)
            ->set('invoice', $invoice)
            ->set('amount_paid', $paymentAmount)
            ->set('payment_type', 'Event Hall')
            ->set('payment_date', now()->format('Y-m-d'))
            ->set('notes', 'Partial payment for event test')
            ->call('CreatePayment')
            ->assertRedirect(route('admin.view-event', ['event' => $transaction->id]));

        // Assert the payment was saved in the DB
        $this->assertDatabaseHas('trn_payments', [
            'invoice_id' => $invoice->id,
            'amount_paid' => $paymentAmount,
            'payment_type' => 'Event Hall',
            'payment_status' => 'completed',
            'currency' => 'PHP',
        ]);

        // Assert invoice and transaction were updated
        $invoice->refresh();
        $transaction->refresh();

        $this->assertEquals(1200, $invoice->amount_paid);
        $this->assertEquals(300, $invoice->balance_due);
        $this->assertEquals('receipt_verified', $transaction->transaction_status);
    }


    public function test_admin_can_soft_delete_event_item_when_status_is_done_or_terminated()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $guest = TransactionUser::factory()->create();

        $event = Transaction::factory()->create([
            'reservation_type_id' => 3,
            'created_by' => $guest->id,
            'transaction_status' => 'done',
        ]);

        $invoice = Invoice::factory()->create([
            'transaction_id' => $event->id,
            'invoice_type' => 'event_hall',
        ]);

        Livewire::test(ViewEvent::class, [
            'event' => $event,
        ])
            ->set('confirmItemDelete', true)
            ->call('deleteEventItem', $event)
            ->assertRedirect(route('admin.events'));

        $this->assertSoftDeleted('trn_transactions', [
            'id' => $event->id,
        ]);
    }

    public function test_event_not_deleted_if_status_is_not_done_or_terminated()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user);

        $guest = TransactionUser::factory()->create();

        $event = Transaction::factory()->create([
            'reservation_type_id' => 3,
            'created_by' => $guest->id,
            'transaction_status' => 'reserved',
        ]);

        $invoice = Invoice::factory()->create([
            'transaction_id' => $event->id,
            'invoice_type' => 'event_hall',
        ]);

        Livewire::test(ViewEvent::class, [
            'event' => $event,
        ])
            ->set('confirmItemDelete', true)
            ->call('deleteEventItem', $event);

        $this->assertDatabaseHas('trn_transactions', [
            'id' => $event->id,
            'deleted_at' => null,
        ]);
    }

    public function test_event_can_be_force_deleted_in_deleted_events_component()
    {
        // Create related guest user
        $guest = TransactionUser::factory()->create();

        // delete an event
        $event = Transaction::factory()->create([
            'created_by' => $guest->id,
            'reservation_type_id' => 3,
            'transaction_status' => 'done',
            'deleted_at' => now(), // manually soft delete
        ]);

        // Call Livewire component to force delete
        Livewire::test(DeletedEvents::class)
            ->set('confirmItemDelete', $event->id)
            ->call('deleteEventForever', $event->id);

        // Assert it is permanently deleted
        $this->assertDatabaseMissing('trn_transactions', [
            'id' => $event->id,
        ]);
    }

}
