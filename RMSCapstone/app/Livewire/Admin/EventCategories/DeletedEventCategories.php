<?php

namespace App\Livewire\Admin\EventCategories;

use App\Models\EventCategory;
use Livewire\Component;

class DeletedEventCategories extends Component
{
    public $deletedEventCategories;

    public function mount()
    {
        $this->fetchDeletedEventCategories();
    }

    public function fetchDeletedEventCategories()
    {
        $this->deletedEventCategories = EventCategory::onlyTrashed()->orderBy('created_at', 'ASC')->get();
    }

    public function restoreEventCategory($eventCategoryId)
    {
        $eventCategory = EventCategory::withTrashed()->find($eventCategoryId);
        if ($eventCategory) {
            $eventCategory->restore();
            session()->flash('message', 'Event category restored successfully.');
            $this->fetchDeletedEventCategories();
        }
    }

    public function deleteEventCategoryForever($eventCategoryId)
    {
        $eventCategory = EventCategory::withTrashed()->find($eventCategoryId);
        if ($eventCategory) {
            $eventCategory->forceDelete();
            session()->flash('message', 'Event category permanently deleted.');
            $this->fetchDeletedEventCategories();
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
