<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

#[Layout('layouts.app')]
class ViewUser extends Component
{
    public User $user;
    public $userRoles = [];
    public $userPermissions = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->userRoles = $user->getRoleNames(); // Retrieve user roles
        $this->userPermissions = $user->getAllPermissions()->pluck('name'); // Retrieve user permissions
    }

    public function deleteUser(User $user)
    {
        if (!$user) {
            session()->flash('error', 'User not found!');
            return;
        }

        $user->delete();

        session()->flash('message', 'User successfully deleted!');

        return redirect()->route('admin.manage-users');
    }

    public function render()
    {
        return view('livewire.admin.users.view-user', [
            'userRoles' => $this->userRoles,
            'userPermissions' => $this->userPermissions,
        ]);
    }
}
