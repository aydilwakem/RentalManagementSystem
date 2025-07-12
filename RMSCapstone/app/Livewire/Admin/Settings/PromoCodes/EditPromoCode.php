<?php

namespace App\Livewire\Admin\Settings\PromoCodes;

use App\Models\PromoCode;
use App\Models\PropertyCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;

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
    public $is_active;
    public $property_category_id;
    public $propertyCategories; 

    // --------------- Modals ------------------ //
    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    // ------------- Promo Code ------------- //
    public function mount(PromoCode $promoCode){

        $this->promoCode = $promoCode;

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
        $this->property_category_id = $promoCode->property_category_id;

        $this->propertyCategories = PropertyCategory::get();

        $this->has_expiration = (string) $promoCode->has_expiration;
        $this->is_active = (string) $promoCode->is_active;

    }

    // -------------- Edit Method ---------------- //
    public function updatePromoCode(){
         try {
        
            // Cast select values to integers to not interfere with select 
            $this->has_expiration = (int) $this->has_expiration;
            $this->is_active = (int) $this->is_active;

            // Validate form input
            $validated =  $this->validate([
                'code' => 'required|string|max:100',
                'description' => 'required|string|max:100',
                'discount_type' => 'required|in:fixed,percentage',
                'discount_value' => 'required|numeric|min:2|max:1000',
                'max_uses' => 'required|numeric|min:1|max:30',
                'per_user_limit' => 'required|numeric|min:1|max:5',
                'min_booking_amount' => 'required|numeric|min:3000|max:20000',
                'start_date' => 'required|date|before_or_equal:end_date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'duration_days' => 'required|numeric|min:5|max:30',
                'has_expiration' => 'required|in:0,1',
                'is_active' => 'required|in:0,1',
                'property_category_id' => 'required|exists:property_categories,id', 


            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
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
            'duration_days' => $this->duration_days,
            'has_expiration' => $this->has_expiration,
            'is_active' => $this->is_active,
            'property_category_id' => $this->property_category_id,     
        ]);

        session()->flash('message', 'Promo Code successfully updated!');

        return redirect()->route('admin.view-promo-codes');
    }

    // ------------ Render Method ------------- //
    public function render()
    {
        return view('livewire.admin.settings.promo-codes.edit-promo-code');
    }
}
