<?php

namespace App\Livewire\Admin\Users;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class CreateUser extends Component
{
    public $name;
    public $email;
    public $password;

    public function saveUser()
    {
        // Validate input
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'string', Rules\Password::defaults()],
        ]);

        // Create user with hashed password
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Reset fields after successful creation
        $this->reset(['name', 'email', 'password']);

        // Flash success message
        session()->flash('message', 'User successfully created!');

        // Redirect to users list
        return redirect()->route('admin.manage-users');
    }

    public function render()
    {
        return view('livewire.admin.users.create-user');
    }
}
