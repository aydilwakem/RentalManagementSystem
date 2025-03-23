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
    public $perPage = 5;

    public $confirmItemDelete = false;

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
        $users = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.users.view-users', compact('users'));
    }
}
