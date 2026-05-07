<?php

namespace Tests\Feature\Admin\Leases;

use App\Livewire\Admin\Properties\Leases\CreateLease;
use App\Livewire\Admin\Properties\Leases\DeletedLeases;
use App\Livewire\Admin\Properties\Leases\EditLease;
use App\Livewire\Admin\Properties\Leases\ViewLease;
use App\Livewire\Admin\Properties\Leases\ViewLeases;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class LeasesTest extends TestCase
{
    //With Permission Routes
    public function test_leases_view_component_is_visible()
    {
        Permission::findOrCreate('leases-list');

        $user = User::factory()->create();
        $user->givePermissionTo('leases-list');

        $this->actingAs($user)
            ->get(route('admin.leases'))
            ->assertSeeLivewire(ViewLeases::class);
    }

    public function test_leases_single_view_component_is_visible()
    {
        Permission::findOrCreate('leases-view');

        $user = User::factory()->create();
        $user->givePermissionTo('leases-view');

        // Create TransactionUser
        $guest = TransactionUser::factory()->create();

        // Create Transaction
        $transaction = Transaction::factory()->create([
            'reservation_type_id' => 1, //HOUSE
            'created_by' => $guest->id,
        ]);

        //Create a valid Invoice for that transaction
        Invoice::factory()->create([
            'transaction_id' => $transaction->id,
            'invoice_type' => 'House',
        ]);

        // Test
        $this->actingAs($user)
            ->get(route('admin.view-lease', ['transaction' => $transaction->id]))
            ->assertStatus(200)
            ->assertSeeLivewire(ViewLease::class);
    }

    public function test_lease_create_component_is_visible()
    {
        Permission::findOrCreate('leases-create');

        $user = User::factory()->create();
        $user->givePermissionTo('leases-create');

        $this->actingAs($user)
            ->get(route('admin.create-lease'))
            ->assertSeeLivewire(CreateLease::class);
    }

    public function test_lease_edit_component_is_visible()
    {
      
       Permission::findOrCreate('leases-edit');

       $user = User::factory()->create();
       $user->givePermissionTo('leases-edit');

        // Create TransactionUser
        $guest = TransactionUser::factory()->create();

        // Create Transaction
        $transaction = Transaction::factory()->create([
            'reservation_type_id' => 1, //HOUSE
            'created_by' => $guest->id,
        ]);

        // Add invoice
        Invoice::factory()->create([
            'transaction_id' => $transaction->id,
            'invoice_type' => 'House',
        ]);

        // Act as the user and visit the edit route 
        $this->actingAs($user)
        ->get(route('admin.edit-lease', ['transaction' => $transaction->id]))
        ->assertSeeLivewire(EditLease::class);
    }

     public function test_leases_soft_delete_component_is_visible()
    {
        Permission::findOrCreate('leases-soft-delete');

        $user = User::factory()->create();
        $user->givePermissionTo('leases-soft-delete');

        $this->actingAs($user)
            ->get(route('admin.deleted-leases'))
            ->assertSeeLivewire(DeletedLeases::class);
    }

    //Routes require permission
    //Without Permission
    public function test_lease_list_route_requires_permission()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.leases'))
            ->assertForbidden(); // or ->assertStatus(403);
    }

    public function test_lease_single_view_route_requires_permission()
    {
        $guest = TransactionUser::factory()->create();

        $transaction = Transaction::factory()->create([
            'reservation_type_id' => 1,
            'created_by' => $guest->id,
        ]);

        Invoice::factory()->create([
            'transaction_id' => $transaction->id,
            'invoice_type' => 'House',
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.view-lease', ['transaction' => $transaction->id]))
            ->assertForbidden();
    }

     public function test_lease_create_route_requires_permission()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.create-lease'))
            ->assertForbidden();
    }

    public function test_leases_edit_route_requires_permission()
    {
        $guest = TransactionUser::factory()->create();

        $transaction = Transaction::factory()->create([
            'reservation_type_id' => 1,
            'created_by' => $guest->id,
        ]);

        Invoice::factory()->create([
            'transaction_id' => $transaction->id,
            'invoice_type' => 'House',
        ]);

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.edit-lease', ['transaction' => $transaction->id]))
            ->assertForbidden();
    }

    public function test_lease_soft_delete_route_requires_permission()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.deleted-leases'))
            ->assertForbidden();
    }


    // ---------------------- CRUD TRANSACTIONS 
    public function test_lease_can_be_created()
    {
        //$this->withoutExceptionHandling();

        // Create a tenant
        $tenant = TransactionUser::factory()->create([
            'trn_user_type' => 'tenant',
        ]);

        // Create an available house (property)
        $house = Property::factory()->create([
            'property_type_id' => 2, // HOUSE
            'property_status' => 'available',
        ]);

        // Insert default setting for deposit percentage
        DB::table('st_settings')->updateOrInsert(['id' => 1], ['deposit_percentage' => 50]);

        // Lease values
        $totalAmount = 30000;
        $depositAmount = $totalAmount * 0.5;
        $start = now()->addDays(5)->startOfDay();
        $end = now()->addMonths(4)->startOfDay(); // Valid: at least 3 months later

        // Create lease-like transaction
        $transaction = Transaction::create([
            'transaction_number' => 'LSE-' . strtoupper(Str::random(8)),
            'reservation_type_id' => 1, // house
            'created_by' => $tenant->id,
            'trn_user_type' => 'tenant',
            'start_datetime' => $start,
            'end_datetime' => $end,
            'total_amount' => $totalAmount,
            'deposit_amount' => $depositAmount,
            'reservation_source' => 'WebApp',
            'transaction_status' => 'pending',
            'pax' => 3,
        ]);

        // Create invoice
        $invoice = Invoice::create([
            'transaction_id' => $transaction->id,
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'invoice_type' => 'House',
            'sub_total' => $totalAmount,
            'deposit_paid' => 0,
            'amount_paid' => 0,
            'balance_due' => $totalAmount,
            'due_date' => $end,
            'invoice_status' => 'pending',
        ]);

        // Attach house to transaction
        $days = $start->diffInDays($end) + 1;

        $transaction->properties()->attach($house->id, [
            'adults' => 1,
            'kids' => 0,
            'extra_guest' => 0,
            'extra_charge' => 0,
            'amount' => $totalAmount,
            'total_amount' => $totalAmount,
            'days' => $days,
        ]);

        //Assertions
        $this->assertDatabaseHas('trn_transactions', [
            'id' => $transaction->id,
            'reservation_type_id' => 1,
            'created_by' => $tenant->id,
            'total_amount' => $totalAmount,
            'transaction_status' => 'pending',
        ]);

        $this->assertDatabaseHas('trn_invoice', [
            'transaction_id' => $transaction->id,
            'invoice_type' => 'House',
            'balance_due' => $totalAmount,
        ]);

        $this->assertDatabaseHas('transaction_properties', [
            'transaction_id' => $transaction->id,
            'property_id' => $house->id,
            'total_amount' => $totalAmount,
            'days' => $days,
        ]);
    }

     public function test_lease_transaction_status_can_be_updated()
    {
        // Create a tenant
        $tenant = TransactionUser::factory()->create(['trn_user_type' => 'tenant']);

        // Create a lease transaction
        $lease = Transaction::factory()->create([
            'reservation_type_id' => 1, // Lease
            'created_by' => $tenant->id,
            'transaction_status' => 'pending',
        ]);

        // Add invoice (optional, depending on mount() logic)
        Invoice::factory()->create([
            'transaction_id' => $lease->id,
            'invoice_type' => 'House',
        ]);

        // Simulate Livewire update
        Livewire::test(EditLease::class, [
            'transaction' => $lease,
        ])
        ->set('transaction_status', 'confirmed')
        ->call('updateLease')
        ->assertRedirect(route('admin.leases'));

        $lease->refresh();

        $this->assertEquals('confirmed', $lease->transaction_status);
        $this->assertDatabaseHas('trn_transactions', [
            'id' => $lease->id,
            'transaction_status' => 'confirmed',
        ]);
    }

     public function test_admin_can_create_payment_for_lease_and_updates_invoice_and_transaction()
    {
        // Create admin and give dummy permission
        $admin = User::factory()->create();
        $admin->givePermissionTo('leases-view');

        // Create a tenant
        $tenant = TransactionUser::factory()->create([
            'trn_user_type' => 'tenant',
        ]);

        // Create a lease transaction
        $transaction = Transaction::factory()->create([
            'reservation_type_id' => 1, // lease
            'created_by' => $tenant->id,
            'deposit_amount' => 1000,
        ]);

        // Create invoice for the lease
        $invoice = Invoice::factory()->create([
            'transaction_id' => $transaction->id,
            'sub_total' => 1500,
            'amount_paid' => 200,
            'balance_due' => 1300,
            'invoice_status' => 'pending',
            'invoice_type' => 'House',
        ]);

        $paymentAmount = 1000;

        // Act as admin and test Livewire payment
        $this->actingAs($admin);

        Livewire::test(ViewLease::class, [
            'transaction' => $transaction,
        ])
            ->set('transaction', $transaction)
            ->set('invoice', $invoice)
            ->set('amount_paid', $paymentAmount)
            ->set('payment_type', 'House Rent')
            ->set('payment_date', now()->format('Y-m-d'))
            ->set('notes', 'Partial lease payment')
            ->call('CreatePayment')
            ->assertRedirect(route('admin.view-lease', ['transaction' => $transaction->id]));

        // Check DB record
        $this->assertDatabaseHas('trn_payments', [
            'invoice_id' => $invoice->id,
            'amount_paid' => $paymentAmount,
            'payment_type' => 'House Rent',
            'payment_status' => 'completed',
            'currency' => 'PHP',
        ]);

        // Check invoice and transaction status updates
        $invoice->refresh();
        $transaction->refresh();

        $this->assertEquals(1200, $invoice->amount_paid);
        $this->assertEquals(300, $invoice->balance_due);
        $this->assertEquals('receipt_verified', $transaction->transaction_status);
    }

    public function test_admin_can_soft_delete_lease_item_when_status_is_done_or_terminated()
    {
        $this->withoutExceptionHandling();

        $user = User::factory()->create();
        $this->actingAs($user);

        $guest = TransactionUser::factory()->create();

        $transaction = Transaction::factory()->create([
            'reservation_type_id' => 1,
            'created_by' => $guest->id,
            'transaction_status' => 'done',
        ]);

        $invoice = Invoice::factory()->create([
            'transaction_id' => $transaction->id,
            'invoice_type' => 'House',
        ]);

        Livewire::test(ViewLease::class, [
            'transaction' => $transaction,
        ])
            ->set('confirmItemDelete', true)
            ->call('deleteLease', $transaction)
            ->assertRedirect(route('admin.leases'));

        $this->assertSoftDeleted('trn_transactions', [
            'id' => $transaction->id,
        ]);
    }

    public function test_lease_not_deleted_if_status_is_not_done_or_terminated()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $guest = TransactionUser::factory()->create();

        $transaction = Transaction::factory()->create([
            'reservation_type_id' => 1,
            'created_by' => $guest->id,
            'transaction_status' => 'reserved',
        ]);

        $invoice = Invoice::factory()->create([
            'transaction_id' => $transaction->id,
            'invoice_type' => 'House',
        ]);

        Livewire::test(ViewLease::class, [
            'transaction' => $transaction,
        ])
            ->set('confirmItemDelete', true)
            ->call('deleteLease', $transaction);

        $this->assertDatabaseHas('trn_transactions', [
            'id' => $transaction->id,
            'deleted_at' => null,
        ]);
    }

    public function test_lease_can_be_force_deleted_in_deleted_leases_component()
    {
        // Create related guest user
        $tenant = TransactionUser::factory()->create();

        // delete lease
        $transaction = Transaction::factory()->create([
            'created_by' => $tenant->id,
            'reservation_type_id' => 1,
            'transaction_status' => 'done',
            'deleted_at' => now(), // manually soft delete
        ]);

        // Call Livewire component to force delete
        Livewire::test(DeletedLeases::class)
            ->set('confirmItemDelete', $transaction->id)
            ->call('deleteLeaseForever', $transaction->id);

        // Assert it is permanently deleted
        $this->assertDatabaseMissing('trn_transactions', [
            'id' => $transaction->id,
        ]);
    }
}
