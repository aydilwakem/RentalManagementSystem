<?php

namespace App\Livewire\Admin\Properties;

use App\Models\Property;
use Illuminate\Database\QueryException;
use Livewire\Component;

class DeletedProperties extends Component
{
    public $deletedProperties;

    public $confirmItemDelete = false;
    public $cannotDeleteItem = false; //Will appear if parent table item is still in soft delete

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        $this->fetchDeletedProperties();
    }

    public function fetchDeletedProperties()
    {
        $this->deletedProperties = Property::onlyTrashed()->ofType('House')->orderBy('created_at', 'ASC')->get();
    }

    public function restoreProperty($propertyId)
    {
        $property = Property::withTrashed()->find($propertyId);
        if ($property) {
            $property->restore(); // Restore the property
            session()->flash('message', 'House restored successfully.');
            $this->deletedProperties = Property::onlyTrashed()->get();
        }
    }

    public function deletePropertyForever($propertyId)
    {
        try {
            $property = Property::withTrashed()->find($this->confirmItemDelete);
            if ($property) {
                $property->forceDelete(); // Permanently delete the room
                session()->flash('message', 'House permanently deleted.');
                $this->fetchDeletedProperties();
            }
            $this->confirmItemDelete = false;
        } catch (QueryException $e) {
            // Check if the error is an integrity constraint violation
            if ($e->getCode() == 23000) {
                $this->cannotDeleteItem = true; // Show the cannot delete modal
                $this->confirmItemDelete = false;
            } else {
                throw $e; // Re-throw other exceptions
            }
        }
    }

    public function render()
    {
        // Generate fake IDs for deleted properties
        $fakeIDs = session('fake_ids_properties', []);

        $deletedIds = $this->deletedProperties->pluck('id')->toArray();

        // Refresh fake IDs if mismatch or count changes
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedProperties as $index => $property) {
                $fakeIDs[$property->id] = 'PRT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_properties' => $fakeIDs]);
        }
        return view('livewire.admin.properties.deleted-properties', [
            'deletedProperties' => $this->deletedProperties,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
