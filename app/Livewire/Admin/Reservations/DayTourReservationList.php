<?php

namespace App\Livewire\Admin\Reservations;

use App\Mail\DayTourConfirmedMail;
use App\Mail\DayTourCompletedMail;
use App\Mail\DayTouReservationCompletedMail;
use App\Mail\DayTouReservationConfirmedMail;
use App\Mail\ReservationCompletedMail;
use App\Mail\ReservationConfirmedMail;
use App\Models\Transaction;
use App\Models\TransactionUser;
use App\Models\DayTour;
use App\Models\DayTourRate;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use Spatie\Activitylog\Models\Activity as LogActivity;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;


#[Layout('layouts.app')]
class DayTourReservationList extends Component
{
    use WithPagination;

    // Properties that can be modified via URL parameters
    #[Url(history: true)]
    public $search = '';

    #[Url]
    public $perPage = 10;

    #[Url(history: true)]
    public $sortBy = 'updated_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    public $statusFilter = '';
    public $reservation_type_id = 4; // Day Tour reservation type

    // ------------------------------- FLAGS --------------------------------------------- //
    public $confirmingAction = false;
    public $cannotMarkAsDoneModal = false;
    public $actionTitle = '';
    public $actionMessage = '';
    public $actionMethod = '';
    public $actionId;
    public $actionButtonType = 'default';

    //-------------------------------- BRANDING -------------------------------------------- //
    public string $companyName = 'Company';
    public string $logoPath = '';
    public string $companyEmail;
    public string $companyContact;
    public string $companyAddress;
    public string $facebookLink;
    public string $instagramLink;

    //------------------------------------MOUNT------------------------------------------ //
    public function mount()
    {
        // Ensure daytour-reservation-list use a separate session key
        if (!session()->has('fake_ids_daytour_reservation_list')) {
            session(['fake_ids_daytour_reservation_list' => []]);
        }
    }

    // -------------------------------------- RENDER -------------------------------------- //

    public function render()
    {
        $transactions = Transaction::query()
            ->select('trn_transactions.*')
            ->with(['transactionUser', 'guestDetails', 'dayTour', 'dayTourRate', 'invoice.payments'])
            ->where('reservation_type_id', $this->reservation_type_id)
            ->nonArchived()
            ->when($this->search !== '', function ($query) {
                $query
                    ->whereHas('transactionUser', function ($subQuery) {
                        $subQuery
                            ->where('first_name', 'like', '%' . $this->search . '%')
                            ->orWhere('last_name', 'like', '%' . $this->search . '%')
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $this->search . '%'])
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                    ->orWhere('transaction_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('transaction_status', $this->statusFilter);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        // Query for full list (for fake ID generation only)
        $allTransactions = Transaction::where('reservation_type_id', $this->reservation_type_id)
            ->orderBy('created_at', 'ASC')
            ->get();

        // Store in session
        $fakeIDs = session('fake_ids_daytour_reservation_list', []);

        // Refresh to prevent duplicates
        $needsRefresh = count($fakeIDs) !== $allTransactions->count();

        // Check if any existing ID doesn't start with DT-
        foreach ($fakeIDs as $id => $fake) {
            if (!str_starts_with($fake, 'DT-')) {
                $needsRefresh = true;
                break;
            }
        }

        if ($needsRefresh) {
            $fakeIDs = [];
            foreach ($allTransactions as $index => $transaction) {
                $fakeIDs[$transaction->id] = 'DT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_daytour_reservation_list' => $fakeIDs]);
        }

        return view('livewire.admin.reservations.day-tour-reservation-list', compact('transactions', 'fakeIDs'));
    }

    // -------------------------------------- CONFIRMATION MODAL -------------------------------------- //

    public function showActionModal($method, $title, $message, $id, $actionType = 'default')
    {
        $this->actionMethod = $method;
        $this->actionTitle = $title;
        $this->actionMessage = $message;
        $this->actionId = $id;
        $this->confirmingAction = true;
        $this->actionButtonType = $actionType;
    }

    public function executeAction()
    {
        if (method_exists($this, $this->actionMethod)) {
            $this->{$this->actionMethod}($this->actionId);
        }
        $this->confirmingAction = false;
    }

    // -------------------------------------- BUTTON ACTIONS -------------------------------------- //

    /**
     * Confirms the selected day tour transaction
     */
    public function confirmReservation($id)
    {
        $transaction = Transaction::with([
            'transactionUser',
            'dayTour',
            'dayTourRate',
            'invoice.payments',
            'guestDetails.guestType'
        ])->find($id);

        if (!$transaction) {
            session()->flash('error', 'Transaction not found.');
            return;
        }

        // Update transaction status
        $transaction->update(['transaction_status' => 'confirmed']);
        session()->flash('message', 'Day Tour reservation successfully confirmed!');

        // Send confirmation email
        $this->sendConfirmationEmail($transaction);
    }

    /**
     * Starts the selected day tour transaction
     */
    public function startReservation($id)
    {
        $transaction = Transaction::find($id);

        if ($transaction) {
            $transaction->update([
                'transaction_status' => 'ongoing',
                'actual_start_datetime' => now(),
            ]);

            session()->flash('message', 'Day Tour successfully started!');
        } else {
            session()->flash('error', 'Transaction not found.');
        }
    }

    /**
     * Marks the selected day tour transaction as 'done'
     */
    public function markAsDone($id)
    {
        $transaction = Transaction::with([
            'transactionUser',
            'dayTour',
            'dayTourRate',
            'invoice.payments',
            'guestDetails.guestType'
        ])->find($id);

        if (!$transaction) {
            session()->flash('error', 'Transaction not found.');
            return;
        }

        $invoice = $transaction->invoice;

        if (!$invoice || $invoice->invoice_status !== 'completed') {
            $this->cannotMarkAsDoneModal = true;
            return;
        }

        // Update transaction status and record actual completion
        $transaction->update([
            'transaction_status' => 'done',
            'actual_end_datetime' => now(),
        ]);

        session()->flash('message', 'Day Tour successfully marked as done!');

        // Send completion email
        $this->sendCompletionEmail($transaction);
    }

    /**
     * Marks the selected day tour transaction as 'no show'
     */
    public function markNoShow($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->update(['transaction_status' => 'no_show']);
            session()->flash('message', 'Day Tour marked as no show!');
        }
    }

    /**
     * Cancels the selected day tour transaction
     */
    public function cancelReservation($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->update(['transaction_status' => 'cancelled']);
            session()->flash('message', 'Day Tour successfully cancelled!');
        }
    }

