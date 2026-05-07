<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class ViewUsers extends Component
{
    use WithPagination;

    public $sortBy = 'id';
    public $sortDir = 'ASC';
    public $search = '';
    public $perPage = 10;

    public User $user;
    public $userRoles = [];
    public $roleFilter = ''; 

    public $confirmItemDelete = false;


    //mount function to fetch role names
    //mount session for fake IDs
    public function mount(User $user){
        $this->user = $user;
        $this->userRoles = $user->getRoleNames()->toArray();

        // Ensure it use a separate session key
        if (!session()->has('fake_ids_users')) {
            session(['fake_ids_users' => []]);
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmItemDelete = $id;
    }

    public function deleteUser($id)
    {

        if ($id) {
            if ($this->confirmItemDelete) {
                User::find($this->confirmItemDelete)?->delete();
                $this->confirmItemDelete = false;

                // Fetch remaining users - sorted by creation date
                $users = User::orderBy('created_at', 'ASC')->get();

                // Reset fake IDs
                $fakeIDs = [];
                foreach ($users as $index => $userItem) {
                    $fakeIDs[$userItem->id] = 'USER-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
                }

                // Store updated fake IDs in a unique session key
                session(['fake_ids_users' => $fakeIDs]);

            session()->flash('message', 'User successfully deleted!');
            }
        }   
    }

    public function setSortBy($sortByField)
    {
        if ($this->sortBy === $sortByField) {
            $this->sortDir = ($this->sortDir == "ASC") ? "DESC" : "ASC";
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = "ASC";
    }

    public function render()
{
    // Fetch users based on search, filter, sort, and paginate
    $users = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        ->when($this->roleFilter === 'Super Admin', function ($query) {
            $query->whereHas('roles', function ($roleQuery) {
                $roleQuery->where('name', 'Super Admin');
            });
        })
        ->when($this->roleFilter === 'Admin', function ($query) {
            $query->whereHas('roles', function ($roleQuery) {
                $roleQuery->where('name', 'Admin');
            });
        })
        ->when($this->roleFilter === 'Staff', function ($query) {
            $query->whereDoesntHave('roles', function ($roleQuery) {
                $roleQuery->whereIn('name', ['Super Admin', 'Admin']);
            });
        })
        ->orderBy($this->sortBy, $this->sortDir)
        ->paginate($this->perPage);

    // Fetch ALL users to generate static fake IDs (always by created_at ASC)
    $allUsers = User::orderBy('created_at', 'ASC')->get();

    // Calculate fake IDs
    $fakeIDs = session('fake_ids_users', []);
    if (count($fakeIDs) !== $allUsers->count()) {
        $fakeIDs = [];
        foreach ($allUsers as $index => $userItem) {
            $fakeIDs[$userItem->id] = 'USER-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
        }
        session(['fake_ids_users' => $fakeIDs]);
    }

    return view('livewire.admin.users.view-users', compact('users', 'fakeIDs'));
}
}
