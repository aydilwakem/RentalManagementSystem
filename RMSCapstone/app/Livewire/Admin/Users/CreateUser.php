<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permissions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CreateUser extends Component
{
    // Public properties to store form inputs
    public $name;
    public $email;
    public $password;
    public $selectedRole;
    public $roles = []; // List of available roles

    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    // Mount method runs when the component is initialized
    public function mount()
    {
        // Fetch all role names from the database and store them in $roles
        $this->roles = Role::pluck('name')->toArray();
    }

    public function saveUser()
    {
        try{
        // Validate input fields to ensure correct data is entered
        $this->validate([
            'name' => 'required|string|max:255', // Name is required and must be a string
            'email' => 'required|string|email|max:255|unique:users,email', // Email must be unique
            'password' => ['required', 'string', Rules\Password::defaults()], // Enforce password rules
            'selectedRole' => ['required', 'exists:roles,name'], // Ensure the role exists in the roles table
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmCreateItem = false;
        throw $e;
    }

        // Create a new user in the database with a hashed password
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password), // Hash password for security
        ]);

        // Assign the selected role to the created user
        $user->assignRole($this->selectedRole);

        // Reset input fields after successful user creation
        $this->reset(['name', 'email', 'password', 'selectedRole']);

        // Flash a success message to the session
        session()->flash('message', 'User successfully created!');

        // Redirect to the users list page
        return redirect()->route('admin.manage-users');
    }

    public function render()
    {
        // Render the Livewire component view and pass the roles data
        return view('livewire.admin.users.create-user', [
            'roles' => $this->roles,
        ]);
    }
}
