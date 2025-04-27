<?php

namespace App\Livewire\Admin\Activities;

use App\Models\Activity;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreateActivity extends Component
{
    use WithFileUploads;

    public $name;
    public $description;
    public $amount;
    public $inclusions;
    public $image;

    public $confirmCreateItem = false;

    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function saveActivity()
    {
        try{
        // Validate input
        $this->validate([
            'name' => 'required|string|max:255|unique:prd_activities,name',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0|max:100',
            'inclusions' => 'nullable|string',
            'image' => 'nullable|image|max:1024', // Max 1MB image
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmCreateItem = false;
        throw $e;
    }

        // Ensure image upload is valid
        if ($this->image && !$this->image->isValid()) {
            session()->flash('error', 'Image upload failed. Please try again.');
            return;
        }

        // Store Image (if uploaded)
        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('activities', 'public');
        }

        // Create Activity
        Activity::create([
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
            'inclusions' => $this->inclusions,
            'image' => $imagePath,
        ]);

        // Reset form fields
        $this->reset(['name', 'description', 'amount', 'inclusions', 'image']);

        // Flash success message
        session()->flash('message', 'Activity successfully created!');

        // Redirect back to activities list
        return redirect()->route('admin.activities');
    }

    public function render()
    {
        return view('livewire.admin.activities.create-activity');
    }
}
