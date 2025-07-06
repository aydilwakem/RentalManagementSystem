<?php

namespace App\Livewire\Admin\Properties\Leases;

use App\Models\Invoice;
use App\Models\Property;
use App\Models\Transaction;
use App\Models\TransactionUser;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreateLease extends Component
{
    //---------------------------------------------- DECLARATIONS ----------------------------------//
    use WithFileUploads;
    public $reservation_type_id = 1; // House
    public $trn_user_type = 'tenant';

    //---------------------------------------------- FIELDS ----------------------------------//
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
    public $transaction_status;

    public $selectedTenant; // Tenant selected from dropdown
    public $confirmCreateItem = false; //modal

    //---------------------------------------------- MODAL METHOD ----------------------------------//
    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    //-------------------------------------- CALCULATE TOTAL AMOUNT OF DURATION ----------------------------------//
    public function calculateTotalAmount()
    {
        if ($this->start_date && $this->end_date && $this->monthly_rent) {
            $start = Carbon::parse($this->start_date);
            $end = Carbon::parse($this->end_date);

            // Calculate number of months
            $months = $start->diffInMonths($end) + 1;

            // Multiply by rent
            $this->total_amount = $months * $this->monthly_rent;
        } else {
            $this->total_amount = 0;
        }
    }

    //---------------------------------------------- LIVEWIRE UPDATES ----------------------------------//
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


    //---------------------------------------------- MOUNT ----------------------------------//
    public function mount()
    {
        //Mount Tenants and Houses
        $this->tenants = TransactionUser::where('trn_user_type', 'tenant')->get();
        $this->houses = Property::where('property_type_id', 2)->get();

        $this->houses = Property::ofType('House')->where('property_status', 'available')->get();

        // Set default check-in/out dates
        $now = Carbon::now('Asia/Manila');
        $this->start_date = $now->format('Y-m-d');
        $this->end_date = $now->copy()->addMonths((3))->format('Y-m-d');
    }

    //------------------------------- DISPLAY MONTHLY RENT METHOD ----------------------------------//
    public function updatedHouseId($value)
    {
        //$house = $this->houses->firstWhere('id', $value);
        $house = collect($this->houses)->firstWhere('id', $value);
        $this->monthly_rent = $house ? $house->amount : 0;

        $this->calculateTotalAmount();

        // Optional: Re-run to refresh booking flags
        $this->getAvailableHouses();
        $this->getAvailableTenants();
    }


    //---------------------------------------------- SAVE METHOD ----------------------------------//
    public function saveLease()
    {
        try {
            $this->validate([
                'house_id' => 'required|exists:properties,id',
                'selectedTenant' => 'required|exists:trn_users,id',
                //'start_date' => 'required|date',
                'start_date' => ['required', 'date',
                function ($attribute, $value, $fail) {
                    $startDate = Carbon::parse($value);
                    $startOfMonth = now()->startOfMonth();

                    if ($startDate->lessThan($startOfMonth)) {
                        $fail('The start date cannot be from a previous month.');
                    }
                },
            ],

            'end_date' => ['required','date','after:start_date',
                function ($attribute, $value, $fail) {
                    if (isset($this->start_date)) {
                        $start = \Carbon\Carbon::parse($this->start_date);
                        $end = \Carbon\Carbon::parse($value);

                        if ($end->lessThan($start->copy()->addMonths(3))) {
                            $fail('The end date must be at least 3 months after the start date.');
                        }
                    }
                },
            ],

                //'end_date' => 'required|date|after:start_date',
                'total_amount' => 'required|numeric|min:0',
                'pax' => 'required|numeric|min:1',
                'transaction_status' => 'required|in:pending,confirmed,ongoing,terminated',

            ]);
        } catch (ValidationException $e) {
            $this->confirmCreateItem = false;
            throw $e;
        }

        DB::transaction(function () {
            $depositPercentage = DB::table('st_settings')->value('deposit_percentage');

            // Step 1: Create transaction
            $transaction = Transaction::create([
                'transaction_number' => 'LSE-' . strtoupper(Str::random(8)),
                'reservation_type_id' => $this->reservation_type_id,
                'trn_user_type' => $this->trn_user_type,
                'start_datetime' => Carbon::parse(time: $this->start_date),
                'end_datetime' => Carbon::parse(time: $this->end_date),
                'total_amount' => $this->total_amount,
                'deposit_amount' => $this->total_amount * ($depositPercentage / 100),
                'reservation_source' => $this->reservation_source,
                'transaction_status' => $this->transaction_status,
                'created_by' => $this->selectedTenant,
                'pax' => $this->pax,
            ]);

            // Step 2: Generate invoice number
            $invoiceNumber = 'INV-' . strtoupper(Str::random(8));

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


    //------------------------------------GET TENANT METHOD ----------------------------------//
    public function getAvailableTenants()
    {
        if (!$this->start_date || !$this->end_date) {
            return;
        }

        $startDate = Carbon::parse($this->start_date)->startOfDay();
        $endDate = Carbon::parse($this->end_date)->endOfDay();

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

    //------------------------------------ GET HOUSE METHOD ----------------------------------//
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


//------------------------------------- RENDER ----------------------------------//
    public function render()
    {
        return view('livewire.admin.properties.leases.create-lease', [
            'houses' => $this->houses,
            'tenants' => $this->tenants,
        ]);
    }
}
