<?php

namespace App\Livewire\Admin\Settings\PromoCodes;

use App\Models\PromoCode;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewPromoCode extends Component
{
    // ----------------- Declarations ----------- //
    public PromoCode $promoCode; 
    public $has_expiration = '0';
    public $is_active = '0';

    // ----------------- Modals --------------- //
    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

    // ---------------- Confirm Modal -------------- //
     public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    // --------- Delete Method ---------- //
    public function deletePromoCode(){
        if ($this->confirmItemDelete) {
        $promoCode = PromoCode::find($this->confirmItemDelete);

        if (!$promoCode) {
            session()->flash('error', 'Promo Code not found!');
            return redirect()->route('admin.view-promo-codes');
        }

        //Check if active
        if ($promoCode->is_active) {
            $this->cannotDeleteItem = true;
            $this->confirmItemDelete = null;
            return;
        }

        // Attempt delete 
        $promoCode->delete();

        $this->confirmItemDelete = null;

        session()->flash('message', 'Promo Code successfully deleted!');
        }

        return redirect()->route('admin.view-promo-codes');
    }


    // --------------- Render Method ----------- //
    public function render()
    {
        return view('livewire.admin.settings.promo-codes.view-promo-code');
    }
}
