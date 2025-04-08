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
    public $userId;

    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRole = $user->roles->first()?->name; // Get the first assigned role
        $this->roles = Role::pluck('name')->toArray(); // Fetch all roles
        $this->userId = $user->id;
    }

    public function updateUser()
    {
        try{
        $this->validate([
            'name' => "nullable|string|max:255|unique:users,name,{$this->userId},id",
            'email' => 'nullable|email|unique:users,email,' . $this->user->id,
            'password' => 'nullable|min:8',
            'selectedRole' => 'nullable|exists:roles,name',
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmEditItem = false;
        throw $e;
    }

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
