<?php

namespace App\Livewire\Admin\Settings\PromoCodes;

use App\Models\PromoCode;
use Livewire\Component;

class DeletedPromoCodes extends Component
{
    //----------------- Declarations --------------- //
    public $deletedPromoCodes; 

    // ------------ Modals ------------- //
    public $confirmItemDelete = false;

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    // -------------------- Mount ------------------ //
    public function mount(){
        $this->fetchDeletedPromoCodes(); 
    }

    // ----------------------- Fetch Method ------------- //
    public function fetchDeletedPromoCodes(){
        $this->deletedPromoCodes = PromoCode::onlyTrashed()->orderBy('created_at', 'ASC')->get();
    }

    // ------------------ Restore Method ------------------- //
     public function restorePromoCode($promoCodeId)
    {
        $promoCode = PromoCode::withTrashed()->find($promoCodeId);
        if ($promoCode) {
            $promoCode->restore();
            session()->flash('message', 'Promo Code restored successfully.');
            $this->fetchDeletedPromoCodes();
        }
    }

    // -------------- Delete Forever Method ------------- //
    public function deletePromoCodeForever($promoCodeId)
    {
        $promoCode = PromoCode::withTrashed()->find($this->confirmItemDelete);
        if ($promoCode) {
            $promoCode->forceDelete();
            session()->flash('message', 'Promo Code permanently deleted.');
            $this->fetchDeletedPromoCodes();
        }
        //Closes the modal
        $this->confirmItemDelete = false;
    }

    // -------------------- Render Method --------------- //
    public function render()
    {
        // Create or reuse fake IDs for ONLY deleted records
        $fakeIDs = session('fake_ids_deleted_codes', []);

        //Fetches all ids into array
        $deletedIds = $this->deletedPromoCodes->pluck('id')->toArray();

        // Recalculate fake IDs if mismatch or deleted list has changed
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedPromoCodes as $index => $code) {
                $fakeIDs[$code->id] = 'CODE-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_deleted_codes' => $fakeIDs]);
        }

        return view('livewire.admin.settings.promo-codes.deleted-promo-codes', [
            'deletedPromoCodes' => $this->deletedPromoCodes, 
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
