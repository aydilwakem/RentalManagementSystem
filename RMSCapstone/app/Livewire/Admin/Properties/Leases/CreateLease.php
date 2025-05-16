<?php

namespace App\Livewire\Admin\Properties\Leases;

use App\Models\Invoice;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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
        $this->getAvailableHouses();
        $this->getAvailableTenants();
    }

    public function updatedEndDate()
    {
        $this->calculateTotalAmount();
        $this->getAvailableHouses();
        $this->getAvailableTenants();
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
        
        $this->houses = Property::ofType('House')->where('property_status', 'available')->get();

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
        }catch (ValidationException $e) {
        $this->confirmCreateItem = false;
        throw $e;
    }

        DB::transaction(function () {
        $depositPercentage = DB::table('st_settings')->value('deposit_percentage');

        // Step 1: Create transaction
        $transaction = Transaction::create([
            'reservation_type_id' => $this->reservation_type_id,
            'trn_user_type' => $this->trn_user_type,
            'start_datetime' => Carbon::parse($this->start_date),
            'end_datetime' => Carbon::parse($this->end_date),
            'total_amount' => $this->total_amount,
            'deposit_amount' => $this->total_amount * ($depositPercentage / 100),
            'reservation_source' => $this->reservation_source,
            'transaction_status' => $this->transaction_status,
            'created_by' => $this->selectedTenant,
            'pax' => $this->pax,
        ]);

        // Step 2: Generate invoice number
        $latestInvoice = Invoice::whereYear('created_at', now()->year)->orderBy('created_at', 'desc')->first();
        $invoiceNumber = 'INV-' . now()->year . '-' . str_pad(($latestInvoice ? (int)substr($latestInvoice->invoice_number, -3) + 1 : 1), 3, '0', STR_PAD_LEFT);

        // Step 3: Create invoice
        Invoice::create([
            'transaction_id' => $transaction->id,
            'invoice_number' => $invoiceNumber,
            'invoice_type' => 'House',
            'sub_total' => $this->total_amount,
            'deposit_paid' => 0,
            'amount_paid' => 0,
            'balance_due' => $this->total_amount,
            'due_date' => Carbon::parse($this->end_date),
            'invoice_status' => 'pending',
        ]);

        // Step 4: Calculate number of days
        $days = Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1;

        // Step 5: Attach house to transaction
        $transaction->properties()->attach($this->house_id, [
            'adults' => 1,
            'kids' => 0,
            'extra_guest' => 0,
            'extra_charge' => 0,
            'amount' => $this->total_amount,
            'total_amount' => $this->total_amount,
            'days' => $days,
        ]);
    });

    $this->reset(['start_date', 'end_date', 'total_amount', 'transaction_status', 'selectedTenant', 'pax', 'house_id']);
    session()->flash('success', 'Lease saved successfully!');
    return redirect()->route('admin.leases');
    
}

    //To get all available tenants and marked those unavailable as leased
    public function getAvailableTenants()
    {
        if (!$this->start_date || !$this->end_date) {
            return;
        }
    
    $startDate = \Carbon\Carbon::parse($this->start_date)->startOfDay();
    $endDate = \Carbon\Carbon::parse($this->end_date)->endOfDay();
    
        // Step 1: Get all available event halls (unfiltered)
        $allTenants = TransactionUser::where('trn_user_type', 'tenant')
            ->get();
    
        // Step 2: Load only overlapping transactions manually
        $allTenants->load(['transactions' => function ($query) use ($startDate, $endDate) {
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->where('start_datetime', '<', $endDate)
                  ->where('end_datetime', '>', $startDate);
            });
        }]);
    
        // Step 3: Flag each hall as booked if it has any overlapping transactions
        $this->tenants = $allTenants->map(function ($tenant) {
        $tenant->isLeased = $tenant->transactions->isNotEmpty();

        if ($tenant->isLeased) {
            $property = $tenant->transactions
                ->first()
                ->properties()
                ->first();

            $tenant->leasedPropertyName = $property?->name_number ?? 'Unnamed Property';
        } else {
            $tenant->leasedPropertyName = null;
        }

        return $tenant;
    });
    
    }

    //To get all available houses and marked those unavailable as Booked
    public function getAvailableHouses()
    {
        if (!$this->start_date || !$this->end_date) {
            return;
        }
    
    $startDate = \Carbon\Carbon::parse($this->start_date)->startOfDay();
    $endDate = \Carbon\Carbon::parse($this->end_date)->endOfDay();
    
        // Step 1: Get all available event halls (unfiltered)
        $allHouses = Property::ofType('House')
            ->where('property_status', 'available')
            ->get();
    
        // Step 2: Load only overlapping transactions manually
        $allHouses->load(['transactions' => function ($query) use ($startDate, $endDate) {
            $query->where(function ($q) use ($startDate, $endDate) {
                $q->where('start_datetime', '<', $endDate)
                  ->where('end_datetime', '>', $startDate);
            });
        }]);
    
        // Step 3: Flag each house as booked if it has any overlapping transactions
        $this->houses = $allHouses->map(function ($house) {
            $house->isBooked = $house->transactions->isNotEmpty();
            return $house;
        });
    
    }



    public function render()
    {
        return view('livewire.admin.properties.leases.create-lease',[
            'houses' => $this->houses,
            'tenants' => $this->tenants,
        ]);
    }
}
