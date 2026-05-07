<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class DeletedUsers extends Component
{
    public $deletedUsers;

    public $confirmItemDelete = false;

    public function confirmDeleteForever($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function mount()
    {
        $this->fetchDeletedUsers();
    }

    
    public function fetchDeletedUsers()
    {
        $this->deletedUsers = User::onlyTrashed()->orderBy('created_at', 'ASC')->get();
    }

    public function restoreUser($userId)
    {
        $user = User::withTrashed()->find($userId);
        if ($user) {
            $user->restore();
            session()->flash('message', 'User restored successfully.');
            $this->fetchDeletedUsers();
        }
    }

    public function deleteUserForever($userId)
    {
        $user = User::withTrashed()->find($this->confirmItemDelete);
        if ($user) {
            $user->forceDelete();
            session()->flash('message', 'User permanently deleted.');
            $this->fetchDeletedUsers();
        }
        $this->confirmItemDelete = false;
    }

    public function render()
    {
        // Create or reuse fake IDs for ONLY deleted records
        $fakeIDs = session('fake_ids_users', []);

        $deletedIds = collect($this->deletedUsers)->pluck('id')->toArray();

        // Recalculate fake IDs if mismatch or deleted list has changed
        if (array_diff($deletedIds, array_keys($fakeIDs)) || count($fakeIDs) !== count($deletedIds)) {
            $fakeIDs = [];
            foreach ($this->deletedUsers as $index => $user) {
                $fakeIDs[$user->id] = 'USER-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            session(['fake_ids_users' => $fakeIDs]);
        }

        return view('livewire.admin.users.deleted-users', [
            'deletedUsers' => $this->deletedUsers,
            'fakeIDs' => $fakeIDs,
        ]);
    }
}
