<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

#[Layout('layouts.app')]
class EditUser extends Component
{
    public User $user;
    public $name;
    public $email;
    public $password;
    public $selectedRole;
    public $roles = []; // List of available roles

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRole = $user->roles->first()?->name; // Get the first assigned role
        $this->roles = Role::pluck('name')->toArray(); // Fetch all roles
    }

    public function updateUser()
    {
        $this->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $this->user->id,
            'password' => 'nullable|min:8',
            'selectedRole' => 'nullable|exists:roles,name',
        ]);

        $this->user->update([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password ? Hash::make($this->password) : $this->user->password,
        ]);

        // Sync the selected role
        $this->user->syncRoles([$this->selectedRole]);

        session()->flash('message', 'User successfully updated!');

        return redirect()->route('admin.manage-users');
    }

    public function render()
    {
        return view('livewire.admin.users.edit-user', [
            'roles' => $this->roles, // Pass roles to the view
        ]);
    }
}
