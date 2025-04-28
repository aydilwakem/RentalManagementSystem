<?php

namespace App\Livewire\Admin\EventCategories;

use App\Models\EventCategory;
use Illuminate\Database\QueryException;
use Livewire\Component;

class DeletedEventCategories extends Component
{
    //Public variable declaration for deleted categories
    public $deletedEventCategories;

    public $confirmItemDelete = false;

    //Variable to handle integrity constraints, will appear if parent table item is still in soft delete
    public $cannotDeleteItem = false; 

    //Method to make modal true by getting item id
    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        $this->fetchDeletedEventCategories();
    }

    //Method to fetch all soft deleted items and store it in variable
    public function fetchDeletedEventCategories()
    {
        $this->deletedEventCategories = EventCategory::onlyTrashed()->orderBy('created_at', 'ASC')->get();
    }

    /**
     * Restores a soft-deleted event category.
     * - Finds the trashed event category by ID.
     * - Restores it and refreshes the deleted event categories list.
     */
    public function restoreEventCategory($eventCategoryId)
    {
        $eventCategory = EventCategory::withTrashed()->find($eventCategoryId);
        if ($eventCategory) {
            $eventCategory->restore();
            session()->flash('message', 'Event category restored successfully.');
            $this->fetchDeletedEventCategories();
        }
    }

    /**
     * Permanently deletes a soft-deleted event category.
     * - Attempts to force delete the selected event category.
     * - Catches integrity constraint violations (e.g., if the event category is still linked to other records) and handles them by showing a "cannot delete" modal.
     * - Refreshes the deleted event categories list after successful deletion.
     */
    public function deleteEventCategoryForever($eventCategoryId)
    {
        try{
        $eventCategory = EventCategory::withTrashed()->find($this->confirmItemDelete);
        if ($eventCategory) {
            $eventCategory->forceDelete();
            session()->flash('message', 'Event category permanently deleted.');
            $this->fetchDeletedEventCategories();
        }
        $this->confirmItemDelete = false;
    }catch (QueryException $e) {
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
        // Create or reuse fake IDs for ONLY deleted records
        $fakeIDs = session('fake_ids_eventCategories', []);

        $deletedIds = $this->deletedEventCategories->pluck('id')->toArray();

        // Recalculate fake IDs if mismatch or deleted list has changed
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedEventCategories as $index => $eventCategory) {
                $fakeIDs[$eventCategory->id] = 'ECT-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_event' => $fakeIDs]);
        }

        return view('livewire.admin.event-categories.deleted-event-categories', [
            'deletedEventCategories' => $this->deletedEventCategories,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
