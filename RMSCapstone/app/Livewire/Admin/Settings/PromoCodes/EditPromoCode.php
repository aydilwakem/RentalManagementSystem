<?php

namespace App\Livewire\Admin\Settings\PromoCodes;

use App\Models\PromoCode;
use App\Models\PropertyCategory;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


#[Layout('layouts.app')]
class EditPromoCode extends Component
{
    // --------------  Declarations ----------- //
    public PromoCode $promoCode;

    // ------------- Fields --------------- //
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
    public $has_expiration;
    public $is_active = false;
    public $property_category_id;
    public $propertyCategories;
    public $stay_start_date;
    public $stay_end_date;

    // --------------- Modals ------------------ //
    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    // ------------- Promo Code ------------- //
    public function mount(PromoCode $promoCode)
    {

        $this->promoCode = $promoCode;


        // toggle value
        $this->is_active = (bool) $promoCode->is_active;
        $this->has_expiration = (bool) $promoCode->has_expiration;

        $this->code = $promoCode->code;
        $this->description = $promoCode->description;
        $this->discount_type = $promoCode->discount_type;
        $this->discount_value = $promoCode->discount_value;
        $this->max_uses = $promoCode->max_uses;
        $this->per_user_limit = $promoCode->per_user_limit;
        $this->min_booking_amount = $promoCode->min_booking_amount;
        $this->start_date = optional($promoCode->start_date)->format('Y-m-d');
        $this->end_date = optional($promoCode->end_date)->format('Y-m-d');
        $this->duration_days = $promoCode->duration_days;
        // $this->has_expiration = $promoCode->has_expiration;
        // $this->is_active = $promoCode->is_active;
        
        $this->stay_start_date = optional($promoCode->stay_start_date)->format('Y-m-d');
        $this->stay_end_date = optional($promoCode->stay_end_date)->format('Y-m-d');

        $this->property_category_id = $promoCode->property_category_id;

        $this->propertyCategories = PropertyCategory::get();

        //$this->has_expiration = (string) $promoCode->has_expiration;
        //$this->is_active = (string) $promoCode->is_active;

    }

    // -------------- Edit Method ---------------- //
    public function updatePromoCode()
    {
        try {

            // Cast select values to integers to not interfere with select
            $this->has_expiration = (int) $this->has_expiration;
            $this->is_active = (int) $this->is_active;

            // If has_expiration is true but no dates are given, force it to false
            if ($this->has_expiration === 1 && empty($this->start_date) && empty($this->end_date)) {
                $this->has_expiration = 0;
            }


            // Validate form input
            $validated =  $this->validate([
                'code' => [
                    'required',
                    'string',
                    'max:100',
                    Rule::unique('promo_codes', 'code')->ignore($this->promoCode->id),
                ],
                'description' => 'nullable|string|max:100',
                'discount_type' => 'required|in:fixed,percentage',
                'discount_value' => 'required|numeric|min:2|max:1000',
                'max_uses' => 'nullable|numeric|min:1|max:30',
                'per_user_limit' => 'required|numeric|min:1|max:5',
                'min_booking_amount' => 'nullable|numeric|min:3000|max:20000',
                'start_date' => 'nullable|date|before_or_equal:end_date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
                'stay_start_date' => 'nullable|date|before_or_equal:stay_end_date',
                'stay_end_date' => 'nullable|date|after_or_equal:stay_start_date',
                'duration_days' => 'nullable|numeric|min:5|max:30',
                'has_expiration' => 'required|in:0,1',
                'is_active' => 'required|in:0,1',
                'property_category_id' => 'nullable|exists:property_categories,id',

            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
        }


        // Convert blank optional numeric/date fields to NULL
        foreach (['max_uses', 'min_booking_amount', 'start_date', 'end_date', 'stay_start_date', 'stay_end_date', 'duration_days', 'property_category_id'] as $field) {
            if (!isset($validated[$field]) || $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        //Update Promo Code
        $this->promoCode->update([
            'code' => $this->code,
            'description' => $this->description,
            'discount_type' => $this->discount_type,
            'discount_value' => $this->discount_value,
            'max_uses' => $this->max_uses,
            'per_user_limit' => $this->per_user_limit,
            'min_booking_amount' => $this->min_booking_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'stay_start_date' => $this->stay_start_date,
            'stay_end_date' => $this->stay_end_date,
            'duration_days' => $this->duration_days,
            'has_expiration' => $this->has_expiration,
            'is_active' => $this->is_active,
            'property_category_id' => $this->property_category_id,
        ]);

        session()->flash('message', 'Promo Code successfully updated!');

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

    // ------------ Render Method ------------- //
    public function render()
    {
        return view('livewire.admin.settings.promo-codes.edit-promo-code');
    }
}
