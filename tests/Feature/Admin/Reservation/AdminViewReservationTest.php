<?php

namespace Tests\Feature\Admin\Reservation;

use App\Livewire\Admin\Reservations\ViewReservation;
use App\Mail\SendOfficialReceiptMail;
use App\Models\Invoice;
use App\Models\Receipt;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Barryvdh\DomPDF\PDF;
use Dompdf\Dompdf;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Mockery;
use Symfony\Component\HttpFoundation\StreamedResponse;



use Tests\TestCase;

class AdminViewReservationTest extends TestCase
{
    //For receipt generation and create payment
   public function test_generates_receipt_if_invoice_is_completed()
    {
        // Create a completed invoice attached to a transaction
        $transaction = Transaction::factory()->has(
            Invoice::factory()->state([
                'invoice_status' => 'completed',
                'amount_paid' => 1000,
            ])
        )->create();

        // Load the invoice 
        $invoice = $transaction->invoice;

        
        Livewire::test(ViewReservation::class, ['transaction' => $transaction])
            ->call('GenerateReceipt')
            ->assertSet('receipt.invoice_id', $invoice->id)
            ->assertSet('receipt.amount_received', 1000)
            ->assertSet('receipt.receipt_number', fn ($val) => str_starts_with($val, 'OR-'));
    }

   public function test_show_receipt_when_invoice_has_no_balance_and_receipt_exists()
    {
        $transaction = Transaction::factory()->has(
            Invoice::factory()->state([
                'invoice_status' => 'completed',
                'balance_due' => 0,
                'amount_paid' => 1500,
            ])
        )->create();

        $invoice = $transaction->invoice;

        
        $receipt = Receipt::create([
            'invoice_id' => $invoice->id,
            'receipt_number' => 'OR-' . now()->format('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'amount_received' => $invoice->amount_paid,
            'receipt_date' => now(),
            'notes' => 'Test receipt generation',
        ]);

        // Step 3: Mount the component and call ShowReceipt
        Livewire::test(ViewReservation::class, ['transaction' => $transaction])
            ->call('ShowReceipt')
            ->assertSet('receipt.id', $receipt->id)
            ->assertSet('showReceiptModal', true);
    }

    /**
     * @method static \Mockery\Expectation shouldReceive(string $method)
     */

   public function test_admin_can_create_payment_and_updates_invoice_and_transaction()
    {
        Mail::fake(); 
        
        $user = TransactionUser::factory()->create();

        $transaction = Transaction::factory()->create([
            'created_by' => $user->id,
            'deposit_amount' => 1000,
        ]);

        $invoice = Invoice::factory()->create([
            'transaction_id' => $transaction->id,
            'sub_total' => 1500,
            'amount_paid' => 200, // Starting with partial payment
            'balance_due' => 1300,
            'invoice_status' => 'pending',
        ]);

        
        $paymentAmount = 1000;

        //Test livewire
        Livewire::test(ViewReservation::class, [
            'transaction' => $transaction,
        ])
        ->set('transaction', $transaction)
        ->set('invoice', $invoice)
        ->set('amount_paid', $paymentAmount)
        ->set('payment_type', 'Room Rent')
        //->set('payment_method_id', 1)
        ->set('payment_date', now()->format('Y-m-d'))
        ->set('notes', 'Partial payment for testing')
        ->call('CreatePayment')
        ->assertRedirect(route('admin.view-reservation', ['transaction' => $transaction->id]));

        //check db
        $this->assertDatabaseHas('trn_payments', [
            'invoice_id' => $invoice->id,
            'amount_paid' => $paymentAmount,
            'payment_type' => 'Room Rent',
            'payment_status' => 'completed',
            'currency' => 'PHP',
        ]);

        //refresh
        $invoice->refresh();
        $transaction->refresh();

        $this->assertEquals(1200, $invoice->amount_paid);
        $this->assertEquals(300, $invoice->balance_due);
        $this->assertEquals('receipt_verified', $transaction->transaction_status);
    }


    
}
