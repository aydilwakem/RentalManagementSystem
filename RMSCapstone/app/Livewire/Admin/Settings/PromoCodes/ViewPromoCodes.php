<?php

namespace App\Livewire\Admin\Settings\PromoCodes;

use App\Models\PromoCode;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewPromoCodes extends Component
{
    // ------------------------ Pagination and Sorting ---------------- //
    use WithPagination;
    #[Url(history: true)]
    public $search = '';
    #[Url()]
    public $perPage = 10;
    #[Url(history: true)]
    public $sortBy = 'created_at';
    #[Url(history: true)]
    public $sortDir = 'DESC';
    
    // --------------------- Modals ----------------------- //
    public $confirmItemDelete = false;
    public $confirmBulkDelete = false;
    public $selectedItemId = null;
    public $cannotDeleteItem = false;

    // -------------- Bulk Deletes ----------------------- //
    public $selectedRows = [];
    public $selectPageRows = false;

    public function updatedSelectPageRows($value){
        if ($value){
            $this->selectedRows = $this->promoCodes->pluck('id')->map(function ($id){
                return (string) $id; 
                
            })->toArray();;
        }else{
          $this->reset(['selectedRows', 'selectPageRows']);   
        } 
    }

    public function deleteSelectedRows(){
        try {
        // Fetch selected promo codes
        $promoCodes = PromoCode::whereIn('id', $this->selectedRows)->get();

        // Check if any selected promo code is active
        foreach ($promoCodes as $promoCode) {
            if ($promoCode->is_active) {
                $this->cannotDeleteItem = true;
                $this->confirmBulkDelete = false;
                return;
            }
        }

        // Bulk Delete
        PromoCode::whereIn('id', $this->selectedRows)->delete();

        $this->confirmBulkDelete = false;
        session()->flash('message', 'All selected promo codes got deleted!');
    } catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() == 23000) {
            $this->cannotDeleteItem = true; // FK error
        } else {
            throw $e;
        }
    }
    }


    // ------------------ Render Method --------------------------- //
    public function render()
    {
         $promoCodes = $this->promoCodes;
            // Retrieve unique session for promocodes
        $fakeIDs = session('fake_ids_promoCodes', []);

        // Recalculate fake IDs if count mismatches
        if (count($fakeIDs) !== PromoCode::count()) {
            $fakeIDs = [];
            foreach (PromoCode::orderBy('created_at', 'ASC')->get() as $index => $act) {
                $fakeIDs[$act->id] = 'CODE-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_promoCodes' => $fakeIDs]);
        }

        return view('livewire.admin.settings.promo-codes.view-promo-codes', [
            'promoCodes' => $promoCodes,
            'fakeIDs' => $fakeIDs,
        ]);
    }

    // ---------------- Mount with Fake IDs ------------- //
    public function mount()
    {
        // Ensure promo code use a separate session key
        if (!session()->has('fake_ids_promoCodes')) {
            session(['fake_ids_promoCodes' => []]);
        }
    }

    // ---------------- Sort By Function ------------------------ //
    public function setSortBy($sortByField)
    {
        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    // ------------------ Delete Modal ----------------- //
    public function confirmDelete($id)
    {
        $this->selectedItemId = $id;
        $this->confirmItemDelete = true;
    }

    public function confirmDeleteInBulk(){
        $this->confirmBulkDelete = true;
    }

    // ----------------- Get Method ---------------------- //
    public function getPromoCodesProperty(){
        return PromoCode::with('propertyCategory')
        ->where('code', 'like', '%' . trim($this->search) . '%')
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);
    }

    // ---------------- Individual Delete ---------------- //
    public function deletePromoCode() {
        $promoCode = PromoCode::find($this->selectedItemId);

    if (!$promoCode) {
        session()->flash('error', 'Promo Code not found.');
        return;
    }

    // Check if promo code is still active
    if ($promoCode->is_active) {
            $this->cannotDeleteItem = true;
            $this->confirmItemDelete = null;
            return;
        }

    try {

        // Delete the event hall
        $promoCode->delete();

        // Reset confirmation modal
        $this->confirmItemDelete = false;
        $this->selectedItemId = null;

        // Refresh the list of event halls and regenerate fake ids
        $promoCodes = PromoCode::orderBy('created_at', 'ASC')->get();
        $fakeIDs = [];
        foreach ($promoCodes as $index => $promoCode) {
            $fakeIDs[$promoCode->id] = 'CODE-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        }

        // Store updated fake IDs in session
        session(['fake_ids_promoCodes' => $fakeIDs]);

        // Flash success message
        session()->flash('message', 'Promo Code successfully deleted!');
    }catch (\Illuminate\Database\QueryException $e) {
        if ($e->getCode() == 23000) {
            $this->cannotDeleteItem = true;
        } else {
            throw $e;
        }
    }
    }


    // ------------- Lazy Loading ---------- //
    public function placeholder()
    {
        return view('livewire.admin.placeholder');
    }
}
