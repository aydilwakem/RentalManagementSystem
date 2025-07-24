<?php

namespace App\Livewire\Admin\Services;

use App\Models\Service;
use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ViewService extends Component
{
    public Service $service; 

    // ------------------ Modals -------------------- //
    public $confirmItemDelete = false;
    public $cannotDeleteItem = false;

    // ------------------- Open Modals --------------------- //
    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    // ---------------------- Delete Method --------------------- //
     public function deleteService()
    {
        if ($this->confirmItemDelete) {
        $service = Service::find($this->confirmItemDelete);

        if (!$service) {
            session()->flash('error', 'Service not found!');
            return redirect()->route('admin.services');
        }

        // Check if service is still active
    if ($service->is_active) {
            $this->cannotDeleteItem = true;
            $this->confirmItemDelete = null;
            return;
        }

        // Check if the service is linked to any transaction
        $usedInTransactions = Transaction::whereHas('services', function ($query) use ($service) {
            $query->where('service_id', $service->id);
        })->exists();

        if ($usedInTransactions) {
            $this->cannotDeleteItem = true; //Cannot delete 
            $this->confirmItemDelete = null;
            return;
        }

        // Delete the service
        $service->delete();

        $this->confirmItemDelete = null;

        session()->flash('message', 'Service successfully deleted!');
        }

        return redirect()->route('admin.services');
    }


    // ----------------------- Render --------------------- //
    public function render()
    {
        return view('livewire.admin.services.view-service');
    }
}
