<?php

namespace Tests\Feature\Admin\Reservation;

use App\Livewire\Admin\Reservations\ReservationList;
use App\Mail\ReservationCompletedMail;
use App\Mail\ReservationConfirmedMail;
use App\Models\Activity;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminReservationTest extends TestCase
{
    //ADMIN SIDE RESERVATION PROCESS


    //Confirm Reservation
    public function test_confirms_a_reservation_and_sends_email()
    {
        // Fake the mail sending
        Mail::fake();

        // Create related data using factories
        $transaction = Transaction::factory()
            ->hasAttached(Property::factory()->count(2), ['amount' => 1000])
            ->hasAttached(Activity::factory()->count(1), ['amount' => 500, 'quantity' => 2])
            ->create([
                'transaction_status' => 'pending',
            ]);

        $invoice = Invoice::factory()->create([
            'transaction_id' => $transaction->id,
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'amount_paid' => 500.00,
            'balance_due' => 500.00,
        ]);

        Setting::create([
            'company_name' => 'Test Company',
            'logo' => 'test-logo.png',
            'email' => 'admin@test.com',
            'contact_number' => '09123456789',
            'address' => '123 Admin St',
            'facebook' => 'https://facebook.com/test',
            'instagram' => 'https://instagram.com/test',
        ]);

        // Call Method
        $component = app(ReservationList::class);
        $component->confirmReservation($transaction->id);

        // Assert transaction status changed
        $this->assertDatabaseHas('trn_transactions', [
            'id' => $transaction->id,
            'transaction_status' => 'confirmed',
        ]);

        // Assert email was sent
        Mail::assertSent(ReservationConfirmedMail::class, function ($mail) use ($transaction) {
            return $mail->hasTo($transaction->transactionUser->email) &&
                   $mail->reservationData['transaction_number'] === $transaction->id;
        });

        // Assert flash message exists
        $this->assertEquals(session('message'), 'Transaction successfully confirmed!');
    }

   
    public function test_handles_transaction_not_found_gracefully()
    {
        $component = app(ReservationList::class);
        $component->confirmReservation(9999); // Non-existing ID

        $this->assertEquals(session('error'), 'Transaction not found.');
    }

    public function test_starts_the_reservation_and_sets_status_to_ongoing()
    {
        // Transaction that is confirmed
        $transaction = Transaction::factory()->create([
            'transaction_status' => 'confirmed',
        ]);

        // Act: call startReservation
        $component = app(ReservationList::class);
        $component->startReservation($transaction->id);

        // Assert: transaction status should now be 'ongoing'
        $this->assertDatabaseHas('trn_transactions', [
            'id' => $transaction->id,
            'transaction_status' => 'ongoing',
        ]);

        // Assert: session flash message is set
        $this->assertEquals(session('message'), 'Transaction successfully started!');
    }

    public function test_handles_starting_non_existent_transaction_gracefully()
    {
        
        $component = app(ReservationList::class);
        $component->startReservation(9999); // non-existent ID

        // Assert: nothing breaks, and transaction table remains unchanged
        $this->assertDatabaseMissing('trn_transactions', [
            'id' => 9999,
        ]);
    }

     public function test_marks_reservation_as_done_if_invoice_is_completed()
    {
        Mail::fake();

        // Manually insert setting
        Setting::create([
            'company_name' => 'Test Co.',
            'logo' => 'logo.png',
            'email' => 'info@testco.com',
            'contact_number' => '09999999999',
            'address' => '123 Street',
            'facebook' => 'https://facebook.com/testco',
            'instagram' => 'https://instagram.com/testco',
        ]);

        $user = TransactionUser::factory()->create();

        $transaction = Transaction::factory()->create([
            'created_by' => $user->id,
            'transaction_status' => 'ongoing',
        ]);

        
        $invoice = Invoice::factory()->create([
            'transaction_id' => $transaction->id,
            'invoice_status' => 'completed',
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
        ]);

        // Simulate Livewire call
        $component = app(ReservationList::class);
        $component->markAsDone($transaction->id);

       //Check if transaction has done status in db
        $this->assertDatabaseHas('trn_transactions', [
            'id' => $transaction->id,
            'transaction_status' => 'done',
        ]);

        Mail::assertSent(ReservationCompletedMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });

        $this->assertEquals(session('message'), 'Transaction successfully confirmed!');
    }

    public function test_does_not_mark_as_done_if_invoice_is_not_completed()
    {
        $user = TransactionUser::factory()->create();

        $transaction = Transaction::factory()->create([
            'created_by' => $user->id,
            'transaction_status' => 'ongoing',
        ]);

        Invoice::factory()->create([
            'transaction_id' => $transaction->id,
            'invoice_status' => 'pending',
        ]);

        $component = app(ReservationList::class);
        $component->markAsDone($transaction->id);

        //checks that db has no done status for the transaction
        $this->assertDatabaseMissing('trn_transactions', [
            'id' => $transaction->id,
            'transaction_status' => 'done',
        ]);

        $this->assertTrue($component->cannotMarkAsDoneModal);
    }

    public function test_shows_error_if_transaction_is_not_found()
    {
        $component = app(ReservationList::class);
        $component->markAsDone(9999); // Nonexistent

        $this->assertEquals(session('error'), 'Transaction not found.');
    }

    public function test_marks_transaction_as_no_show()
    {
        $transaction = Transaction::factory()->create([
            'transaction_status' => 'confirmed',
        ]);

       // Log::shouldReceive('info')->once()->with('Transaction ID: ' . $transaction->id);
       // Log::shouldReceive('info')->once()->with('Transaction: ', [$transaction]);

        $component = app(ReservationList::class);
        $component->markNoShow($transaction->id);

        $this->assertDatabaseHas('trn_transactions', [
            'id' => $transaction->id,
            'transaction_status' => 'no_show',
        ]);

        $this->assertEquals(session('message'), 'Transaction marked as no show!');
    }

    public function test_marks_transaction_as_cancelled()
    {
        $transaction = Transaction::factory()->create([
            'transaction_status' => 'pending',
        ]);

        $component = app(ReservationList::class);
        $component->cancelReservation($transaction->id);

        $this->assertDatabaseHas('trn_transactions', [
            'id' => $transaction->id,
            'transaction_status' => 'cancelled',
        ]);

        $this->assertEquals(session('message'), 'Transaction successfully cancelled!');
    }

    public function test_terminates_the_transaction()
    {
        $transaction = Transaction::factory()->create([
            'transaction_status' => 'confirmed',
        ]);

        $component = app(ReservationList::class);
        $component->terminateReservation($transaction->id);

        $this->assertDatabaseHas('trn_transactions', [
            'id' => $transaction->id,
            'transaction_status' => 'terminated',
        ]);

        $this->assertEquals(session('message'), 'Transaction successfully terminated!');
    }

    public function test_soft_deletes_the_transaction()
    {
        $transaction = Transaction::factory()->create();

        $component = app(ReservationList::class);
        $component->deleteReservation($transaction->id);

        $this->assertSoftDeleted('trn_transactions', [
            'id' => $transaction->id,
        ]);

        $this->assertEquals(session('message'), 'Transaction successfully deleted!');
    }
}

