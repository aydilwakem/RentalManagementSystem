<?php

namespace App\Livewire\Admin\Activities;

use App\Models\Activity;
use Livewire\Attributes\Rule;
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
    public $newImages = []; //new files
    public $images = [];
    public $uploadedImagePreviews = []; // temporary url for preview
    public $persistedImagePaths = []; // string paths once uploaded
    protected $listeners = ['updateImageOrder'];

    //Public declaration for add item modal
    public $confirmCreateItem = false;

    public $schedule_type = 'no_schedule';
    public $available_times = [];


    public function mount()
    {
        // default time
        $this->available_times = ['08:00'];
    }

    public function addTime()
    {
        $this->available_times[] = '';
    }

    public function removeTime($index)
    {
        unset($this->available_times[$index]);
        $this->available_times = array_values($this->available_times); // Reindex
    }


    //Method to make the modal true
    public function confirmCreate()
    {
        $this->confirmCreateItem = true;
    }

    public function updatedNewImages()
    {
        $this->validate([
            'newImages.*' => 'image|max:2024|mimes:jpeg,png,jpg,gif',
        ]);

        foreach ($this->newImages as $image) {
            $this->uploadedImagePreviews[] = $image; // store temporary for prview
        }
        $this->newImages = [];
    }

    public function removeImage($index)
    {
        if (isset($this->uploadedImagePreviews[$index])) {
            unset($this->uploadedImagePreviews[$index]);
            $this->uploadedImagePreviews = array_values($this->uploadedImagePreviews);
        } else if (isset($this->persistedImagePaths[$index])) {
            unset($this->persistedImagePaths[$index]);
            $this->persistedImagePaths = array_values($this->persistedImagePaths);
        }
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

        try {
            // Validate input
            $this->validate([
                'name' => 'required|string|max:255|unique:prd_activities,name',
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:0|max:10000',
                'inclusions' => 'nullable|string',
                'newImages' => 'nullable|array',
                'newImages.*' => 'image|mimes:jpeg,png,jpg,gif|max:2024',
                'schedule_type' => 'required|in:no_schedule,system,guest',
                'available_times' => 'nullable|array',
                'available_times.*' => 'nullable|date_format:H:i',

            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, close the modal
            $this->confirmCreateItem = false;
            throw $e;
        }

        $allStoredImagePaths = [];

        // store newly uploaded images
        foreach ($this->uploadedImagePreviews as $imageObject) {
            if (is_object($imageObject) && method_exists($imageObject, 'isValid')) {
                if ($imageObject->isValid()) {
                    $path = $imageObject->store('activities', 'public');
                    $allStoredImagePaths[] = $path;
                } else {
                    session()->flash('error', 'Image upload failed. Please try again.');
                    return;
                }
            }
        }

        $allStoredImagePaths = array_merge($allStoredImagePaths, $this->persistedImagePaths);

        // Create Activity
        Activity::create([
            'name' => $this->name,
            'description' => $this->description,
            'amount' => $this->amount,
            'inclusions' => $this->inclusions,
            'images' => $allStoredImagePaths,
            'schedule_type' => $this->schedule_type,
            'available_times' => $this->schedule_type === 'system' ? array_filter($this->available_times) : null,
        ]);

        // Reset form fields
        $this->reset(['name', 'description', 'amount', 'inclusions', 'images', 'newImages', 'uploadedImagePreviews', 'persistedImagePaths', 'available_times', 'schedule_type']);

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
