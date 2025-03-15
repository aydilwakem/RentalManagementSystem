<?php

namespace App\Livewire\Admin\Maintenance;

use App\Models\Maintenance;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ViewMaintenances extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url()]
    public $perPage = 5;

    #[Url(history: true)]
    public $sortBy = 'created_at';

    #[Url(history: true)]
    public $sortDir = 'DESC';

    public $priorityStatus = '';

    public function deleteMaintenances($id)
    {
        // Find the event hall by ID
        $maintenance = Maintenance::find($id);

        if ($maintenance) {
            // Delete the event hall
            $maintenance->delete();

            // Flash success message
            session()->flash('message', 'Maintenance successfully deleted!');
        }
    }

    public function setSortBy($sortByField)
    {

        if ($this->sortBy == $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }
        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }


    public function render()
    {
        $maintenance = Maintenance::query()
            ->search($this->search)
            ->when($this->priorityStatus !== '', function ($query) {
                $query->where('priority_status', $this->priorityStatus);
            })
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.maintenance.view-maintenances', compact('maintenance'));
    }
}
