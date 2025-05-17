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

    //Public declaration of activity variable with model
    public Activity $activity;

    //Public declarations of fillable fields
    public $name;
    public $description;
    public $amount;
    public $inclusions;
    public $image;
    public $newImage;
    public $activityId;

    //Public declaration of edit confirmation modal
    public $confirmEditItem = false;
    public $confirmDeleteImage = false;

    //Method to make the modal true
    public function confirmEdit($id)
    {
        $this->confirmEditItem = $id;
    }

    public function confirmImageDelete()
    {
        $this->confirmDeleteImage = true;
    }

    public function removeStoredImage()
{
    if ($this->image) {
        Storage::disk('public')->delete($this->image);

        $this->activity->update(['image' => null]);
        $this->image = null;

        $this->activity->refresh(); // Refresh model to sync with DB
    }

    $this->confirmDeleteImage = false;

    session()->flash('message', 'Image successfully deleted.');
}


    // To display info of the selected activity
    public function mount(Activity $activity)
    {
        $this->activity = $activity;
        $this->activityId = $activity->id;
        $this->name = $activity->name;
        $this->description = $activity->description;
        $this->amount = $activity->amount;
        $this->inclusions = $activity->inclusions;
        $this->image = $activity->image;
    }

    /**
     * Validate and update an existing activity record.
     * Handles optional image replacement, updates fields, and flashes a success message.
     */
    public function updateActivity()
    {
        //Validates all required fields
        try {
            $this->validate([
                'name' => "required|string|max:255|unique:prd_activities,name,{$this->activityId},id",
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:100',
                'inclusions' => 'nullable|string',
                'newImage' => 'nullable|image|max:2048', // Ensure image size is within limit
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmEditItem = false;
            throw $e;
        } //Handles constraints

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
