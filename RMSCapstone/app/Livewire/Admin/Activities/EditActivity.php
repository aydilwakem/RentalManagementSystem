<?php

namespace App\Livewire\Admin\Activities;

use App\Models\Activity;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class EditActivity extends Component
{
    use WithFileUploads;

    public Activity $activity;
    public $name;
    public $description;
    public $amount;
    public $inclusions;
    public $image;
    public $newImage;


    public $confirmEditItem = false;

    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }


    // To display info of the selected activity
    public function mount(Activity $activity)
    {
        $this->activity = $activity;
        $this->name = $activity->name;
        $this->description = $activity->description;
        $this->amount = $activity->amount;
        $this->inclusions = $activity->inclusions;
        $this->image = $activity->image;
    }

    public function updateActivity()
    {
        try{
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'inclusions' => 'nullable|string',
            'newImage' => 'nullable|image|max:2048', // Ensure image size is within limit
        ]);
    }catch (\Illuminate\Validation\ValidationException $e) {
        // If validation fails, close the modal
        $this->confirmEditItem = false;
        throw $e;
    }

        // Ensure the image is uploaded properly
        if ($this->newImage && !$this->newImage->isValid()) {
            session()->flash('error', 'Image upload failed. Please try again.');
            return;
        }

        // Handle Image Upload
        if ($this->newImage) {
            if ($this->activity->image) {
                Storage::disk('public')->delete($this->activity->image);
            }
            // Save the image in public folder
            $this->image = $this->newImage->store('activities', 'public');
        }

        // Update Activity
        $this->activity->update([
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
            'inclusions' => $this->inclusions,
            'image' => $this->image,
        ]);

        session()->flash('message', 'Activity successfully updated!');

        return redirect()->route('admin.activities');
    }

    public function render()
    {
        return view('livewire.admin.activities.edit-activity');
    }
}
