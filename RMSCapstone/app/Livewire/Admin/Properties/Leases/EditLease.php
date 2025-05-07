<?php

namespace App\Livewire\Admin\Properties\Leases;

use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class EditLease extends Component
{
    // Public declarations of fields
    // public $transactionId;
    public Transaction $transaction; 
    public $reservation_type_id = 1;
    public $trn_user_type = 'tenant';

    public $house_id; // house_id
    public $houses = [];
    public $tenants; 
    public $start_date = '';
    public $end_date = '';
    public $pax; 


    public $monthly_rent; 
    public $total_amount = 0; //total amount from start date to end-date
    
    public $selectedHouse; // Declare the selectedRoom property
    public $selectedTenant;

    public $reservation_source = 'WebApp';
    public $transaction_status;

    public $confirmEditItem = false;

    public function confirmEdit()
    {
        $this->confirmEditItem = true;
    }

    public function mount(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->house_id = $transaction->properties()->first()->id ?? null;
        $this->selectedTenant = $transaction->created_by;
        $this->start_date = Carbon::parse($transaction->start_datetime)->format('Y-m-d');
        $this->end_date = Carbon::parse($transaction->end_datetime)->format('Y-m-d');
        $this->total_amount = $transaction->total_amount;
        $this->pax = $transaction->pax;
        $this->monthly_rent = $this->calculateMonthlyRentFromTotal();
        $this->reservation_source = $transaction->reservation_source;

         $this->transaction_status = $transaction->transaction_status;

        $this->tenants = TransactionUser::where('trn_user_type', 'tenant')->get();
        $this->houses = Property::where('property_type_id', 2)
            ->get()
            ->map(function ($house) {
                $hasActiveLease = Transaction::whereHas('properties', function ($q) use ($house) {
                    $q->where('transaction_properties.property_id', $house->id);
                })
                    ->where('end_datetime', '>=', now())
                    ->exists();

                $house->is_leased = $hasActiveLease;
                return $house;
            });
    }


    public function calculateMonthlyRentFromTotal()
    {
        if ($this->start_date && $this->end_date && $this->total_amount) {
            $months = Carbon::parse($this->start_date)->diffInMonths(Carbon::parse($this->end_date)) + 1;
            return $months ? round($this->total_amount / $months, 2) : 0;
        }
        return 0;
    }

    public function updatedStartDate()
    {
        $this->calculateTotalAmount();
    }

    public function updatedEndDate()
    {
        $this->calculateTotalAmount();
    }

    public function updatedMonthlyRent()
    {
        $this->calculateTotalAmount();
    }

    public function calculateTotalAmount()
    {
        if ($this->start_date && $this->end_date && $this->monthly_rent) {
            $months = Carbon::parse($this->start_date)->diffInMonths(Carbon::parse($this->end_date)) + 1;
            $this->total_amount = $months * $this->monthly_rent;
        } else {
            $this->total_amount = 0;
        }
    }

    public function updateLease(){
        $this->validate([
            'transaction_status' => 'required|in:pending,confirmed,ongoing,done,terminated',
        ]);
    
        // Directly use the $transaction model, no need for $id here.
        $this->transaction->transaction_status = $this->transaction_status;
        $this->transaction->save();
    
        session()->flash('success', 'Lease status updated successfully!');
        return redirect()->route('admin.leases');
    }
    
    public function render()
    {
        return view('livewire.admin.properties.leases.edit-lease');
    }
}
