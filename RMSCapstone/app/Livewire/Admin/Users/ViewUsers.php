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
    public function mount(User $user){
        $this->user = $user;
        $this->userRoles = $user->getRoleNames()->toArray();
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

    return view('livewire.admin.users.view-users', compact('users'));
}
}
