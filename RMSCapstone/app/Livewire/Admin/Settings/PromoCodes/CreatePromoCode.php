<?php

namespace App\Livewire\Admin\Settings\PromoCodes;

use App\Models\PromoCode;
use App\Models\PropertyCategory;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Str;

class CreatePromoCode extends Component
{
    // --------------------- VARIABLE DECLARATION ----------------------- //
    public $propertyCategories;
    public $confirmCreateItem = false;

    //Fields
    public $code;
    public $description;
    public $discount_type;
    public $discount_value;
    public $max_uses;
    public $per_user_limit;
    public $min_booking_amount;
    public $start_date;
    public $end_date;
    public $duration_days;
    public $has_expiration = '';
    public $is_active = '';
    public $property_category_id;


    // ---------------------- MOUNT --------------------------------- //
    public function mount() {
        $this->propertyCategories = PropertyCategory::get();
    }

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }


    // --------------------- CREATE METHOD ---------------------------- //
    public function savePromoCode(){
        try {

            // Cast select values to integers to not interfere with select
            $this->has_expiration = (int) $this->has_expiration;
            $this->is_active = (int) $this->is_active;

            // Validate form input
            $validated =  $this->validate([
                'code' => 'required|string|max:100|unique:promo_codes,code',
                'description' => 'nullable|string|max:100',
                'discount_type' => 'required|in:fixed,percentage',
                'discount_value' => 'required|numeric|min:2|max:1000',
                'max_uses' => 'nullable|numeric|min:1|max:30',
                'per_user_limit' => 'required|numeric|min:1|max:5',
                'min_booking_amount' => 'nullable|numeric|min:3000|max:20000',
                'start_date' => 'nullable|date|before_or_equal:end_date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'duration_days' => 'nullable|numeric|min:5|max:30',
                'has_expiration' => 'required|in:0,1',
                'is_active' => 'required|in:0,1',
                'property_category_id' => 'required|exists:property_categories,id',


            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        //dd($validated);

        //Use the validated variable for create
        PromoCode::create($validated);

         // Reset all form fields
        $this->reset([
            'code',
            'description',
            'discount_type',
            'discount_value',
            'max_uses',
            'per_user_limit',
            'min_booking_amount',
            'start_date',
            'end_date',
            'duration_days',
            'has_expiration',
            'is_active',
            'property_category_id',
        ]);

        // Flash message for success
        session()->flash('message', 'Promo Code successfully created!');

        // Redirect back to promo list
       return redirect()->route('admin.view-promo-codes');
    }

    // --------------------- GENERATE PROMO CODE ---------------------------- //
    public function generateCode()
    {
        $prefix = strtoupper(Str::random(3));
        $suffix = random_int(100, 999);
        $this->code = $prefix . $suffix;
    }

    // --------------------- CALCULATE PROMO DURATION ---------------------- //
    public function totalDays()
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        $duration = $start->diffInDays($end) + 1;

        $this->duration_days = $duration;
    }

    public function updated($property)
    {
        if (in_array($property, ['start_date', 'end_date']) && $this->start_date && $this->end_date) {
            $this->totalDays();
        }
    }



    // -------------------------- RENDER METHOD ------------------------- //
    public function render()
    {
        return view('livewire.admin.settings.promo-codes.create-promo-code');
    }
}
