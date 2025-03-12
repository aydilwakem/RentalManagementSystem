<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;

#[Layout('layouts.app')]
class ViewUser extends Component
{
    // Public property to hold the user record
    public User $user;

    // Function to find the user record
    public function mount(User $user)
    {
        $this->user = $user;
    }

    // Function to delete a user
    public function deleteUser(User $user)
    {
        if (!$user) {
            session()->flash('error', 'User not found!');
            return;
        }

        // Delete user
        $user->delete();

        session()->flash('message', 'User successfully deleted!');

        return redirect()->route('admin.manage-users');
    }

    public function render()
    {
        return view('livewire.admin.users.view-user');
    }
}
