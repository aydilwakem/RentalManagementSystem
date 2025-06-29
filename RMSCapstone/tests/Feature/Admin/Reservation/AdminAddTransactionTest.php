<?php

namespace Tests\Feature\Admin\Reservation;

use App\Livewire\Admin\Reservations\AddTransaction;
use App\Models\Activity;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class AdminAddTransactionTest extends TestCase
{
    //For admin to add activities on an on-going reservation

    public function test_can_add_activity_to_cart()
    {
        $activity = Activity::factory()->create(['amount' => 500]);

        $transaction = Transaction::factory()->create([
            'pax' => 5,
        ]);

        Livewire::test(AddTransaction::class, ['transaction' => $transaction])
            ->set("quantity.{$activity->id}", 2)
            ->call('addActivityToCart', $activity->id)
            ->assertHasNoErrors()
            ->assertSet('cart.0.activity_id', $activity->id)
            ->assertSet('cart.0.quantity', 2)
            ->assertSet('cart.0.amount', 1000); // (500 * 2)
    }

    public function test_can_remove_activity_from_cart()
    {
        $activity = Activity::factory()->create(['amount' => 500]);
        $transaction = Transaction::factory()->create(['pax' => 3]);

        Livewire::test(AddTransaction::class, ['transaction' => $transaction])
            ->set("quantity.{$activity->id}", 2)
            ->call('addActivityToCart', $activity->id) // Add activity to cart
            ->assertSet('cart.0.activity_id', $activity->id)
            ->call('removeFromCart', 'activity', $activity->id) // Remove it
            ->assertSet('cart', []); // Ensure cart is empty
    }

    public function test_can_increment_activity_quantity_up_to_total_pax()
    {
        $activity = Activity::factory()->create(['amount' => 500]);
        $transaction = Transaction::factory()->create(['pax' => 5]);

        Livewire::test(AddTransaction::class, ['transaction' => $transaction])
            ->set('total_pax', 5)
            ->set("quantity.{$activity->id}", 2)
            ->call('addActivityToCart', $activity->id)
            ->call('incrementActivity', $activity->id)
            ->assertSet("quantity.{$activity->id}", 3)
            ->assertSet("cart.0.quantity", 3)
            ->assertSet("cart.0.amount", 1500); // 500 * 3
    }

    public function test_can_decrement_activity_quantity_but_not_below_one()
    {
        $activity = Activity::factory()->create(['amount' => 500]);
        $transaction = Transaction::factory()->create(['pax' => 5]);

        Livewire::test(AddTransaction::class, ['transaction' => $transaction])
            ->set('total_pax', 5)
            ->set("quantity.{$activity->id}", 3)
            ->call('addActivityToCart', $activity->id)
            ->call('decrementActivity', $activity->id)
            ->assertSet("quantity.{$activity->id}", 2)
            ->assertSet("cart.0.quantity", 2)
            ->assertSet("cart.0.amount", 1000); // 500 * 2
    }

    public function test_register_attaches_activity_and_updates_invoice()
    {
        // Create activity
        $activity = Activity::factory()->create(['amount' => 100]);

        // Create user, transaction, and invoice
        $user = TransactionUser::factory()->create();
        $transaction = Transaction::factory()->create(['created_by' => $user->id]);
        $invoice = Invoice::factory()->create([
            'transaction_id' => $transaction->id,
            'sub_total' => 500,
            'balance_due' => 200,
            'invoice_status' => 'pending',
            'requested_remaining_balance' => true,
        ]);

        Livewire::test(AddTransaction::class, ['transaction' => $transaction])
            ->set('transaction', $transaction)
            ->set('cart', [[
                'type' => 'activity',
                'activity_id' => $activity->id,
                'activity_name' => $activity->name,
                'quantity' => 2,
                'amount' => 200,
                'status' => 'pending',
            ]])
            ->call('register')
            ->assertSet('cart', []);

        // Assert activity is attached to transaction with correct pivot data
        $this->assertDatabaseHas('transaction_activities', [
            'transaction_id' => $transaction->id,
            'activity_id' => $activity->id,
            'quantity' => 2,
            'amount' => 200,
        ]);

        // Assert invoice was updated
        $invoice->refresh();
        $this->assertEquals(700, $invoice->sub_total);
        $this->assertEquals(400, $invoice->balance_due);
        $this->assertFalse($invoice->requested_remaining_balance);
    }

}