    /**
     * Terminates the selected day tour transaction
     */
    public function terminateReservation($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->update(['transaction_status' => 'terminated']);
            session()->flash('message', 'Day Tour successfully terminated!');
        }
    }

    /**
     * Deletes the selected day tour transaction from the database
     */
    public function deleteReservation($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->delete();
            session()->flash('message', 'Day Tour successfully deleted!');
        }
    }

    // ------------------------ EMAIL METHODS ------------------------------- //

    protected function sendConfirmationEmail($transaction)
    {
        $user = $transaction->transactionUser;
        $invoice = $transaction->invoice;
        $guestDetails = $transaction->guestDetails;
        $dayTour = $transaction->dayTour;
        $dayTourRate = $transaction->dayTourRate;

        if (!$user || !$invoice) {
            logger()->error('User or invoice not found for transaction ID ' . $transaction->id);
            session()->flash('error', 'Confirmation email could not be sent due to missing data.');
            return;
        }

        $setting = Setting::first();
        $convenienceFeeTotal = $invoice->payments->sum('convenience_fee');

        // Prepare data for email
        $dayTourData = [
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'contact_number' => $user->contact_number,
            'transaction_number' => $transaction->transaction_number,
            'tour_date' => $transaction->start_datetime,
            'adult_count' => $transaction->total_adults,
            'kid_count' => $transaction->total_kids,
            'total_guests' => $transaction->pax,
            'subtotal' => $transaction->sub_total,
            'convenience_fee' => $convenienceFeeTotal,
            'total_amount' => $transaction->total_amount,
            'requests' => $transaction->requests,
            'request_reply' => $transaction->request_reply,

            'invoice_number' => $invoice->invoice_number,
            'invoice_basesubtotal' => $invoice->base_subtotal,
            'invoice_total_discount' => $invoice->total_discount,
            'invoice_subtotal' => $invoice->sub_total,
            'amount_paid' => $invoice->amount_paid,
            'balance_due' => $invoice->balance_due,

            'guest_details' => $guestDetails,

            // Branding
            'branding_company_name' => $setting->company_name,
            'logo_path' => $setting->logo,
            'branding_company_email' => $setting->email,
            'branding_company_contact' => $setting->contact_number,
            'company_address' => $setting->address,
            'facebook_link' => $setting->facebook,
            'instagram_link' => $setting->instagram,

            // Day Tour Specifics
            'day_tour_name' => $dayTour->name,
            'day_tour_rate' => $dayTourRate->rate,
        ];

        try {
            Mail::to($dayTourData['email'])->send(new DayTouReservationConfirmedMail($dayTourData));
        } catch (\Exception $e) {
            logger()->error('Day Tour confirmation email send failed: ' . $e->getMessage());
            session()->flash('error', 'Reservation confirmed, but email failed to send.');
        }
    }

    protected function sendCompletionEmail($transaction)
    {
        $user = $transaction->transactionUser;
        $invoice = $transaction->invoice;
        $guestDetails = $transaction->guestDetails;

        if (!$user) {
            logger()->error('User not found for transaction ID ' . $transaction->id);
            session()->flash('error', 'Completion email could not be sent due to missing user data.');
            return;
        }

        $setting = Setting::first();

        $dayTourData = [
            'name' => $user->first_name . ' ' . $user->last_name,
            'email' => $user->email,
            'contact_number' => $user->contact_number,
            'transaction_number' => $transaction->transaction_number,
            'tour_date' => $transaction->actual_start_datetime ?? $transaction->start_datetime,
            'completion_date' => $transaction->actual_end_datetime ?? now(),
            'adult_count' => $transaction->total_adults,
            'kid_count' => $transaction->total_kids,
            'total_guests' => $transaction->pax,
            'subtotal' => $transaction->sub_total,
            'convenience_fee' => $transaction->convenience_fee,
            'total_amount' => $transaction->total_amount,

            'invoice_number' => $invoice->invoice_number,
            'invoice_basesubtotal' => $invoice->base_subtotal,
            'invoice_total_discount' => $invoice->total_discount,
            'invoice_subtotal' => $invoice->sub_total,
            'amount_paid' => $invoice->amount_paid,
            'balance_due' => $invoice->balance_due,

            'guest_details' => $guestDetails,

            // Branding
            'branding_company_name' => $setting->company_name,
            'logo_path' => $setting->logo,
            'branding_company_email' => $setting->email,
            'branding_company_contact' => $setting->contact_number,
            'company_address' => $setting->address,
            'facebook_link' => $setting->facebook,
            'instagram_link' => $setting->instagram,
        ];

        try {
            Mail::to($dayTourData['email'])->send(new DayTouReservationCompletedMail($dayTourData));
        } catch (\Exception $e) {
            logger()->error('Day Tour completion email send failed: ' . $e->getMessage());
            session()->flash('error', 'Day Tour marked as done, but email failed to send.');
        }
    }

    // ------------------------ EXPORT PDF METHOD ------------------------------- //
    public function exportToursToday()
    {
        $today = Carbon::today();

        // Fetch all day tour transactions for today
        $transactions = Transaction::query()
            ->with(['transactionUser', 'guestDetails'])
            ->whereDate('start_datetime', $today)
            ->nonArchived()
            ->where('reservation_type_id', $this->reservation_type_id)
            ->orderBy('start_datetime')
            ->get();

        // Summary of transactions
        $totalTours = $transactions->count();
        $totalGuests = $transactions->sum('pax');
        $totalAmountEarned = $transactions->sum('total_amount');

        // Pass variables to pdf
        $pdf = Pdf::loadView('livewire.admin.reports.day-tours-today-report', [
            'transactions' => $transactions,
            'date' => $today->toDateString(),
            'totalTours' => $totalTours,
            'totalGuests' => $totalGuests,
            'totalAmountEarned' => $totalAmountEarned
        ]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'Day-Tours-Today-' . $today->format('Ymd') . '.pdf');
    }

    // -------------------------------------- SORTING --------------------------------------- //

    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = $this->sortDir == 'ASC' ? 'DESC' : 'ASC';
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = 'ASC';
    }

    // -------------------------------------- ROLLBACK OF STATUS ---------------------------- //

    public function rollbackStatus($id)
    {
        Log::info("Rollback status method called for day tour transaction ID: {$id}");

        $transaction = Transaction::find($id);

        if (!$transaction) {
            Log::warning("Day Tour transaction not found for ID: {$id}");
            return back()->with('error', 'Transaction not found.');
        }

        $currentStatus = $transaction->transaction_status;

        // Rollback map for day tours
        $rollbackMap = [
            'done' => 'ongoing',
            'ongoing' => 'confirmed',
            'confirmed' => 'receipt_verified',
            'receipt_verified' => 'reserved',
            'reserved' => 'pending',

            'cancelled' => 'reserved',
            'no_show' => 'confirmed',
            'terminated' => 'ongoing',
        ];

        $previousStatus = $rollbackMap[$currentStatus] ?? null;

        if (!$previousStatus) {
            return back()->with('error', 'No previous status available for rollback.');
        }

        $transaction->transaction_status = $previousStatus;
        $transaction->save();

        Log::info("Day Tour transaction ID {$id} rolled back from {$currentStatus} to {$previousStatus}");

        return back()->with('success', "Status rolled back to: {$previousStatus}");
    }

    // ---------------------------------- LAZY LOADING ---------------------------- //
    public function placeholder()
    {
        return view('livewire.admin.placeholder');
    }

    /**
     * Archives the selected day tour transaction
     */
    public function archiveDayTour($id)
    {
        $transaction = Transaction::find($id);
        if ($transaction) {
            $transaction->update(['transaction_status' => 'archived']);
            session()->flash('message', 'Day Tour successfully archived!');
        }
    }
}
