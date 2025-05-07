<?php

namespace App\Livewire\Admin\Activities;

use App\Models\Activity;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class CreateActivity extends Component
{
    use WithFileUploads;

    // Public declarations for fillable fields
    public $name;
    public $description;
    public $amount;
    public $inclusions;
    public $image;
    public $images = [];
    public $storedImages = [];

    //Public declaration for add item modal
    public $confirmCreateItem = false;

    //Method to make the modal true
    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images); // reindex array
    }

    public function updatedImages()
    {
        // Prevent duplicate uploads by only appending new images
        $this->images = array_merge($this->storedImages, $this->images);
    }

    /**
     * Method to create a new activity
     *
     * Adds a try catch error for handling constraints
     * Validates the form inputs, uploads the image if provided,
     * Creates a new Activity record and resets the form,
     * Flashes a success message, and redirects back to the activities list.
     */

    public function saveActivity()
    {
    try{
        // Validate input
        $this->validate([
            'name' => 'required|string|max:255|unique:prd_activities,name',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0|max:10000',
            'inclusions' => 'nullable|string',
            'image' => 'nullable|image|max:1024', // Max 1MB image
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
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

        $imagePaths = [];
        if (is_array($this->images)) {
            foreach ($this->images as $image) {
                if ($image->isValid()) {
                    $path = $image->store('activities', 'public');
                    $imagePaths[] = $path;
                } else {
                    session()->flash('error', 'Image upload failed. Please try again.');
                    return;
                }
            }
        }

        // Create Activity
        Activity::create([
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
            'inclusions' => $this->inclusions,
            'image' => $imagePath,
            'images' => array_merge($imagePaths, $this->storedImages),

        ]);

        // Reset form fields
        $this->reset(['name', 'description', 'amount', 'inclusions', 'image', 'images']);

        // Flash success message
        session()->flash('message', 'Activity successfully created!');

        // Redirect back to activities list
        return redirect()->route('admin.activities');
    }

    //Method to render the page
    public function render()
    {
        return view('livewire.admin.activities.create-activity');
    }
}
