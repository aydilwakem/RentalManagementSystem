<?php

namespace App\Livewire\Admin\Properties\Leases;

use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreateLease extends Component
{

    use WithFileUploads;
    public $reservation_type_id = 1; // House
    public $trn_user_type = 'tenant';

    // Public declarations of fields
    public $house_id; // house_id
    public $houses = [];
    public $tenants; 
    public $start_date = '';
    public $end_date = '';
    public $pax; 


    public $monthly_rent; 
    public $total_amount = 0; //total amount from start date to end-date
    public $selectedHouse; // Declare the selectedRoom property
    
    public $reservation_source = 'WebApp';
    public $transaction_status = 'pending';

    public $selectedTenant; // Tenant selected from dropdown
    public $confirmCreateItem = false; //modal

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function calculateTotalAmount()
    {
        if ($this->start_date && $this->end_date && $this->monthly_rent) {
            $start = Carbon::parse($this->start_date);
            $end = Carbon::parse($this->end_date);

            // Calculate number of months (ceil to charge full month even if partial)
            $months = $start->diffInMonths($end) + 1;

            // Multiply by rent
            $this->total_amount = $months * $this->monthly_rent;
        } else {
            $this->total_amount = 0;
        }
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


    //Mount all tenants
    public function mount(){
        //Mount Tenants and Houses
        $this->tenants = TransactionUser::where('trn_user_type', 'tenant')->get();
        $this->houses = Property::where('property_type_id', 2)->get();

        //Use map function to check if there is an entry with an active lease
        //Check if the house has an active lease in transactions_property
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

        // Load tenants and check for active leases, display the leased
        $this->tenants = TransactionUser::where('trn_user_type', 'tenant')
        ->get()
        ->map(function ($tenant) {
            // Find an active transaction for the tenant (created_by is the tenant ID)
            $activeLease = Transaction::where('created_by', $tenant->id)
                ->where('end_datetime', '>=', now())
                ->first();

            $tenant->has_active_lease = false;
            $tenant->leased_property = null;

            if ($activeLease) {
                // Get the property linked via the transaction_properties pivot table
                $property = $activeLease->properties()->first(); // Retrieve the first property assigned

                if ($property) {
                    $tenant->has_active_lease = true;
                    $tenant->leased_property = $property->name_number ?? 'Unnamed Property';
                }
            }

            return $tenant;
        });
    }

    public function saveLease(){
        try{
        $this->validate([
            'house_id' => 'required|exists:properties,id',
            'selectedTenant' => 'required|exists:trn_users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'total_amount' => 'required|numeric|min:0',
            'pax' => 'required|numeric|min:1',
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmCreateItem = false;
        throw $e;
    }
    
        // Create the lease (Transaction)
        $transaction = Transaction::create([
            'reservation_type_id' => $this->reservation_type_id,
            'trn_user_type' => $this->trn_user_type,
            'start_datetime' => Carbon::parse($this->start_date),
            'end_datetime' =>  Carbon::parse($this->end_date),
            'total_amount' => $this->total_amount,
            'reservation_source' => $this->reservation_source,
            'transaction_status' => $this->transaction_status,
            'created_by' => $this->selectedTenant,
            'pax' => $this->pax, 
        ]);
        
    
        // Calculate number of days
        $days = Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1;
    
        // Attach house to transaction with pivot data
        $transaction->properties()->attach($this->house_id, [
            'adults' => 1,
            'kids' => 0,
            'extra_guest' => 0,
            'extra_charge' => 0,
            'amount' => $this->total_amount,
            'total_amount' => $this->total_amount,
            'days' => $days,
        ]);

        // Reset form fields
        $this->reset(['start_date', 'end_date', 'total_amount', 'transaction_status', 'selectedTenant',  'pax', 'house_id']);
        
        session()->flash('message', 'Lease saved successfully!');
        return redirect()->route('admin.leases');
    }







    public function render()
    {
        return view('livewire.admin.properties.leases.create-lease');
    }
}
